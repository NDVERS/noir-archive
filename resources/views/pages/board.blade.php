@extends('layouts.detective')

@section('title', 'Papan Investigasi Benang Merah - ' . ($caseInfo['title'] ?? 'The Phantom Prototype'))

@section('content')
@php
    $corkboardNodes = [];

    // 1. Suspects (3 polaroid cards in top row at Y = 40px)
    $suspectDefaultX = [100, 480, 860];
    $suspectRotations = ['origin-top rotate-[1deg]', 'origin-top -rotate-[1.2deg]', 'origin-top rotate-[1.5deg]'];
    foreach ($suspects as $idx => $s) {
        $corkboardNodes[] = [
            'id' => 'node_' . $s['id'],
            'rawId' => $s['id'],
            'type' => 'suspect',
            'title' => $s['name'],
            'subtitle' => $s['role'],
            'age' => $s['age'] ?? 40,
            'x' => $suspectDefaultX[$idx] ?? (100 + $idx * 380),
            'y' => 40,
            'width' => 180,
            'rotationClass' => $suspectRotations[$idx] ?? 'origin-top rotate-1',
        ];
    }

    // 2. Evidences (Row 2 at Y = 360px and Row 3 at Y = 560px)
    $evdPositions = [
        ['x' => 80, 'y' => 360, 'rot' => 'origin-top -rotate-[1deg]'],
        ['x' => 320, 'y' => 360, 'rot' => 'origin-top rotate-[1.5deg]'],
        ['x' => 560, 'y' => 360, 'rot' => 'origin-top -rotate-[1.8deg]'],
        ['x' => 800, 'y' => 360, 'rot' => 'origin-top rotate-[1deg]'],
        ['x' => 200, 'y' => 560, 'rot' => 'origin-top -rotate-[1.5deg]'],
        ['x' => 680, 'y' => 560, 'rot' => 'origin-top rotate-[2deg]'],
    ];
    foreach ($evidences as $idx => $e) {
        $corkboardNodes[] = [
            'id' => 'node_' . $e['id'],
            'rawId' => $e['id'],
            'type' => 'evidence',
            'title' => $e['title'],
            'subtitle' => $e['category'],
            'category' => $e['category'],
            'x' => $evdPositions[$idx]['x'] ?? (80 + ($idx % 4) * 240),
            'y' => $evdPositions[$idx]['y'] ?? 360,
            'width' => 180,
            'rotationClass' => $evdPositions[$idx]['rot'] ?? 'origin-top rotate-1',
        ];
    }
@endphp

<div class="flex-1 bg-corkboard min-h-[calc(100vh-60px)] flex flex-col relative select-none overflow-hidden"
     x-data="createCorkboardComponent(@js($corkboardNodes))"
     @mousemove="onDrag($event)"
     @mouseup="stopDrag()"
     @touchmove="onDrag($event)"
     @touchend="stopDrag()">

    <!-- Corkboard Header Controls Toolbar -->
    <div class="relative z-30 bg-noir-900/95 border-b border-stone-800 px-4 py-2.5 flex flex-wrap items-center justify-between gap-3 shadow-2xl backdrop-blur">
        <div class="flex items-center gap-3">
            <span class="text-xl">📌</span>
            <div>
                <h2 class="text-sm font-bold font-noir text-stone-100 uppercase tracking-wider">
                    PAPAN INVESTIGASI BENANG MERAH (CORKBOARD GRAPH)
                </h2>
                <p class="text-[11px] font-mono text-stone-400">
                    Klik satu pin/kartu, lalu klik pin/kartu lainnya untuk menghubungkan benang merah. Seret (drag) kartu untuk mengatur tata letak.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <span class="text-xs font-mono bg-stone-950 px-2.5 py-1 rounded border border-stone-800 text-amber-400 font-bold">
                <span x-text="($store.game?.boardConnections || []).length"></span> Sambungan Benang
            </span>

            <button @click="resetPositions()" class="px-3 py-1 bg-stone-800 hover:bg-stone-700 text-amber-300 rounded text-xs font-mono border border-stone-600 transition shadow cursor-pointer">
                📐 Tata Ulang Posisi
            </button>

            <button @click="clearAllConnections()" class="px-3 py-1 bg-stone-800 hover:bg-stone-700 text-stone-300 rounded text-xs font-mono border border-stone-600 transition shadow cursor-pointer">
                🗑️ Bersihkan Benang
            </button>

            <button @click="$store.game.isIndictmentModalOpen = true; NoirAudio.playPaper()" class="px-3.5 py-1 bg-linear-to-r from-red-800 to-red-950 hover:from-red-700 hover:to-red-900 text-white rounded text-xs font-mono font-bold shadow-lg border border-red-700 transition cursor-pointer">
                ⚖️ Rumuskan Dakwaan
            </button>
        </div>
    </div>

    <!-- Active Pinboard Canvas & SVG Overlay -->
    <div class="flex-1 w-full h-212.5 relative overflow-auto bg-corkboard">
        
        <!-- Large Corkboard Workspace Canvas -->
        <div class="relative w-[2000px] h-325">

            <!-- Native SVG Red Threads Layer -->
            <svg class="absolute inset-0 pointer-events-none" 
                 style="width: 2000px; height: 1300px; z-index: 20;"
                 x-html="svgLinesMarkup">
            </svg>

            <!-- Board HTML Nodes (Suspects, Evidences, Facts) -->
            <template x-for="node in nodes" :key="node.id">
                <div class="absolute cursor-move z-20 select-none group transition-shadow"
                     :style="'left: ' + node.x + 'px; top: ' + node.y + 'px; width: ' + (node.width || 180) + 'px;'"
                     @mousedown="startDrag(node.id, $event)"
                     @touchstart="startDrag(node.id, $event)">
                    
                    <!-- Golden/Red Pushpin Element at Top Center -->
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 w-7 h-7 z-30 cursor-pointer flex items-center justify-center"
                         @click.stop="handleNodeClick(node.id)">
                        <!-- Pin Head Visual -->
                        <div class="w-4 h-4 rounded-full border-2 border-black/80 shadow-md ring-1 ring-amber-900/80 transition-transform group-hover:scale-125"
                             :class="selectedNodeId === node.id 
                                 ? 'bg-amber-400 ring-4 ring-amber-300 animate-ping' 
                                 : (node.type === 'suspect' ? 'bg-amber-600' : (node.type === 'fact' ? 'bg-yellow-500' : 'bg-red-800'))">
                        </div>
                    </div>

                    <!-- 1. Suspect Polaroid Card with Portrait Aspect Mugshot Frame -->
                    <template x-if="node.type === 'suspect'">
                        <div class="bg-stone-200 p-2.5 rounded shadow-2xl border-2 border-stone-400 text-stone-900 flex flex-col items-center text-center transition"
                             :class="[node.rotationClass || 'origin-top', selectedNodeId === node.id ? 'ring-4 ring-amber-500 border-amber-600 scale-105' : '']">
                            
                            <!-- High-Contrast 1950s Criminal Mugshot Frame with Spotlight Background -->
                            <div class="w-full h-36 bg-linear-to-b from-stone-800 via-stone-700 to-stone-900 rounded border border-stone-500 mb-2 flex flex-col items-center justify-center relative overflow-hidden shadow-inner">
                                
                                <!-- Mugshot Background Grid Lines -->
                                <div class="absolute inset-0 opacity-15 pointer-events-none" 
                                     style="background-image: linear-gradient(#ffffff 1px, transparent 1px); background-size: 100% 10px;"></div>

                                <!-- Ambient Spotlight Radial Glow -->
                                <div class="absolute inset-0 bg-radial from-stone-600/60 via-stone-800/80 to-stone-950 pointer-events-none"></div>

                                <!-- High-Contrast Vector Silhouette SVG Matching Desk Hub -->
                                <div class="relative z-10 w-24 h-24 flex items-center justify-center">
                                    <template x-if="node.rawId === 'suspect_thorne'">
                                        <svg class="w-24 h-24 drop-shadow-[0_4px_6px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 14 C34 14, 26 25, 26 42 C26 56, 36 64, 50 64 C64 64, 74 56, 74 42 C74 25, 66 14, 50 14 Z" fill="#1c1917" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M24 38 C22 22, 34 10, 50 10 C66 10, 78 22, 76 38 C70 26, 60 20, 50 20 C40 20, 30 26, 24 38 Z" fill="#0c0a09" stroke="#d6d3d1" stroke-width="1.5"/>
                                            <circle cx="40" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                                            <circle cx="60" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                                            <line x1="48" y1="40" x2="52" y2="40" stroke="#fafaf9" stroke-width="2.5"/>
                                            <line x1="36" y1="36" x2="42" y2="42" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="56" y1="36" x2="62" y2="42" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                                            <path d="M50 44 L48 50 L52 50" stroke="#d6d3d1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <line x1="46" y1="56" x2="54" y2="56" stroke="#d6d3d1" stroke-width="1.5" stroke-linecap="round"/>
                                            <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M38 66 L50 82 L62 66" stroke="#f59e0b" stroke-width="2" fill="#292524"/>
                                            <path d="M47 82 L53 82 L50 96 Z" fill="#dc2626"/>
                                        </svg>
                                    </template>
                                    <template x-if="node.rawId === 'suspect_elena'">
                                        <svg class="w-24 h-24 drop-shadow-[0_4px_6px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 12 C32 12, 22 24, 22 46 C22 62, 28 68, 32 68 C32 52, 38 20, 50 20 C62 20, 68 52, 68 68 C72 68, 78 62, 78 46 C78 24, 68 12, 50 12 Z" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M50 24 C40 24, 36 34, 36 48 C36 58, 42 64, 50 64 C58 64, 64 58, 64 48 C64 34, 60 24, 50 24 Z" fill="#262626" stroke="#d6d3d1" stroke-width="1.5"/>
                                            <path d="M39 42 Q44 38 49 42" stroke="#fafaf9" stroke-width="2.5" stroke-linecap="round"/>
                                            <circle cx="44" cy="42" r="1.5" fill="#fafaf9"/>
                                            <path d="M51 42 Q56 38 61 42" stroke="#fafaf9" stroke-width="2.5" stroke-linecap="round"/>
                                            <circle cx="56" cy="42" r="1.5" fill="#fafaf9"/>
                                            <path d="M46 54 Q50 52 54 54 Q50 57 46 54 Z" fill="#dc2626" stroke="#ef4444" stroke-width="0.8"/>
                                            <path d="M14 96 C14 74, 28 66, 50 66 C72 66, 86 74, 86 96 Z" fill="#171717" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M28 66 L40 80 L50 66 L60 80 L72 66" stroke="#991b1b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </template>
                                    <template x-if="node.rawId === 'suspect_croft'">
                                        <svg class="w-24 h-24 drop-shadow-[0_4px_6px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 10 C36 10, 38 28, 38 34 L62 34 C62 28, 64 10, 50 10 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M38 28 L62 28" stroke="#dc2626" stroke-width="3.5"/>
                                            <ellipse cx="50" cy="34" rx="30" ry="6" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M50 34 C41 34, 38 42, 38 52 C38 60, 44 64, 50 64 C56 64, 62 60, 62 52 C62 42, 59 34, 50 34 Z" fill="#27272a" stroke="#d6d3d1" stroke-width="1.5"/>
                                            <line x1="42" y1="44" x2="48" y2="44" stroke="#fafaf9" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="52" y1="44" x2="58" y2="44" stroke="#fafaf9" stroke-width="2" stroke-linecap="round"/>
                                            <line x1="54" y1="56" x2="68" y2="54" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                                            <circle cx="68" cy="54" r="2" fill="#ea580c" stroke="#f97316" stroke-width="1"/>
                                            <path d="M70 52 Q76 44 70 36 T78 24" stroke="#d4d4d8" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                                            <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M42 66 L50 82 L58 66 Z" fill="#ffffff" stroke="#e7e5e4" stroke-width="1"/>
                                            <polygon points="46,71 54,71 50,75" fill="#000000"/>
                                            <polygon points="46,75 54,75 50,71" fill="#000000"/>
                                        </svg>
                                    </template>
                                </div>

                                <!-- Mugshot Archive Booking Banner at Bottom of Photo -->
                                <div class="relative z-20 mt-auto w-full bg-stone-950/90 border-t border-stone-600/80 px-1.5 py-0.5 flex items-center justify-between text-[9px] font-mono">
                                    <span class="text-amber-400 font-bold tracking-wider" 
                                          x-text="node.rawId === 'suspect_thorne' ? '#THO-53' : (node.rawId === 'suspect_elena' ? '#ELE-53' : '#CRO-53')"></span>
                                    <span class="text-stone-300 font-medium" x-text="'Usia ' + node.age + ' Thn'"></span>
                                </div>
                            </div>

                            <div class="font-bold font-typewriter text-xs text-stone-900 leading-tight" x-text="node.title"></div>
                            <div class="text-[10px] font-mono text-stone-600 mt-0.5 leading-tight" x-text="node.subtitle"></div>
                        </div>
                    </template>

                    <!-- 2. Evidence Card with Forensic Top Strip Visual -->
                    <template x-if="node.type === 'evidence'">
                        <div class="paper-texture p-3 rounded shadow-2xl border-2 border-stone-700 text-stone-900 transition relative overflow-hidden"
                             :class="[node.rotationClass || 'origin-top', selectedNodeId === node.id ? 'ring-4 ring-amber-500 border-amber-600 scale-105' : '']">
                            <div class="absolute top-0 left-0 right-0 h-1 bg-linear-to-r from-red-800 to-amber-700"></div>
                            <div class="flex items-center justify-between mb-1 mt-0.5">
                                <span class="text-[9px] font-mono font-bold text-red-900 bg-red-100 px-1 py-0.2 rounded" x-text="'[' + node.rawId + ']'"></span>
                                <span class="text-[9px] font-mono text-stone-600 uppercase truncate" x-text="node.subtitle"></span>
                            </div>
                            <div class="font-bold font-typewriter text-[11px] text-stone-900 leading-snug wrap-break-word" x-text="node.title"></div>
                        </div>
                    </template>

                    <!-- 3. Fact Sticky Note (Unlocked from Objections) -->
                    <template x-if="node.type === 'fact'">
                        <div class="p-3 rounded shadow-2xl border-2 text-stone-900 transition font-typewriter relative"
                             :style="'background-color: #fef08a; border-color: #ca8a04;'"
                             :class="[node.rotationClass || 'origin-top', selectedNodeId === node.id ? 'ring-4 ring-amber-500 scale-105' : '']">
                            <div class="text-[10px] font-black uppercase text-red-900 mb-1 flex items-center gap-1">
                                <span>⚡</span>
                                <span x-text="node.title"></span>
                            </div>
                            <div class="text-[11px] leading-tight text-stone-800 wrap-break-word" x-text="node.subtitle"></div>
                        </div>
                    </template>

                </div>
            </template>
        </div>

    </div>

</div>
@endsection

@props([
    'suspects' => [],
    'caseId' => 'case_001',
])

<!-- Suspect Index Cards Rack (Calibrated Height Proportion ~50-52%) -->
<div class="flex flex-col min-h-0 flex-[5.2] justify-between">
    <!-- Header Title -->
    <div class="flex items-center justify-between mb-1.5 shrink-0">
        <div class="flex items-center gap-1.5">
            <span class="text-xs">👤</span>
            <h3 class="text-[11px] font-bold font-mono tracking-wider text-stone-200 uppercase">
                PROFIL TERSANGKA & SAKSI KUNCI ({{ count($suspects) }})
            </h3>
        </div>
        <span class="text-[9px] font-mono text-stone-400 hidden sm:inline">Pilih kartu untuk interogasi mendalam</span>
    </div>

    <!-- 3-Column Suspect Card Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-2.5 items-stretch flex-1 min-h-0">
        @foreach($suspects as $suspect)
            <div class="suspect-index-card p-2 sm:p-2.5 rounded text-stone-900 flex flex-col justify-between h-full transform hover:-translate-y-0.5 transition duration-150 group relative">
                
                <div class="flex-1 flex flex-col min-h-0">
                    <!-- High-Contrast 1950s Criminal Mugshot Frame (Compact Height ~20-22) -->
                    <div class="w-full h-20 sm:h-22 bg-linear-to-b from-stone-900 via-stone-850 to-stone-950 rounded border border-stone-600 mb-1.5 flex flex-col items-center justify-center relative overflow-hidden shadow-inner shrink-0">
                        
                        <!-- Mugshot Grid Lines -->
                        <div class="absolute inset-0 opacity-15 pointer-events-none" 
                             style="background-image: linear-gradient(#ffffff 1px, transparent 1px); background-size: 100% 8px;"></div>

                        <!-- Ambient Spotlight Radial Glow -->
                        <div class="absolute inset-0 bg-radial from-stone-600/50 via-stone-850/80 to-stone-950 pointer-events-none"></div>

                        <!-- Vector Silhouette SVG (Scaled ~18-20) -->
                        <div class="relative z-10 w-18 h-18 flex items-center justify-center">
                            @if($suspect['id'] === 'suspect_thorne')
                                <!-- Dr. Aris Thorne -->
                                <svg class="w-18 h-18 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] group-hover:scale-105 transition duration-200" viewBox="0 0 100 100" fill="none">
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
                            @elseif($suspect['id'] === 'suspect_elena')
                                <!-- Elena Vance -->
                                <svg class="w-18 h-18 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] group-hover:scale-105 transition duration-200" viewBox="0 0 100 100" fill="none">
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
                            @else
                                <!-- Julian Croft -->
                                <svg class="w-18 h-18 drop-shadow-[0_2px_4px_rgba(0,0,0,0.8)] group-hover:scale-105 transition duration-200" viewBox="0 0 100 100" fill="none">
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
                            @endif
                        </div>

                        <!-- Mugshot Archive Stamp Banner -->
                        <div class="relative z-20 mt-auto w-full bg-stone-950/95 border-t border-stone-700 px-1.5 py-0.2 flex items-center justify-between text-[8px] font-mono">
                            <span class="text-amber-400 font-bold tracking-wider">#{{ strtoupper(substr($suspect['id'], 8, 3)) }}-53</span>
                            <span class="text-stone-300 font-medium">{{ $suspect['age'] }} Thn</span>
                        </div>
                    </div>

                    <h4 class="font-bold font-typewriter text-[11px] sm:text-xs text-stone-900 leading-tight truncate">
                        {{ $suspect['name'] }}
                    </h4>
                    <div class="text-[9px] font-mono text-stone-600 mb-1 truncate">
                        {{ $suspect['role'] }}
                    </div>

                    <!-- Alibi Box (Compact 2-Line Clamp) -->
                    <div class="text-[10px] font-typewriter font-medium text-stone-900 bg-stone-100/90 border border-stone-300 p-1.5 rounded leading-relaxed shadow-inner flex flex-col justify-start mb-1.5 line-clamp-2">
                        <div>
                            <span class="font-bold text-stone-950">Alibi:</span> 
                            <span class="italic text-stone-900">"{{ $suspect['alibi'] }}"</span>
                        </div>
                    </div>
                </div>

                <!-- Action Button -->
                <a href="{{ route('game.interrogation', ['caseId' => $caseId, 'suspectId' => $suspect['id']]) }}" 
                   @click="NoirAudio.playClick()"
                   class="block text-center w-full py-1 bg-stone-900 hover:bg-stone-800 text-amber-200 font-mono text-[10px] sm:text-[11px] font-bold rounded shadow-xs border border-stone-700 transition mt-auto shrink-0">
                    Mulai Interogasi ➔
                </a>
            </div>
        @endforeach
    </div>
</div>

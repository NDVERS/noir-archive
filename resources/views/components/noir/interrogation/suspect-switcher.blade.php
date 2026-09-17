@props([
    'suspects' => [],
])

<!-- Suspect Switcher Dossier Tabs & Tape Status -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2.5 items-stretch border-b border-stone-800 pb-3.5">
    @foreach($suspects as $idx => $suspect)
        <button @click="loadSuspect('{{ $suspect['id'] }}')" 
                :class="activeSuspectId === '{{ $suspect['id'] }}' 
                    ? 'border-amber-500/90 text-amber-200 bg-stone-900/95 shadow-md shadow-amber-950/20' 
                    : 'border-stone-800/90 text-stone-400 bg-stone-950/70 hover:text-stone-200 hover:border-stone-700 hover:bg-stone-900/80'"
                class="p-2.5 rounded-lg text-left font-mono border transition-all duration-150 flex items-center gap-2.5 relative group cursor-pointer overflow-hidden">
            
            <!-- Active Left Glow Border Accent Bar -->
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500 transition-all duration-200"
                 :class="activeSuspectId === '{{ $suspect['id'] }}' ? 'opacity-100' : 'opacity-0'"></div>

            <!-- Dossier Badge Initial -->
            <div class="w-8 h-8 rounded bg-stone-800/90 border border-stone-700/80 flex items-center justify-center shrink-0 font-bold text-[11px] transition-colors"
                 :class="activeSuspectId === '{{ $suspect['id'] }}' ? 'border-amber-500/80 text-amber-300 bg-amber-950/60' : 'text-stone-400 group-hover:text-stone-200'">
                {{ substr($suspect['name'], 0, 2) }}
            </div>

            <!-- 2-Row Suspect Details -->
            <div class="flex-1 min-w-0 pr-1">
                <div class="flex items-center justify-between gap-1">
                    <span class="font-bold text-xs truncate uppercase tracking-tight block"
                          :class="activeSuspectId === '{{ $suspect['id'] }}' ? 'text-amber-200' : 'text-stone-300 group-hover:text-white'">
                        {{ $suspect['name'] }}
                    </span>
                    <!-- Status Badges: Locked vs Active Indicator -->
                    <template x-if="$store.game?.isSuspectLocked('{{ $suspect['id'] }}')">
                        <span class="px-1.5 py-0.2 text-[9px] font-mono font-bold uppercase rounded bg-red-950 text-red-400 border border-red-800 shrink-0">
                            🔒 BUNGKAM
                        </span>
                    </template>
                    <template x-if="!$store.game?.isSuspectLocked('{{ $suspect['id'] }}')">
                        <span class="w-1.5 h-1.5 rounded-full shrink-0"
                              :class="activeSuspectId === '{{ $suspect['id'] }}' ? 'bg-amber-400 shadow-[0_0_6px_#f59e0b]' : 'bg-transparent'"></span>
                    </template>
                </div>
                <span class="text-[10px] uppercase tracking-wider text-stone-400 font-mono truncate block mt-0.5">
                    {{ $suspect['role'] }}
                </span>
            </div>
        </button>
    @endforeach

    <!-- Mini Reel-to-Reel Tape Recorder Status & Transcript Launch Panel -->
    <div class="p-2 bg-stone-950/90 border border-stone-800/90 rounded-lg text-stone-400 font-mono text-xs shadow-inner flex items-center justify-between gap-2">
        <!-- Mini Reel-to-Reel Tape Recorder SVG Graphic -->
        <div class="flex items-center gap-1 bg-stone-900 border border-stone-700/80 px-1.5 py-1 rounded shadow-xs shrink-0">
            <!-- Left Reel -->
            <div class="w-3.5 h-3.5 rounded-full border border-stone-400 flex items-center justify-center transition-transform"
                 :class="isTyping ? 'animate-spin' : ''"
                 style="animation-duration: 2s;">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
            </div>
            <!-- Tape Window -->
            <div class="w-2.5 h-2 bg-stone-950 rounded-xs flex items-center justify-center">
                <div class="w-full h-0.5 bg-amber-600/70"></div>
            </div>
            <!-- Right Reel -->
            <div class="w-3.5 h-3.5 rounded-full border border-stone-400 flex items-center justify-center transition-transform"
                 :class="isTyping ? 'animate-spin' : ''"
                 style="animation-duration: 2s;">
                <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
            </div>
        </div>

        <div class="flex-1 min-w-0 flex flex-col justify-center">
            <div class="flex items-center gap-1.5">
                <span class="inline-block w-2 h-2 rounded-full shrink-0"
                      :class="isTyping ? 'bg-red-500 animate-ping' : 'bg-red-600'"></span>
                <span class="font-mono text-[10px] uppercase tracking-wider font-bold truncate text-stone-300"
                      x-text="isTyping ? 'MEREKAM...' : 'STANDBY'"></span>
            </div>
            <span class="text-[9px] text-stone-500 font-mono tracking-tight truncate mt-0.5">
                PENYADAPAN A-3
            </span>
        </div>

        <!-- Open Transcript Modal Button -->
        <button @click="$store.game.openTranscriptModal(activeSuspectId)"
                title="Buka Transkrip Rekaman Percakapan"
                class="px-2 py-1 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700/80 hover:border-amber-500/60 text-stone-300 hover:text-amber-300 font-mono text-[10px] tracking-wider uppercase transition flex items-center gap-1 cursor-pointer shrink-0 shadow-xs">
            <span>📜</span>
            <span class="hidden sm:inline">Transkrip</span>
        </button>
    </div>
</div>

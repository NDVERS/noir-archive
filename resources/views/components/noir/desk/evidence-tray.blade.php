@props([
    'evidences' => [],
])

<!-- Evidence Tray: Forensic Evidence Baggies Collection (Calibrated Height ~45-48%) -->
<div class="flex flex-col min-h-0 flex-[4.8] justify-between">
    <!-- Header Title -->
    <div class="flex items-center justify-between mb-1.5 shrink-0">
        <div class="flex items-center gap-1.5">
            <span class="text-xs">💼</span>
            <h3 class="text-[11px] font-bold font-mono tracking-wider text-stone-200 uppercase">
                TRAY BARANG BUKTI FORENSIK ({{ count($evidences) }})
            </h3>
        </div>
        <span class="text-[9px] font-mono text-stone-400 hidden sm:inline">Klik kantong bukti untuk analisa lab</span>
    </div>

    <!-- 2x3 Evidence Grid with Uniform Row Heights -->
    <div class="grid grid-cols-2 grid-rows-3 gap-1.5 sm:gap-2 flex-1 min-h-0 items-stretch">
        @foreach($evidences as $evidence)
            <div @click="$store.game.inspectedEvidence = {{ json_encode($evidence) }}; NoirAudio.playPaper()"
                 class="evidence-bag-card cursor-pointer p-1.5 sm:p-2 rounded-lg border border-stone-700/80 hover:border-amber-500/80 shadow-xs transition duration-150 flex items-center sm:items-start gap-2 group relative overflow-hidden h-full">
                
                <!-- Category Icon Badge -->
                <div class="w-7 h-7 sm:w-8 sm:h-8 rounded bg-stone-950/90 border border-stone-700 flex items-center justify-center text-amber-400 text-xs sm:text-sm shrink-0 group-hover:scale-105 group-hover:border-amber-500/60 transition shadow-inner">
                    @if($evidence['category'] === 'Physical Evidence')
                        🔧
                    @elseif($evidence['category'] === 'Digital Evidence')
                        💾
                    @elseif($evidence['category'] === 'Forensic Trace')
                        🧪
                    @else
                        📄
                    @endif
                </div>

                <!-- Evidence Metadata & Title -->
                <div class="flex-1 min-w-0 flex flex-col justify-center">
                    <div class="flex items-center justify-between gap-1">
                        <span class="text-[9px] font-mono font-bold text-red-400 bg-red-950/90 border border-red-800/60 px-1 py-0.2 rounded tracking-wide shrink-0">
                            {{ $evidence['id'] }}
                        </span>
                        <span class="text-[9px] font-mono font-semibold bg-stone-850 text-stone-200 px-1.5 py-0.2 rounded border border-stone-700 truncate">
                            {{ $evidence['category'] }}
                        </span>
                    </div>
                    <h4 class="font-bold text-[11px] sm:text-xs text-stone-100 truncate group-hover:text-amber-300 transition mt-0.5 leading-tight">
                        {{ $evidence['title'] }}
                    </h4>
                    <p class="text-[9px] sm:text-[10px] font-typewriter font-medium text-stone-300 line-clamp-1 mt-0.5 leading-none">
                        {{ $evidence['short_desc'] }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</div>

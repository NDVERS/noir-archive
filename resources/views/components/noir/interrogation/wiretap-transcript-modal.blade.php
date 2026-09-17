@props([
    'suspects' => [],
    'activeSuspectId' => 'suspect_thorne',
])

<!-- Wiretap Transcript Archive Modal -->
<div x-show="$store.game?.isTranscriptModalOpen" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/90 backdrop-blur-md"
     style="display: none;">
    
    <div @click.away="$store.game?.closeTranscriptModal()" 
         class="relative max-w-4xl w-full bg-stone-900 border-2 border-stone-600 rounded-xl p-5 sm:p-7 text-stone-100 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.95)] flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex flex-wrap justify-between items-start border-b border-stone-700/80 pb-4 mb-4 gap-3 shrink-0">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="stamp-classified px-2 py-0.5 text-[9px] font-mono">BERKAS PENYADAPAN POLISI</span>
                    <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-amber-400 bg-amber-950/60 border border-amber-500/40 px-2 py-0.5 rounded">
                        REKAMAN RUANG A-3
                    </span>
                    <template x-if="$store.game?.isSuspectLocked($store.game?.activeTranscriptSuspectId || activeSuspectId)">
                        <span class="text-[10px] font-mono font-bold uppercase tracking-wider text-red-400 bg-red-950/80 border border-red-600/80 px-2 py-0.5 rounded animate-pulse">
                            🔒 HAK BUNGKAM
                        </span>
                    </template>
                </div>
                <h3 class="text-xl sm:text-2xl font-black font-noir text-stone-100 tracking-wide uppercase flex items-center gap-2">
                    <span>📜 TRANSKRIP REKAMAN INTEROGASI</span>
                </h3>
                <div class="text-xs font-mono text-stone-400 mt-1 flex flex-wrap items-center gap-2">
                    <span>SUBJEK:</span>
                    <span class="text-amber-300 font-bold uppercase" 
                          x-text="(suspectsData || []).find(s => s.id === ($store.game?.activeTranscriptSuspectId || activeSuspectId))?.name || 'Saksi'"></span>
                    <span class="text-stone-600">//</span>
                    <span class="text-stone-400" 
                          x-text="'TOTAL ENTRI: ' + ($store.game?.getTranscript($store.game?.activeTranscriptSuspectId || activeSuspectId)?.length || 0) + ' LOG'"></span>
                </div>
            </div>

            <!-- Suspect switch tabs inside modal -->
            <div class="flex items-center gap-1.5 bg-stone-950 p-1 rounded-lg border border-stone-800">
                @foreach($suspects as $s)
                    <button @click="$store.game.activeTranscriptSuspectId = '{{ $s['id'] }}'; NoirAudio.playClick()"
                            :class="($store.game?.activeTranscriptSuspectId || activeSuspectId) === '{{ $s['id'] }}' ? 'bg-amber-500/20 text-amber-300 border-amber-500/60' : 'text-stone-400 hover:text-stone-200 border-transparent'"
                            class="px-2.5 py-1 text-[11px] font-mono rounded border transition cursor-pointer">
                        {{ substr($s['name'], 0, 2) }}
                    </button>
                @endforeach
                <button @click="$store.game.closeTranscriptModal()" 
                        class="ml-2 w-7 h-7 rounded bg-stone-800 hover:bg-stone-700 text-stone-300 hover:text-white flex items-center justify-center font-mono text-xs cursor-pointer">
                    ✕
                </button>
            </div>
        </div>

        <!-- Transcript Content Log List -->
        <div class="flex-1 overflow-y-auto space-y-3 pr-2 scrollbar-thin font-typewriter text-xs">
            <template x-if="!$store.game?.getTranscript($store.game?.activeTranscriptSuspectId || activeSuspectId) || $store.game?.getTranscript($store.game?.activeTranscriptSuspectId || activeSuspectId).length === 0">
                <div class="py-16 text-center text-stone-500 font-mono text-xs space-y-2">
                    <div class="text-3xl">📼</div>
                    <p class="uppercase tracking-wider">Belum ada rekaman transkrip percakapan untuk subjek ini.</p>
                    <p class="text-[11px] text-stone-600 font-typewriter">Lakukan interogasi, ajukan pertanyaan, atau gali alibi untuk mengisi pita transkrip.</p>
                </div>
            </template>

            <template x-for="(entry, eIdx) in ($store.game?.getTranscript($store.game?.activeTranscriptSuspectId || activeSuspectId) || [])" :key="eIdx">
                <div class="p-3 rounded-lg border transition-all duration-150 relative"
                     :class="{
                         'bg-red-950/40 border-red-800/80 shadow-[0_0_12px_rgba(220,38,38,0.15)]': entry.type === 'cracked_reaction' || entry.type === 'objection_action',
                         'bg-emerald-950/30 border-emerald-700/60': entry.type === 'conclusion' || entry.type === 'post_cracked',
                         'bg-amber-950/30 border-amber-800/50': entry.type === 'press' || entry.type === 'press_action' || entry.type === 'factual_claim',
                         'bg-stone-950/70 border-stone-800/80': entry.type === 'question' || entry.type === 'normal' || !entry.type
                     }">
                    
                    <!-- Row Header: Speaker, Badge & Timestamp -->
                    <div class="flex items-center justify-between gap-2 pb-1.5 mb-1.5 border-b border-stone-800/60 font-mono text-[10px]">
                        <div class="flex items-center gap-2">
                            <span class="font-bold tracking-wide uppercase"
                                  :class="entry.speaker?.includes('Detektif') ? 'text-amber-400' : 'text-stone-300'"
                                  x-text="entry.speaker"></span>
                            
                            <!-- Badges -->
                            <template x-if="entry.type === 'cracked_reaction' || entry.type === 'objection_action'">
                                <span class="px-1.5 py-0.5 rounded bg-red-900/80 border border-red-500 text-red-200 font-bold uppercase tracking-wider text-[9px]">
                                    [ OBJECTION // CRACKED ]
                                </span>
                            </template>
                            <template x-if="entry.type === 'conclusion'">
                                <span class="px-1.5 py-0.5 rounded bg-emerald-900/80 border border-emerald-500 text-emerald-200 font-bold uppercase tracking-wider text-[9px]">
                                    [ PENGAKUAN RESMI ]
                                </span>
                            </template>
                            <template x-if="entry.type === 'post_cracked'">
                                <span class="px-1.5 py-0.5 rounded bg-stone-900 border border-red-600 text-red-400 font-bold uppercase tracking-wider text-[9px]">
                                    [ HAK BUNGKAM ]
                                </span>
                            </template>
                            <template x-if="entry.type === 'press' || entry.type === 'press_action'">
                                <span class="px-1.5 py-0.5 rounded bg-amber-900/80 border border-amber-500 text-amber-200 font-bold uppercase tracking-wider text-[9px]">
                                    [ PRESS STATEMENT ]
                                </span>
                            </template>
                            <template x-if="entry.type === 'question'">
                                <span class="px-1.5 py-0.5 rounded bg-stone-800 border border-stone-600 text-stone-300 font-mono text-[9px]">
                                    [ PERTANYAAN ]
                                </span>
                            </template>
                        </div>

                        <span class="text-stone-500 font-mono" x-text="entry.timestamp"></span>
                    </div>

                    <!-- Log text -->
                    <p class="leading-relaxed font-typewriter text-stone-200 text-xs select-text"
                       :class="entry.speaker?.includes('Detektif') ? 'text-amber-200 italic' : 'text-stone-200'"
                       x-text="entry.text"></p>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="pt-4 border-t border-stone-800 flex flex-wrap items-center justify-between gap-3 shrink-0">
            <div class="text-[11px] font-mono text-stone-500 flex items-center gap-2">
                <span class="inline-block w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>ARSIP TERSINKRONISASI DENGAN MEJA KERJA & PAPAN INVESTIGASI</span>
            </div>

            <div class="flex items-center gap-2">
                <button @click="window.print()" 
                        class="px-3 py-1.5 bg-stone-800 hover:bg-stone-700 text-stone-300 font-mono text-xs rounded border border-stone-600 transition cursor-pointer flex items-center gap-1.5">
                    <span>🖨️</span>
                    <span>Cetak Transkrip</span>
                </button>
                <button @click="$store.game.closeTranscriptModal()" 
                        class="px-4 py-1.5 bg-stone-700 hover:bg-stone-600 text-white font-mono text-xs rounded border border-stone-500 transition cursor-pointer">
                    Tutup Arsip
                </button>
            </div>
        </div>

    </div>
</div>

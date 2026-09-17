<!-- Global Verdict Result Dossier Modal -->
<div x-show="$store.game.isVerdictModalOpen" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md overflow-y-auto"
     style="display: none;">
    
    <div class="relative max-w-xl w-full paper-texture rounded-sm p-6 text-stone-900 shadow-2xl border-4 border-stone-800 my-8">
        
        <div class="text-center border-b-2 border-stone-800/30 pb-4 mb-4">
            <div class="inline-block px-4 py-1 text-sm font-black tracking-widest uppercase mb-2 rounded"
                 :class="$store.game.verdictResult?.success ? 'stamp-red' : 'border-2 border-stone-800 text-stone-800'">
                <span x-text="$store.game.verdictResult?.success ? 'VONIS HUKUM: BERSALAH (CONVICTED)' : 'VONIS HUKUM: DITOLAK (ACQUITTED)'"></span>
            </div>
            <h2 class="text-3xl font-black font-noir text-stone-900" x-text="'EVALUASI DAKWAAN: GRADE ' + ($store.game.verdictResult?.grade || 'F')"></h2>
            <div class="font-mono text-sm font-bold mt-1" x-text="'Skor Akurasi Kasus: ' + ($store.game.verdictResult?.total_score || 0) + ' / 100 Poin'"></div>
        </div>

        <div class="space-y-4 font-typewriter text-xs leading-relaxed">
            <!-- Breakdown Cards -->
            <div class="grid grid-cols-3 gap-2 text-center">
                <div class="p-2 bg-stone-100 rounded border border-stone-400">
                    <div class="text-[10px] text-stone-600 uppercase">Tersangka (40)</div>
                    <div class="font-bold text-sm" 
                         :class="$store.game.verdictResult?.breakdown?.culprit?.is_correct ? 'text-emerald-700' : 'text-red-700'"
                         x-text="($store.game.verdictResult?.breakdown?.culprit?.score || 0) + ' Poin'"></div>
                </div>
                <div class="p-2 bg-stone-100 rounded border border-stone-400">
                    <div class="text-[10px] text-stone-600 uppercase">Motif (20)</div>
                    <div class="font-bold text-sm" 
                         :class="$store.game.verdictResult?.breakdown?.motive?.is_correct ? 'text-emerald-700' : 'text-red-700'"
                         x-text="($store.game.verdictResult?.breakdown?.motive?.score || 0) + ' Poin'"></div>
                </div>
                <div class="p-2 bg-stone-100 rounded border border-stone-400">
                    <div class="text-[10px] text-stone-600 uppercase">Rantai Bukti (40)</div>
                    <div class="font-bold text-sm text-stone-900" 
                         x-text="($store.game.verdictResult?.breakdown?.evidence_chain?.score || 0) + ' Poin'"></div>
                </div>
            </div>

            <!-- Epilogue Narrative -->
            <div class="p-4 bg-stone-100/90 border-l-4 rounded-r shadow-inner"
                 :class="$store.game.verdictResult?.success ? 'border-emerald-700' : 'border-red-800'">
                <div class="font-bold text-stone-900 mb-1 uppercase tracking-wide">
                    Laporan Akhir Jaksa Penuntut:
                </div>
                <p class="text-stone-800 leading-relaxed" x-text="$store.game.verdictResult?.narrative"></p>
            </div>
        </div>

        <div class="mt-6 pt-4 border-t border-stone-400 flex justify-between items-center">
            <button @click="$store.game.isVerdictModalOpen = false; NoirAudio.playClick()" class="px-4 py-2 bg-stone-400 hover:bg-stone-500 text-stone-900 font-mono text-xs rounded transition">
                Tutup Laporan
            </button>

            <button @click="$store.game.isVerdictModalOpen = false; $store.game.resetGame()" class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-amber-100 font-mono text-xs rounded transition shadow">
                Mulai Ulang Kasus
            </button>
        </div>
    </div>
</div>

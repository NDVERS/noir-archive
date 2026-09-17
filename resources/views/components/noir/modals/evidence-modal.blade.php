<!-- Global Evidence Inspection Modal -->
<div x-show="$store.game.inspectedEvidence !== null" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
     style="display: none;">
    <div @click.away="$store.game.inspectedEvidence = null" 
         class="relative max-w-lg w-full paper-texture rounded-sm p-6 text-stone-900 shadow-2xl border-4 border-stone-800">
        
        <div class="flex justify-between items-start border-b-2 border-stone-800/20 pb-3 mb-4">
            <div>
                <div class="text-[10px] font-mono uppercase tracking-widest text-red-800 font-bold">VERIDIA POLICE FORENSIC ARCHIVE</div>
                <h3 class="text-xl font-bold font-typewriter tracking-tight text-stone-900" x-text="$store.game.inspectedEvidence?.title"></h3>
            </div>
            <button @click="$store.game.inspectedEvidence = null" class="text-stone-600 hover:text-black font-mono font-bold text-lg">✕</button>
        </div>

        <div class="space-y-3 font-typewriter text-xs leading-relaxed text-stone-800">
            <div class="flex items-center gap-2">
                <span class="font-bold uppercase text-[11px] bg-stone-300/80 px-2 py-0.5 rounded text-stone-900" x-text="$store.game.inspectedEvidence?.category"></span>
                <span class="text-stone-600 font-mono text-[11px]" x-text="'Lokasi: ' + $store.game.inspectedEvidence?.found_at"></span>
            </div>

            <div class="p-3 bg-stone-100/70 border-l-4 border-red-800 rounded-r shadow-inner">
                <div class="font-bold text-stone-900 mb-1">Hasil Inspeksi Forensik:</div>
                <p x-text="$store.game.inspectedEvidence?.full_inspection"></p>
            </div>

            <div class="flex flex-wrap gap-1.5 pt-2">
                <template x-for="tag in ($store.game.inspectedEvidence?.tags || [])" :key="tag">
                    <span class="text-[10px] bg-stone-300 text-stone-700 px-1.5 py-0.5 rounded font-mono" x-text="'#' + tag"></span>
                </template>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-stone-400 flex justify-end">
            <button @click="$store.game.inspectedEvidence = null" class="px-4 py-1.5 bg-stone-900 hover:bg-stone-800 text-amber-100 font-mono text-xs rounded transition shadow">
                Tutup Berkas
            </button>
        </div>
    </div>
</div>

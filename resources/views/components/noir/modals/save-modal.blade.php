<!-- Save / Load Slots Modal -->
<div x-show="$store.game.isSaveModalOpen" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm"
     style="display: none;">
    <div @click.away="$store.game.isSaveModalOpen = false" 
         class="relative max-w-md w-full paper-texture rounded-sm p-5 text-stone-900 shadow-2xl border-4 border-stone-800">
        
        <div class="flex justify-between items-center border-b-2 border-stone-800/20 pb-2 mb-4">
            <h3 class="text-lg font-bold font-noir uppercase text-stone-900">ARSIP PENYIMPANAN KASUS</h3>
            <button @click="$store.game.isSaveModalOpen = false" class="text-stone-600 hover:text-black font-mono font-bold">✕</button>
        </div>

        <div class="space-y-3 font-typewriter text-xs">
            @for($slot = 1; $slot <= 3; $slot++)
                <div class="p-3 bg-stone-100 rounded border border-stone-400 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-stone-900">Slot #{{ $slot }}</div>
                        <div class="text-[10px] text-stone-600">Penyimpanan Flat-File JSON</div>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <button @click="$store.game.saveToSlot('{{ $slot }}')" class="px-2.5 py-1 bg-amber-800 hover:bg-amber-900 text-white rounded font-mono text-[11px] shadow">
                            Simpan
                        </button>
                        <button @click="$store.game.loadFromSlot('{{ $slot }}')" class="px-2.5 py-1 bg-stone-800 hover:bg-stone-900 text-stone-200 rounded font-mono text-[11px] shadow">
                            Muat
                        </button>
                    </div>
                </div>
            @endfor
        </div>
    </div>
</div>

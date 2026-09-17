@props([
    'evidences' => [],
])

<!-- Objection Evidence Selector Modal -->
<div x-show="objectionModalOpen" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md"
     style="display: none;">
    
    <div @click.away="objectionModalOpen = false" 
         class="relative max-w-3xl w-full paper-texture rounded-sm p-6 text-stone-900 shadow-2xl border-4 border-stone-800">
        
        <div class="flex justify-between items-start border-b-2 border-stone-800/30 pb-3 mb-4">
            <div>
                <span class="stamp-classified inline-block px-2 py-0.5 text-[10px] mb-1 font-mono">RUANG BUKTI INVESTIGASI</span>
                <h3 class="text-xl font-black font-typewriter tracking-tight text-stone-900 uppercase">
                    PILIH BARANG BUKTI UNTUK MENYANGGAH SAKSI
                </h3>
                <p class="font-typewriter text-xs text-stone-700 mt-0.5">
                    Klaim Saksi: "<span class="italic font-bold" x-text="getCurrentNode()?.text"></span>"
                </p>
            </div>
            <button @click="objectionModalOpen = false" class="text-stone-600 hover:text-black font-mono font-bold text-lg">✕</button>
        </div>

        <!-- Evidence Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-80 overflow-y-auto p-1 font-typewriter">
            @foreach($evidences as $e)
                <div @click="presentEvidence('{{ $e['id'] }}')"
                     class="cursor-pointer bg-stone-100 hover:bg-stone-200 p-3 rounded border-2 border-stone-400 hover:border-red-800 shadow transition flex flex-col justify-between group">
                    <div>
                        <div class="flex items-center justify-between text-[10px] font-mono text-stone-600 mb-1">
                            <span class="font-bold text-red-900 bg-stone-200 px-1 rounded">[{{ $e['id'] }}]</span>
                            <span>{{ $e['category'] }}</span>
                        </div>
                        <h4 class="font-bold text-xs text-stone-900 group-hover:text-red-900 transition">
                            {{ $e['title'] }}
                        </h4>
                        <p class="text-[11px] text-stone-700 line-clamp-2 mt-1 leading-snug">
                            {{ $e['short_desc'] }}
                        </p>
                    </div>
                    <div class="mt-2 pt-2 border-t border-stone-300 flex justify-end">
                        <span class="text-[10px] font-mono font-bold text-red-800 group-hover:underline">
                            Sodorkan Bukti Ini ➔
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 pt-3 border-t border-stone-400 flex justify-end">
            <button @click="objectionModalOpen = false" class="px-4 py-1.5 bg-stone-400 hover:bg-stone-500 text-stone-900 font-mono text-xs rounded transition">
                Batal
            </button>
        </div>
    </div>
</div>

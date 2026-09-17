@props([
    'caseInfo' => [],
])

<!-- Case Dossier: Manila Folder on Leather Desk Mat (Full Height Aligned) -->
<div class="h-full flex flex-col min-h-0 bg-desk-mat p-2.5 sm:p-3 rounded-lg shadow-2xl relative border border-stone-800/80">
    <!-- Manila Folder Document Container -->
    <div class="paper-texture p-3.5 sm:p-4 rounded-sm text-stone-900 shadow-[0_12px_28px_rgba(0,0,0,0.6)] border-3 sm:border-4 border-stone-800/90 relative flex flex-col flex-1 min-h-0 overflow-hidden justify-between">
        
        <!-- Diegetic Silver Metal Paperclip Visual -->
        <div class="absolute -top-3.5 left-5 z-20 pointer-events-none">
            <svg class="w-6 h-11 drop-shadow-md text-stone-600" viewBox="0 0 32 64" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 18 V46 C12 53, 20 53, 20 46 V10 C20 3, 4 3, 4 10 V48 C4 59, 28 59, 28 48 V18" class="stroke-zinc-700 fill-none" />
            </svg>
        </div>

        <!-- Dossier Header: Classified Stamps -->
        <div class="flex justify-between items-start border-b-2 border-stone-800/20 pb-2 mb-2 pl-6 shrink-0">
            <div>
                <span class="stamp-classified inline-block px-1.5 py-0.2 text-[8px] sm:text-[9px] mb-0.5 font-mono">DOSSIER RESMI #001</span>
                <h3 class="text-sm sm:text-base font-black font-typewriter tracking-tight text-stone-900 uppercase">
                    BERKAS PERKARA
                </h3>
            </div>
            <div class="stamp-red px-1.5 py-0.2 text-[10px] sm:text-[11px] font-black tracking-widest shadow-xs">
                RAHASIA
            </div>
        </div>

        <!-- Dossier Body: Info, Briefing & Objectives (Internal Scroll if needed) -->
        <div class="flex-1 min-h-0 overflow-y-auto custom-scrollbar space-y-2 font-typewriter text-xs text-stone-900 pr-1">
            <!-- Victim / Reporter Card -->
            <div>
                <span class="font-bold text-stone-950 uppercase tracking-wide text-[9px] sm:text-[10px] block mb-0.5">KORBAN / PELAPOR:</span>
                <div class="text-stone-900 bg-stone-100/90 border border-stone-300 p-1.5 rounded font-semibold text-[11px] sm:text-xs shadow-inner">
                    {{ $caseInfo['victim'] ?? 'Dr. Wallace Vance (Chief Quantum Scientist)' }}
                </div>
            </div>

            <!-- Incident Location & Time -->
            <div>
                <span class="font-bold text-stone-950 uppercase tracking-wide text-[9px] sm:text-[10px] block mb-0.5">LOKASI & WAKTU KEJADIAN:</span>
                <div class="text-stone-800 bg-stone-100/90 border border-stone-300 p-1.5 rounded text-[11px] sm:text-xs shadow-inner">
                    {{ $caseInfo['location'] ?? 'Aethelgard Quantum Dynamics - Sublevel 3 Facility' }}
                </div>
            </div>

            <!-- Investigation Instructions -->
            <div>
                <span class="font-bold text-stone-950 uppercase tracking-wide text-[9px] sm:text-[10px] block mb-0.5">INSTRUKSI INVESTIGASI:</span>
                <p class="text-stone-800 italic bg-amber-50/50 p-2 rounded border-l-3 border-amber-700 leading-snug text-[10px] sm:text-[11px] shadow-xs">
                    "{{ $caseInfo['initial_briefing'] ?? '' }}"
                </p>
            </div>

            <!-- Case Objectives Checklist -->
            <div class="pt-1.5 border-t border-stone-800/20">
                <span class="font-bold text-stone-950 uppercase tracking-wide text-[9px] sm:text-[10px] block mb-1">TARGET PENYELESAIAN KASUS:</span>
                <ul class="space-y-1 text-xs text-stone-900 leading-tight">
                    @foreach($caseInfo['objectives'] ?? [] as $index => $obj)
                        <li class="flex items-start gap-1.5 bg-stone-100/70 p-1 rounded border border-stone-300/60">
                            <span class="font-black text-red-800 font-mono text-[10px] shrink-0">[{{ $index + 1 }}]</span>
                            <span class="font-medium text-[10px] sm:text-[11px] leading-tight">{{ $obj }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Dossier Footer Archival Strip -->
        <div class="mt-1.5 pt-1.5 border-t border-stone-800/15 flex items-center justify-between text-[9px] font-mono text-stone-600 shrink-0">
            <span>KLASIFIKASI: TINGKAT 1</span>
            <span class="font-bold text-stone-800">STATUS: PENYELIDIKAN AKTIF</span>
        </div>

    </div>
</div>

@props([
    'caseId' => 'case_001',
    'activeSuspectId' => 'suspect_thorne',
])

<!-- Cinematic Start Overlay (Guarantees User Gesture before Dialogue Audio) -->
<div x-show="!hasStarted" 
     x-transition:leave="transition ease-out duration-400"
     x-transition:leave-start="opacity-100 scale-100"
     x-transition:leave-end="opacity-0 scale-105 pointer-events-none"
     @click="startInterrogation()"
     class="fixed inset-0 z-50 bg-black/92 backdrop-blur-md flex flex-col items-center justify-center p-4 sm:p-6 text-center cursor-pointer select-none">
    
    <!-- Ambient Radial Spotlight behind Card -->
    <div class="absolute inset-0 opacity-25 pointer-events-none bg-[radial-gradient(ellipse_at_center,rgba(245,158,11,0.25)_0%,transparent_65%)]"></div>

    <!-- Classified Police Dossier Card -->
    <div class="relative z-10 max-w-xl w-full bg-stone-900/95 border-2 border-stone-700/80 p-6 sm:p-9 rounded-xl shadow-[0_25px_60px_-15px_rgba(0,0,0,0.95)] space-y-5 text-left transform hover:scale-[1.01] transition overflow-hidden backdrop-blur-md">
        
        <!-- Corner Metal Rivet Accents -->
        <div class="absolute top-2.5 left-2.5 w-3.5 h-3.5 border-t-2 border-l-2 border-amber-600/70 pointer-events-none"></div>
        <div class="absolute top-2.5 right-2.5 w-3.5 h-3.5 border-t-2 border-r-2 border-amber-600/70 pointer-events-none"></div>
        <div class="absolute bottom-2.5 left-2.5 w-3.5 h-3.5 border-b-2 border-l-2 border-amber-600/70 pointer-events-none"></div>
        <div class="absolute bottom-2.5 right-2.5 w-3.5 h-3.5 border-b-2 border-r-2 border-amber-600/70 pointer-events-none"></div>

        <!-- Header Stamp Row -->
        <div class="flex justify-between items-center border-b border-stone-800 pb-3 pl-2 pr-2">
            <span class="stamp-classified text-[10px] px-2 py-0.5 font-mono">DOKUMEN INTEROGASI POLISI #53</span>
            <div class="stamp-red px-2.5 py-0.5 text-[10px] font-mono font-black tracking-widest uppercase -rotate-6 shadow-sm">
                TOP SECRET // CONFIDENTIAL
            </div>
        </div>

        <!-- Reel-to-Reel Tape Recorder & Title Section -->
        <div class="text-center space-y-3 pt-1">
            <!-- 1950s Reel-to-Reel Magnetic Audio Tape SVG -->
            <div class="flex items-center justify-center">
                <div class="w-24 h-14 bg-stone-950 rounded-lg border border-stone-700 shadow-inner flex items-center justify-around px-2.5 relative">
                    <!-- Left Tape Reel -->
                    <div class="w-8 h-8 rounded-full border-2 border-stone-500 flex items-center justify-center animate-spin" style="animation-duration: 5s;">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                    </div>
                    <!-- Tape Head -->
                    <div class="w-2.5 h-5 bg-stone-700 rounded-xs flex flex-col items-center justify-center">
                        <div class="w-1 h-2 bg-stone-900"></div>
                    </div>
                    <!-- Right Tape Reel -->
                    <div class="w-8 h-8 rounded-full border-2 border-stone-500 flex items-center justify-center animate-spin" style="animation-duration: 5s;">
                        <div class="w-2.5 h-2.5 rounded-full bg-amber-500"></div>
                    </div>
                    <!-- Tape Status REC LED -->
                    <div class="absolute top-1.5 right-2 w-1.5 h-1.5 rounded-full bg-red-600 animate-ping"></div>
                </div>
            </div>

            <h2 class="text-2xl sm:text-3xl font-black font-noir text-stone-100 uppercase tracking-wider">
                SESI INTEROGASI SAKSI
            </h2>

            <div class="text-xs font-mono text-stone-400 bg-stone-950/80 p-2 rounded border border-stone-800">
                <span>SUBJEK: </span>
                <span class="text-amber-400 font-bold uppercase tracking-wide" 
                      x-text="activeSuspectId === 'suspect_thorne' ? 'DR. ARIS THORNE // LEAD QUANTUM ENGINEER' : (activeSuspectId === 'suspect_elena' ? 'ELENA VANCE // SECURITY CHIEF' : 'CORTEZ CROFT // VENTURE BROKER')"></span>
            </div>

            <p class="text-xs font-typewriter text-stone-400 leading-relaxed max-w-md mx-auto pt-1">
                Gali motif, uji alibi, dan cocokkan bukti fisik dari meja kerja untuk meruntuhkan kebohongan saksi.
            </p>
        </div>

        <!-- Pulsing Start Action CTA (Clean 1-line whitespace-nowrap) -->
        <div class="pt-2 w-full flex justify-center">
            <div class="w-full max-w-md px-6 py-3.5 bg-amber-950/90 hover:bg-amber-900/90 text-amber-200 border-2 border-amber-500 rounded-lg text-xs sm:text-sm font-mono font-bold tracking-widest shadow-[0_0_30px_rgba(245,158,11,0.35)] animate-pulse flex items-center justify-center gap-2 whitespace-nowrap">
                <span>[</span>
                <span>TEKAN SPASI ATAU KLIK UNTUK MASUK</span>
                <span>]</span>
            </div>
        </div>

        <!-- Quick Navigation Back Links (Isolated with @click.stop) -->
        <div class="flex flex-wrap items-center justify-center gap-3 pt-1">
            <a href="{{ route('game.desk', ['caseId' => $caseId ?? 'case_001']) }}" 
               @click.stop="NoirAudio.playClick()" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded bg-stone-950/80 hover:bg-stone-800 border border-stone-700/80 hover:border-amber-500/60 text-stone-400 hover:text-amber-300 font-mono text-xs tracking-wider transition-all shadow-sm cursor-pointer">
                <span>📂</span>
                <span>Kembali ke Meja Kerja</span>
            </a>
            
            <a href="{{ route('game.board', ['caseId' => $caseId ?? 'case_001']) }}" 
               @click.stop="NoirAudio.playClick()" 
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded bg-stone-950/80 hover:bg-stone-800 border border-stone-700/80 hover:border-amber-500/60 text-stone-400 hover:text-amber-300 font-mono text-xs tracking-wider transition-all shadow-sm cursor-pointer">
                <span>📌</span>
                <span>Papan Investigasi</span>
            </a>
        </div>

        <!-- Control Legend Footer with Retro Keycaps -->
        <div class="pt-3 border-t border-stone-800/90 flex flex-wrap items-center justify-center gap-4 text-xs font-mono text-stone-400">
            <div class="flex items-center gap-1.5">
                <kbd class="px-2 py-0.5 bg-stone-800/90 border border-stone-600 rounded text-amber-400 font-mono text-[11px] shadow-inner font-bold">SPASI / ENTER</kbd>
                <span>Lewati Teks</span>
            </div>
            <div class="flex items-center gap-1.5">
                <kbd class="px-2 py-0.5 bg-stone-800/90 border border-stone-600 rounded text-amber-400 font-mono text-[11px] shadow-inner font-bold">1 - 9</kbd>
                <span>Pilih Opsi Dialog</span>
            </div>
        </div>
    </div>
</div>

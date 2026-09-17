<!-- Suspect Tension & Dynamic Polygraph ECG HUD Card -->
<div class="w-full p-2.5 bg-polygraph-grid rounded-lg border shadow-inner text-left relative overflow-hidden transition-all duration-300"
     :class="effectiveTension >= 100 ? 'border-red-600/90 shadow-[0_0_20px_rgba(239,68,68,0.25)]' : (effectiveTension >= 70 ? 'border-orange-500/80 shadow-[0_0_15px_rgba(249,115,22,0.15)]' : 'border-stone-700/80')">
    
    <!-- Header: Polygraph Label & Dynamic BPM Readout -->
    <div class="flex items-center justify-between text-[10px] font-mono mb-1">
        <span class="text-stone-400 uppercase tracking-wider flex items-center gap-1.5 font-bold">
            <span class="inline-block w-1.5 h-1.5 rounded-full" 
                  :class="effectiveTension >= 100 ? 'bg-red-500 animate-ping' : (effectiveTension >= 70 ? 'bg-orange-500 animate-pulse' : (effectiveTension >= 40 ? 'bg-amber-400' : 'bg-emerald-400'))"></span>
            POLIGRAF ECG
        </span>
        <span class="font-bold font-mono tracking-wider" :class="tensionStatus.color" x-text="tensionStatus.bpm + ' BPM'"></span>
    </div>

    <!-- Real-time Oscilloscope Screen with Animated Waveform & Glowing Laser Blip -->
    <div class="relative h-10 w-full bg-stone-950/90 rounded border border-stone-800/90 overflow-hidden my-1.5 flex items-center">
        <!-- Seamless Looping Cardiogram Waveform -->
        <div class="w-[200%] h-full flex items-center animate-ecg-scroll shrink-0" 
             :style="'--ecg-duration: ' + tensionStatus.duration">
            <svg class="w-full h-full drop-shadow-sm" 
                 :class="tensionStatus.color" 
                 viewBox="0 0 300 50" 
                 preserveAspectRatio="none" 
                 fill="none" 
                 stroke="currentColor" 
                 stroke-width="2.2" 
                 stroke-linecap="round" 
                 stroke-linejoin="round">
                <path :d="tensionStatus.path" />
            </svg>
        </div>

        <!-- Active Pulse Glowing Laser Blip Dot -->
        <div class="absolute right-2 top-1/2 -translate-y-1/2 w-2 h-2 rounded-full animate-laser-blip pointer-events-none"
             :style="'background-color: ' + tensionStatus.glowColor + '; box-shadow: 0 0 8px ' + tensionStatus.glowColor"></div>

        <!-- Screen Edge Vignette & Phosphor CRT Overlay -->
        <div class="absolute inset-0 bg-linear-to-r from-stone-950/60 via-transparent to-stone-950/60 pointer-events-none"></div>
    </div>

    <!-- Tension Progress Meter -->
    <div class="relative w-full h-1.5 bg-stone-950 rounded-full overflow-hidden border border-stone-800/80 mb-1.5">
        <div class="h-full rounded-full transition-all duration-500"
             :style="'width: ' + effectiveTension + '%'"
             :class="effectiveTension >= 100 ? 'bg-linear-to-r from-red-600 via-rose-500 to-amber-500 animate-pulse' : (effectiveTension >= 70 ? 'bg-linear-to-r from-amber-600 to-orange-500' : (effectiveTension >= 40 ? 'bg-linear-to-r from-amber-500 to-yellow-400' : 'bg-linear-to-r from-emerald-600 to-emerald-400'))">
        </div>
    </div>
    
    <!-- Status Label Badge -->
    <div class="text-[9px] font-mono text-center uppercase font-bold tracking-tight py-0.5 px-1 rounded bg-stone-950/70 border border-stone-800/60" 
         :class="tensionStatus.color" 
         x-text="tensionStatus.label"></div>
</div>

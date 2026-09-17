<!-- Left: Suspect Spotlight Frame, Mood Badge & Dynamic Tension ECG HUD -->
<div class="flex flex-col items-center text-center shrink-0 w-52">
    <div class="relative">
        <!-- Outer Glow Ring with Vintage Mugshot Spotlight & Dynamic Tension Vibration -->
        <div class="w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-linear-to-b from-stone-700 via-stone-800 to-stone-950 border-4 flex items-center justify-center shadow-[0_15px_35px_rgba(0,0,0,0.9)] overflow-hidden transition-all duration-300"
             :class="{
                 'animate-breakdown-shake border-red-600 shadow-red-900/70': suspectTension >= 100 || getCurrentNode()?.type === 'cracked_reaction',
                 'animate-tension-shake border-orange-500 shadow-orange-950/50': suspectTension >= 50 && suspectTension < 100,
                 'border-stone-600/90': suspectTension < 50
             }">
            
            <!-- Dynamic Silhouette SVG according to active suspect -->
            <template x-if="activeSuspectId === 'suspect_thorne'">
                <svg class="w-36 h-36 drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                    <path d="M50 14 C34 14, 26 25, 26 42 C26 56, 36 64, 50 64 C64 64, 74 56, 74 42 C74 25, 66 14, 50 14 Z" fill="#1c1917" stroke="#e7e5e4" stroke-width="2"/>
                    <path d="M24 38 C22 22, 34 10, 50 10 C66 10, 78 22, 76 38 C70 26, 60 20, 50 20 C40 20, 30 26, 24 38 Z" fill="#0c0a09" stroke="#d6d3d1" stroke-width="1.5"/>
                    <circle cx="40" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                    <circle cx="60" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                    <line x1="48" y1="40" x2="52" y2="40" stroke="#fafaf9" stroke-width="2.5"/>
                    <line x1="36" y1="36" x2="42" y2="42" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    <line x1="56" y1="36" x2="62" y2="42" stroke="#ffffff" stroke-width="2" stroke-linecap="round"/>
                    <path d="M50 44 L48 50 L52 50" stroke="#d6d3d1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <line x1="46" y1="56" x2="54" y2="56" stroke="#d6d3d1" stroke-width="1.5" stroke-linecap="round"/>
                    <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                    <path d="M38 66 L50 82 L62 66" stroke="#f59e0b" stroke-width="2" fill="#292524"/>
                    <path d="M47 82 L53 82 L50 96 Z" fill="#dc2626"/>
                </svg>
            </template>

            <template x-if="activeSuspectId === 'suspect_elena'">
                <svg class="w-36 h-36 drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                    <path d="M50 12 C32 12, 22 24, 22 46 C22 62, 28 68, 32 68 C32 52, 38 20, 50 20 C62 20, 68 52, 68 68 C72 68, 78 62, 78 46 C78 24, 68 12, 50 12 Z" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                    <path d="M50 24 C40 24, 36 34, 36 48 C36 58, 42 64, 50 64 C58 64, 64 58, 64 48 C64 34, 60 24, 50 24 Z" fill="#262626" stroke="#d6d3d1" stroke-width="1.5"/>
                    <path d="M39 42 Q44 38 49 42" stroke="#fafaf9" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="44" cy="42" r="1.5" fill="#fafaf9"/>
                    <path d="M51 42 Q56 38 61 42" stroke="#fafaf9" stroke-width="2.5" stroke-linecap="round"/>
                    <circle cx="56" cy="42" r="1.5" fill="#fafaf9"/>
                    <path d="M46 54 Q50 52 54 54 Q50 57 46 54 Z" fill="#dc2626" stroke="#ef4444" stroke-width="0.8"/>
                    <path d="M14 96 C14 74, 28 66, 50 66 C72 66, 86 74, 86 96 Z" fill="#171717" stroke="#e7e5e4" stroke-width="2"/>
                    <path d="M28 66 L40 80 L50 66 L60 80 L72 66" stroke="#991b1b" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </template>

            <template x-if="activeSuspectId === 'suspect_croft'">
                <div class="relative w-36 h-36 flex items-center justify-center">
                    <!-- Floating Cigar Smoke Particles -->
                    <div class="absolute top-6 right-2 pointer-events-none z-30">
                        <span class="absolute w-3.5 h-3.5 rounded-full cigar-smoke-1"></span>
                        <span class="absolute w-4 h-4 rounded-full cigar-smoke-2"></span>
                        <span class="absolute w-3 h-3 rounded-full cigar-smoke-3"></span>
                    </div>
                    <svg class="w-36 h-36 drop-shadow-[0_4px_8px_rgba(0,0,0,0.8)]" viewBox="0 0 100 100" fill="none">
                        <path d="M50 10 C36 10, 38 28, 38 34 L62 34 C62 28, 64 10, 50 10 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                        <path d="M38 28 L62 28" stroke="#dc2626" stroke-width="3.5"/>
                        <ellipse cx="50" cy="34" rx="30" ry="6" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                        <path d="M50 34 C41 34, 38 42, 38 52 C38 60, 44 64, 50 64 C56 64, 62 60, 62 52 C62 42, 59 34, 50 34 Z" fill="#27272a" stroke="#d6d3d1" stroke-width="1.5"/>
                        <line x1="42" y1="44" x2="48" y2="44" stroke="#fafaf9" stroke-width="2" stroke-linecap="round"/>
                        <line x1="52" y1="44" x2="58" y2="44" stroke="#fafaf9" stroke-width="2" stroke-linecap="round"/>
                        <line x1="54" y1="56" x2="68" y2="54" stroke="#ffffff" stroke-width="2.5" stroke-linecap="round"/>
                        <circle cx="68" cy="54" r="2" fill="#ea580c" stroke="#f97316" stroke-width="1"/>
                        <path d="M70 52 Q76 44 70 36 T78 24" stroke="#d4d4d8" stroke-width="2" stroke-linecap="round" opacity="0.85"/>
                        <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                        <path d="M42 66 L50 82 L58 66 Z" fill="#ffffff" stroke="#e7e5e4" stroke-width="1"/>
                        <polygon points="46,71 54,71 50,75" fill="#000000"/>
                        <polygon points="46,75 54,75 50,71" fill="#000000"/>
                    </svg>
                </div>
            </template>
        </div>

        <!-- Mood Badge -->
        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-3 py-0.5 rounded-full text-[10px] font-mono font-bold uppercase shadow tracking-wider whitespace-nowrap"
             :class="getCurrentNode()?.type === 'cracked_reaction' ? 'bg-red-800 text-white animate-bounce' : (getCurrentNode()?.type === 'post_cracked' ? 'bg-stone-950 border-2 border-red-600 text-red-400 shadow-md font-bold' : (getCurrentNode()?.type === 'conclusion' ? 'bg-emerald-900 border border-emerald-500/80 text-emerald-200 animate-pulse' : (getCurrentNode()?.type === 'factual_claim' ? 'bg-amber-700 text-amber-100' : 'bg-stone-800 text-stone-300')))">
            <span x-text="getCurrentNode()?.type === 'cracked_reaction' ? 'GUGUR ALIBI (CRACKED)' : (getCurrentNode()?.type === 'post_cracked' ? 'HAK BUNGKAM (CLOSED)' : (getCurrentNode()?.type === 'conclusion' ? 'PENGAKUAN TERCATAT' : (getCurrentNode()?.type === 'factual_claim' ? 'KLAIM FAKTA' : 'TENANG')))"></span>
        </div>
    </div>

    <h3 class="text-base font-bold font-noir text-stone-100 mt-4" 
        x-text="getCurrentNode()?.speaker || 'Saksi'"></h3>
    <div class="text-xs font-mono text-stone-400 mb-3" 
         x-text="activeSuspectId === 'suspect_thorne' ? 'Lead Quantum Engineer' : (activeSuspectId === 'suspect_elena' ? 'Security Chief' : 'Venture Broker')"></div>

    <!-- Embedded Polygraph ECG HUD Component -->
    <x-noir.interrogation.polygraph-card />
</div>

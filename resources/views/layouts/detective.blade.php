<!DOCTYPE html>
<html lang="id" class="h-full bg-noir-950 text-stone-200">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'The Phantom Prototype') - Veridia Noir Investigations</title>

    <!-- Retro-Noir Favicon & Theme Color -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#0c0a09">

    <!-- Google Fonts for Noir Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Instrument+Sans:wght@400;500;600;700&family=Special+Elite&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-noir-950 text-stone-300 font-sans antialiased selection:bg-crimson-800 selection:text-white min-h-screen h-screen overflow-hidden flex flex-col"
      :class="{ 'crt-scanlines': $store.game.crtEnabled, 'animate-screen-shake': $store.game.screenShake }"
      x-data
      x-init="$store.game.initGame({
          credibility: {{ $saves[0]['credibility_score'] ?? 100 }},
          unlocked_clues: []
      })">

    <!-- Vignette Atmosphere Overlay -->
    <div class="fixed inset-0 vignette-overlay z-40 pointer-events-none"></div>

    <!-- Screen Flash on Objection / Alibi Breakdown -->
    <div x-show="$store.game.objectionFlash" 
         x-transition:enter="transition ease-out duration-75"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-350"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-105"
         class="fixed inset-0 bg-linear-to-t from-red-600/60 via-amber-500/30 to-red-600/60 z-50 pointer-events-none animate-screen-flash" 
         style="display: none;"></div>

    <!-- Top Detective HUD -->
    <header class="sticky top-0 z-30 shrink-0 bg-noir-900/95 border-b border-stone-800/80 backdrop-blur-md px-4 py-1.5 sm:py-2 shadow-2xl">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-3">
            
            <!-- Left: Detective Agency Badge & Case Title -->
            <a href="{{ route('game.welcome') }}" 
               @click="NoirAudio.playClick()"
               class="flex items-center gap-3 group transition cursor-pointer" 
               title="Kembali ke Menu Utama">
                <div class="relative flex items-center justify-center w-10 h-10 rounded-full bg-stone-950 border border-amber-500/60 shadow-[0_0_12px_rgba(245,158,11,0.25)] p-0.5 shrink-0 group-hover:border-amber-400 group-hover:scale-105 transition-all">
                    <img src="{{ asset('favicon.svg') }}" 
                         alt="Veridia Police Department Logo" 
                         class="w-full h-full object-contain filter drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)]">
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-mono tracking-widest text-red-500 uppercase font-semibold">CONFIDENTIAL FILE</span>
                        <span class="text-[10px] bg-stone-800 px-1.5 py-0.5 rounded text-stone-400 font-mono">CASE #001</span>
                    </div>
                    <h1 class="text-base font-bold font-noir text-stone-100 group-hover:text-amber-400 tracking-wide transition-colors">
                        {{ $caseInfo['title'] ?? 'The Phantom Prototype' }}
                    </h1>
                </div>
            </a>

            <!-- Center: Navigation Tabs -->
            <nav class="flex items-center gap-1.5 bg-noir-950/80 p-1 rounded-lg border border-stone-800">
                <a href="{{ route('game.desk', ['caseId' => $caseId ?? 'case_001']) }}" 
                   @click="NoirAudio.playClick()"
                   class="px-3 py-1.5 text-xs font-medium rounded-md transition-all flex items-center gap-1.5 {{ request()->routeIs('game.desk', 'game.home') ? 'bg-stone-800 text-amber-400 border border-amber-500/30 shadow-sm' : 'text-stone-400 hover:text-stone-200 hover:bg-stone-900' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    <span>Meja Kerja</span>
                </a>

                <a href="{{ route('game.board', ['caseId' => $caseId ?? 'case_001']) }}" 
                   @click="NoirAudio.playClick()"
                   class="px-3 py-1.5 text-xs font-medium rounded-md transition-all flex items-center gap-1.5 {{ request()->routeIs('game.board') ? 'bg-stone-800 text-red-400 border border-red-500/30 shadow-sm' : 'text-stone-400 hover:text-stone-200 hover:bg-stone-900' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                    <span>Papan Investigasi</span>
                </a>

                <a href="{{ route('game.interrogation', ['caseId' => $caseId ?? 'case_001']) }}" 
                   @click="NoirAudio.playClick()"
                   class="px-3 py-1.5 text-xs font-medium rounded-md transition-all flex items-center gap-1.5 {{ request()->routeIs('game.interrogation') ? 'bg-stone-800 text-blue-400 border border-blue-500/30 shadow-sm' : 'text-stone-400 hover:text-stone-200 hover:bg-stone-900' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    <span>Ruang Interogasi</span>
                </a>

                <button @click="$store.game.isIndictmentModalOpen = true; NoirAudio.playPaper()"
                        class="px-3 py-1.5 text-xs font-semibold rounded-md bg-linear-to-r from-red-900 to-red-950 text-red-200 border border-red-700/60 hover:from-red-800 hover:to-red-900 transition-all flex items-center gap-1.5 shadow-md">
                    <svg class="w-3.5 h-3.5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <span>Surat Dakwaan</span>
                </button>
            </nav>

            <!-- Right: Credibility Bar & Actions -->
            <div class="flex items-center gap-4">
                <!-- Credibility Meter -->
                <div class="flex items-center gap-2 bg-stone-900/90 px-3 py-1.5 rounded-lg border border-stone-800">
                    <div class="text-right">
                        <div class="text-[10px] uppercase font-mono tracking-wider text-stone-400">Kredibilitas</div>
                        <div class="text-xs font-bold font-mono" 
                             :class="$store.game.credibility > 50 ? 'text-emerald-400' : ($store.game.credibility > 25 ? 'text-amber-400' : 'text-red-500')"
                             x-text="$store.game.credibility + ' %'">100 %</div>
                    </div>
                    <div class="w-20 bg-stone-950 rounded-full h-2.5 overflow-hidden border border-stone-700">
                        <div class="h-full transition-all duration-500 rounded-full"
                             :style="'width: ' + $store.game.credibility + '%'"
                             :class="$store.game.credibility > 50 ? 'bg-linear-to-r from-emerald-600 to-emerald-400' : ($store.game.credibility > 25 ? 'bg-linear-to-r from-amber-600 to-amber-400' : 'bg-linear-to-r from-red-700 to-red-500 animate-pulse')">
                        </div>
                    </div>
                </div>

                <!-- Utilities: CRT, Audio & Save -->
                <div class="flex items-center gap-1.5 text-stone-400">
                    <button @click="$store.game.toggleCrt(); NoirAudio.playClick()" 
                            class="px-2 py-1 rounded transition text-xs font-mono flex items-center gap-1 border shadow-sm"
                            :class="$store.game.crtEnabled ? 'border-amber-600/70 text-amber-300 bg-amber-950/40 hover:bg-amber-900/50' : 'border-stone-800 text-stone-500 hover:text-stone-300 bg-stone-900/80'"
                            title="Toggle Retro CRT Scanlines & Noir Vignette">
                        <span>📺</span>
                        <span class="text-[10px] font-bold tracking-wider" x-text="$store.game.crtEnabled ? 'CRT ON' : 'CRT OFF'"></span>
                    </button>

                    <button @click="const state = NoirAudio.toggle(); $store.game.soundEnabled = state; $store.game.showToast(state ? 'Suara Diaktifkan' : 'Suara Dimatikan', 'info')" 
                            class="p-1.5 hover:text-stone-100 hover:bg-stone-800 rounded transition" 
                            title="Toggle Audio FX">
                        <span x-show="$store.game.soundEnabled" class="text-xs">🔊</span>
                        <span x-show="!$store.game.soundEnabled" class="text-xs" style="display: none;">🔇</span>
                    </button>

                    <button @click="$store.game.isSaveModalOpen = true; NoirAudio.playClick()" 
                            class="p-1.5 hover:text-amber-400 hover:bg-stone-800 rounded transition text-xs font-mono flex items-center gap-1" 
                            title="Save / Load State">
                        💾 <span class="hidden sm:inline">Save</span>
                    </button>

                    <button @click="$store.game.resetGame()" 
                            class="p-1.5 hover:text-red-400 hover:bg-stone-800 rounded transition text-xs font-mono" 
                            title="Reset Progress">
                        🔄
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Global Toast Notification -->
    <div x-show="$store.game.toastVisible" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-4"
         class="fixed top-16 right-6 z-50 max-w-sm rounded-lg shadow-2xl p-3 border font-mono text-xs flex items-center gap-2"
         :class="{
             'bg-stone-900/95 border-emerald-500/60 text-emerald-300': $store.game.toastType === 'success',
             'bg-stone-900/95 border-red-500/60 text-red-300': $store.game.toastType === 'error',
             'bg-stone-900/95 border-amber-500/60 text-amber-300': $store.game.toastType === 'info'
         }"
         style="display: none;">
        <span x-text="$store.game.toastType === 'success' ? '✓' : ($store.game.toastType === 'error' ? '⚠' : 'ℹ')"></span>
        <span x-text="$store.game.toastMessage" class="flex-1 leading-snug"></span>
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 min-h-0 w-full overflow-hidden flex flex-col relative">
        @yield('content')
    </main>

    <!-- Global Modals -->
    <x-noir.modals.evidence-modal />
    <x-noir.modals.indictment-modal :suspects="$suspects ?? []" :evidences="$evidences ?? []" :case-id="$caseId ?? 'case_001'" />
    <x-noir.modals.verdict-modal />
    <x-noir.modals.save-modal />

</body>
</html>

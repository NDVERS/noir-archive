<!DOCTYPE html>
<html lang="id" class="h-full bg-stone-950 text-stone-200">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>The Phantom Prototype - Start Menu // Veridia Police Department</title>

    <!-- Retro-Noir Favicon & Theme Color -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="alternate icon" href="{{ asset('favicon.ico') }}">
    <meta name="theme-color" content="#0c0a09">

    <!-- Google Fonts for 1950s Detective Noir Aesthetic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;700;900&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Instrument+Sans:wght@400;500;600;700&family=Special+Elite&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-screen w-screen overflow-hidden bg-stone-950 text-stone-300 font-mono antialiased select-none relative flex flex-col justify-between"
      x-data="{
          crtEnabled: true,
          sfxEnabled: true,
          showLoadModal: false,
          showBriefModal: false,
          showSettingsModal: false,
          hoveredMenu: null,
          isDispatching: false,
          saves: @js($saves ?? []),
          
          init() {
              const savedCrt = localStorage.getItem('noir_crt_enabled');
              if (savedCrt !== null) this.crtEnabled = (savedCrt === 'true');

              const savedSfx = localStorage.getItem('noir_sfx_enabled');
              if (savedSfx !== null) this.sfxEnabled = (savedSfx === 'true');
          },

          toggleCrt() {
              this.crtEnabled = !this.crtEnabled;
              localStorage.setItem('noir_crt_enabled', this.crtEnabled);
              this.playClick();
          },

          toggleSfx() {
              this.sfxEnabled = !this.sfxEnabled;
              localStorage.setItem('noir_sfx_enabled', this.sfxEnabled);
              if (window.NoirAudio) window.NoirAudio.enabled = this.sfxEnabled;
              if (this.sfxEnabled) this.playClick();
          },

          playClick() {
              if (window.NoirAudio && this.sfxEnabled) {
                  window.NoirAudio.hasUserInteracted = true; // Klik adalah user gesture valid
                  window.NoirAudio.playClick();
              }
          },

          playTypewriter() {
              if (window.NoirAudio && window.NoirAudio.hasUserInteracted && this.sfxEnabled) {
                  window.NoirAudio.playTypewriter();
              }
          },

          playPaper() {
              if (window.NoirAudio && this.sfxEnabled) {
                  window.NoirAudio.hasUserInteracted = true;
                  window.NoirAudio.playPaper();
              }
          },

          playObjection() {
              if (window.NoirAudio && this.sfxEnabled) {
                  window.NoirAudio.hasUserInteracted = true;
                  window.NoirAudio.playObjection();
              }
          },

          playVerdict(isSuccess) {
              if (window.NoirAudio && this.sfxEnabled) {
                  window.NoirAudio.hasUserInteracted = true;
                  window.NoirAudio.playVerdict(isSuccess);
              }
          },

          playHeartbeat(bpm) {
              if (window.NoirAudio && this.sfxEnabled) {
                  window.NoirAudio.hasUserInteracted = true;
                  window.NoirAudio.playHeartbeat(bpm);
              }
          },

          startInvestigation() {
              this.playPaper();
              this.isDispatching = true;
              setTimeout(() => {
                  window.location.href = '{{ route('game.desk', ['caseId' => $caseId ?? 'case_001']) }}';
              }, 260);
          },

          handleGlobalKey(e) {
              if (this.showLoadModal || this.showBriefModal || this.showSettingsModal) {
                  if (e.key === 'Escape') {
                      this.showLoadModal = false;
                      this.showBriefModal = false;
                      this.showSettingsModal = false;
                      this.playClick();
                  }
                  return;
              }

              if (e.key === '1') {
                  this.startInvestigation();
              } else if (e.key === '2') {
                  this.showLoadModal = true;
                  this.playClick();
              } else if (e.key === '3') {
                  this.showBriefModal = true;
                  this.playClick();
              } else if (e.key === '4') {
                  this.showSettingsModal = true;
                  this.playClick();
              }
          },

          async loadSlot(slotNum) {
              this.playPaper();
              try {
                  const res = await fetch(`/api/game/load/${slotNum}`);
                  const data = await res.json();
                  if (data && data.success && data.state) {
                      localStorage.setItem('detective_save_slot_1', JSON.stringify(data.state));
                  }
              } catch (e) {}
              window.location.href = '{{ route('game.desk', ['caseId' => $caseId ?? 'case_001']) }}';
          },

          async deleteSlot(slotNum) {
              if (!confirm(`Apakah Anda yakin ingin menghapus data arsip Slot [${slotNum}]?`)) return;
              this.playClick();
              try {
                  const res = await fetch(`/api/game/reset/{{ $caseId ?? 'case_001' }}`, {
                      method: 'POST',
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || ''
                      }
                  });
                  if (res.ok) {
                      this.saves = this.saves.filter(s => s.slot !== String(slotNum));
                  }
              } catch (e) {}
          }
      }"
      :class="{ 'crt-scanlines': crtEnabled }"
      @keydown.window="handleGlobalKey($event)">

    <!-- Atmospheric Overlays -->
    <div class="fixed inset-0 vignette-overlay z-10 pointer-events-none"></div>
    <div class="fixed inset-0 rain-overlay z-10 pointer-events-none opacity-25"></div>
    <div class="fixed inset-0 banker-light-cone z-0 pointer-events-none"></div>
    <div class="fixed inset-0 venetian-blinds-ambient z-0 pointer-events-none opacity-40"></div>

    <!-- ========================================================================= -->
    <!-- ROW 1: COMPACT TOP HEADER BAR                                             -->
    <!-- ========================================================================= -->
    <header class="relative z-20 w-full h-12 px-6 flex items-center justify-between border-b border-stone-800/80 bg-stone-950/90 backdrop-blur-xs shrink-0">
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-mono font-bold tracking-widest text-amber-500 uppercase">
                    VPD ARCHIVE TERMINAL // LEVEL-4 EYES ONLY
                </span>
            </div>
            <span class="hidden sm:inline-block text-[11px] text-stone-600 font-mono">| NEW VERIDIA POLICE DEPT • 1953</span>
        </div>

        <div class="flex items-center gap-2">
            <!-- CRT Filter Switch -->
            <button @click="toggleCrt()" 
                    @mouseenter="playTypewriter()"
                    class="px-2.5 py-1 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[11px] font-mono tracking-wider text-stone-300 flex items-center gap-1.5 transition cursor-pointer">
                <span>📻 CRT:</span>
                <strong x-text="crtEnabled ? 'ON' : 'OFF'" :class="crtEnabled ? 'text-amber-400' : 'text-stone-500'"></strong>
            </button>

            <!-- SFX Synthesizer Switch -->
            <button @click="toggleSfx()" 
                    @mouseenter="playTypewriter()"
                    class="px-2.5 py-1 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[11px] font-mono tracking-wider text-stone-300 flex items-center gap-1.5 transition cursor-pointer">
                <span>🔊 SFX:</span>
                <strong x-text="sfxEnabled ? 'ON' : 'OFF'" :class="sfxEnabled ? 'text-amber-400' : 'text-stone-500'"></strong>
            </button>
        </div>
    </header>

    <!-- ========================================================================= -->
    <!-- ROW 2: MAIN ASYMMETRIC 2-COLUMN VIEWPORT BODY (NO SCROLLBAR)              -->
    <!-- ========================================================================= -->
    <main class="relative z-20 flex-1 min-h-0 w-full max-w-7xl mx-auto px-6 py-2 sm:py-3 flex items-center justify-center">
        <div class="w-full grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-12 items-center">
            
            <!-- LEFT COLUMN: Brand Title & Typewriter Menu Controls -->
            <div class="md:col-span-7 flex flex-col justify-center space-y-4 lg:space-y-5 text-left">
                
                <!-- Badge, Classification & Main Title -->
                <div class="space-y-2">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-stone-950 border border-amber-500/80 shadow-[0_0_24px_rgba(245,158,11,0.35)] p-1 shrink-0">
                            <img src="{{ asset('favicon.svg') }}" 
                                 alt="Veridia Police Logo" 
                                 class="w-full h-full object-contain filter drop-shadow-[0_1px_4px_rgba(0,0,0,0.9)]">
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[11px] font-mono font-black tracking-widest text-red-500 uppercase bg-red-950/70 px-2 py-0.5 rounded border border-red-700/80 shadow-xs">
                                    TOP SECRET // CASE #001
                                </span>
                                <span class="text-[11px] text-amber-500/90 font-mono font-bold tracking-wider">OPERATION OUROBOROS</span>
                            </div>
                            <div class="text-[11px] text-stone-400 font-mono tracking-wider">
                                AETHELGARD QUANTUM FACILITY • 23:42 MALAM
                            </div>
                        </div>
                    </div>

                    <h1 class="font-serif text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black tracking-[0.16em] uppercase text-transparent bg-clip-text bg-linear-to-b from-amber-100 via-amber-400 to-amber-700 filter drop-shadow-[0_3px_15px_rgba(0,0,0,0.95)] leading-tight">
                        {{ $caseInfo['title'] ?? 'The Phantom Prototype' }}
                    </h1>

                    <p class="font-mono text-xs text-stone-400 tracking-wider">
                        A 1950s NOIR DETECTIVE INVESTIGATION // NEW VERIDIA
                    </p>
                </div>

                <!-- Teletype Styled Menu Selection -->
                <nav class="space-y-2.5 pt-1">
                    
                    <!-- Menu 01: Start Investigation -->
                    <button @click="startInvestigation()"
                            @mouseenter="hoveredMenu = 1; playTypewriter()"
                            @mouseleave="hoveredMenu = null"
                            class="group w-full flex items-center justify-between p-3 rounded bg-stone-900/90 hover:bg-stone-800/95 border-l-4 border-l-amber-500 border-y border-r border-stone-800 hover:border-amber-500/80 transition-all cursor-pointer shadow-lg transform hover:translate-x-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-amber-400 text-xs tracking-widest bg-amber-950/90 px-2 py-0.5 rounded border border-amber-700/80">
                                [ 01 ]
                            </span>
                            <div class="text-left">
                                <div class="text-xs sm:text-sm font-bold tracking-wider text-stone-100 group-hover:text-amber-300">
                                    BUKA KASUS BARU (INVESTIGASI)
                                </div>
                                <div class="text-[10px] text-stone-400 tracking-normal">
                                    Masuk ke Meja Kerja Detektif & periksa berkas perkara awal
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span x-show="isDispatching" class="text-[10px] text-red-400 font-bold tracking-widest animate-pulse">MEMBUKA...</span>
                            <span class="text-amber-400 font-bold text-sm tracking-widest group-hover:translate-x-1 transition-transform">
                                ▶
                            </span>
                        </div>
                    </button>

                    <!-- Menu 02: Load Saved File -->
                    <button @click="showLoadModal = true; playClick()"
                            @mouseenter="hoveredMenu = 2; playTypewriter()"
                            @mouseleave="hoveredMenu = null"
                            class="group w-full flex items-center justify-between p-3 rounded bg-stone-900/90 hover:bg-stone-800/95 border-l-4 border-l-stone-600 hover:border-l-amber-500 border-y border-r border-stone-800 hover:border-amber-500/80 transition-all cursor-pointer shadow-lg transform hover:translate-x-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-stone-300 text-xs tracking-widest bg-stone-800 px-2 py-0.5 rounded border border-stone-700 group-hover:bg-amber-950/90 group-hover:text-amber-400 group-hover:border-amber-700/80">
                                [ 02 ]
                            </span>
                            <div class="text-left">
                                <div class="text-xs sm:text-sm font-bold tracking-wider text-stone-200 group-hover:text-amber-300">
                                    ARSIP PENYELIDIKAN (LOAD GAME)
                                </div>
                                <div class="text-[10px] text-stone-400 tracking-normal">
                                    Muat berkas progress penyelidikan dari slot penyimpanan
                                </div>
                            </div>
                        </div>
                        <span class="text-stone-400 group-hover:text-amber-400 font-bold text-sm tracking-widest group-hover:translate-x-1 transition-transform">
                            ▶
                        </span>
                    </button>

                    <!-- Menu 03: Case Briefing & Synopsis -->
                    <button @click="showBriefModal = true; playClick()"
                            @mouseenter="hoveredMenu = 3; playTypewriter()"
                            @mouseleave="hoveredMenu = null"
                            class="group w-full flex items-center justify-between p-3 rounded bg-stone-900/90 hover:bg-stone-800/95 border-l-4 border-l-stone-600 hover:border-l-amber-500 border-y border-r border-stone-800 hover:border-amber-500/80 transition-all cursor-pointer shadow-lg transform hover:translate-x-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-stone-300 text-xs tracking-widest bg-stone-800 px-2 py-0.5 rounded border border-stone-700 group-hover:bg-amber-950/90 group-hover:text-amber-400 group-hover:border-amber-700/80">
                                [ 03 ]
                            </span>
                            <div class="text-left">
                                <div class="text-xs sm:text-sm font-bold tracking-wider text-stone-200 group-hover:text-amber-300">
                                    SINOPSIS & PROFIL KASUS
                                </div>
                                <div class="text-[10px] text-stone-400 tracking-normal">
                                    Latar belakang sabotase Dr. Wallace Vance & 3 tersangka
                                </div>
                            </div>
                        </div>
                        <span class="text-stone-400 group-hover:text-amber-400 font-bold text-sm tracking-widest group-hover:translate-x-1 transition-transform">
                            ▶
                        </span>
                    </button>

                    <!-- Menu 04: Preferences & Audio Settings -->
                    <button @click="showSettingsModal = true; playClick()"
                            @mouseenter="hoveredMenu = 4; playTypewriter()"
                            @mouseleave="hoveredMenu = null"
                            class="group w-full flex items-center justify-between p-3 rounded bg-stone-900/90 hover:bg-stone-800/95 border-l-4 border-l-stone-600 hover:border-l-amber-500 border-y border-r border-stone-800 hover:border-amber-500/80 transition-all cursor-pointer shadow-lg transform hover:translate-x-1.5">
                        <div class="flex items-center gap-3">
                            <span class="font-bold text-stone-300 text-xs tracking-widest bg-stone-800 px-2 py-0.5 rounded border border-stone-700 group-hover:bg-amber-950/90 group-hover:text-amber-400 group-hover:border-amber-700/80">
                                [ 04 ]
                            </span>
                            <div class="text-left">
                                <div class="text-xs sm:text-sm font-bold tracking-wider text-stone-200 group-hover:text-amber-300">
                                    PREFERENSI AUDIO & FILTER RETRO
                                </div>
                                <div class="text-[10px] text-stone-400 tracking-normal">
                                    Konfigurasi efek visual CRT scanline & uji sound synthesizer
                                </div>
                            </div>
                        </div>
                        <span class="text-stone-400 group-hover:text-amber-400 font-bold text-sm tracking-widest group-hover:translate-x-1 transition-transform">
                            ▶
                        </span>
                    </button>

                </nav>
            </div>

            <!-- RIGHT COLUMN: Diegetic Manila Case Dossier Props (Physical Kraft Paper Feel) -->
            <div class="hidden md:flex md:col-span-5 items-center justify-center relative">
                
                <!-- Realistic Manila Evidence Folder Card -->
                <div class="w-full max-w-85 lg:max-w-92.5 manila-folder rounded-md p-5 -rotate-2 transform hover:rotate-0 transition-transform duration-500 relative text-stone-900 font-mono select-none">
                    
                    <!-- Paperclip Graphic Top Left -->
                    <div class="absolute -top-3 left-6 w-4 h-9 border-2 border-stone-400 rounded-full bg-linear-to-b from-stone-300 to-stone-400 shadow-md"></div>

                    <!-- Manila Folder Top Tab -->
                    <div class="flex items-center justify-between border-b-2 border-amber-900/40 pb-2 mb-3">
                        <span class="text-[11px] font-black text-amber-950 uppercase tracking-wider">
                            DOSSIER #1953-OUROBOROS
                        </span>
                        <span class="text-[9px] bg-red-900 text-white px-1.5 py-0.5 rounded font-black tracking-widest">
                            CLASSIFIED
                        </span>
                    </div>

                    <!-- Red Ink Diagonal Confidential Stamp -->
                    <div class="absolute top-12 right-4 stamp-wet-ink text-xs uppercase px-2.5 py-1 rotate-12 tracking-widest bg-red-500/10 pointer-events-none">
                        CONFIDENTIAL
                    </div>

                    <!-- Polaroid Crime Silhouette Image Frame -->
                    <div class="bg-stone-100 border border-stone-400 p-2 rounded shadow-md mb-3 text-stone-900">
                        <div class="h-28 bg-stone-900 rounded flex items-center justify-center relative overflow-hidden border border-stone-700">
                            <!-- Silhouette Graphic -->
                            <div class="absolute inset-0 bg-linear-to-t from-stone-950 via-stone-900 to-amber-900/50"></div>
                            <div class="relative text-center z-10 space-y-1">
                                <span class="text-2xl">🔬</span>
                                <div class="text-[10px] text-amber-300 uppercase tracking-wider font-black">
                                    AETHELGARD QUANTUM LAB
                                </div>
                                <div class="text-[9px] text-stone-300">TKP: SUBLEVEL 3 FACILITY</div>
                            </div>
                        </div>
                        <div class="text-[9px] text-stone-700 text-center mt-1 font-mono font-bold">
                            BUKTI FOTO KEPOLISIAN • 14/11/1953
                        </div>
                    </div>

                    <!-- Teletype Case Summary Lines (Accurate Lore) -->
                    <div class="space-y-1 text-[11px] text-stone-900 border-t-2 border-amber-900/40 pt-2 font-bold leading-relaxed">
                        <div><strong class="text-amber-950">KORBAN:</strong> Dr. Wallace Vance</div>
                        <div><strong class="text-amber-950">STATUS:</strong> Sabotase Neurotoksik</div>
                        <div><strong class="text-amber-950">HILANG:</strong> Inti Prototipe <em>"Ouroboros-X"</em></div>
                    </div>

                    <!-- Cigar Smoke Ashtray Prop at Bottom Right -->
                    <div class="absolute -bottom-4 -right-4 w-12 h-12 pointer-events-none">
                        <div class="relative w-full h-full">
                            <span class="cigar-smoke-1 absolute bottom-2 right-2 w-3.5 h-3.5 rounded-full opacity-70"></span>
                            <span class="cigar-smoke-2 absolute bottom-2 right-3 w-4 h-4 rounded-full opacity-60"></span>
                            <span class="cigar-smoke-3 absolute bottom-2 right-1 w-4 h-4 rounded-full opacity-50"></span>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <!-- ========================================================================= -->
    <!-- ROW 3: COMPACT BOTTOM FOOTER BAR                                          -->
    <!-- ========================================================================= -->
    <footer class="relative z-20 w-full h-10 px-6 flex items-center justify-between border-t border-stone-800/80 bg-stone-950/90 backdrop-blur-xs shrink-0 text-[11px] text-stone-500 font-mono">
        <div>
            VERIDIA POLICE DEPT. // SPECIAL INVESTIGATION UNIT // 1953
        </div>
        <div class="hidden sm:flex items-center gap-2 text-stone-400">
            <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
            <span>● TAPE SYSTEM READY // STANDBY</span>
        </div>
        <div>
            NAVIGASI: <span class="text-amber-400 font-bold">[ KLIK PILIHAN / TEKAN 1-4 ]</span>
        </div>
    </footer>

    <!-- ========================================================================= -->
    <!-- MODAL 1: LOAD GAME ARCHIVE DRAWER                                         -->
    <!-- ========================================================================= -->
    <div x-show="showLoadModal"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
         style="display: none;">
        
        <div class="w-full max-w-lg bg-stone-900 border-2 border-amber-600/80 rounded-xl shadow-2xl p-5 relative text-stone-200 font-mono space-y-4"
             @click.outside="showLoadModal = false">
            
            <div class="flex items-center justify-between border-b border-stone-800 pb-2.5">
                <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                    <span>📂</span>
                    <span>LACI ARSIP PENYELIDIKAN // SIMPANAN PROGRESS</span>
                </div>
                <button @click="showLoadModal = false; playClick()" class="text-stone-400 hover:text-white text-base font-bold px-1.5 cursor-pointer">✕</button>
            </div>

            <!-- Save Slots List -->
            <div class="space-y-2.5">
                <template x-for="slotNum in ['1', '2', '3']" :key="slotNum">
                    <div class="p-3 rounded-lg bg-stone-950 border border-stone-800 hover:border-amber-500/60 transition flex items-center justify-between gap-3">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded bg-amber-950 text-amber-300 font-bold text-xs" x-text="`SLOT ${slotNum}`"></span>
                                <span class="text-xs text-stone-400 font-bold" x-text="saves.find(s => s.slot === slotNum) ? 'TERISI // KASUS AKTIF' : 'SLOT KOSONG'"></span>
                            </div>
                            <div class="text-[11px] text-stone-300 mt-1" x-show="saves.find(s => s.slot === slotNum)">
                                <span class="text-stone-400">Kasus:</span> The Phantom Prototype • 
                                <span class="text-emerald-400 font-bold">Kredibilitas: <span x-text="saves.find(s => s.slot === slotNum)?.credibility_score ?? 100"></span>%</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <button @click="loadSlot(slotNum)"
                                    class="px-3 py-1.5 rounded bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                                MUAT ➔
                            </button>
                            <button @click="deleteSlot(slotNum)"
                                    x-show="saves.find(s => s.slot === slotNum)"
                                    class="px-2 py-1.5 rounded bg-red-950 hover:bg-red-900 border border-red-800 text-red-300 text-xs transition cursor-pointer"
                                    title="Hapus Simpanan">
                                🗑️
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <div class="text-[11px] text-stone-500 text-center">
                Tekan <strong class="text-amber-400">MUAT</strong> untuk melanjutkan atau <strong class="text-stone-400">ESC</strong> untuk menutup.
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: CASE SYNOPSIS & DOSSIER                                          -->
    <!-- ========================================================================= -->
    <div x-show="showBriefModal"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
         style="display: none;">
        
        <div class="w-full max-w-xl bg-stone-900 border-2 border-amber-600/80 rounded-xl shadow-2xl p-5 relative text-stone-200 font-mono space-y-3.5 max-h-[85vh] overflow-y-auto"
             @click.outside="showBriefModal = false">
            
            <div class="flex items-center justify-between border-b border-stone-800 pb-2.5">
                <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                    <span>📜</span>
                    <span>BRIEFING RESMI KEPOLISIAN // KASUS #001</span>
                </div>
                <button @click="showBriefModal = false; playClick()" class="text-stone-400 hover:text-white text-base font-bold px-1.5 cursor-pointer">✕</button>
            </div>

            <!-- Dossier Content (Accurate Lore) -->
            <div class="space-y-3 text-xs leading-relaxed text-stone-300">
                <div class="p-2.5 bg-red-950/40 border border-red-800/60 rounded-lg text-red-300 text-[11px]">
                    <strong class="text-red-400 uppercase tracking-widest block mb-1">RINGKASAN KEJADIAN PERKARA:</strong>
                    Pada 14 November 1953 pukul 23:42 di fasilitas Aethelgard Quantum Dynamics Sublevel 3, Dr. Wallace Vance ditemukan terkapar lemas akibat paparan gas neurotoksik. Inti kuantum prototipe <em>"Ouroboros-X"</em> raib dicuri dari ruang hampa.
                </div>

                <div class="space-y-1.5">
                    <strong class="text-amber-400 uppercase tracking-widest block text-[11px]">3 TERSANGKA DI FASILITAS:</strong>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-[10px]">
                        <div class="p-2 rounded bg-stone-950 border border-stone-800">
                            <div class="font-bold text-amber-300">1. Dr. Jonathan Thorne</div>
                            <div class="text-stone-400">Kepala Peneliti Partner</div>
                        </div>
                        <div class="p-2 rounded bg-stone-950 border border-stone-800">
                            <div class="font-bold text-amber-300">2. Elena Vance</div>
                            <div class="text-stone-400">Chief Executive Officer</div>
                        </div>
                        <div class="p-2 rounded bg-stone-950 border border-stone-800">
                            <div class="font-bold text-amber-300">3. Julian Croft</div>
                            <div class="text-stone-400">Kepala Keamanan Fasilitas</div>
                        </div>
                    </div>
                </div>

                <div class="p-2.5 bg-stone-950 border border-stone-800 rounded-lg text-stone-400 text-[11px]">
                    <strong class="text-stone-200 block mb-0.5">METODE INVESTIGASI:</strong>
                    Periksa bukti di <strong>Meja Kerja</strong>, rangkai benang merah di <strong>Papan Investigasi</strong>, interogasi tersangka untuk mematahkan kebohongan (*Objection*), lalu susun <strong>Surat Dakwaan</strong> final.
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button @click="startInvestigation()"
                        class="px-3.5 py-1.5 rounded bg-amber-600 hover:bg-amber-500 text-stone-950 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                    MULAI INVESTIGASI SEKARANG ➔
                </button>
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: RETRO & AUDIO PREFERENCES                                        -->
    <!-- ========================================================================= -->
    <div x-show="showSettingsModal"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-sm"
         style="display: none;">
        
        <div class="w-full max-w-md bg-stone-900 border-2 border-amber-600/80 rounded-xl shadow-2xl p-5 relative text-stone-200 font-mono space-y-4"
             @click.outside="showSettingsModal = false">
            
            <div class="flex items-center justify-between border-b border-stone-800 pb-2.5">
                <div class="flex items-center gap-2 text-amber-400 font-bold text-xs uppercase tracking-wider">
                    <span>⚙️</span>
                    <span>PENGATURAN RETRO & AUDIO SYNTHESIZER</span>
                </div>
                <button @click="showSettingsModal = false; playClick()" class="text-stone-400 hover:text-white text-base font-bold px-1.5 cursor-pointer">✕</button>
            </div>

            <div class="space-y-3 text-xs">
                <!-- CRT Scanline Toggle -->
                <div class="p-3 rounded-lg bg-stone-950 border border-stone-800 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-stone-200">CRT Retro Scanline Filter</div>
                        <div class="text-[10px] text-stone-500">Efek garis tabung monitor 1950s</div>
                    </div>
                    <button @click="toggleCrt()" 
                            class="px-2.5 py-1 rounded font-bold transition text-xs cursor-pointer"
                            :class="crtEnabled ? 'bg-amber-600 text-stone-950' : 'bg-stone-800 text-stone-400'"
                            x-text="crtEnabled ? 'AKTIF' : 'NONAKTIF'">
                    </button>
                </div>

                <!-- SFX Synthesizer Toggle -->
                <div class="p-3 rounded-lg bg-stone-950 border border-stone-800 flex items-center justify-between">
                    <div>
                        <div class="font-bold text-stone-200">Web Audio Noir Synthesizer</div>
                        <div class="text-[10px] text-stone-500">Efek ketikan, objection, kertas berkas</div>
                    </div>
                    <button @click="toggleSfx()" 
                            class="px-2.5 py-1 rounded font-bold transition text-xs cursor-pointer"
                            :class="sfxEnabled ? 'bg-amber-600 text-stone-950' : 'bg-stone-800 text-stone-400'"
                            x-text="sfxEnabled ? 'AKTIF' : 'SENYAP'">
                    </button>
                </div>

                <!-- SFX Sound Test Bench -->
                <div class="p-3 rounded-lg bg-stone-950 border border-stone-800 space-y-2">
                    <div class="font-bold text-amber-400 uppercase tracking-wider text-[10px]">Uji Nada Web Audio API:</div>
                    <div class="grid grid-cols-3 gap-1.5">
                        <button @click="playClick()" class="p-1.5 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[10px] text-stone-300 cursor-pointer">
                            🖱️ Klik
                        </button>
                        <button @click="playTypewriter()" class="p-1.5 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[10px] text-stone-300 cursor-pointer">
                            ⌨️ Mesin Tik
                        </button>
                        <button @click="playPaper()" class="p-1.5 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[10px] text-stone-300 cursor-pointer">
                            📄 Berkas
                        </button>
                        <button @click="playObjection()" class="p-1.5 rounded bg-red-950/80 hover:bg-red-900 border border-red-700 text-[10px] text-red-300 font-bold cursor-pointer">
                            🚨 Objection
                        </button>
                        <button @click="playVerdict()" class="p-1.5 rounded bg-amber-950/80 hover:bg-amber-900 border border-amber-700 text-[10px] text-amber-300 font-bold cursor-pointer">
                            ⚖️ Vonis
                        </button>
                        <button @click="playHeartbeat()" class="p-1.5 rounded bg-stone-900 hover:bg-stone-800 border border-stone-700 text-[10px] text-stone-300 cursor-pointer">
                            🩸 Detak
                        </button>
                    </div>
                </div>
            </div>

            <div class="pt-1 flex justify-end">
                <button @click="showSettingsModal = false; playClick()"
                        class="px-3.5 py-1.5 rounded bg-stone-800 hover:bg-stone-700 text-stone-300 font-bold text-xs uppercase tracking-wider transition cursor-pointer">
                    TUTUP
                </button>
            </div>
        </div>
    </div>

</body>
</html>

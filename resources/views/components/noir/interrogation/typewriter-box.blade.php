@props([
    'caseId' => 'case_001',
])

<!-- Right: Typewriter Dialogue Box & Clue Feedback -->
<div class="flex-1 w-full space-y-4">
    
    <!-- Objection Feedback Banner (if any) -->
    <div x-show="objectionFeedback !== null" 
         x-transition
         class="p-4 rounded-lg font-typewriter text-xs shadow-lg border leading-relaxed"
         :class="objectionFeedback?.valid ? 'bg-emerald-950/80 border-emerald-600/80 text-emerald-200' : 'bg-red-950/80 border-red-600/80 text-red-200'"
         style="display: none;">
        <div class="font-bold uppercase tracking-wider mb-1" 
             x-text="objectionFeedback?.valid ? '⚡ BUKTI RELEVAN - OBJECTION BERHASIL!' : '❌ BUKTI DITOLAK - ALIBI TIDAK TERBANTIKAN'"></div>
        <p x-text="objectionFeedback?.message"></p>
    </div>

    <!-- Retro Transcript Header Bar -->
    <div class="flex items-center justify-between px-3.5 py-1.5 bg-stone-950 border-t-2 border-x-2 border-stone-700/80 rounded-t-lg text-[10px] font-mono tracking-wider text-stone-400 shadow-sm select-none">
        <div class="flex items-center gap-2">
            <span class="flex items-center gap-1 text-red-400 font-bold">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-red-500" :class="isTyping ? 'animate-ping' : ''"></span>
                [ REC ● ]
            </span>
            <span class="text-stone-300 font-bold">SALURAN PENYADAPAN RUANG A-3</span>
            <span class="text-stone-600">//</span>
            <span class="text-amber-400 font-bold uppercase" x-text="'SUBJEK AKTIF: ' + (getCurrentNode()?.speaker || 'SAKSI')"></span>
        </div>
        <div class="text-stone-500 hidden sm:block">
            FREKUENSI: 104.2 MHz // SENSITIVITY: MAX
        </div>
    </div>

    <!-- Typewriter Text Box -->
    <div class="paper-dark p-6 rounded-b-lg border-2 border-stone-700/80 text-stone-100 min-h-35 flex flex-col justify-between relative shadow-2xl cursor-pointer select-none"
         @click="if (isTyping) skipTypewriter()">
        <!-- Quotation Icon -->
        <div class="text-3xl text-stone-600 font-serif leading-none absolute top-3 left-3 select-none">“</div>
        
        <div class="pl-6 pt-2 font-typewriter text-sm sm:text-base leading-relaxed text-stone-200">
            <span x-text="displayedText"></span>
            <span x-show="isTyping" class="inline-block w-2 h-4 bg-amber-400 ml-1 animate-pulse"></span>
        </div>

        <!-- Typing Skip & Claim Notice -->
        <div class="flex justify-between items-center pt-4 mt-2 border-t border-stone-800 text-[11px] font-mono text-stone-500">
            <span x-show="getCurrentNode()?.statement_id" class="text-amber-500 font-semibold flex items-center gap-1">
                ⚠️ Saksi baru saja mengeluarkan klaim fakta!
            </span>
            <span x-show="!getCurrentNode()?.statement_id"></span>

            <button x-show="isTyping" @click.stop="skipTypewriter()" class="hover:text-amber-300 text-stone-400 transition cursor-pointer font-mono font-medium">
                [Tekan Spasi / Enter / Klik untuk lewati teks ■]
            </button>
        </div>
    </div>

    <!-- Interrogation Finished / Confession Dossier Callout Banner -->
    <div x-show="getCurrentNode()?.type === 'conclusion' || getCurrentNode()?.type === 'post_cracked' || isInterrogationFinished" 
         x-transition
         class="p-4 bg-linear-to-r from-stone-900 via-amber-950/80 to-stone-900 border-2 border-amber-500 rounded-lg shadow-[0_0_30px_rgba(245,158,11,0.25)] text-stone-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 animate-pulse-slow">
        <div class="space-y-1">
            <div class="flex items-center gap-2">
                <span class="stamp-classified text-[9px] px-2 py-0.5 bg-amber-500/20 text-amber-300 border-amber-400 font-mono">BERKAS PENGAKUAN LENGKAP</span>
                <span class="text-xs font-bold font-noir text-amber-200">KETERANGAN KUNCI TERCATAT</span>
            </div>
            <p class="text-xs font-typewriter text-stone-300 leading-snug">
                Pengakuan tersangka dan alur konspirasi telah terangkum. Anda siap mengajukan Surat Dakwaan resmi.
            </p>
        </div>
        <div class="flex items-center gap-2 shrink-0 w-full sm:w-auto">
            <button @click="$store.game.isIndictmentModalOpen = true; NoirAudio.playPaper()"
                    class="flex-1 sm:flex-initial px-4 py-2.5 bg-linear-to-r from-red-700 via-red-800 to-red-950 hover:from-red-600 hover:to-red-800 text-white font-bold font-mono text-xs uppercase tracking-wider rounded border-2 border-red-500 shadow-[0_0_15px_rgba(239,68,68,0.4)] flex items-center justify-center gap-2 transition transform hover:scale-[1.02] cursor-pointer">
                <span>⚖️</span>
                <span>BUKA SURAT DAKWAAN</span>
            </button>
            <a href="{{ route('game.desk', ['caseId' => $caseId ?? 'case_001']) }}"
               @click="NoirAudio.playPaper()"
               class="px-3.5 py-2.5 bg-stone-800 hover:bg-stone-700 text-stone-300 hover:text-white rounded border border-stone-600 text-xs font-mono transition flex items-center justify-center cursor-pointer shadow"
               title="Kembali ke Meja Kerja">
                <span>📂</span>
            </a>
        </div>
    </div>

    <!-- Press Clarification Clue Box -->
    <div x-show="pressInfo?.clue_hint" 
         x-transition
         class="p-3 bg-amber-950/50 border-2 border-amber-600/60 rounded-lg text-amber-200 text-xs font-mono flex items-start gap-2.5 shadow-lg"
         style="display: none;">
        <span class="text-amber-400 text-sm font-bold shrink-0">💡</span>
        <div class="leading-relaxed">
            <span class="font-bold text-amber-300 uppercase tracking-wide mr-1">Petunjuk Logika Hasil Press:</span>
            <span x-text="pressInfo?.clue_hint"></span>
        </div>
    </div>

    <!-- Action Row: Objection, Press Statement & Branching Options OR Post-Cracked Concluded State -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3 pt-1">
        
        <!-- Interrogation Actions: Objection & Press -->
        <div class="flex flex-wrap items-center gap-2" x-show="getCurrentNode()?.statement_id">
            <!-- OBJECTION Trigger (Highlighted when node is a factual_claim) -->
            <button @click="openObjectionModal()"
                    class="px-4 py-2.5 bg-linear-to-r from-red-700 to-red-900 hover:from-red-600 hover:to-red-800 text-white font-black font-mono text-xs uppercase tracking-wider rounded-lg shadow-xl border-2 border-red-500 animate-pulse-red flex items-center justify-center gap-1.5 transition">
                <span class="text-sm">🚨</span>
                <span>OBJECTION!</span>
            </button>

            <!-- PRESS STATEMENT Trigger -->
            <button @click="pressStatement()"
                    class="px-4 py-2.5 bg-linear-to-r from-amber-700 via-amber-800 to-stone-900 hover:from-amber-600 hover:to-amber-700 text-amber-100 font-bold font-mono text-xs uppercase tracking-wider rounded-lg shadow-lg border border-amber-500/60 flex items-center justify-center gap-1.5 transition duration-150"
                    title="Gali keterangan lebih dalam tanpa penalti kredibilitas">
                <span class="text-sm">⚡</span>
                <span>GALI KETERANGAN (PRESS)</span>
            </button>
        </div>

        <!-- Dialogue Response Branches (When options exist and not post_cracked) -->
        <div x-show="getCurrentNode() && getCurrentNode().options && getCurrentNode().options.length > 0 && getCurrentNode().type !== 'post_cracked' && !$store.game?.isSuspectLocked(activeSuspectId)" 
             class="flex-1 flex flex-wrap gap-2 justify-end">
            <template x-for="(opt, idx) in (getCurrentNode()?.options || [])" :key="opt.label">
                <button @click="goToNode(opt.next_node, opt.label)"
                        class="text-left px-3.5 py-2.5 bg-stone-900/90 hover:bg-stone-855 text-stone-300 hover:text-amber-200 border border-stone-700 hover:border-amber-400 hover:shadow-[0_0_15px_rgba(245,158,11,0.25)] rounded-lg text-xs font-typewriter transition-all duration-150 shadow flex items-center group cursor-pointer">
                    <span class="text-amber-400 font-mono font-bold mr-2 text-[11px] bg-stone-950 px-1.5 py-0.5 rounded border border-stone-700 group-hover:border-amber-400 group-hover:bg-amber-950/80 transition-all duration-150 shadow-xs" x-text="'[' + (idx + 1) + ']'"></span>
                    <span class="text-amber-500/80 font-bold mr-1.5 group-hover:translate-x-0.5 transition-transform">➔</span>
                    <span x-text="opt.label"></span>
                </button>
            </template>
        </div>

        <!-- Post-Cracked Silence / Official Closure State (When options are empty, node is post_cracked, or suspect is locked) -->
        <div x-show="!getCurrentNode()?.options || getCurrentNode()?.options.length === 0 || getCurrentNode()?.type === 'post_cracked' || $store.game?.isSuspectLocked(activeSuspectId)" 
             class="flex-1 w-full">
            <div class="p-4 rounded-lg bg-red-950/30 border border-red-800/60 text-center space-y-2.5 shadow-inner">
                <div class="text-red-400 font-mono text-xs tracking-widest uppercase flex items-center justify-center gap-2 font-bold">
                    <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                    SUBJEK MENGGUNAKAN HAK BUNGKAM // PEMERIKSAAN RESMI DITUTUP PERMANEN
                </div>
                <p class="text-stone-400 text-xs font-serif italic">
                    Semua keterangan penting telah tersimpan permanen dalam pita penyadapan.
                </p>
                <div class="pt-1 flex flex-wrap items-center justify-center gap-2">
                    <button @click="$store.game.openTranscriptModal(activeSuspectId)"
                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded bg-stone-900 hover:bg-stone-850 border border-stone-700 hover:border-amber-500 text-stone-300 hover:text-amber-300 font-mono text-xs transition cursor-pointer shadow-sm">
                        <span>📜</span>
                        <span>Buka Arsip Transkrip Percakapan</span>
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Quick Alibi Dossier Card (Detective Notepad) -->
    <div class="bg-stone-950/80 border border-stone-800 rounded-lg p-3 sm:p-3.5 text-xs font-mono shadow-md backdrop-blur-xs relative overflow-hidden transition">
        <div class="flex items-center justify-between border-b border-stone-800/80 pb-2 mb-2">
            <div class="flex items-center gap-2">
                <span class="text-amber-500 font-bold">📋 CATATAN DOSIR ALIBI:</span>
                <span class="text-stone-200 font-bold tracking-wide" x-text="getActiveSuspect()?.name || 'Saksi Terpilih'"></span>
                <span class="text-[10px] text-stone-500 font-mono" x-text="'(' + (getActiveSuspect()?.role || '-') + ')'"></span>
            </div>
            <span class="stamp-classified text-[9px] px-1.5 py-0.5 font-mono">DOKUMEN RESMI</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 font-typewriter text-stone-300">
            <div class="md:col-span-2">
                <span class="text-amber-400/90 font-mono text-[10px] font-bold uppercase tracking-wider block mb-1">Klaim Alibi Saat Kejadian:</span>
                <p class="italic text-stone-300 bg-stone-900/90 p-2.5 rounded border border-stone-800 leading-relaxed text-[11px]" 
                   x-text="'“' + (getActiveSuspect()?.alibi || 'Belum ada rekaman alibi tersimpan.') + '”'"></p>
            </div>
            <div>
                <span class="text-stone-400 font-mono text-[10px] font-bold uppercase tracking-wider block mb-1">Dugaan Motif & Sikap:</span>
                <div class="bg-stone-900/90 p-2.5 rounded border border-stone-800 text-[11px] space-y-1.5">
                    <p class="leading-tight"><span class="text-amber-500/90 font-mono text-[10px] font-bold">MOTIF:</span> <span class="text-stone-300" x-text="getActiveSuspect()?.motive_theory || '-'"></span></p>
                    <p class="leading-tight"><span class="text-stone-500 font-mono text-[10px] font-bold">SIKAP:</span> <span class="text-stone-400" x-text="getActiveSuspect()?.personality || '-'"></span></p>
                </div>
            </div>
        </div>
    </div>

</div>

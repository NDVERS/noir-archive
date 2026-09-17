@props([
    'caseId' => 'case_001',
    'caseInfo' => [],
    'suspectsCount' => 3,
    'evidencesCount' => 6,
])

<!-- Header Plaque: Brass/Wood Slim Nameplate (Calibrated Single-Row Height) -->
<header class="w-full shrink-0 bg-stone-950/95 border border-stone-800 rounded-lg px-3 py-1.5 shadow-xl flex items-center justify-between gap-3 relative overflow-hidden backdrop-blur-xs">
    <!-- Top Subtle Gold Edge Accent -->
    <div class="absolute top-0 left-0 right-0 h-0.5 bg-linear-to-r from-amber-600/30 via-amber-500/80 to-amber-600/30"></div>

    <!-- Left & Center Integrated Info -->
    <div class="flex items-center gap-2.5 min-w-0 flex-1">
        <!-- Detective Badge Icon -->
        <div class="w-7 h-7 rounded-full bg-stone-900 border border-amber-500/60 shadow-[0_0_8px_rgba(245,158,11,0.25)] flex items-center justify-center shrink-0">
            <img src="{{ asset('favicon.svg') }}" alt="VPD" class="w-4 h-4 object-contain">
        </div>

        <div class="flex items-center gap-2 min-w-0 flex-wrap">
            <span class="text-[9px] font-mono uppercase bg-red-950/90 text-red-400 border border-red-800/80 px-1.5 py-0.2 rounded font-black tracking-wider shadow-xs shrink-0">
                KASUS #001
            </span>
            <span class="text-xs sm:text-sm font-mono font-bold tracking-tight text-amber-400 truncate">
                {{ $caseInfo['title'] ?? 'The Phantom Prototype' }}
            </span>
            <span class="text-[11px] font-mono text-stone-500 hidden xl:inline">
                // {{ $caseInfo['codename'] ?? 'OPERATION_OUROBOROS' }}
            </span>
            <span class="text-[10px] font-mono text-stone-400 hidden md:inline truncate">
                &bull; {{ $caseInfo['location'] ?? 'Aethelgard Quantum Lab' }} ({{ $caseInfo['time'] ?? '23:42' }})
            </span>
            <!-- Live Status Badges -->
            <span class="text-[9px] font-mono bg-stone-900 text-stone-300 border border-stone-700 px-1.5 py-0.2 rounded hidden sm:inline-flex items-center gap-1.5 shrink-0">
                <span class="text-amber-400 font-semibold">👤 Saksi: {{ $suspectsCount }}</span>
                <span class="text-stone-600">|</span>
                <span class="text-red-400 font-semibold">💼 Bukti: {{ $evidencesCount }}</span>
            </span>
        </div>
    </div>

    <!-- Quick Navigation Action Buttons -->
    <div class="flex items-center gap-2 shrink-0">
        <a href="{{ route('game.interrogation', ['caseId' => $caseId, 'suspectId' => 'suspect_thorne']) }}" 
           @click="NoirAudio.playClick()"
           class="px-2.5 py-1 bg-amber-950/80 hover:bg-amber-900 text-amber-200 rounded text-[11px] font-mono font-bold border border-amber-600/60 shadow-md flex items-center gap-1.5 transition duration-150 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
            <span class="hidden sm:inline">Interogasi</span>
            <span class="sm:hidden">Saksi</span>
        </a>

        <a href="{{ route('game.board', ['caseId' => $caseId]) }}" 
           @click="NoirAudio.playClick()"
           class="px-2.5 py-1 bg-stone-900 hover:bg-stone-850 text-stone-200 rounded text-[11px] font-mono font-bold border border-red-900/70 shadow-md flex items-center gap-1.5 transition duration-150 cursor-pointer">
            <svg class="w-3.5 h-3.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <span class="hidden sm:inline">Papan Benang Merah</span>
            <span class="sm:hidden">Papan</span>
        </a>
    </div>
</header>

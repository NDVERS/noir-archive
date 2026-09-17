@extends('layouts.detective')

@section('title', 'Ruang Interogasi - ' . ($caseInfo['title'] ?? 'The Phantom Prototype'))

@section('content')
<div class="flex-1 bg-noir-950 p-4 sm:p-6 min-h-[calc(100vh-60px)] flex flex-col justify-between relative overflow-hidden"
     @keydown.window="handleGlobalKeydown($event)"
     x-data="interrogationRoom({
         caseId: '{{ $caseId }}',
         activeSuspectId: '{{ $activeSuspectId }}',
         dialoguesData: @js($dialogues ?? []),
         suspectsData: @js($suspects ?? [])
     })">
    
    <!-- Ambient Rain Overlay -->
    <div class="rain-overlay"></div>

    <!-- Cinematic Start Overlay -->
    <x-noir.interrogation.start-overlay :case-id="$caseId" :active-suspect-id="$activeSuspectId" />

    <div class="max-w-6xl mx-auto w-full space-y-6 relative z-10">

        <!-- Suspect Switcher Dossier Tabs & Tape Status -->
        <x-noir.interrogation.suspect-switcher :suspects="$suspects" />

        <!-- Dramatic Interrogation Chamber Overhead Spotlight Stage -->
        <div class="relative bg-interrogation-chamber rounded-2xl p-6 sm:p-10 border-2 border-stone-800 shadow-[0_25px_60px_-15px_rgba(0,0,0,0.9)] overflow-hidden">
            
            <!-- Atmospheric Floating Dust Motes in Lamp Beam -->
            <div class="absolute inset-0 floating-dust z-15 pointer-events-none"></div>

            <!-- Overhead Cone Spotlight Radial Projection -->
            <div class="absolute -top-10 left-12 sm:left-24 w-80 sm:w-96 h-96 bg-radial from-amber-400/20 via-amber-500/5 to-transparent rounded-full blur-2xl pointer-events-none z-10"></div>
            
            <!-- Industrial Hanging Lamp Cord & Shade Fixture Visual -->
            <div class="absolute top-0 left-28 sm:left-40 -translate-x-1/2 flex flex-col items-center pointer-events-none z-20 opacity-85">
                <!-- Metal Wire Cable -->
                <div class="w-0.5 h-7 sm:h-9 bg-linear-to-b from-stone-500 via-stone-700 to-stone-900 shadow"></div>
                <!-- Lamp Hood Socket -->
                <div class="w-8 h-3 bg-stone-800 rounded-t-md border border-stone-600 shadow-md"></div>
                <!-- Lamp Shade Cone -->
                <div class="w-16 h-4 bg-linear-to-b from-stone-800 via-amber-950 to-stone-950 rounded-b-lg border-b-2 border-amber-500/60 shadow-lg"></div>
                <!-- Hot Filament Bulb Glow -->
                <div class="w-4 h-1.5 bg-amber-300 rounded-full blur-[1px] shadow-[0_0_12px_#f59e0b]"></div>
            </div>

            <!-- Heavy Table Shadow & Edge Layer at Bottom -->
            <div class="absolute bottom-0 left-0 right-0 h-16 bg-linear-to-t from-stone-950 via-stone-900/40 to-transparent pointer-events-none z-15 border-b-4 border-stone-950">
                <div class="w-full h-0.5 bg-linear-to-r from-transparent via-amber-900/20 to-transparent"></div>
            </div>

            <div class="relative z-20 flex flex-col md:flex-row items-center md:items-start gap-8 pt-6 sm:pt-4">
                
                <!-- Left: Suspect Spotlight Frame, Mood Badge & Dynamic Tension ECG HUD -->
                <x-noir.interrogation.spotlight-stage />

                <!-- Right: Typewriter Dialogue Box & Clue Feedback -->
                <x-noir.interrogation.typewriter-box :case-id="$caseId" />

            </div>
        </div>

    </div>

    <!-- Objection Evidence Selector Modal -->
    <x-noir.interrogation.objection-selector :evidences="$evidences" />

    <!-- Wiretap Transcript Archive Modal -->
    <x-noir.interrogation.wiretap-transcript-modal :suspects="$suspects" :active-suspect-id="$activeSuspectId" />

</div>
@endsection

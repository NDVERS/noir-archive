@extends('layouts.detective')

@section('title', 'Meja Kerja Detektif - ' . ($caseInfo['title'] ?? 'The Phantom Prototype'))

@section('content')
<!-- Master Work Desk Container: 100% Viewport Locked (Direct Full Height, Zero Gap) -->
<div class="w-full h-full flex flex-col p-2 gap-2 overflow-hidden bg-desk-wood text-stone-200 select-none relative font-mono">
    
    <!-- Diegetic Environmental Overlays (Wajib Absolute agar keluar dari aliran flex) -->
    <div class="absolute inset-0 rain-overlay pointer-events-none z-0"></div>
    <div class="absolute inset-0 banker-desk-glow pointer-events-none z-0"></div>
    <div class="absolute top-12 right-16 coffee-stain pointer-events-none z-0"></div>

    <div class="w-full h-full flex flex-col flex-1 min-h-0 gap-2 relative z-10 px-2 sm:px-4">
        
        <!-- 1. Header Plaque (Attached directly at top, mt-0) -->
        <div class="mt-0 shrink-0">
            <x-noir.desk.header-plaque 
                :case-id="$caseId" 
                :case-info="$caseInfo" 
                :suspects-count="count($suspects)"
                :evidences-count="count($evidences)"
            />
        </div>

        <!-- 2. Main Desk Surface: Split 2-Column Grid (100% Fit, Zero Window Scroll) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-2 flex-1 min-h-0 items-stretch overflow-hidden">
            
            <!-- Left Side: Manila Case Dossier (5 cols) -->
            <div class="lg:col-span-5 flex flex-col min-h-0 h-full">
                <x-noir.desk.case-dossier 
                    :case-info="$caseInfo" 
                />
            </div>

            <!-- Right Side: Suspect Cards Rack & Evidence Tray (7 cols) -->
            <div class="lg:col-span-7 flex flex-col min-h-0 h-full gap-2 justify-between">
                <!-- Suspect Cards Rack (Calibrated ~52% height) -->
                <x-noir.desk.suspect-grid 
                    :suspects="$suspects" 
                    :case-id="$caseId" 
                />

                <!-- Evidence Tray (Calibrated ~48% height) -->
                <x-noir.desk.evidence-tray 
                    :evidences="$evidences" 
                />
            </div>

        </div>

    </div>
</div>
@endsection

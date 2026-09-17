@props([
    'suspects' => [],
    'evidences' => [],
    'caseId' => 'case_001',
])

<!-- Global Indictment Modal -->
<div x-show="$store.game.isIndictmentModalOpen" 
     x-transition.opacity
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/85 backdrop-blur-md overflow-y-auto"
     x-data="{
         selectedCulprit: '',
         selectedMotive: '',
         selectedEvidences: [],
         isSubmitting: false,
         toggleEvidence(id) {
             if (this.selectedEvidences.includes(id)) {
                 this.selectedEvidences = this.selectedEvidences.filter(e => e !== id);
             } else {
                 this.selectedEvidences.push(id);
             }
         },
         async submitIndictment() {
             if (!this.selectedCulprit) {
                 alert('Pilih tersangka utama yang Anda dakwa!');
                 return;
             }
             if (!this.selectedMotive) {
                 alert('Tentukan motif kejahatan yang melatarbelakangi kasus!');
                 return;
             }
             if (this.selectedEvidences.length === 0) {
                 alert('Pilih setidaknya satu bukti kunci untuk rantai pembuktian!');
                 return;
             }

             this.isSubmitting = true;
             NoirAudio.playPaper();

             try {
                 const res = await fetch('/api/game/accuse', {
                     method: 'POST',
                     headers: {
                         'Content-Type': 'application/json',
                         'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || ''
                     },
                     body: JSON.stringify({
                         case_id: '{{ $caseId ?? 'case_001' }}',
                         culprit_id: this.selectedCulprit,
                         motive_id: this.selectedMotive,
                         evidence_chain: this.selectedEvidences
                     })
                 });
                 const data = await res.json();
                 $store.game.verdictResult = data;
                 $store.game.isIndictmentModalOpen = false;
                 $store.game.isVerdictModalOpen = true;
                 NoirAudio.playVerdict(data.success);
             } catch (err) {
                 alert('Terjadi kesalahan saat memproses surat dakwaan.');
             } finally {
                 this.isSubmitting = false;
             }
         }
     }"
     style="display: none;">
    
    <div @click.away="$store.game.isIndictmentModalOpen = false" 
         class="relative max-w-2xl w-full paper-texture rounded-sm p-5 sm:p-7 text-stone-900 shadow-2xl border-4 border-stone-800 my-8 overflow-hidden">
        
        <div class="text-center border-b-2 border-stone-800/30 pb-3 mb-4">
            <span class="stamp-classified inline-block px-3 py-0.5 text-xs mb-1 font-mono">SURAT DAKWAAN RESMI</span>
            <h2 class="text-2xl sm:text-3xl font-black font-noir text-stone-900 tracking-wide uppercase">Warrant of Arrest & Indictment</h2>
            <p class="font-typewriter text-xs text-stone-700 mt-0.5">Kejaksaan Khusus Distrik New Veridia & Dewan Direksi Aethelgard</p>
        </div>

        <form @submit.prevent="submitIndictment()" class="space-y-5 font-typewriter text-xs">
            
            <!-- 1. Suspect Selection with Vintage Mugshot Spotlight SVG -->
            <div>
                <label class="block font-bold text-xs sm:text-sm text-stone-900 mb-1.5 uppercase tracking-wide">
                    1. Tersangka Utama Pelaku Kejahatan (Bobot: 40 Poin)
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                    @foreach($suspects as $s)
                        <div @click="selectedCulprit = '{{ $s['id'] }}'; NoirAudio.playClick()"
                             :class="selectedCulprit === '{{ $s['id'] }}' 
                                 ? 'border-2 border-red-800 bg-red-900/15 shadow-xl ring-2 ring-red-700 font-bold scale-[1.02]' 
                                 : 'border border-stone-400 bg-stone-100/70 hover:bg-stone-200/80'"
                             class="cursor-pointer p-2.5 rounded transition text-center flex flex-col items-center justify-between gap-1 relative overflow-hidden group">
                            
                            <!-- Active Selection Stamp Indicator -->
                            <div x-show="selectedCulprit === '{{ $s['id'] }}'" 
                                 class="absolute top-1 right-1 bg-red-800 text-white text-[9px] font-mono px-1.5 py-0.2 rounded font-black tracking-wider shadow">
                                TERDAKWA
                            </div>

                            <!-- High-Contrast Vintage Mugshot Spotlight Avatar -->
                            <div class="w-16 h-16 rounded-full bg-linear-to-b from-stone-600 via-stone-700 to-stone-900 border-2 border-stone-500 relative overflow-hidden flex items-center justify-center shadow-inner mb-1">
                                <!-- Subtle Grid Lines -->
                                <div class="absolute inset-0 opacity-20 pointer-events-none" 
                                     style="background-image: linear-gradient(#ffffff 1px, transparent 1px); background-size: 100% 8px;"></div>
                                <!-- Radial Glow -->
                                <div class="absolute inset-0 bg-radial from-stone-500/40 via-stone-700/60 to-stone-950 pointer-events-none"></div>

                                <div class="relative z-10">
                                    @if($s['id'] === 'suspect_thorne')
                                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 14 C34 14, 26 25, 26 42 C26 56, 36 64, 50 64 C64 64, 74 56, 74 42 C74 25, 66 14, 50 14 Z" fill="#1c1917" stroke="#e7e5e4" stroke-width="2"/>
                                            <circle cx="40" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                                            <circle cx="60" cy="40" r="8" fill="#292524" stroke="#fafaf9" stroke-width="2.5"/>
                                            <line x1="48" y1="40" x2="52" y2="40" stroke="#fafaf9" stroke-width="2.5"/>
                                            <line x1="36" y1="36" x2="42" y2="42" stroke="#ffffff" stroke-width="2"/>
                                            <line x1="56" y1="36" x2="62" y2="42" stroke="#ffffff" stroke-width="2"/>
                                            <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M38 66 L50 82 L62 66" stroke="#f59e0b" stroke-width="2" fill="#292524"/>
                                        </svg>
                                    @elseif($s['id'] === 'suspect_elena')
                                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 12 C32 12, 22 24, 22 46 C22 62, 28 68, 32 68 C32 52, 38 20, 50 20 C62 20, 68 52, 68 68 C72 68, 78 62, 78 46 C78 24, 68 12, 50 12 Z" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M39 42 Q44 38 49 42" stroke="#fafaf9" stroke-width="2.5"/>
                                            <path d="M51 42 Q56 38 61 42" stroke="#fafaf9" stroke-width="2.5"/>
                                            <path d="M14 96 C14 74, 28 66, 50 66 C72 66, 86 74, 86 96 Z" fill="#171717" stroke="#e7e5e4" stroke-width="2"/>
                                            <path d="M28 66 L40 80 L50 66 L60 80 L72 66" stroke="#991b1b" stroke-width="3"/>
                                        </svg>
                                    @else
                                        <svg class="w-14 h-14" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 10 C36 10, 38 28, 38 34 L62 34 C62 28, 64 10, 50 10 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                            <ellipse cx="50" cy="34" rx="30" ry="6" fill="#09090b" stroke="#e7e5e4" stroke-width="2"/>
                                            <line x1="54" y1="56" x2="68" y2="54" stroke="#ffffff" stroke-width="2.5"/>
                                            <circle cx="68" cy="54" r="2" fill="#ea580c"/>
                                            <path d="M16 96 C16 74, 30 66, 50 66 C70 66, 84 74, 84 96 Z" fill="#18181b" stroke="#e7e5e4" stroke-width="2"/>
                                        </svg>
                                    @endif
                                </div>
                            </div>

                            <div class="text-xs text-stone-950 font-bold leading-tight">{{ $s['name'] }}</div>
                            <div class="text-[10px] text-stone-600 leading-tight">{{ $s['role'] }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 2. Motive Selection: Police Dossier Radio Selection Cards -->
            <div>
                <label class="block font-bold text-xs sm:text-sm text-stone-900 mb-1.5 uppercase tracking-wide">
                    2. Motif Kejahatan Terbukti (Bobot: 20 Poin)
                </label>
                <div class="flex flex-col gap-2">
                    <!-- Motive 1: Industrial Espionage -->
                    <div @click="selectedMotive = 'industrial_espionage_debt'; NoirAudio.playClick()"
                         :class="selectedMotive === 'industrial_espionage_debt' 
                             ? 'bg-red-950/20 border-red-800 text-stone-950 font-bold ring-1 ring-red-800 shadow-sm' 
                             : 'bg-stone-100/90 border-stone-300 text-stone-700 hover:bg-stone-200/80'"
                         class="p-2.5 rounded border text-left cursor-pointer transition-all font-mono text-xs flex items-start gap-2.5">
                        <span class="font-bold text-sm shrink-0" 
                              :class="selectedMotive === 'industrial_espionage_debt' ? 'text-red-800' : 'text-stone-400'"
                              x-text="selectedMotive === 'industrial_espionage_debt' ? '[•]' : '[ ]'"></span>
                        <div>
                            <div class="font-bold text-xs text-stone-950">Spionase Industri & Pelunasan Utang</div>
                            <div class="text-[11px] font-normal text-stone-600 leading-snug mt-0.5">
                                Mencuri chip demi bayaran 2.5 juta kredit dari sindikat dan balas dendam royalti paten.
                            </div>
                        </div>
                    </div>

                    <!-- Motive 2: Life Insurance & Murder -->
                    <div @click="selectedMotive = 'insurance_homicide'; NoirAudio.playClick()"
                         :class="selectedMotive === 'insurance_homicide' 
                             ? 'bg-red-950/20 border-red-800 text-stone-950 font-bold ring-1 ring-red-800 shadow-sm' 
                             : 'bg-stone-100/90 border-stone-300 text-stone-700 hover:bg-stone-200/80'"
                         class="p-2.5 rounded border text-left cursor-pointer transition-all font-mono text-xs flex items-start gap-2.5">
                        <span class="font-bold text-sm shrink-0" 
                              :class="selectedMotive === 'insurance_homicide' ? 'text-red-800' : 'text-stone-400'"
                              x-text="selectedMotive === 'insurance_homicide' ? '[•]' : '[ ]'"></span>
                        <div>
                            <div class="font-bold text-xs text-stone-950">Klaim Asuransi Jiwa & Pembunuhan Berencana</div>
                            <div class="text-[11px] font-normal text-stone-600 leading-snug mt-0.5">
                                Menyingkirkan Wallace demi mencairkan 5 juta kredit polis asuransi konsorsium.
                            </div>
                        </div>
                    </div>

                    <!-- Motive 3: Hostile Takeover -->
                    <div @click="selectedMotive = 'corporate_hostile_takeover'; NoirAudio.playClick()"
                         :class="selectedMotive === 'corporate_hostile_takeover' 
                             ? 'bg-red-950/20 border-red-800 text-stone-950 font-bold ring-1 ring-red-800 shadow-sm' 
                             : 'bg-stone-100/90 border-stone-300 text-stone-700 hover:bg-stone-200/80'"
                         class="p-2.5 rounded border text-left cursor-pointer transition-all font-mono text-xs flex items-start gap-2.5">
                        <span class="font-bold text-sm shrink-0" 
                              :class="selectedMotive === 'corporate_hostile_takeover' ? 'text-red-800' : 'text-stone-400'"
                              x-text="selectedMotive === 'corporate_hostile_takeover' ? '[•]' : '[ ]'"></span>
                        <div>
                            <div class="font-bold text-xs text-stone-950">Akuisisi Korporasi Paksa (Hostile Takeover)</div>
                            <div class="text-[11px] font-normal text-stone-600 leading-snug mt-0.5">
                                Menghancurkan reputasi Aethelgard agar nilai saham anjlok untuk dibeli Apex Global.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Key Evidence Chain with Left Checkboxes and Strict Width Clamping -->
            <div class="w-full max-w-full overflow-hidden">
                <label class="block font-bold text-xs sm:text-sm text-stone-900 mb-0.5 uppercase tracking-wide">
                    3. Rantai Barang Bukti Pembuktian (Bobot: 40 Poin)
                </label>
                <p class="text-[11px] text-stone-600 mb-2">
                    Centang bukti fisik, forensik, dan dokumen digital yang secara langsung membuktikan tindak kejahatan tersangka:
                </p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-52 overflow-y-auto p-1.5 bg-stone-200/60 rounded border border-stone-400 w-full max-w-full">
                    @foreach($evidences as $e)
                        <div @click="toggleEvidence('{{ $e['id'] }}'); NoirAudio.playClick()"
                             :class="selectedEvidences.includes('{{ $e['id'] }}') 
                                 ? 'bg-red-800 text-white shadow-md font-semibold border-red-900 ring-1 ring-red-700' 
                                 : 'bg-stone-100 text-stone-900 hover:bg-stone-200 border-stone-300'"
                             class="cursor-pointer p-2.5 rounded text-xs flex items-center gap-2.5 border transition leading-snug w-full min-w-0">
                            
                            <!-- Checkbox on the Left Side -->
                            <div class="w-5 h-5 rounded flex items-center justify-center font-bold text-xs font-mono shrink-0 shadow-sm"
                                 :class="selectedEvidences.includes('{{ $e['id'] }}') ? 'bg-amber-400 text-stone-950' : 'bg-stone-300 text-stone-600 border border-stone-400'">
                                <span x-text="selectedEvidences.includes('{{ $e['id'] }}') ? '✓' : ''"></span>
                            </div>

                            <!-- Evidence Content with Safe Text Wrapping -->
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 mb-0.5">
                                    <span class="font-mono text-[10px] font-bold px-1 py-0.2 rounded"
                                          :class="selectedEvidences.includes('{{ $e['id'] }}') ? 'bg-red-950 text-red-200' : 'bg-stone-200 text-stone-800'">
                                        [{{ $e['id'] }}]
                                    </span>
                                    <span class="text-[9px] opacity-80 uppercase font-mono truncate">{{ $e['category'] }}</span>
                                </div>
                                <div class="font-bold text-[11px] leading-tight wrap-break-word">
                                    {{ $e['title'] }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions: High-Contrast Submit Button -->
            <div class="pt-3 border-t-2 border-stone-800/30 flex justify-between items-center gap-4">
                <button type="button" @click="$store.game.isIndictmentModalOpen = false" class="px-4 py-2 bg-stone-400 hover:bg-stone-500 text-stone-950 font-bold rounded font-mono text-xs transition shadow">
                    Batal
                </button>

                <button type="submit" 
                        :disabled="isSubmitting"
                        class="px-6 py-2.5 bg-linear-to-r from-red-800 via-red-900 to-stone-950 hover:from-red-700 hover:to-red-900 text-stone-100 font-bold font-mono text-xs rounded-lg shadow-2xl border-2 border-red-600 transition flex items-center gap-2 cursor-pointer tracking-wide">
                    <span x-show="!isSubmitting">⚖️ AJUKAN DAKWAAN ➔</span>
                    <span x-show="isSubmitting" style="display: none;">MEMPROSES VONIS...</span>
                </button>
            </div>
        </form>
    </div>
</div>

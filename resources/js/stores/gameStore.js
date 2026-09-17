import { NoirAudio } from '../audio/NoirAudio';

export function registerGameStore(Alpine) {
    Alpine.store('game', {
        caseId: 'case_001',
        credibility: 100,
        unlockedClues: [],
        unlockedDialogueNodes: [],
        lockedSuspects: [],
        interrogationTranscripts: {},
        dialogueProgress: {},
        boardFacts: [],
        boardConnections: [],
        boardPositions: {},
        soundEnabled: true,
        crtEnabled: true,
        screenShake: false,
        inspectedEvidence: null,
        isObjectionModalOpen: false,
        activeStatementForObjection: null,
        isIndictmentModalOpen: false,
        isSaveModalOpen: false,
        isVerdictModalOpen: false,
        isTranscriptModalOpen: false,
        activeTranscriptSuspectId: null,
        verdictResult: null,
        objectionFlash: false,
        toastMessage: '',
        toastType: 'info',
        toastVisible: false,

        initGame(initialState = {}) {
            const savedLocal = localStorage.getItem(`phantom_case_${this.caseId}`);
            if (savedLocal) {
                try {
                    const parsed = JSON.parse(savedLocal);
                    this.credibility = parsed.credibility ?? 100;
                    this.unlockedClues = parsed.unlockedClues ?? [];
                    this.unlockedDialogueNodes = parsed.unlockedDialogueNodes ?? [];
                    this.lockedSuspects = parsed.lockedSuspects ?? [];
                    this.interrogationTranscripts = parsed.interrogationTranscripts ?? {};
                    this.dialogueProgress = parsed.dialogueProgress ?? {};
                    this.boardFacts = parsed.boardFacts ?? [];
                    this.boardConnections = parsed.boardConnections ?? [];
                    this.boardPositions = parsed.boardPositions ?? {};
                } catch (e) {
                    console.error("Failed to parse local storage save", e);
                }
            }

            const savedCrt = localStorage.getItem('phantom_crt_enabled');
            if (savedCrt !== null) {
                this.crtEnabled = savedCrt === 'true';
            }

            if (initialState.credibility !== undefined) {
                this.credibility = initialState.credibility;
            }
            if (initialState.unlocked_clues) {
                this.unlockedClues = [...new Set([...this.unlockedClues, ...initialState.unlocked_clues])];
            }
            if (initialState.locked_suspects) {
                this.lockedSuspects = [...new Set([...this.lockedSuspects, ...initialState.locked_suspects])];
            }
            if (initialState.interrogation_transcripts) {
                this.interrogationTranscripts = { ...this.interrogationTranscripts, ...initialState.interrogation_transcripts };
            }
            if (initialState.board_connections) {
                this.boardConnections = initialState.board_connections;
            }
            if (initialState.board_facts) {
                this.boardFacts = initialState.board_facts;
            }
        },

        toggleCrt() {
            this.crtEnabled = !this.crtEnabled;
            localStorage.setItem('phantom_crt_enabled', this.crtEnabled ? 'true' : 'false');
            this.showToast(this.crtEnabled ? 'Filter Retro CRT Diaktifkan' : 'Filter Retro CRT Dimatikan', 'info');
        },

        triggerScreenShake(duration = 650) {
            this.screenShake = true;
            setTimeout(() => {
                this.screenShake = false;
            }, duration);
        },

        triggerObjectionFlash(duration = 450) {
            this.objectionFlash = true;
            setTimeout(() => {
                this.objectionFlash = false;
            }, duration);
        },

        showToast(msg, type = 'info') {
            this.toastMessage = msg;
            this.toastType = type;
            this.toastVisible = true;
            setTimeout(() => {
                this.toastVisible = false;
            }, 3500);
        },

        adjustCredibility(delta) {
            this.credibility = Math.max(0, Math.min(100, this.credibility + delta));
            this.syncLocal();
            if (delta < 0) {
                this.showToast(`Kredibilitas berkurang ${delta} poin!`, 'error');
            } else if (delta > 0) {
                this.showToast(`Kredibilitas bertambah +${delta} poin!`, 'success');
            }
        },

        unlockClue(clueId, fact = null) {
            if (clueId && !this.unlockedClues.includes(clueId)) {
                this.unlockedClues.push(clueId);
            }
            if (fact && !this.boardFacts.some(f => f.id === fact.id)) {
                this.boardFacts.push(fact);
            }
            this.syncLocal();
        },

        isSuspectLocked(suspectId) {
            return Array.isArray(this.lockedSuspects) && this.lockedSuspects.includes(suspectId);
        },

        lockSuspect(suspectId) {
            if (!this.lockedSuspects.includes(suspectId)) {
                this.lockedSuspects.push(suspectId);
            }
            if (!this.dialogueProgress) this.dialogueProgress = {};
            this.syncLocal();
            this.saveToSlot('auto');
        },

        recordTranscript(suspectId, entry) {
            if (!suspectId || !entry) return;
            if (!this.interrogationTranscripts) this.interrogationTranscripts = {};
            if (!Array.isArray(this.interrogationTranscripts[suspectId])) {
                this.interrogationTranscripts[suspectId] = [];
            }

            const list = this.interrogationTranscripts[suspectId];
            const timestamp = entry.timestamp || new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            // Avoid consecutive exact text duplicates
            const last = list[list.length - 1];
            if (last && last.text === entry.text && last.type === entry.type) {
                return;
            }

            list.push({
                speaker: entry.speaker || 'Saksi',
                text: entry.text || '',
                type: entry.type || 'normal',
                label_selected: entry.label_selected || null,
                timestamp: timestamp
            });

            this.syncLocal();
        },

        getTranscript(suspectId) {
            if (!this.interrogationTranscripts || !this.interrogationTranscripts[suspectId]) {
                return [];
            }
            return this.interrogationTranscripts[suspectId];
        },

        openTranscriptModal(suspectId = null) {
            if (suspectId) {
                this.activeTranscriptSuspectId = suspectId;
            }
            this.isTranscriptModalOpen = true;
            NoirAudio.playPaper();
        },

        closeTranscriptModal() {
            this.isTranscriptModalOpen = false;
            NoirAudio.playClick();
        },

        syncLocal() {
            const payload = {
                caseId: this.caseId,
                credibility: this.credibility,
                unlockedClues: this.unlockedClues,
                unlockedDialogueNodes: this.unlockedDialogueNodes,
                lockedSuspects: this.lockedSuspects,
                interrogationTranscripts: this.interrogationTranscripts,
                dialogueProgress: this.dialogueProgress,
                boardFacts: this.boardFacts,
                boardConnections: this.boardConnections,
                boardPositions: this.boardPositions,
            };
            localStorage.setItem(`phantom_case_${this.caseId}`, JSON.stringify(payload));
        },

        async saveToSlot(slot = '1') {
            NoirAudio.playPaper();
            const payload = {
                case_id: this.caseId,
                credibility_score: this.credibility,
                unlocked_clues: this.unlockedClues,
                unlocked_dialogue_nodes: this.unlockedDialogueNodes,
                locked_suspects: this.lockedSuspects,
                interrogation_transcripts: this.interrogationTranscripts,
                dialogue_progress: this.dialogueProgress,
                board_connections: this.boardConnections,
                board_facts: this.boardFacts,
                board_nodes_positions: this.boardPositions,
            };

            try {
                const res = await fetch('/api/game/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({ slot, state: payload })
                });
                const data = await res.json();
                if (data.success && slot !== 'auto') {
                    this.showToast(data.message, 'success');
                    this.syncLocal();
                }
            } catch (err) {
                if (slot !== 'auto') {
                    this.showToast('Gagal menyimpan ke server, disimpan di LocalStorage.', 'info');
                }
                this.syncLocal();
            }
        },

        async loadFromSlot(slot = '1') {
            NoirAudio.playPaper();
            try {
                const res = await fetch(`/api/game/load/${slot}`);
                const data = await res.json();
                if (data.success && data.save) {
                    this.credibility = data.save.credibility_score ?? 100;
                    this.unlockedClues = data.save.unlocked_clues ?? [];
                    this.unlockedDialogueNodes = data.save.unlocked_dialogue_nodes ?? [];
                    this.lockedSuspects = data.save.locked_suspects ?? [];
                    this.interrogationTranscripts = data.save.interrogation_transcripts ?? {};
                    this.dialogueProgress = data.save.dialogue_progress ?? {};
                    this.boardFacts = data.save.board_facts ?? [];
                    this.boardConnections = data.save.board_connections ?? [];
                    this.boardPositions = data.save.board_nodes_positions ?? {};
                    this.syncLocal();
                    this.showToast(`Berhasil memuat Slot [${slot}].`, 'success');
                    setTimeout(() => window.location.reload(), 600);
                } else {
                    this.showToast('Gagal memuat slot.', 'error');
                }
            } catch (err) {
                this.showToast('Gagal terhubung ke server penyimpanan.', 'error');
            }
        },

        async resetGame() {
            NoirAudio.playPaper();
            if (confirm("Apakah Anda yakin ingin mereset seluruh progres investigasi kasus ini?")) {
                localStorage.removeItem(`phantom_case_${this.caseId}`);
                try {
                    await fetch(`/api/game/reset/${this.caseId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                        }
                    });
                } catch (e) {}
                window.location.reload();
            }
        }
    });
}

export default registerGameStore;

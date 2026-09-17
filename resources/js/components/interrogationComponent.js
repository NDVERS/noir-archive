import { NoirAudio } from '../audio/NoirAudio';

export function createInterrogationComponent({ caseId, activeSuspectId, dialoguesData = {}, suspectsData = [] }) {
    return {
        caseId: caseId,
        activeSuspectId: activeSuspectId,
        dialoguesData: dialoguesData,
        suspectsData: suspectsData,
        currentNodeId: '',
        displayedText: '',
        fullText: '',
        isTyping: false,
        typingTimer: null,
        hasStarted: false,
        isInterrogationFinished: false,
        objectionModalOpen: false,
        isSubmittingObjection: false,
        objectionFeedback: null,
        unlockedNodes: [],
        suspectTension: 20,
        pressBonusBpm: 0,
        pressTimeout: null,
        pressInfo: null,

        getActiveSuspect() {
            return (this.suspectsData || []).find(s => s.id === this.activeSuspectId) || null;
        },

        getPostCrackedNode(suspectId) {
            const suspectTree = this.dialoguesData[suspectId];
            if (!suspectTree || !suspectTree.nodes) return null;
            return suspectTree.nodes.find(n => n.type === 'post_cracked') || null;
        },

        init() {
            const suspectTree = this.dialoguesData[this.activeSuspectId];
            if (this.$store.game?.isSuspectLocked(this.activeSuspectId)) {
                const postCracked = this.getPostCrackedNode(this.activeSuspectId);
                if (postCracked) {
                    this.currentNodeId = postCracked.id;
                    this.isInterrogationFinished = true;
                    this.fullText = postCracked.text;
                    this.displayedText = postCracked.text;
                    return;
                }
            }

            if (suspectTree) {
                this.currentNodeId = suspectTree.initial_node || 'thorne_intro';
            }
        },

        startInterrogation() {
            if (this.hasStarted) return;
            this.hasStarted = true;
            window.NoirAudio?.wakeAudio();
            NoirAudio.playPaper();

            if (this.$store.game?.isSuspectLocked(this.activeSuspectId)) {
                const postCracked = this.getPostCrackedNode(this.activeSuspectId);
                if (postCracked) {
                    this.goToNode(postCracked.id);
                    return;
                }
            }

            const suspectTree = this.dialoguesData[this.activeSuspectId];
            if (suspectTree) {
                this.goToNode(this.currentNodeId || suspectTree.initial_node || 'thorne_intro');
            }
        },

        get effectiveTension() {
            if (this.$store.game?.isSuspectLocked(this.activeSuspectId)) return 20;
            return Math.min(100, this.suspectTension + this.pressBonusBpm);
        },

        get tensionStatus() {
            const eff = this.effectiveTension;
            if (eff >= 100) return { 
                label: 'ALIBI RUNTUH (BREAKDOWN)', 
                color: 'text-red-500', 
                strokeClass: 'text-red-500', 
                glowColor: '#ef4444',
                bpm: 168, 
                duration: '0.35s', 
                state: 'breakdown',
                path: 'M0,25 L6,25 L11,12 L16,38 L22,1 L28,49 L34,14 L40,25 L50,25 L55,10 L60,40 L66,0 L72,50 L78,12 L84,25 L100,25 L105,8 L111,42 L117,2 L123,48 L129,15 L135,25 L150,25 L156,25 L161,12 L166,38 L172,1 L178,49 L184,14 L190,25 L200,25 L205,10 L210,40 L216,0 L222,50 L228,12 L234,25 L250,25 L255,8 L261,42 L267,2 L273,48 L279,15 L285,25 L300,25'
            };
            if (eff >= 70) return { 
                label: 'TERTEKAN & TERPOJOK', 
                color: 'text-orange-400', 
                strokeClass: 'text-orange-400', 
                glowColor: '#f97316',
                bpm: 140, 
                duration: '0.5s', 
                state: 'panicked',
                path: 'M0,25 L10,25 Q16,16 22,25 L30,25 L35,34 L42,2 L50,48 L56,25 Q64,14 72,25 L90,25 L95,34 L102,2 L110,48 L116,25 L150,25 L160,25 Q166,16 172,25 L180,25 L185,34 L192,2 L200,48 L206,25 Q214,14 222,25 L240,25 L245,34 L252,2 L260,48 L266,25 L300,25'
            };
            if (eff >= 40) return { 
                label: 'GELISAH & DEFENSIF', 
                color: 'text-amber-400', 
                strokeClass: 'text-amber-400', 
                glowColor: '#eab308',
                bpm: 105, 
                duration: '0.8s', 
                state: 'agitated',
                path: 'M0,25 L15,25 Q22,18 28,25 L38,25 L43,31 L50,4 L57,45 L63,25 Q72,17 80,25 L150,25 L165,25 Q172,18 178,25 L188,25 L193,31 L200,4 L207,45 L213,25 Q222,17 230,25 L300,25'
            };
            return { 
                label: 'TENANG & DINGIN', 
                color: 'text-emerald-400', 
                strokeClass: 'text-emerald-400', 
                glowColor: '#22c55e',
                bpm: 72, 
                duration: '1.2s', 
                state: 'calm',
                path: 'M0,25 L20,25 Q28,21 34,25 L45,25 L50,29 L56,7 L62,41 L68,25 Q76,19 84,25 L150,25 L170,25 Q178,21 184,25 L195,25 L200,29 L206,7 L212,41 L218,25 Q226,19 234,25 L300,25'
            };
        },

        loadSuspect(suspectId) {
            this.activeSuspectId = suspectId;
            this.objectionFeedback = null;
            this.pressInfo = null;
            this.suspectTension = 20;
            this.pressBonusBpm = 0;
            this.isInterrogationFinished = false;
            if (this.pressTimeout) clearTimeout(this.pressTimeout);

            if (this.$store.game?.isSuspectLocked(suspectId)) {
                const postCracked = this.getPostCrackedNode(suspectId);
                if (postCracked) {
                    this.currentNodeId = postCracked.id;
                    this.isInterrogationFinished = true;
                    if (this.hasStarted) {
                        this.goToNode(postCracked.id);
                    } else {
                        this.fullText = postCracked.text;
                        this.displayedText = postCracked.text;
                    }
                    return;
                }
            }

            const suspectTree = this.dialoguesData[suspectId];
            if (suspectTree) {
                if (this.hasStarted) {
                    this.goToNode(suspectTree.initial_node || 'thorne_intro');
                } else {
                    this.currentNodeId = suspectTree.initial_node || 'thorne_intro';
                }
            }
        },

        getCurrentNode() {
            const suspectTree = this.dialoguesData[this.activeSuspectId];
            if (!suspectTree || !suspectTree.nodes) return null;
            return suspectTree.nodes.find(n => n.id === this.currentNodeId) || suspectTree.nodes[0];
        },

        goToNode(nodeId, selectedLabel = null) {
            window.NoirAudio?.wakeAudio();
            NoirAudio.playClick();
            this.currentNodeId = nodeId;
            this.objectionFeedback = null;
            this.pressInfo = null;
            const node = this.getCurrentNode();
            if (node) {
                if (selectedLabel) {
                    this.$store.game?.recordTranscript(this.activeSuspectId, {
                        speaker: 'Detektif Konsultan',
                        text: selectedLabel,
                        type: 'question',
                        label_selected: selectedLabel
                    });
                }

                this.$store.game?.recordTranscript(this.activeSuspectId, {
                    speaker: node.speaker || 'Saksi',
                    text: node.text,
                    type: node.type
                });

                if (node.type === 'cracked_reaction') {
                    this.suspectTension = 100;
                    this.pressBonusBpm = 0;
                    if (this.pressTimeout) clearTimeout(this.pressTimeout);
                }

                if (node.type === 'conclusion' || node.type === 'cracked_reaction' || node.type === 'post_cracked') {
                    this.isInterrogationFinished = true;
                }

                if (node.type === 'post_cracked') {
                    this.suspectTension = 20;
                    this.pressBonusBpm = 0;
                    this.$store.game?.lockSuspect(this.activeSuspectId);
                    this.$store.game?.showToast('Subjek menggunakan Hak Bungkam. Pemeriksaan resmi ditutup permanen.', 'info');
                }

                this.typewriteText(node.text);
            }
        },

        typewriteText(text) {
            if (this.typingTimer) clearInterval(this.typingTimer);
            this.fullText = text;
            this.displayedText = '';
            this.isTyping = true;
            let index = 0;

            this.typingTimer = setInterval(() => {
                if (index < this.fullText.length) {
                    this.displayedText += this.fullText[index];
                    if (index % 2 === 0) {
                        NoirAudio.playTypewriter();
                    }
                    index++;
                } else {
                    this.finishTyping();
                }
            }, 20);
        },

        finishTyping(playFeedback = false) {
            if (this.typingTimer) clearInterval(this.typingTimer);
            this.displayedText = this.fullText;
            this.isTyping = false;
            if (playFeedback) {
                NoirAudio.playClick();
            }
        },

        skipTypewriter() {
            window.NoirAudio?.wakeAudio();
            if (this.isTyping) {
                this.finishTyping(true);
            }
        },

        handleGlobalKeydown(e) {
            if (!this.hasStarted) {
                if (e.code === 'Space' || e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    this.startInterrogation();
                    return;
                }
            }

            window.NoirAudio?.wakeAudio();

            const activeTag = document.activeElement?.tagName?.toLowerCase();
            if (['input', 'textarea', 'select'].includes(activeTag)) return;

            if (this.objectionModalOpen || this.$store.game?.isIndictmentModalOpen || this.$store.game?.isTranscriptModalOpen) return;

            if (e.code === 'Space' || e.key === ' ' || e.key === 'Enter') {
                if (this.isTyping) {
                    e.preventDefault();
                    this.skipTypewriter();
                    return;
                }
            }

            const match = e.code.match(/^(?:Digit|Numpad)([1-9])$/);
            if (match && !this.isTyping) {
                const selectedIndex = parseInt(match[1]) - 1;
                const activeOptions = this.getCurrentNode()?.options || [];
                if (selectedIndex >= 0 && selectedIndex < activeOptions.length && !this.$store.game?.isSuspectLocked(this.activeSuspectId)) {
                    e.preventDefault();
                    const chosen = activeOptions[selectedIndex];
                    this.goToNode(chosen.next_node, chosen.label);
                }
            }
        },

        pressStatement() {
            window.NoirAudio?.wakeAudio();
            const node = this.getCurrentNode();
            if (!node) return;
            NoirAudio.playTypewriter();
            NoirAudio.playPaper();

            this.suspectTension = Math.min(85, this.suspectTension + 20);
            this.pressBonusBpm = 20;
            if (this.pressTimeout) clearTimeout(this.pressTimeout);
            this.pressTimeout = setTimeout(() => {
                this.pressBonusBpm = 0;
            }, 3000);

            NoirAudio.playHeartbeat(this.tensionStatus.bpm);

            if (node.press_response) {
                this.pressInfo = node.press_response;
                this.$store.game?.recordTranscript(this.activeSuspectId, {
                    speaker: 'Detektif Konsultan',
                    text: '[PRESS STATEMENT / MENDALAMI KLAIM KETERANGAN SAKSI]',
                    type: 'press_action'
                });
                this.$store.game?.recordTranscript(this.activeSuspectId, {
                    speaker: node.speaker || 'Saksi',
                    text: node.press_response.text,
                    type: 'press'
                });
                this.typewriteText(node.press_response.text);
            } else {
                this.pressInfo = {
                    speaker: node.speaker || 'Saksi',
                    text: 'Klarifikasi Terdesak: \'Kenapa Anda menanyakan hal itu dengan nada menuduh?! Saya sudah memberikan semua keterangan yang saya ketahui!\'',
                    clue_hint: 'Saksi tampak tertekan saat Anda mendalami keterangannya. Terus cari celah kontradiksi pada alibi mereka!'
                };
                this.$store.game?.recordTranscript(this.activeSuspectId, {
                    speaker: 'Detektif Konsultan',
                    text: '[PRESS STATEMENT / MENDALAMI KLAIM KETERANGAN SAKSI]',
                    type: 'press_action'
                });
                this.$store.game?.recordTranscript(this.activeSuspectId, {
                    speaker: node.speaker || 'Saksi',
                    text: this.pressInfo.text,
                    type: 'press'
                });
                this.typewriteText(this.pressInfo.text);
            }
        },

        openObjectionModal() {
            NoirAudio.playPaper();
            this.objectionModalOpen = true;
        },

        async presentEvidence(evidenceId) {
            const node = this.getCurrentNode();
            if (!node || !node.statement_id) return;

            this.isSubmittingObjection = true;
            this.objectionModalOpen = false;

            try {
                const res = await fetch('/api/game/present-evidence', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\'csrf-token\']')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        case_id: this.caseId,
                        statement_id: node.statement_id,
                        evidence_id: evidenceId
                    })
                });
                const data = await res.json();
                this.objectionFeedback = data;

                if (data.valid) {
                    this.suspectTension = 100;
                    this.pressBonusBpm = 0;
                    if (this.pressTimeout) clearTimeout(this.pressTimeout);
                    this.$store.game.triggerObjectionFlash(500);
                    this.$store.game.triggerScreenShake(700);
                    NoirAudio.playObjection();
                    NoirAudio.playHeartbeat(168);
                    this.$store.game.adjustCredibility(data.credibility_delta);
                    this.$store.game.unlockClue(data.unlocked_clue_id, data.board_fact);

                    this.$store.game?.recordTranscript(this.activeSuspectId, {
                        speaker: 'Detektif Konsultan',
                        text: 'OBJECTION! Menyodorkan barang bukti [' + evidenceId + '] terhadap alibi saksi.',
                        type: 'objection_action'
                    });
                    this.$store.game?.recordTranscript(this.activeSuspectId, {
                        speaker: node.speaker || 'Saksi',
                        text: data.message,
                        type: 'cracked_reaction'
                    });

                    let targetNode = data.target_dialogue_node;
                    if (!targetNode) {
                        if (node.statement_id === 'STMT_THORNE_CROFT_DENIAL' || data.unlocked_clue_id === 'CLUE_THORNE_FINANCE_CRACKED') {
                            targetNode = 'thorne_confession_details';
                        } else if (node.statement_id === 'STMT_THORNE_BREACH_METHOD') {
                            targetNode = 'thorne_confession_details';
                        } else if (node.statement_id === 'STMT_ELENA_KEY_DENIAL') {
                            targetNode = 'elena_cracked_cctv';
                        }
                    }

                    if (targetNode) {
                        setTimeout(() => {
                            this.goToNode(targetNode);
                        }, 1600);
                    }
                } else {
                    NoirAudio.playClick();
                    this.suspectTension = Math.max(15, this.suspectTension - 10);
                    this.$store.game.adjustCredibility(data.credibility_delta);

                    this.$store.game?.recordTranscript(this.activeSuspectId, {
                        speaker: 'Detektif Konsultan',
                        text: 'Menyodorkan bukti [' + evidenceId + '] (Ditolak: Bukti tidak membantah klaim).',
                        type: 'objection_failed'
                    });
                }
            } catch (e) {
                alert('Gagal menghubungi server investigasi.');
            } finally {
                this.isSubmittingObjection = false;
            }
        }
    };
}

export default createInterrogationComponent;

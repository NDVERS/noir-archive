// Web Audio API Procedural Noir Sound Synthesizer
const NoirAudio = {
    ctx: null,
    enabled: true,
    isUnlocked: false,
    hasUserInteracted: false,

    ensureAudioContext() {
        // JANGAN PERNAH inisialisasi context jika user belum pernah berinteraksi
        if (!this.hasUserInteracted) {
            return null;
        }
        if (!this.ctx) {
            const AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) {
                this.ctx = new AudioCtx();
            }
        }
        return this.ctx;
    },

    wakeAudio() {
        if (!this.hasUserInteracted || !this.enabled) {
            return;
        }
        this.ensureAudioContext();
    },

    unlock() {
        if (this.hasUserInteracted) {
            this.ensureAudioContext();
        }
    },

    init() {
        return null;
    },

    toggle() {
        this.enabled = !this.enabled;
        return this.enabled;
    },

    playClick() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'triangle';
            osc.frequency.setValueAtTime(140, ctx.currentTime);
            osc.frequency.exponentialRampToValueAtTime(30, ctx.currentTime + 0.05);

            gain.gain.setValueAtTime(0.15, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.05);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.05);
        } catch (e) {}
    },

    playTypewriter() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            const randFreq = 600 + Math.random() * 400;
            osc.type = 'square';
            osc.frequency.setValueAtTime(randFreq, ctx.currentTime);

            gain.gain.setValueAtTime(0.06, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.035);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start();
            osc.stop(ctx.currentTime + 0.035);
        } catch (e) {}
    },

    playPaper() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            // Filtered white noise puff for paper rustle
            const bufferSize = Math.floor(ctx.sampleRate * 0.1);
            const buffer = ctx.createBuffer(1, bufferSize, ctx.sampleRate);
            const data = buffer.getChannelData(0);
            for (let i = 0; i < bufferSize; i++) {
                data[i] = (Math.random() * 2 - 1) * Math.exp(-i / (bufferSize * 0.3));
            }

            const noise = ctx.createBufferSource();
            noise.buffer = buffer;

            const filter = ctx.createBiquadFilter();
            filter.type = 'bandpass';
            filter.frequency.value = 1200;
            filter.Q.value = 1.2;

            const gain = ctx.createGain();
            gain.gain.setValueAtTime(0.12, ctx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.1);

            noise.connect(filter);
            filter.connect(gain);
            gain.connect(ctx.destination);
            noise.start();
        } catch (e) {}
    },

    playObjection() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const now = ctx.currentTime;
            // Dramatic low brass / brass stab chord
            [110, 164.81, 220, 329.63, 440].forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = idx % 2 === 0 ? 'sawtooth' : 'triangle';
                osc.frequency.setValueAtTime(freq * 1.5, now);
                osc.frequency.exponentialRampToValueAtTime(freq, now + 0.08);

                gain.gain.setValueAtTime(0.25 / (idx + 1), now);
                gain.gain.exponentialRampToValueAtTime(0.001, now + 0.8);

                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now);
                osc.stop(now + 0.8);
            });
        } catch (e) {}
    },

    playPinTack() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const now = ctx.currentTime;
            // Wood tack sound (low resonant thud + quick high click)
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sine';
            osc.frequency.setValueAtTime(220, now);
            osc.frequency.exponentialRampToValueAtTime(60, now + 0.06);

            gain.gain.setValueAtTime(0.3, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.06);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.06);

            // Click component
            const clickOsc = ctx.createOscillator();
            const clickGain = ctx.createGain();
            clickOsc.type = 'triangle';
            clickOsc.frequency.setValueAtTime(1200, now);
            clickOsc.frequency.exponentialRampToValueAtTime(100, now + 0.02);

            clickGain.gain.setValueAtTime(0.15, now);
            clickGain.gain.exponentialRampToValueAtTime(0.001, now + 0.02);

            clickOsc.connect(clickGain);
            clickGain.connect(ctx.destination);
            clickOsc.start(now);
            clickOsc.stop(now + 0.02);
        } catch (e) {}
    },

    playStringSnip() {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const now = ctx.currentTime;
            // Snip / pluck sound: fast pitch bend down with short release
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            osc.type = 'sawtooth';
            osc.frequency.setValueAtTime(480, now);
            osc.frequency.exponentialRampToValueAtTime(120, now + 0.08);

            gain.gain.setValueAtTime(0.18, now);
            gain.gain.exponentialRampToValueAtTime(0.001, now + 0.08);

            const filter = ctx.createBiquadFilter();
            filter.type = 'bandpass';
            filter.frequency.setValueAtTime(1800, now);
            filter.Q.value = 2.0;

            osc.connect(filter);
            filter.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.08);
        } catch (e) {}
    },

    playVerdict(isSuccess) {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const now = ctx.currentTime;
            const freqs = isSuccess ? [261.63, 329.63, 392.00, 523.25] : [220, 207.65, 196, 174.61];

            freqs.forEach((freq, idx) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = isSuccess ? 'triangle' : 'sawtooth';
                osc.frequency.setValueAtTime(freq, now + (idx * 0.12));

                gain.gain.setValueAtTime(0.15, now + (idx * 0.12));
                gain.gain.exponentialRampToValueAtTime(0.001, now + 1.6);

                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(now + (idx * 0.12));
                osc.stop(now + 1.6);
            });
        } catch (e) {}
    },

    playHeartbeat(bpm = 72) {
        if (!this.hasUserInteracted || !this.enabled) return;
        const ctx = this.ensureAudioContext();
        if (!ctx || ctx.state !== 'running') return;
        try {
            const now = ctx.currentTime;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();

            // Subtle medical / polygraph pip tone with pitch scaling according to BPM
            const baseFreq = bpm >= 150 ? 940 : (bpm >= 120 ? 840 : (bpm >= 90 ? 760 : 680));
            osc.type = 'sine';
            osc.frequency.setValueAtTime(baseFreq, now);
            osc.frequency.exponentialRampToValueAtTime(baseFreq * 1.12, now + 0.035);

            // Soft volume for atmospheric feedback
            gain.gain.setValueAtTime(0.04, now);
            gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.045);

            osc.connect(gain);
            gain.connect(ctx.destination);
            osc.start(now);
            osc.stop(now + 0.045);
        } catch (e) {}
    }
};

window.NoirAudio = NoirAudio;

// One-time capture phase unlock listener on user gesture
const unlockNoirAudio = () => {
    NoirAudio.hasUserInteracted = true;
    if (!NoirAudio.ctx) {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (AudioCtx) NoirAudio.ctx = new AudioCtx();
    }
    if (NoirAudio.ctx && NoirAudio.ctx.state === 'suspended') {
        NoirAudio.ctx.resume();
    }
    window.removeEventListener('click', unlockNoirAudio, true);
    window.removeEventListener('keydown', unlockNoirAudio, true);
    window.removeEventListener('pointerdown', unlockNoirAudio, true);
};

window.addEventListener('click', unlockNoirAudio, true);
window.addEventListener('keydown', unlockNoirAudio, true);
window.addEventListener('pointerdown', unlockNoirAudio, true);

export default NoirAudio;
export { NoirAudio };

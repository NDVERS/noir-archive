import { NoirAudio } from '../audio/NoirAudio';

// Corkboard Factory Function
export function createCorkboardComponent(initialNodes = []) {
    return {
        nodes: [],
        defaultNodes: initialNodes,
        selectedNodeId: null,
        draggingNodeId: null,
        dragOffsetX: 0,
        dragOffsetY: 0,

        init() {
            window.activeCorkboardComponent = this;
            this.initNodes();
            this.$nextTick(() => {
                this.updateAllCoordinates();
            });
            window.addEventListener('resize', () => this.updateAllCoordinates());
        },

        initNodes() {
            let savedPositions = {};
            try {
                if (window.Alpine && window.Alpine.store('game')) {
                    savedPositions = window.Alpine.store('game').boardPositions || {};
                }
            } catch (e) {
                console.warn('Could not read saved positions', e);
            }

            // Clone default nodes and apply valid saved positions if available
            this.nodes = (this.defaultNodes || []).map(node => {
                const saved = savedPositions[node.id];
                const x = (saved && typeof saved.x === 'number' && !isNaN(saved.x)) ? saved.x : node.x;
                const y = (saved && typeof saved.y === 'number' && !isNaN(saved.y)) ? saved.y : node.y;
                return {
                    ...node,
                    x: x,
                    y: y,
                };
            });

            // Append dynamic unlocked facts from store if any
            try {
                const facts = (window.Alpine && window.Alpine.store('game')?.boardFacts) || [];
                facts.forEach((fact, idx) => {
                    const id = 'node_' + fact.id;
                    const saved = savedPositions[id];
                    const x = (saved && typeof saved.x === 'number' && !isNaN(saved.x)) ? saved.x : (120 + idx * 240);
                    const y = (saved && typeof saved.y === 'number' && !isNaN(saved.y)) ? saved.y : 740;

                    this.nodes.push({
                        id: id,
                        rawId: fact.id,
                        type: 'fact',
                        title: fact.title,
                        subtitle: fact.text,
                        color: fact.color || '#dc2626',
                        x: x,
                        y: y,
                        width: 180,
                        rotationClass: (idx % 2 === 0 ? 'origin-top rotate-[2deg]' : 'origin-top -rotate-[2deg]')
                    });
                });
            } catch (e) {}
        },

        getPinCoord(node) {
            if (!node) return { x: 0, y: 0 };
            const w = Number(node.width) || (node.type === 'suspect' ? 180 : 180);
            const pinOffsetY = 0; // Exactly at the pushpin center

            return {
                x: Math.round(Number(node.x) + (w / 2)),
                y: Math.round(Number(node.y) + pinOffsetY)
            };
        },

        get connections() {
            return (window.Alpine && window.Alpine.store('game')?.boardConnections) || [];
        },

        get computedLines() {
            const conns = this.connections;
            if (!conns || !conns.length) return [];
            const lines = conns.map((conn, idx) => {
                const fromNode = this.nodes.find(n => n.id === conn.from);
                const toNode = this.nodes.find(n => n.id === conn.to);
                if (!fromNode || !toNode) return null;

                const fromPin = this.getPinCoord(fromNode);
                const toPin = this.getPinCoord(toNode);

                const x1 = fromPin.x;
                const y1 = fromPin.y;
                const x2 = toPin.x;
                const y2 = toPin.y;

                const midX = Math.round((x1 + x2) / 2);
                const distance = Math.hypot(x2 - x1, y2 - y1);
                const sag = Math.min(Math.max(distance * 0.08, 10), 35); // Lengkungan proporsional 8% dari panjang benang
                const midY = Math.round(((y1 + y2) / 2) + sag);

                return {
                    idx: idx,
                    from: conn.from,
                    to: conn.to,
                    x1: x1,
                    y1: y1,
                    x2: x2,
                    y2: y2,
                    midX: midX,
                    midY: midY,
                    distance: distance
                };
            }).filter(line => line !== null && !isNaN(line.x1) && !isNaN(line.x2) && !isNaN(line.y1) && !isNaN(line.y2));

            return lines;
        },

        get svgLinesMarkup() {
            if (!this.computedLines || !this.computedLines.length) return '';

            return this.computedLines.map(line => `
                <g class="group/thread cursor-pointer" onclick="window.removeCorkboardConnection(${line.idx})" style="pointer-events: stroke;">
                    <title>Klik untuk melepas sambungan benang merah ini</title>
                    <!-- Area Hit Transparan Lebar (Mudah Diklik) -->
                    <path d="M ${line.x1} ${line.y1} Q ${line.midX} ${line.midY} ${line.x2} ${line.y2}" 
                          fill="none" stroke="transparent" stroke-width="16" stroke-linecap="round" />
                    <!-- Bayangan Wol Gelap -->
                    <path d="M ${line.x1} ${line.y1 + 3} Q ${line.midX} ${line.midY + 3} ${line.x2} ${line.y2 + 3}" 
                          fill="none" stroke="#260404" stroke-width="4.5" stroke-linecap="round" opacity="0.65" />
                    <!-- Benang Wol Merah Utama dengan Efek Glow Hover -->
                    <path d="M ${line.x1} ${line.y1} Q ${line.midX} ${line.midY} ${line.x2} ${line.y2}" 
                          fill="none" stroke="#dc2626" stroke-width="3" stroke-linecap="round"
                          class="transition-all duration-200 group-hover/thread:stroke-amber-400 group-hover/thread:stroke-[4.5px]" />
                </g>
            `).join('');
        },

        get activeConnections() {
            return this.computedLines;
        },

        getLineCoords(conn) {
            if (!conn || !conn.from || !conn.to) return null;
            const fromNode = this.nodes.find(n => n.id === conn.from);
            const toNode = this.nodes.find(n => n.id === conn.to);
            if (!fromNode || !toNode) return null;

            const fromPin = this.getPinCoord(fromNode);
            const toPin = this.getPinCoord(toNode);

            const x1 = fromPin.x;
            const y1 = fromPin.y;
            const x2 = toPin.x;
            const y2 = toPin.y;

            const midX = Math.round((x1 + x2) / 2);
            const distance = Math.hypot(x2 - x1, y2 - y1);
            const sag = Math.min(Math.max(distance * 0.08, 10), 35);
            const midY = Math.round(((y1 + y2) / 2) + sag);

            return {
                x1: x1,
                y1: y1,
                x2: x2,
                y2: y2,
                midX: midX,
                midY: midY
            };
        },

        connectNodes(fromId, toId) {
            const gameStore = window.Alpine && window.Alpine.store('game');
            if (gameStore) {
                const exists = gameStore.boardConnections.some(c => 
                    (c.from === fromId && c.to === toId) ||
                    (c.from === toId && c.to === fromId)
                );

                if (exists) {
                    gameStore.boardConnections = gameStore.boardConnections.filter(c => 
                        !((c.from === fromId && c.to === toId) ||
                          (c.from === toId && c.to === fromId))
                    );
                    NoirAudio.playStringSnip();
                    gameStore.showToast('Benang merah dilepas.', 'info');
                } else {
                    gameStore.boardConnections.push({
                        from: fromId,
                        to: toId
                    });
                    NoirAudio.playPinTack();
                    NoirAudio.playPaper();
                    gameStore.showToast('Benang merah terhubung!', 'success');
                }

                gameStore.syncLocal();
            }
            this.updateAllCoordinates();
        },

        resetPositions() {
            NoirAudio.playPaper();
            this.nodes.forEach((node, idx) => {
                const def = this.defaultNodes.find(d => d.id === node.id);
                if (def) {
                    node.x = def.x;
                    node.y = def.y;
                } else if (node.type === 'fact') {
                    node.x = 120 + idx * 240;
                    node.y = 740;
                }
                if (window.Alpine && window.Alpine.store('game')) {
                    window.Alpine.store('game').boardPositions[node.id] = { x: node.x, y: node.y };
                }
            });
            if (window.Alpine && window.Alpine.store('game')) {
                window.Alpine.store('game').syncLocal();
                window.Alpine.store('game').showToast('Tata letak papan dikembalikan ke posisi awal.', 'info');
            }
            this.updateAllCoordinates();
        },

        handleNodeClick(nodeId) {
            const gameStore = window.Alpine && window.Alpine.store('game');

            if (!this.selectedNodeId) {
                this.selectedNodeId = nodeId;
                NoirAudio.playPinTack();
                if (gameStore) gameStore.showToast('Simpul Awal Dipilih. Klik simpul tujuan untuk menghubungkan benang.', 'info');
            } else if (this.selectedNodeId === nodeId) {
                this.selectedNodeId = null;
                NoirAudio.playClick();
                if (gameStore) gameStore.showToast('Pemilihan simpul dibatalkan.', 'info');
            } else {
                this.connectNodes(this.selectedNodeId, nodeId);
                this.selectedNodeId = null;
            }
        },

        removeConnection(index) {
            NoirAudio.playStringSnip();
            const gameStore = window.Alpine && window.Alpine.store('game');
            if (gameStore) {
                gameStore.boardConnections.splice(index, 1);
                gameStore.syncLocal();
                gameStore.showToast('Benang merah dilepas.', 'info');
            }
            this.updateAllCoordinates();
        },

        clearAllConnections() {
            const gameStore = window.Alpine && window.Alpine.store('game');
            if (confirm('Lepaskan semua sambungan benang merah di papan investigasi?')) {
                NoirAudio.playStringSnip();
                if (gameStore) {
                    gameStore.boardConnections = [];
                    gameStore.syncLocal();
                    gameStore.showToast('Seluruh benang merah dibersihkan.', 'info');
                }
                this.updateAllCoordinates();
            }
        },

        startDrag(nodeId, event) {
            this.draggingNodeId = nodeId;
            const node = this.nodes.find(n => n.id === nodeId);
            if (node) {
                const clientX = event.clientX || event.touches?.[0]?.clientX;
                const clientY = event.clientY || event.touches?.[0]?.clientY;
                this.dragOffsetX = clientX - node.x;
                this.dragOffsetY = clientY - node.y;
            }
        },

        onDrag(event) {
            if (!this.draggingNodeId) return;
            const node = this.nodes.find(n => n.id === this.draggingNodeId);
            if (node) {
                const clientX = event.clientX || event.touches?.[0]?.clientX;
                const clientY = event.clientY || event.touches?.[0]?.clientY;
                node.x = Math.max(10, Math.min(1800, clientX - this.dragOffsetX));
                node.y = Math.max(10, Math.min(1200, clientY - this.dragOffsetY));

                const gameStore = window.Alpine && window.Alpine.store('game');
                if (gameStore) {
                    gameStore.boardPositions[node.id] = { x: node.x, y: node.y };
                    gameStore.syncLocal();
                }
                this.nodes = [...this.nodes];
            }
        },

        stopDrag() {
            if (this.draggingNodeId) {
                this.draggingNodeId = null;
                this.updateAllCoordinates();
            }
        },

        getNodeCenter(nodeId) {
            const node = this.nodes.find(n => n.id === nodeId);
            return this.getPinCoord(node);
        },

        updateAllCoordinates() {
            this.nodes = [...this.nodes];
        }
    };
}

window.createCorkboardComponent = createCorkboardComponent;

window.removeCorkboardConnection = function(index) {
    if (window.activeCorkboardComponent) {
        window.activeCorkboardComponent.removeConnection(index);
    } else {
        const gameStore = window.Alpine && window.Alpine.store('game');
        if (gameStore && gameStore.boardConnections) {
            gameStore.boardConnections.splice(index, 1);
            gameStore.syncLocal();
            gameStore.showToast('Benang merah dilepas.', 'info');
            if (window.NoirAudio) window.NoirAudio.playStringSnip();
        }
    }
};

export default createCorkboardComponent;

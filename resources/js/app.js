import './bootstrap';
import Alpine from 'alpinejs';
import { NoirAudio } from './audio/NoirAudio';
import { createCorkboardComponent } from './components/corkboardComponent';
import { createInterrogationComponent } from './components/interrogationComponent';
import { registerGameStore } from './stores/gameStore';

// Expose globals for Alpine and inline scripts
window.Alpine = Alpine;
window.NoirAudio = NoirAudio;
window.createCorkboardComponent = createCorkboardComponent;
window.createInterrogationComponent = createInterrogationComponent;

// Register Alpine components & stores
Alpine.data('corkboard', createCorkboardComponent);
Alpine.data('interrogationRoom', createInterrogationComponent);
registerGameStore(Alpine);

// Start Alpine
Alpine.start();
import './bootstrap';

import Alpine from 'alpinejs';
import registerTheme from './theme';
import registerViewMode from './view-mode';

window.Alpine = Alpine;

registerTheme(Alpine);
registerViewMode(Alpine);

Alpine.start();

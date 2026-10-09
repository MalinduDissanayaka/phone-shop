import './bootstrap';

import Alpine from 'alpinejs';
import registerTheme from './theme';

window.Alpine = Alpine;

registerTheme(Alpine);

Alpine.start();

/* ====================================================================
   ALPINE.JS — INIT + PLUGINS
   ==================================================================== */

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import focus from '@alpinejs/focus';
import mask from '@alpinejs/mask';
import collapse from '@alpinejs/collapse';
import intersect from '@alpinejs/intersect';

// Register plugins
Alpine.plugin(persist);
Alpine.plugin(focus);
Alpine.plugin(mask);
Alpine.plugin(collapse);
Alpine.plugin(intersect);

// Expose to global window for inline x-data="window.x.foo()" if needed
window.Alpine = Alpine;

// Start Alpine after DOM is ready
Alpine.start();

export default Alpine;

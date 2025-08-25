import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Sortable from 'sortablejs';
// or, if using CommonJS syntax:
// const Sortable = require('sortablejs').default;

// Example initialization:
let el = document.getElementById('sortable-list');
if (el) {
  Sortable.create(el, {
    animation: 150,
    handle: '.handle',       // optional: drag only from handle
    ghostClass: 'sortable-ghost',
  });
}

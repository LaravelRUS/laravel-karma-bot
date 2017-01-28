import './../css/app.scss';

import 'babel-polyfill';
import ko from 'tko/dist/tko';
import ComponentRegistrar from './Support/ComponentRegistrar';

window.ko = ko;


(new ComponentRegistrar())
    .register(require('./Component/Dropdown').default);



let boot = function () {
    document.body.classList.add('loaded');

    for (let node of document.querySelectorAll('[data-vm]')) {
        let cls  = require(`./ViewModels/${node.getAttribute('data-vm')}`).default;

        ko.applyBindings(new cls(node), node);
    }
};


if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function (event) {
        boot();
    });
} else {
    boot();
}

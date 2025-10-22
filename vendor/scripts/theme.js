/* globals bootstrap:false, Prism:false */

// Script principal para la gestión de temas y utilidades visuales

(function () {
    'use strict';

    // Escapa caracteres especiales en HTML
    function escapeHtml(html) {
        return html.replace(/×/g, '&times;')
            .replace(/«/g, '&laquo;')
            .replace(/»/g, '&raquo;')
            .replace(/←/g, '&larr;')
            .replace(/→/g, '&rarr;');
    }

    // Limpia el código fuente HTML para mostrarlo bonito
    function cleanSource(html) {
        let lines = escapeHtml(html).split('\n').filter(Boolean).slice(0, -1);
        const indentSize = lines[0].length - lines[0].trim().length;
        const re = new RegExp(' {' + indentSize + '}');

        lines = lines.map(line => {
            return re.test(line) ? line.slice(Math.max(0, indentSize)) : line;
        });

        return lines.join('\n');
    }

    // Añade o quita la clase .navbar-transparent al hacer scroll
    function addNavbarTransparentClass() {
        const navBarElement = document.querySelector('#home > .navbar');

        if (!navBarElement) {
            return;
        }

        window.addEventListener('scroll', () => {
            const scroll = document.documentElement.scrollTop;

            if (scroll > 50) {
                navBarElement.classList.remove('navbar-transparent');
            } else {
                navBarElement.classList.add('navbar-transparent');
            }
        });
    }

    // Añade funcionalidad a los modales de código fuente
    function addSourceModals() {
        const sourceModalElement = document.getElementById('source-modal');

        if (!sourceModalElement) {
            return;
        }

        sourceModalElement.querySelector('.btn-copy').addEventListener('click', (e) => {
            if (navigator.clipboard) {
                const code = sourceModalElement.querySelector('.modal-body pre').innerText;
                navigator.clipboard.writeText(code);
            }

            const sourceModal = bootstrap.Modal.getOrCreateInstance(sourceModalElement);
            sourceModal.hide();
        });

        document.body.addEventListener('click', event => {
            if (!event.target.matches('.source-button')) {
                return;
            }

            const sourceModal = bootstrap.Modal.getOrCreateInstance(sourceModalElement);
            let html = event.target.parentNode.innerHTML;

            html = Prism.highlight(cleanSource(html), Prism.languages.html, 'html');

            sourceModalElement.querySelector('code').innerHTML = html;
            sourceModal.show();
        }, false);
    }

    // Permite cambiar entre temas claro/oscuro
    function toggleThemeMenu() {
        let themeMenu = document.querySelector('#theme-menu');

        if (!themeMenu) return;

        document.querySelectorAll('[data-bs-theme-value]').forEach(value => {
            value.addEventListener('click', () => {
                const theme = value.getAttribute('data-bs-theme-value');
                document.documentElement.setAttribute('data-bs-theme', theme);
            });
        });
    }

    addNavbarTransparentClass();
    addSourceModals();
    toggleThemeMenu();

    // Previene navegación en enlaces vacíos y botones submit
    const targets = document.querySelectorAll('[href="#"], [type="submit"]');
    for (const element of targets) {
        element.addEventListener('click', event => {
            event.preventDefault();
        });
    }

    // Añade botón "View Source" en cada componente Bootstrap
    const bsComponents = document.querySelectorAll('.bs-component');
    for (const element of bsComponents) {
        const button = '<button class="source-button btn btn-primary btn-xs" type="button" tabindex="0"><i class="bi bi-code"></i></button>';
        element.insertAdjacentHTML('beforeend', button);
    }

    // Inicializa popovers de Bootstrap
    const popoverElements = document.querySelectorAll('[data-bs-toggle="popover"]');
    for (const popover of popoverElements) {
        new bootstrap.Popover(popover); // eslint-disable-line no-new
    }

    // Inicializa tooltips de Bootstrap
    const tooltipElements = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    for (const tooltip of tooltipElements) {
        new bootstrap.Tooltip(tooltip); // eslint-disable-line no-new
    }
})();

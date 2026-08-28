(function () {
    'use strict';

    var modal = null;

    function ensureModal() {
        if (modal) return modal;
        modal = document.createElement('div');
        modal.className = 'gmw-video-modal';
        modal.setAttribute('role', 'dialog');
        modal.setAttribute('aria-modal', 'true');
        modal.innerHTML =
            '<div class="gmw-video-modal-backdrop"></div>' +
            '<div class="gmw-video-modal-content">' +
            '<button type="button" class="gmw-video-modal-close" aria-label="Close">&#215;</button>' +
            '<div class="gmw-video-modal-embed"></div>' +
            '</div>';
        document.body.appendChild(modal);

        modal.querySelector('.gmw-video-modal-close').addEventListener('click', closeModal);
        modal.querySelector('.gmw-video-modal-backdrop').addEventListener('click', closeModal);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeModal();
        });
        return modal;
    }

    function openModal(key, link) {
        var embedTemplate = document.querySelector('template.gmw-video-embed[data-gmw-embed-key="' + key + '"]');
        var embedHtml = embedTemplate ? embedTemplate.innerHTML : '';
        if (!embedHtml) {
            window.open(link.href, '_blank', 'noopener');
            return;
        }
        var ratio = embedTemplate ? embedTemplate.getAttribute('data-gmw-ratio') : '';
        var m = ensureModal();
        var embedBox = m.querySelector('.gmw-video-modal-embed');
        embedBox.innerHTML = embedHtml;
        embedBox.classList.remove('gmw-video-modal-embed-portrait');
        if (ratio === 'portrait') {
            embedBox.classList.add('gmw-video-modal-embed-portrait');
        }
        m.classList.add('gmw-video-modal-open');
        document.body.classList.add('gmw-video-modal-locked');
        var iframe = embedBox.querySelector('iframe');
        if (iframe) iframe.focus();
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('gmw-video-modal-open');
        document.body.classList.remove('gmw-video-modal-locked');
        modal.querySelector('.gmw-video-modal-embed').innerHTML = '';
    }

    document.addEventListener('click', function (e) {
        var link = e.target.closest ? e.target.closest('.gmw-gallery-video-link') : null;
        if (!link) return;
        e.preventDefault();
        var key = link.getAttribute('data-gmw-embed-key');
        openModal(key, link);
    });
})();
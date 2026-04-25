(function () {
    var tabRoots = document.querySelectorAll('[data-code-tabs]');

    tabRoots.forEach(function (root) {
        var buttons = root.querySelectorAll('[data-code-tab]');
        var panels = root.querySelectorAll('[data-code-panel]');

        buttons.forEach(function (button) {
            button.addEventListener('click', function () {
                var tab = button.getAttribute('data-code-tab');

                buttons.forEach(function (candidate) {
                    candidate.classList.toggle('is-active', candidate === button);
                });

                panels.forEach(function (panel) {
                    panel.hidden = panel.getAttribute('data-code-panel') !== tab;
                });
            });
        });
    });

    var modal = document.querySelector('[data-lta-modal]');
    var modalContent = document.querySelector('[data-lta-modal-content]');

    function openModal(text) {
        if (!modal || !modalContent) {
            window.alert(text);
            return;
        }

        modalContent.textContent = text;
        modal.hidden = false;
        document.documentElement.classList.add('lta-modal-open');
        var closeButton = modal.querySelector('[data-lta-modal-close]');
        if (closeButton) {
            closeButton.focus();
        }
    }

    function closeModal() {
        if (!modal) {
            return;
        }

        modal.hidden = true;
        document.documentElement.classList.remove('lta-modal-open');
    }

    document.addEventListener('click', function (event) {
        var day = event.target.closest('[data-lta-day]');
        if (day) {
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.button === 1) {
                return;
            }
            event.preventDefault();
            openModal(day.getAttribute('data-popup') || day.textContent.trim());
            return;
        }

        if (event.target.closest('[data-lta-modal-close]')) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
})();

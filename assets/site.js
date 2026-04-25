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
    var modalTitle = document.getElementById('lta-modal-title');
    var activeTooltip;

    function openModal(text, title) {
        if (!modal || !modalContent) {
            window.alert(text);
            return;
        }

        if (modalTitle && title) {
            modalTitle.textContent = title;
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

    function isFinePointer() {
        return window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;
    }

    function removeTooltip() {
        if (activeTooltip) {
            activeTooltip.remove();
            activeTooltip = null;
        }
    }

    function showTooltip(day) {
        if (!isFinePointer()) {
            return;
        }

        removeTooltip();
        activeTooltip = document.createElement('div');
        activeTooltip.className = 'lta-day-tooltip';
        activeTooltip.textContent = day.getAttribute('data-popup') || day.textContent.trim();
        document.body.appendChild(activeTooltip);

        var rect = day.getBoundingClientRect();
        var tipRect = activeTooltip.getBoundingClientRect();
        var top = window.scrollY + rect.top - tipRect.height - 10;
        var left = window.scrollX + rect.left + (rect.width / 2) - (tipRect.width / 2);

        if (top < window.scrollY + 8) {
            top = window.scrollY + rect.bottom + 10;
        }
        left = Math.max(window.scrollX + 8, Math.min(left, window.scrollX + document.documentElement.clientWidth - tipRect.width - 8));

        activeTooltip.style.top = top + 'px';
        activeTooltip.style.left = left + 'px';
    }

    document.addEventListener('click', function (event) {
        var day = event.target.closest('[data-lta-day]');
        if (day) {
            if (event.metaKey || event.ctrlKey || event.shiftKey || event.button === 1) {
                return;
            }
            if (isFinePointer()) {
                event.preventDefault();
                var target = new URL(window.location.href);
                target.searchParams.set('day', day.getAttribute('data-solar-day'));
                target.searchParams.set('month', day.getAttribute('data-solar-month'));
                target.searchParams.set('year', day.getAttribute('data-solar-year'));
                target.hash = 'calendar';
                window.location.href = target.toString();
                return;
            }
            event.preventDefault();
            openModal(day.getAttribute('data-popup') || day.textContent.trim(), day.getAttribute('data-popup-title') || 'Chi tiết ngày');
            return;
        }

        if (event.target.closest('[data-lta-modal-close]')) {
            event.preventDefault();
            closeModal();
        }
    });

    document.addEventListener('mouseover', function (event) {
        var day = event.target.closest('[data-lta-day]');
        if (day) {
            showTooltip(day);
        }
    });

    document.addEventListener('mouseout', function (event) {
        if (event.target.closest('[data-lta-day]')) {
            removeTooltip();
        }
    });

    document.addEventListener('scroll', removeTooltip, true);

    document.querySelectorAll('[data-nap-am-tool]').forEach(function (root) {
        var napButtons = root.querySelectorAll('[data-nap-filter]');
        var dongButtons = root.querySelectorAll('[data-dong-filter]');
        var days = root.querySelectorAll('[data-lta-day]');
        var napFilter = '';
        var dongFilter = '';

        function applyFilters() {
            days.forEach(function (day) {
                var napMatches = napFilter === '' || day.getAttribute('data-nap-element') === napFilter;
                var dongMatches = dongFilter === '' || day.getAttribute('data-dong-cong') === dongFilter;
                var matches = napMatches && dongMatches;
                day.classList.toggle('is-nap-match', (napFilter !== '' || dongFilter !== '') && matches);
                day.classList.toggle('is-nap-dim', (napFilter !== '' || dongFilter !== '') && !matches);
            });
        }

        napButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                napFilter = button.getAttribute('data-nap-filter') || '';

                napButtons.forEach(function (candidate) {
                    candidate.classList.toggle('is-active', candidate === button);
                });

                applyFilters();
            });
        });

        dongButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                dongFilter = button.getAttribute('data-dong-filter') || '';

                dongButtons.forEach(function (candidate) {
                    candidate.classList.toggle('is-active', candidate === button);
                });

                applyFilters();
            });
        });
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            removeTooltip();
            closeModal();
        }
    });
})();

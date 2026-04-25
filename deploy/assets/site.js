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
        var dayStyle = window.getComputedStyle(day);
        activeTooltip.style.setProperty('--tooltip-bg', dayStyle.getPropertyValue('--day-element-soft').trim() || '#fff');
        activeTooltip.style.setProperty('--tooltip-ink', dayStyle.getPropertyValue('--day-element-strong').trim() || '#17201b');
        activeTooltip.style.setProperty('--tooltip-border', dayStyle.getPropertyValue('--day-element-line').trim() || 'rgba(255, 255, 255, 0.16)');
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
                target.hash = day.closest('#nap-am') ? 'nap-am' : 'calendar';
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

    function initNapTool(root) {
        if (root.getAttribute('data-nap-tool-ready') === '1') {
            return;
        }
        root.setAttribute('data-nap-tool-ready', '1');
        var napButtons = root.querySelectorAll('[data-nap-filter]');
        var dongButtons = root.querySelectorAll('[data-dong-filter]');
        var napSelect = root.querySelector('[data-nap-filter-select]');
        var dongSelect = root.querySelector('[data-dong-filter-select]');
        var days = root.querySelectorAll('[data-lta-day]');
        var napFilter = napSelect ? napSelect.value : '';
        var dongFilter = dongSelect ? dongSelect.value : '';

        function applyFilters() {
            if (napFilter === '') {
                root.removeAttribute('data-active-nap');
            } else {
                root.setAttribute('data-active-nap', napFilter);
            }

            days.forEach(function (day) {
                var napMatches = napFilter === '' || day.getAttribute('data-nap-element') === napFilter;
                var dongMatches = dongFilter === '' || day.getAttribute('data-dong-cong') === dongFilter;
                var matches = napMatches && dongMatches;
                day.classList.toggle('is-nap-match', (napFilter !== '' || dongFilter !== '') && matches);
                day.classList.toggle('is-nap-dim', (napFilter !== '' || dongFilter !== '') && !matches);
            });
        }

        function syncActiveButtons(buttons, attr, value) {
            buttons.forEach(function (candidate) {
                candidate.classList.toggle('is-active', (candidate.getAttribute(attr) || '') === value);
            });
        }

        napButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                napFilter = button.getAttribute('data-nap-filter') || '';
                if (napSelect) {
                    napSelect.value = napFilter;
                }
                syncActiveButtons(napButtons, 'data-nap-filter', napFilter);

                applyFilters();
            });
        });

        dongButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                dongFilter = button.getAttribute('data-dong-filter') || '';
                if (dongSelect) {
                    dongSelect.value = dongFilter;
                }
                syncActiveButtons(dongButtons, 'data-dong-filter', dongFilter);

                applyFilters();
            });
        });

        if (napSelect) {
            napSelect.addEventListener('change', function () {
                napFilter = napSelect.value || '';
                syncActiveButtons(napButtons, 'data-nap-filter', napFilter);
                applyFilters();
            });
        }

        if (dongSelect) {
            dongSelect.addEventListener('change', function () {
                dongFilter = dongSelect.value || '';
                syncActiveButtons(dongButtons, 'data-dong-filter', dongFilter);
                applyFilters();
            });
        }

        applyFilters();
    }

    document.querySelectorAll('[data-nap-am-tool]').forEach(initNapTool);

    var monthCache = {};
    var monthRequest;

    function cacheKey(url) {
        var target = new URL(url, window.location.href);
        target.hash = '';
        target.searchParams.set('view', 'month');
        return target.toString();
    }

    function setMonthLoading(root, loading) {
        root.classList.toggle('is-month-loading', loading);
        root.setAttribute('aria-busy', loading ? 'true' : 'false');
    }

    function refreshPageMeta(doc) {
        var title = doc.querySelector('title');
        var canonical = doc.querySelector('link[rel="canonical"]');
        var description = doc.querySelector('meta[name="description"]');

        if (title) {
            document.title = title.textContent;
        }
        if (canonical && document.querySelector('link[rel="canonical"]')) {
            document.querySelector('link[rel="canonical"]').setAttribute('href', canonical.getAttribute('href') || '');
        }
        if (description && document.querySelector('meta[name="description"]')) {
            document.querySelector('meta[name="description"]').setAttribute('content', description.getAttribute('content') || '');
        }
        if (doc.body && doc.body.getAttribute('data-day-element')) {
            document.body.setAttribute('data-day-element', doc.body.getAttribute('data-day-element') || '');
        }
    }

    function replaceMonthWorkspace(html, url, pushState) {
        var parser = new DOMParser();
        var doc = parser.parseFromString(html, 'text/html');
        var nextWorkspace = doc.querySelector('[data-month-app]');
        var currentWorkspace = document.querySelector('[data-month-app]');
        var nextNav = doc.querySelector('.lta-nav');
        var currentNav = document.querySelector('.lta-nav');

        if (!nextWorkspace || !currentWorkspace) {
            window.location.href = url;
            return;
        }

        currentWorkspace.replaceWith(nextWorkspace);
        initNapTool(nextWorkspace);
        refreshPageMeta(doc);

        if (nextNav && currentNav) {
            currentNav.innerHTML = nextNav.innerHTML;
        }

        if (pushState) {
            window.history.pushState({ ltaMonthUrl: url }, '', url);
        }

        var calendar = document.getElementById('calendar');
        if (calendar) {
            calendar.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function loadMonth(url, pushState) {
        var currentWorkspace = document.querySelector('[data-month-app]');
        var key = cacheKey(url);

        if (!currentWorkspace) {
            window.location.href = url;
            return;
        }

        if (monthCache[key]) {
            replaceMonthWorkspace(monthCache[key], url, pushState);
            return;
        }

        if (monthRequest && typeof monthRequest.abort === 'function') {
            monthRequest.abort();
        }
        monthRequest = new AbortController();
        setMonthLoading(currentWorkspace, true);

        fetch(url, {
            headers: {
                'Accept': 'text/html',
                'X-Lich-Ta-Partial': 'month'
            },
            signal: monthRequest.signal
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Không tải được lịch tháng.');
                }
                return response.text();
            })
            .then(function (html) {
                monthCache[key] = html;
                replaceMonthWorkspace(html, url, pushState);
            })
            .catch(function (error) {
                if (error.name === 'AbortError') {
                    return;
                }
                window.location.href = url;
            })
            .finally(function () {
                var activeWorkspace = document.querySelector('[data-month-app]');
                if (activeWorkspace) {
                    setMonthLoading(activeWorkspace, false);
                }
            });
    }

    document.addEventListener('click', function (event) {
        var monthLink = event.target.closest('[data-month-nav] a');
        if (!monthLink || event.metaKey || event.ctrlKey || event.shiftKey || event.button === 1) {
            return;
        }

        event.preventDefault();
        loadMonth(monthLink.href, true);
    });

    document.addEventListener('submit', function (event) {
        var form = event.target.closest('[data-month-picker]');
        if (!form) {
            return;
        }

        event.preventDefault();
        var formData = new FormData(form);
        var target = new URL(form.action || window.location.href, window.location.href);
        formData.forEach(function (value, key) {
            target.searchParams.set(key, value);
        });
        target.searchParams.set('view', 'month');
        loadMonth(target.toString(), true);
    });

    window.addEventListener('popstate', function () {
        if (document.querySelector('[data-month-app]')) {
            loadMonth(window.location.href, false);
        }
    });

    document.querySelectorAll('[data-date-form]').forEach(function (form) {
        var dayInput = form.querySelector('input[name="day"]');
        var monthInput = form.querySelector('input[name="month"]');
        var yearInput = form.querySelector('input[name="year"]');
        var leapLabel = form.querySelector('.lta-lunar-leap');
        var leapInput = form.querySelector('input[name="lunar_leap"]');

        function inputMode() {
            var checked = form.querySelector('input[name="date_type"]:checked');
            return checked ? checked.value : 'solar';
        }

        function solarDaysInMonth(month, year) {
            return new Date(year, month, 0).getDate();
        }

        function syncDateLimits() {
            if (!dayInput || !monthInput || !yearInput) {
                return;
            }

            var mode = inputMode();
            var month = Math.max(1, Math.min(12, parseInt(monthInput.value, 10) || 1));
            var year = Math.max(1800, Math.min(2199, parseInt(yearInput.value, 10) || 1800));
            var maxDay = mode === 'lunar' ? 30 : solarDaysInMonth(month, year);
            dayInput.max = String(maxDay);
            if ((parseInt(dayInput.value, 10) || 1) > maxDay) {
                dayInput.value = String(maxDay);
            }

            if (leapLabel) {
                leapLabel.classList.toggle('is-hidden', mode !== 'lunar');
            }
            if (leapInput && mode !== 'lunar') {
                leapInput.checked = false;
            }
        }

        form.querySelectorAll('input[name="date_type"]').forEach(function (radio) {
            radio.addEventListener('change', syncDateLimits);
        });
        [dayInput, monthInput, yearInput].forEach(function (input) {
            if (input) {
                input.addEventListener('input', syncDateLimits);
            }
        });
        syncDateLimits();
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            removeTooltip();
            closeModal();
        }
    });

    if (document.body && document.body.classList.contains('lta-embed-body') && window.parent !== window) {
        var lastEmbedHeight = 0;

        function postEmbedHeight() {
            var height = Math.ceil(Math.max(
                document.documentElement.scrollHeight,
                document.body.scrollHeight
            ));

            if (Math.abs(height - lastEmbedHeight) < 2) {
                return;
            }

            lastEmbedHeight = height;
            window.parent.postMessage({
                type: 'lta:embed-height',
                height: height
            }, '*');
        }

        window.addEventListener('load', postEmbedHeight);
        window.addEventListener('resize', postEmbedHeight);

        if ('ResizeObserver' in window) {
            new ResizeObserver(postEmbedHeight).observe(document.body);
        }

        postEmbedHeight();
    }
})();

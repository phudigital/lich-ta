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
})();

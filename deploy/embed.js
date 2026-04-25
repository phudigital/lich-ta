(function () {
    var script = document.currentScript || (function () {
        var scripts = document.getElementsByTagName('script');
        return scripts[scripts.length - 1];
    })();

    if (!script) {
        return;
    }

    var targetId = script.getAttribute('data-target');
    var mount = targetId ? document.getElementById(targetId) : null;
    if (!mount) {
        mount = document.createElement('div');
        script.parentNode.insertBefore(mount, script);
    }

    var src = new URL(script.src);
    src.pathname = src.pathname.replace(/embed\.js$/, 'embed.php');

    var params = ['day', 'month', 'year'];
    params.forEach(function (name) {
        var value = script.getAttribute('data-' + name);
        if (value) {
            src.searchParams.set(name, value);
        }
    });

    var iframe = document.createElement('iframe');
    iframe.src = src.toString();
    iframe.title = script.getAttribute('data-title') || 'Lịch âm Việt Nam';
    iframe.loading = 'lazy';
    iframe.style.width = script.getAttribute('data-width') || '100%';
    iframe.style.maxWidth = script.getAttribute('data-max-width') || '760px';
    iframe.style.height = script.getAttribute('data-height') || '620px';
    iframe.style.border = '0';
    iframe.style.borderRadius = script.getAttribute('data-radius') || '18px';
    iframe.style.overflow = 'hidden';
    iframe.style.display = 'block';

    if (script.getAttribute('data-auto-height') !== 'false') {
        window.addEventListener('message', function (event) {
            if (event.source !== iframe.contentWindow) {
                return;
            }

            if (!event.data || event.data.type !== 'lta:embed-height') {
                return;
            }

            var height = parseInt(event.data.height, 10);
            if (height > 0) {
                iframe.style.height = Math.max(height, 360) + 'px';
            }
        });
    }

    mount.innerHTML = '';
    mount.appendChild(iframe);
})();

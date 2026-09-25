(function () {
    var input = document.getElementById('ep-search');
    var out   = document.getElementById('ep-results');
    if (!input || !out) return;

    var endpoint = '/wp-json/wp/v2/episodes';
    var searchUrl = '/wp-json/oekodorf/v1/search';
    var episodes = null;
    var timer, controller;

    function decode(html) {
        var t = document.createElement('textarea');
        t.innerHTML = html;
        return t.value;
    }

    function load() {
        var all = [];

        function page(p) {
            var url = endpoint
                + '?per_page=100'
                + '&page=' + p
                + '&_fields=id,link,title'
                + '&orderby=date&order=desc';

            return fetch(url).then(function (r) {
                if (!r.ok) return null;
                var hdr = r.headers.get('X-WP-TotalPages');
                var total = parseInt(hdr || '1', 10);
                return r.json().then(function (eps) {
                    if (Array.isArray(eps)) {
                        all = all.concat(eps);
                    }
                    return p < total ? page(p + 1) : null;
                });
            });
        }

        return page(1).then(function () {
            episodes = all.map(function (e) {
                var title = decode(e.title.rendered);
                return {
                    id: e.id,
                    link: e.link,
                    title: title,
                    needle: title.toLowerCase()
                };
            });
        });
    }

    function mark(text, q) {
        var frag = document.createDocumentFragment();
        var low  = text.toLowerCase();
        var from = 0;

        while (true) {
            var i = low.indexOf(q, from);
            if (i === -1) break;
            if (i > from) {
                var before = text.slice(from, i);
                frag.appendChild(document.createTextNode(before));
            }
            var m = document.createElement('mark');
            m.textContent = text.slice(i, i + q.length);
            frag.appendChild(m);
            from = i + q.length;
        }

        if (from < text.length) {
            var rest = text.slice(from);
            frag.appendChild(document.createTextNode(rest));
        }
        return frag;
    }

    function render(list, emptyText, q) {
        if (!list.length) {
            out.innerHTML = '';
            if (emptyText) {
                var li = document.createElement('li');
                li.className = 'no-hit';
                li.textContent = emptyText;
                out.appendChild(li);
            }
            return;
        }

        var frag = document.createDocumentFragment();

        list.slice(0, 10).forEach(function (e) {
            var li = document.createElement('li');
            var a  = document.createElement('a');
            a.href = e.link;
            a.appendChild(mark(e.title, q));
            li.appendChild(a);

            if (e.excerpt) {
                var p = document.createElement('p');
                p.className = 'ep-excerpt';
                p.appendChild(mark(e.excerpt, q));
                li.appendChild(p);
            }

            frag.appendChild(li);
        });

        out.innerHTML = '';
        out.appendChild(frag);
    }

    function serverSearch(q) {
        if (controller) controller.abort();
        controller = new AbortController();

        var url = searchUrl + '?q=' + encodeURIComponent(q);

        return fetch(url, { signal: controller.signal })
            .then(function (r) { return r.json(); })
            .then(function (eps) {
                if (!Array.isArray(eps)) return [];
                return eps;
            });
    }

    function search() {
        var q = input.value.trim().toLowerCase();
        if (q.length < 3) {
            out.innerHTML = '';
            return;
        }

        function afterLocal() {
            var local = episodes.filter(function (e) {
                return e.needle.indexOf(q) !== -1;
            });

            render(local, local.length ? '' : 'Suche läuft …', q);

            serverSearch(q).then(function (remote) {
                var seen = {};
                var list = [];
                local.concat(remote).forEach(function (e) {
                    if (seen[e.id]) return;
                    seen[e.id] = true;
                    list.push(e);
                });
                render(list, 'Nix gefunden.', q);
            }).catch(function (err) {
                if (err.name === 'AbortError') return;
                if (!local.length) render([], 'Nix gefunden.', q);
            });
        }

        if (episodes) {
            afterLocal();
            return;
        }

        out.innerHTML = '<li class="no-hit">Lade Folgen …</li>';
        load().then(afterLocal).catch(function () {
            var msg = 'Suche grad nicht verfügbar.';
            out.innerHTML = '<li class="no-hit">' + msg + '</li>';
        });
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(search, 150);
    });
})();
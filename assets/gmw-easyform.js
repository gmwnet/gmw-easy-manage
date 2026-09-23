/* GMW EasyForms embed — fetch-POST submit; host does all validation. */
(function () {
    'use strict';

    document.querySelectorAll('.gmw-easyform').forEach(function (root) {
        var form = root.querySelector('form.gmw-easyform-form');
        if (!form) {
            return;
        }

        var thankYou = root.getAttribute('data-thank-you') || 'Thank you! Your submission was received.';

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var btn = form.querySelector('button[type="submit"]');
            var originalLabel = btn ? btn.textContent : '';
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<span class="gmw-easyform-spinner" aria-hidden="true"></span> Submitting…';
            }

            var existingError = root.querySelector('.gmw-easyform-error');
            if (existingError) {
                existingError.remove();
            }

            // Honeypot: inject random-named empty fields at submit time. The host
            // rejects any posted field not in the form definition (when non-empty),
            // so bots that autofill all fields trip the trap.
            for (var i = 0; i < 2; i++) {
                var hp = document.createElement('input');
                hp.type = 'hidden';
                hp.name = 'hpn_' + Math.random().toString(36).slice(2, 12);
                hp.value = '';
                form.appendChild(hp);
            }

            fetch(form.getAttribute('action'), {
                method: 'POST',
                credentials: 'omit',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(form)
            }).then(function (resp) {
                return resp.json().then(function (data) {
                    return { ok: resp.ok, data: data };
                });
            }).then(function (result) {
                if (result.ok && result.data && result.data.ok) {
                    var el = document.createElement('div');
                    el.className = 'gmw-easyform-thankyou';
                    el.innerHTML = thankYou;
                    root.innerHTML = '';
                    root.appendChild(el);
                } else {
                    var err = document.createElement('div');
                    err.className = 'gmw-easyform-error';
                    err.textContent = (result.data && result.data.error)
                        ? result.data.error
                        : 'Submission failed. Please try again.';
                    form.insertBefore(err, form.firstChild);
                    err.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }).catch(function () {
                var err = document.createElement('div');
                err.className = 'gmw-easyform-error';
                err.textContent = 'Submission failed. Please try again.';
                form.insertBefore(err, form.firstChild);
                err.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }).finally(function () {
                if (btn) {
                    btn.disabled = false;
                    btn.innerHTML = originalLabel;
                }
            });
        });
    });
})();
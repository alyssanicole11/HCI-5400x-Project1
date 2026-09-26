/*
 * JoyWatch - small bit of JavaScript (the site works without it).
 *
 * On the Title Details form, show only the questions that make sense:
 *   Not yet / nothing chosen -> "Interested?"
 *   Watching                 -> rating
 *   Watched                  -> rating + "Watch again?"
 * Hidden questions keep their values, so nothing is erased (spec rule).
 *
 * Any element with data-show-when="watching,watched" is shown only when the
 * selected watch_status is in that list ("" = nothing selected yet).
 */
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('decision-form');
    if (!form) return;

    function update() {
        var checked = form.querySelector('input[name="watch_status"]:checked');
        var status = checked ? checked.value : '';
        form.querySelectorAll('[data-show-when]').forEach(function (el) {
            var allowed = el.getAttribute('data-show-when').split(',');
            el.hidden = allowed.indexOf(status) === -1;
        });
    }

    form.addEventListener('change', update);
    update();
});

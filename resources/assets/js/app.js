
/**
 * Enable and set up clipboard.
 */

let clipboard = new Clipboard('.clpbrd');

clipboard.on('success', function(e) {
    $(e.trigger).attr('data-original-title', 'Copied!').tooltip('show');
    e.clearSelection();
});

clipboard.on('error', function(e) {
    $(e.trigger).attr('data-original-title', 'Press Ctrl+C to copy').tooltip('show');
});

$(function () {
    $('.clpbrd').tooltip({
        placement: 'bottom',
        trigger: 'hover'
    }).on('hidden.bs.tooltip', function(e) {
        $(e.target).attr('data-original-title', '');
    })
});

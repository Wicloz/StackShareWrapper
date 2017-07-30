const className = '.preview-pdf';

$(function () {
    setIFrameSize();
    $(window).resize(function () {
        setIFrameSize();
    });
});

function setIFrameSize() {
    $(className).height(window.innerHeight - 50 - 1 - 20 - 20);
}

$(document).ready(function () {
    /**
     * Link Active
     */
    var link = $(document).find(".active");
    link.closest('ul').closest('li').children().addClass('active');

    // Footer List
    $('.footer-top .list-title').on('click', function () {
        $(this).parent().toggleClass('open-list');
    });

    // Tooltip and popover demos
    document.querySelectorAll('[data-bs-toggle="tooltip"], [rel=tooltip]')
        .forEach(tooltip => {
            new bootstrap.Tooltip(tooltip)
        })

    document.querySelectorAll('[data-bs-toggle="popover"]')
        .forEach(popover => {
            new bootstrap.Popover(popover)
        })
});
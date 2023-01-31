$(document).ready(function () {
    /**
     * Link Active
     */
    var link = $(document).find(".active");
    link.closest('ul').closest('li').children().addClass('active');
});
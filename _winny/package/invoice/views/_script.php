<script>
$(document).ready(function(){

    var clipboard = new ClipboardJS('.copy');
                
    // Tooltip
    $('.copy').tooltip({
        trigger: 'click',
        placement: 'bottom'
    });
    function setTooltip(el, message) {
    $(el).tooltip('hide')
        .attr('data-original-title', message)
        .tooltip('show');
    }
    function hideTooltip(el) {
    setTimeout(function() {
        $(el).tooltip('hide');
    }, 1000);
    }

    // Clipboard
    clipboard.on('success', function(e) {
        setTooltip(e.trigger, 'Copied!');
        hideTooltip(e.trigger);
    });

    clipboard.on('error', function(e) {
        setTooltip(e.trigger, 'Failed!');
        hideTooltip(e.trigger);
    });
})
</script>
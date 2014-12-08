jQuery( document ).ready( function( $ ) {
    /* open all external links in a new window/tab */
    $("a[href^=http]").each(function(){
        if(this.href.indexOf(location.hostname) === -1) {
            $(this).attr({
                target: "_blank"
            });
        }
    });
    
    // make the body 100% of the viewport width
    $('body').width($( document ).width());
    $(window).resize(function() {
        $('body').width($( document ).width());
    });
    
});
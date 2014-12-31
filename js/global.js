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
    
    /* equal height rows hack, because flexbox support isn't here yet 
     * http://codepen.io/micahgodbolt/pen/FgqLc */
    equalheight = function(container){

    var currentTallest = 0,
         currentRowStart = 0,
         rowDivs = new Array(),
         $el,
         topPosition = 0;
     $(container).each(function() {

       $el = $(this);
       $($el).height('auto')
       topPostion = $el.position().top;

       if (currentRowStart != topPostion) {
         for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
           rowDivs[currentDiv].height(currentTallest);
         }
         rowDivs.length = 0; // empty the array
         currentRowStart = topPostion;
         currentTallest = $el.height();
         rowDivs.push($el);
       } else {
         rowDivs.push($el);
         currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
      }
       for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
         rowDivs[currentDiv].height(currentTallest);
       }
     });
    }

    $(window).load(function() {
      equalheight('.section');
    });


    $(window).resize(function(){
      equalheight('.section');
    });
    
});

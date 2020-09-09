jQuery(document).ready(function($){
    (function(){
        $('.chbecm-booking-engine').css("min-height", "920px");        
    })();
  
    var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
    var eventer = window[eventMethod];
    var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
    
    // Listen to message from child window
    eventer(messageEvent, function (e) {
        if (e.data == "back-to-top") {
            if ($(".chbecm-booking-engine").parent() != undefined) {
                $("html,body").animate({
                    scrollTop: $(".chbecm-booking-engine").parent().offset().top
                }, 500);
            }
        }
    }, false);
    
    // Booking Engine Iframe Resizer
    function initializeIframeResizer() {
        $(".chbecm-booking-engine").iFrameResize({ log: false, checkOrigin: false });
    };
});
// Basic usage: $(element).flicker();
// By default it will animate the opacity of the element to make it flicker from visible to invisible
// The options are (the values shown are the defaults)
//         { 
//             action: "start",        // Can be "start" or "stop"
//             wait: 650,              // Time between animations
//             cssProperty: "opacity"  // Which css style animate. Will be used in jQuerys .css().
//             cssValue: "0"           // Max value to use
//          }
//
// Another examples: 
//    $el.flicker({ cssProperty: "top", cssValue: "20px" });
//    $el.flicker({ cssProperty: "backgroundColor", cssValue: "#BADA55" }); //you need to be able to animate color for this one

$.fn.flicker = function(opts) {
    var default_ops = { action: "start", wait: 100, cssProperty: "opacity", cssValue: "0.8" };
    var currentColor;

    if(typeof opts !== "string") {
        opts = $.extend(default_ops, opts);
    } else {
        default_ops.action = opts;
        opts = default_ops;
    }

    var _flicker = function($el, callback) {
        var toAnimate = {};
        toAnimate[opts.cssProperty] = opts.cssValue;
        $el.animate(toAnimate, opts.wait, function() {
            toAnimate[opts.cssProperty] = currentColor;
            $el.animate(toAnimate, opts.wait);
        });
    };

    return this.each(function() {
        var $el = $(this);
        if(opts.action !== "stop") {
            currentColor = $el.css(opts.cssProperty);
            _flicker($el);
            if(opts.action !== "once") {
                var intervalId = setInterval(function() { _flicker($el); }, opts.wait * 2);
                $el.data("_interval", intervalId);
            }
        } else {
            var finish = $el.finish || $el.stop;
            clearInterval(finish.call($el, true).data("_interval"));
        }
    });
};
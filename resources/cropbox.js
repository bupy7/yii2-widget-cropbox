(function($, Cropbox) {
    $.fn.cropbox = function(options) {
        this.each(function() {
            console.log(this);
        });
//        if (typeof options === 'string') {
//            var method = options.replace(/^[_]*/, '');
//            if (Cropbox.prototype[method]) {
//                //return Cropbox.apply(this, Array.prototype.slice.call(arguments, 1));
//            }
//        } else if (typeof options === 'object' || ! options) {
//            return .init.apply(this, arguments);
//        } else {
//            $.error('Method "' +  options + '" not exists.');
//        }
    };
})(jQuery, Cropbox);

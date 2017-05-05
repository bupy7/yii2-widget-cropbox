(function($, Cropbox) {
    $.fn.cropbox = function(options) {
        // call public method
        if (typeof options === 'string') {
            var method = options.replace(/^[_]*/, '');
            if (Cropbox.prototype[method]) {
                var cb = $(this).data('cropbox');
                return cb[method].apply(cb, Array.prototype.slice.call(arguments, 1));
            }
        // create new instance of Cropbox class
        } else if (typeof options === 'object' || ! options) {
            return this.each(function() {
                $(this).data('cropbox', new Cropbox(this, options));
            });
        // throw an error
        } else {
            $.error('Method Cropbox::"' +  options + '()" not exists.');
        }
    };
})(jQuery, Cropbox);

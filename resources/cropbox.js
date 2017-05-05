(function($, Cropbox) {
    /**
     * @param {Object} s
     */
    function attachEvents(s) {
        var $th = $(this);
        // scaling
        $(s.btnScaleIn).on('click', function() {
            $th.cropbox('scale', 1.05);
        });
        $(s.btnScaleOut).on('click', function() {
            $th.cropbox('scale', 0.95);
        });
        $($th.cropbox('getMembrane')).on('wheel', function(event) {
            if (event.originalEvent.deltaY < 0) {
                $th.cropbox('scale', 1.01);
            } else {
                $th.cropbox('scale', 0.99);
            }
            if (event.preventDefault) {
                event.preventDefault();
            }
        });
        // image loading from a file
        $(s.fileInput).on('change', function() {
            var fileReader = new FileReader();
            fileReader.readAsDataURL(this.files[0]);
            fileReader.onload = function(event) {
                $th.cropbox('load', event.target.result);
            };
        });
        // reset
        $(s.btnReset).on('click', function() {
            $th.cropbox('reset');
        });
        // crop
        $(s.btnCrop).on('click', function() {
            $th.cropbox('crop');
        });
        // the cropped event
        $th.on('cb:cropped', function(event) {
            var $img = $('<img />', {
                src: event.detail.data.image,
                class: 'img-thumbnail'
            });
            $(s.croppedContainer).append($img);
            $(s.croppedDataInput).val(JSON.stringify($th.cropbox('getData')));
        });
        // the reset event
        function resetHandler() {
            $(s.croppedContainer).html('');
            $(s.croppedDataInput).val('');
        };
        $th.on('cb:reset', resetHandler);
        // the loaded event
        $th.on('cb:loaded', resetHandler);
        // the disabled/enabled event
        function disabledHandler() {
            $(s.btnScaleIn).attr('disabled', 'disabled');
            $(s.btnScaleOut).attr('disabled', 'disabled');
            $(s.btnCrop).attr('disabled', 'disabled');
        };
        disabledHandler();
        $th.on('cb:disabledCtrls', disabledHandler);
        $th.on('cb:enabledCtrls', function() {
            $(s.btnScaleIn).removeAttr('disabled');
            $(s.btnScaleOut).removeAttr('disabled');
            $(s.btnCrop).removeAttr('disabled');
        });
    };

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
                var selectors = options.selectors;
                delete options.selectors;
                $(this).data('cropbox', new Cropbox(this, options));
                attachEvents.call(this, selectors);
            });
        // throw an error
        } else {
            $.error('Method Cropbox::"' +  options + '()" not exists.');
        }
    };
})(jQuery, Cropbox);

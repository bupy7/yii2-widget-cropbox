/**
 * @author Vasily Belosludcev <bupy765@gmail.com>
 * @param {jQuery} $
 * @param {Cropbox} Cropbox
 * @since 5.0.0
 */
(function($, Cropbox) {
    'use strict';
    /**
     * @param {object|string} options
     */
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
            var selectors = options.selectors,
                messages = options.messages || [];
            delete options.selectors;
            delete options.messages;
            return this.each(function() {
                $(this).data('cropbox', new Cropbox(this, options));
                attachEvents.call(this, selectors, messages);
            });
        // throw an error
        } else {
            $.error('Method Cropbox::"' +  options + '()" not exists.');
        }
    };
    
    /**
     * @param {Object} s
     * @param {Array} m
     */
    function attachEvents(s, m) {
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
        // the ready event
        $th.on('cb:ready', resetHandler);
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
        // messages
        function showMessage(reset) {
            var index = 0;
            if (typeof $th.data('mi-cropbox') !== 'undefined' && !(reset || false)) {
                index = $th.data('mi-cropbox');
            }
            if (typeof m[index] !== 'undefined') {
                $(s.messageContainer).html(m[index]).show();
            } else {
                $(s.messageContainer).hide();
            }
            $th.data('mi-cropbox', ++index);
        }
        $th.on('cb:cropped', function() {
            showMessage();
        });
        $(s.fileInput).on('change', function() {
            showMessage(true);
        });
        $th.on('cb:reset', function() {
            $(s.messageContainer).hide();
        });
    }
})(jQuery, Cropbox);

/**
 * Cropbox module of jQuery. A lightweight and simple plugin to crop your image. 
 * 
 * @author Belosludcev Vasilij https://github.com/bupy7
 * @since 4.0.0
 */
"use strict";
(function ($) {
    var $th = null,
        $cropInfoInput = null,
        $frame = null,
        $image = null,
        $workarea = null,
        $membrane = null,
        $btnReset = null,
        $btnCrop = null,
        variants = [
            {
                width: 200,
                height: 200
            }
        ],
        indexVariant = 0,
        frameState = {},
        imageState = {},
        sourceImage = new Image,
        ratio = 1,
        methods = {
            init: function(options) {
                $th = $(this); 
                variants = options.variants || variants;
                $cropInfoInput = $th.find(options.cropInfoSelector);   
                $btnReset = $th.find(options.btnResetSelector);
                $btnCrop = $th.find(options.btnCropSelector);
                initSelectImage();
            }
        },
        initComponents = function() {
            $image = $th.find('.image-cropbox');
            $frame = $th.find('.frame-cropbox');
            $workarea = $th.find('.workarea-cropbox');
            $membrane = $th.find('.membrane-cropbox');
        },
        initFrameEvents = function() {
            $frame.on('mousedown', frameMouseDown);
            $frame.on('mousemove', frameMouseMove);
            $frame.on('mouseup', frameMouseUp);
        },
        initImageEvents = function() {
            $membrane.on('mousedown', imageMouseDown);
            $membrane.on('mousemove', imageMouseMove);
            $membrane.on('mouseup', imageMouseUp);
            $membrane.on('mousewheel', imageMouseWheel);
        },
        initWorkareaEvents = function() {
            $(window).on('resize', resizeWorkarea);
        },
        initFrame = function() {
            var left = $workarea.width() / 2 - variants[indexVariant].width / 2,
                top = $workarea.height() / 2 - variants[indexVariant].height / 2;
            $frame.css({
                width: variants[indexVariant].width,
                height: variants[indexVariant].height,
                left: left,
                top: top,
                backgroundImage: 'url("' + sourceImage.src + '")',
                backgroundSize: $image.width() + 'px ' + $image.height() + 'px'
            });
            refrashFrame(left, top);
        },
        refrashFrame = function(left, top) {
            var imgLeft = $image.position().left,
                imgTop = $image.position().top,
                x = imgLeft - left,
                y = imgTop - top;
            if (x > 0) {
                x = 0;
                left = imgLeft;
            } else if ($image.width() + imgLeft < left + $frame.width()) {
                x = $frame.width() - $image.width();
                left = imgLeft + $image.width() - $frame.width();
            } 
            if (y > 0) {
                y = 0;
                top = imgTop;
            } else if ($image.height() + imgTop < top + $frame.height()) {
                y = $frame.height() - $image.height();
                top = imgTop + $image.height() - $frame.height();
            }
            $frame.css({
                left: left,
                top: top,
                backgroundPosition: x + 'px ' + y + 'px'
            });
        },
        frameMouseDown = function(event) {
            event.stopImmediatePropagation();    

            frameState.dragable = true;
            frameState.mouseX = event.clientX;
            frameState.mouseY = event.clientY;
        },
        frameMouseMove = function(event) {
            event.stopImmediatePropagation();

            if (frameState.dragable) {
                var xOld = $frame.css('left'),
                    yOld = $frame.css('top'),
                    left = event.clientX - frameState.mouseX + parseInt(xOld),
                    top = event.clientY - frameState.mouseY + parseInt(yOld);

                frameState.mouseX = event.clientX;
                frameState.mouseY = event.clientY;
                refrashFrame(left, top);
            }
        },
        frameMouseUp = function(event) {
            event.stopImmediatePropagation();    

            frameState.dragable = false;
        },
        imageMouseDown = function(event) {
            event.stopImmediatePropagation();    

            imageState.dragable = true;
            imageState.mouseX = event.clientX;
            imageState.mouseY = event.clientY;
        },
        imageMouseMove = function(event) {
            event.stopImmediatePropagation();

            if (imageState.dragable) {
                var xOld = $image.css('left'),
                    yOld = $image.css('top'),
                    left = event.clientX - imageState.mouseX + parseInt(xOld),
                    top = event.clientY - imageState.mouseY + parseInt(yOld);

                imageState.mouseX = event.clientX;
                imageState.mouseY = event.clientY;
                refrashImage(left, top);

                frameState.mouseX = event.clientX;
                frameState.mouseY = event.clientY;
                refrashFrame(parseInt($frame.css('left')), parseInt($frame.css('top')));
            }
        },
        imageMouseUp = function(event) {
            event.stopImmediatePropagation();    

            imageState.dragable = false;
        },
        refrashImage = function(left, top) {
            $image.css({left: left, top: top});
        },
        initSelectImage = function() {
            $th.find('input[type="file"]').on('change', function() {
                initComponents();
                var fileReader = new FileReader();
                fileReader.readAsDataURL(this.files[0]);
                $(fileReader).one('load', loadImage);
            });
        },
        loadImage = function(event) {
            $image.one('load', function() {
                initFrameEvents();
                initImageEvents();
                initWorkareaEvents();
                sourceImage.src = this.src;
                $(sourceImage).one('load', initFrame);
            });
            $image.attr('src', event.target.result);
        },
        resizeWorkarea = function() { 
        },
        imageMouseWheel = function(event) {
            if (event.deltaY > 0) {
                zoomOut();
            } else {
                zoomIn();
            }
            event.preventDefault ? event.preventDefault() : (event.returnValue = false);
        },
        zoomIn = function () {
            ratio *= 1.01;
            var width = sourceImage.width * ratio,
                height = sourceImage.height * ratio;
            $image.css({width: width, height: height});
            $frame.css({backgroundSize: width + 'px ' + height + 'px'});
            refrashFrame(parseInt($frame.css('left')), parseInt($frame.css('top')));
        },
        zoomOut = function () {
            ratio *= 0.99;
            var width = sourceImage.width * ratio,
                height = sourceImage.height * ratio;
            $image.css({width: width, height: height});
            $frame.css({backgroundSize: width + 'px ' + height + 'px'});
            refrashFrame(parseInt($frame.css('left')), parseInt($frame.css('top')));
        };
        
    $.fn.cropbox = function(options) {
        if (methods[options]) {
			return methods[options].apply(this, Array.prototype.slice.call(arguments, 1));
		} else if (typeof options === 'object' || ! options) {
			return methods.init.apply(this, arguments);
		} else {
			$.error('Method "' +  options + '" not exists.');
		}
    };  
})(jQuery);

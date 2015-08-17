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
        methods = {
            init: function(options) {
                variants = options.variants || variants;
                $cropInfoInput = $(options.selectorCropInfo);
                $th = $(this);
                
                $image = $th.find('.image-cropbox');
                $frame = $th.find('.frame-cropbox');
                $workarea = $th.find('.workarea-cropbox');
                $membrane = $th.find('.membrane-cropbox');
                
                methods.initBrowseImage();
                $(window).on('resize', function() {
                    methods.resizeWorkarea();
                });
            },
            initFrameEvents: function() {
                $frame.on('mousedown', methods.frameMouseDown);
                $frame.on('mousemove', methods.frameMouseMove);
                $frame.on('mouseup', methods.frameMouseUp);
            },
            initImageEvents: function() {
                $membrane.on('mousedown', methods.imageMouseDown);
                $membrane.on('mousemove', methods.imageMouseMove);
                $membrane.on('mouseup', methods.imageMouseUp);
            },
            initFrame: function() {
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
                methods.refrashFrame(left, top);
            },
            refrashFrame: function(left, top) {
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
            frameMouseDown: function(event) {
                event.stopImmediatePropagation();    
  
                frameState.dragable = true;
                frameState.mouseX = event.clientX;
                frameState.mouseY = event.clientY;
            },
            frameMouseMove: function(event) {
                event.stopImmediatePropagation();
                
                if (frameState.dragable) {
                    var xOld = $frame.css('left'),
                        yOld = $frame.css('top'),
                        left = event.clientX - frameState.mouseX + parseInt(xOld),
                        top = event.clientY - frameState.mouseY + parseInt(yOld);
                    
                    frameState.mouseX = event.clientX;
                    frameState.mouseY = event.clientY;
                    methods.refrashFrame(left, top);
                }
            },
            frameMouseUp: function(event) {
                event.stopImmediatePropagation();    
                
                frameState.dragable = false;
            },
            imageMouseDown: function(event) {
                event.stopImmediatePropagation();    

                imageState.dragable = true;
                imageState.mouseX = event.clientX;
                imageState.mouseY = event.clientY;
            },
            imageMouseMove: function(event) {
                event.stopImmediatePropagation();
                
                if (imageState.dragable) {
                    var xOld = $image.css('left'),
                        yOld = $image.css('top'),
                        left = event.clientX - imageState.mouseX + parseInt(xOld),
                        top = event.clientY - imageState.mouseY + parseInt(yOld);
                    
                    imageState.mouseX = event.clientX;
                    imageState.mouseY = event.clientY;
                    methods.refrashImage(left, top);
                    
                    frameState.mouseX = event.clientX;
                    frameState.mouseY = event.clientY;
                    methods.refrashFrame(parseInt($frame.css('left')), parseInt($frame.css('top')));
                }
            },
            imageMouseUp: function(event) {
                event.stopImmediatePropagation();    
                
                imageState.dragable = false;
            },
            refrashImage: function(left, top) {
                $image.css({left: left, top: top});
            },
            initBrowseImage: function() {
                $th.find('input[type="file"]').on('change', function() {
                    var fileReader = new FileReader();
                    fileReader.readAsDataURL(this.files[0]);
                    fileReader.onload = function(event) {
                        methods.setImage(event.target.result);
                    };
                });
            },
            setImage: function(data) {
                $image.one('load', function() {
                    methods.initFrameEvents();
                    methods.initImageEvents();
                    sourceImage.src = data;
                    sourceImage.onload = function() {
                        methods.initFrame();
                    };
                });
                $image.attr('src', data); 
            },
            resizeWorkarea: function() {
            }
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

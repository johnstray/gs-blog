/*
 * singleuploadimage - jQuery plugin for upload a image, simple and elegant.
 * Copyright (c) 2014 Langwan Luo
 * Licensed under the MIT license
 * http://www.opensource.org/licenses/mit-license.php
 * Project home:https://github.com/langwan/jquery.singleuploadimage.js
 * version: 1.0.3
 */

(function ( $ ) {

    $.fn.singleupload = function(options) {

        var $this = this;
        var inputfile = null;
        
        var settings = $.extend({
            action: '#',
            onSuccess: function(url, data) {},
            onError: function(code){},
            onProgress: function(loaded, total) {},
            name: 'img',
            progressBar: '#'
        }, options);

        $('#' + settings.inputId).bind('change', function() {
            $(settings.progressBar).css('display', 'block');
            var fd = new FormData();
            fd.append($('#' + settings.inputId).attr('name'), $('#' + settings.inputId).get(0).files[0]);
            fd.append('sessionHash', settings.sessionHash);
            fd.append('path', settings.filePath);

            var xhr = new XMLHttpRequest();
            xhr.addEventListener("load", function(ev) {
                var res = eval("(" + ev.target.responseText + ")");

                if(res.code != 0) {
                    settings.onError(res.code);
                    return;
                }
                settings.onSuccess(res.url, res.data);

            },
            false);
            xhr.upload.addEventListener("progress", function(ev) {
                settings.onProgress(ev);
            }, false);
            
            xhr.open("POST", settings.action, true);
            xhr.send(fd);  

        });  
       
    	return this;
    }

 
}( jQuery ));

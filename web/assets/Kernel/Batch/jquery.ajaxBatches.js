
(function($) {

    var ajaxQueue = $({}),
        dfd = $.Deferred(),
        promise = dfd.promise(),
        context = null,
        step = 0,
        total = 0,
        addQueue = function(ajaxOptions) {
            var jqXHR;

            total++;

            ajaxQueue.queue(doRequest);

            function doRequest(next) {
                step++;

                if (context) {
                    ajaxOptions.data.context = $.extend({}, ajaxOptions.data.context || {}, context);
                }

                jqXHR = $.ajax( ajaxOptions )
                    .done(function(data, textStatus, jqXHR) {
                        var progress = step == total ? 100 : parseInt((step / total) * 100, 10);
                        context = data.context || context;
                        dfd.notify(data, progress);
                        if (step == total) {
                            dfd.resolve(data, textStatus, jqXHR);
                        }
                    })
                    .fail(function(XMLHttpRequest, textStatus, errorThrown) {
                        dfd.reject(XMLHttpRequest, textStatus, errorThrown);
                    })
                    .then(next);
            }

            return promise;
        },
        resolveAjaxOptions = function(options) {
            return $.extend({}, {
                dataType: 'json',
                type: 'POST',
                cache: false,
                data: {}
            }, typeof options == 'string' ? {url: options} : options);
        };

    $.ajaxBatch = function() {
        for (var i=0; i<arguments.length; i++) {
            if ($.isArray(arguments[i])) {
                $.ajaxBatch.apply(null, arguments[i]);
            } else {
                addQueue(resolveAjaxOptions(arguments[i]));
            }
        }
        return promise;
    };

})(jQuery);
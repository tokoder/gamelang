(function ($, window, document, undefined) {
    "use strict";

    // Prepare globals.
    var gamelang = window.gamelang = window.gamelang || {},
        reportsURL = gamelang.config.currentURL;
    gamelang.i18n = gamelang.i18n || {};
    gamelang.i18n.reports = gamelang.i18n.reports || {};

    /**
     * Activities Object.
     * Handles all operations done on reports package.
     */
    gamelang.reports = {

        // Delete the targeted report.
        delete: function (el) {
            var $this = $(el),
                href = $this.data("endpoint"),
                row = $this.closest("tr"),
                id = row.data("id");

            // We cannot proceed if the URL is not provided.
            if (typeof href === "undefined" || !href.length) {
                return false;
            }

            // Keep the count to see if we shall refresh page.
            var logCount = $("#reports-list").children(".report-item").length;

            return gamelang.ui.confirm(gamelang.i18n.reports.delete, function () {
                var data = {};
                data[gamelang.config.tokenName] = Cookies.get(gamelang.config.tokenCookie);
                data['action'] = "delete-report_" + id;

                gamelang.ajax.request(href, {
                    type: "POST",
                    data: data,
                    complete: function (jqXHR, textStatus) {
                        if (textStatus === "success") {
                            logCount--;
                            if (logCount <= 0) {
                                window.location.href = reportsURL;
                            } else {
                                row.animate({opacity: 0}, function () {
                                    gamelang.ui.reload();
                                });
                            }
                        }
                    }
                });
            })
        }
    };

    $(document).ready(function () {
        // Remove get parameters.
        if (reportsURL.indexOf("?") > 0) {
            reportsURL = reportsURL.substring(0, reportsURL.indexOf("?"));
        }

        // Delete report.
        $(document).on("click", ".report-delete", function (e) {
            e.preventDefault();
            return gamelang.reports.delete(this);
        });
    });

})(window.jQuery || window.Zepto, window, document);

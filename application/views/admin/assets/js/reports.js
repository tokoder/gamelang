(function ($, window, document, undefined) {
    "use strict";

    // Prepare globals.
    cg.reports = cg.reports || {};
    var reportsURL = cg.config.currentURL;

    /**
     * Activities Object.
     * Handles all operations done on reports package.
     */
    cg.reports = {

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

            return cg.ui.confirm(cg.i18n.reports.delete, function () {
                var data = {};
                data[cg.config.token_name] = Cookies.get(cg.config.token_cookie);
                data['action'] = "delete-report_" + id;

                cg.ajax.request(href, {
                    type: "POST",
                    data: data,
                    complete: function (jqXHR, textStatus) {
                        if (textStatus === "success") {
                            logCount--;
                            if (logCount <= 0) {
                                window.location.href = reportsURL;
                            } else {
                                row.animate({opacity: 0}, function () {
                                    cg.ui.reload();
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
            return cg.reports.delete(this);
        });
    });

})(window.jQuery || window.Zepto, window, document);

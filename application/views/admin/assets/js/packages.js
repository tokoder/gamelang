(function ($, window, document, undefined) {
    "use strict";

    // Prepare globals.
    var gamelang = window.gamelang = window.gamelang || {};
    gamelang.i18n = gamelang.i18n || {};
    gamelang.packages = gamelang.packages || {};
    gamelang.i18n.packages = gamelang.i18n.packages || {};

    /**
     * Packages.
     */
    gamelang.packages.proceed = function(el, action) {
        var $this = $(el),
            href = $this.data("endpoint"),
            row = $this.closest("tr"),
            id = row.attr("id") || undefined,
            name = row.data("name") || 'this',
            action = action || -1;

        /** If no URL is provided, nothing to do... */
        if (typeof href === "undefined" || !href.length) {
            return false;
        }

        /** Add opacity to siblings */
        row.siblings("tr").addClass("op-2");

        /** We define the confirmation message. */
        var message = gamelang.i18n.packages[action] || undefined;
        if (typeof message === "undefined") {
            message = gamelang.i18n.default[action] || undefined;
            if (typeof message === "undefined") {
                message = "Are you sure you to " + action + " %s?";
            }
        }

        /** We add the id to the URL if defined. */
        if (typeof id !== "undefined" && id.length) {
            href = href + "#" + id;
        }

        /** Display confirmation message. */
        gamelang.ui.confirm(message.replace(/%s/g, name), function () {
            window.location.href = href;
        }, function () {
            /** Make sure to remove opacity class from siblings. */
            row.siblings("tr").removeClass("op-2");
        });
    };


    $(document).ready(function() {
        /** Activate package. */
        $(document).on("click", ".package-activate", function(e) {
            e.preventDefault();
            return gamelang.packages.proceed(this, "activate");
        });

        /** Deactivate package. */
        $(document).on("click", ".package-deactivate", function(e) {
            e.preventDefault();
            return gamelang.packages.proceed(this, "deactivate");
        });

        /** Delete package. */
        $(document).on("click", ".package-delete", function(e) {
            e.preventDefault();
            return gamelang.packages.proceed(this, "delete");
        });
    });

})(window.jQuery || window.Zepto, window, document);

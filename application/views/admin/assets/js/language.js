(function($, window, document, undefined) {
    "use strict";

    // Prepare globals.
    cg.languages = cg.languages || {};

    /**
     * Languages.
     */
    cg.languages.proceed = function(el, action) {
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
        var message = cg.i18n.languages[action] || undefined;
        if (typeof message === "undefined") {
            message = cg.i18n.default[action] || undefined;
            if (typeof message === "undefined") {
                message = "Are you sure you to " + action + " %s?";
            }
        }

        /** We add the id to the URL if defined. */
        if (typeof id !== "undefined" && id.length) {
            href = href + "#" + id;
        }

        /** Display confirmation message. */
        cg.ui.confirm(message.replace(/%s/g, name), function () {
            window.location.href = href;
        }, function () {
            /** Make sure to remove opacity class from siblings. */
            row.siblings("tr").removeClass("op-2");
        });
    };

    $(document).ready(function () {
        /** Enable language. */
        $(document).on("click", ".language-enable", function (e) {
            e.preventDefault();
            return cg.languages.proceed(this, "enable");
        });

        /** Disable language. */
        $(document).on("click", ".language-disable", function (e) {
            e.preventDefault();
            return cg.languages.proceed(this, "disable");
        });

        /** Make default. */
        $(document).on("click", ".language-default", function (e) {
            e.preventDefault();
            return cg.languages.proceed(this, "make_default");
        });
    });

})(window.jQuery || window.Zepto, window, document);

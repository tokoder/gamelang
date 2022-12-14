(function ($, window, document, undefined) {
    "use strict";

    // Prepare globals.]
    cg.themes = cg.themes || {};
    var themesURL = cg.config.currentURL,
        themeModalContainer = "#theme-details",
        themeModal = "#theme-modal";

    /**
     * Themes - Activation/Deletion handler.
     */
    cg.themes.proceed = function(el, action) {
        var $this = $(el),
            href = $this.attr("href"),
            parent = $this.closest(".theme-item"),
            name = parent.data("name") || "this",
            action = action || -1;

        /** If no URL is provided, nothing to do... */
        if (typeof href === "undefined" || !href.length) {
            return false;
        }

        /** Add opacity to siblings */
        parent.siblings().addClass("op-2");

        /** We define the confirmation message. */
        var message = cg.i18n.themes[action] || undefined;
        if (typeof message === "undefined") {
            message = cg.i18n.default[action] || undefined;
            if (typeof message === "undefined") {
                message = "Are you sure you to " + action + " %s?";
            }
        }

        /** Display confirmation message. */
        cg.ui.confirm(message.replace(/%s/g, name), function () {
            window.location.href = href;
        }, function () {
            /** Make sure to remove opacity class from siblings. */
            parent.siblings().removeClass("op-2");
        });
    };

    /**
     * Themes - Theme details handler.
     */
    cg.themes.details = function (el) {
        var $this = $(el), href = $this.attr("href");

        /** If no URL is provided, nothing to do. */
        if (typeof href === "undefined" || !href.length) {
            return false;
        }

        $(themeModalContainer).load(href + " " + themeModalContainer + " > *", function () {
            window.history.pushState({href: href}, "", href);
            $(themeModal).modal("show");
        });
    };

    $(document).ready(function () {
        /** Remove get parameters from URL. */
        if (themesURL.indexOf("?") > 0) {
            themesURL = themesURL.substring(0, themesURL.indexOf("?"));
        }

        /** Put back URL when modal is closed. */
        $(document).on("hidden.bs.modal", themeModal, function (e) {
            window.history.pushState({href: themesURL}, "", themesURL);
            $(this).remove();
        });

        /** Display theme's details. */
        $(document).on("click", ".theme-details", function (e) {
            e.preventDefault();
            return cg.themes.details(this);
        });

        /** Activate theme. */
        $(document).on("click", ".theme-activate", function (e) {
            e.preventDefault();
            return cg.themes.proceed(this, "activate");
        });

        /** Delete theme. */
        $(document).on("click", ".theme-delete", function (e) {
            e.preventDefault();
            return cg.themes.proceed(this, "delete");
        });
    });

})(window.jQuery || window.Zepto, window, document);

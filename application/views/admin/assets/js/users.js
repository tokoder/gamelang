(function ($, window, document, undefined) {
    "use strict";

    // Prepare globals.
    var gamelang = window.gamelang = window.gamelang || {};
    gamelang.i18n = gamelang.i18n || {};
    gamelang.users = gamelang.users || {};
    gamelang.i18n.users = gamelang.i18n.users || {};

    /**
     * Users.
     */
    gamelang.users.proceed = function(el, action) {
        var $this = $(el),
            href = $this.data("endpoint"),
            row = $this.closest("tr"),
            name = row.data("name") || 'this',
            id = row.attr("id") || undefined,
            action = action || -1;

        /** If no URL is provided, nothing to do... */
        if (typeof href === "undefined" || !href.length) {
            return false;
        }

        /** Add opacity to siblings */
        row.siblings("tr").addClass("op-2");

        /** We define the confirmation message. */
        var message = gamelang.i18n.users[action] || undefined;
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

    $(document).ready(function () {
        /** Activate user. */
        $(document).on("click", ".user-activate", function (e) {
            e.preventDefault();
            return gamelang.users.proceed(this, "activate");
        });

        /** Deactivate user. */
        $(document).on("click", ".user-deactivate", function (e) {
            e.preventDefault();
            return gamelang.users.proceed(this, "deactivate");
        });

        /** Soft delete a user. */
        $(document).on("click", ".user-delete", function (e) {
            e.preventDefault();
            return gamelang.users.proceed(this, "delete");
        });

        /** Restore a deleted user. */
        $(document).on("click", ".user-restore", function (e) {
            e.preventDefault();
            return gamelang.users.proceed(this, "restore");
        });

        /** Remove user and related data. */
        $(document).on("click", ".user-remove", function (e) {
            e.preventDefault();
            return gamelang.users.proceed(this, "remove");
        });
    });

})(window.jQuery || window.Zepto, window, document);

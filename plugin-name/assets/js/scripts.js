(function () {
    "use strict";
    jQuery('.popmake').each(function () {
        jQuery(this)
            .on('popmakeInit', function () {
                var $this = jQuery(this),
                    settings = $this.data('popmake'),
                    allow_unload = false,
                    exit_intent = settings.meta.exit_intent,
                    opened = false,
                    noCookieCheck = function () { return; };

                if (exit_intent !== undefined && exit_intent.enabled) {

                    noCookieCheck = function () {
                        return jQuery.pm_cookie("popmake-exit-intent-" + settings.id + "-" + exit_intent.cookie_key) === undefined;
                    };

                    $this.on('popmakeSetCookie', function () {
                        if (exit_intent.cookie_time !== '' && noCookieCheck()) {
                            jQuery.pm_cookie(
                                "popmake-exit-intent-" + settings.id + "-" + exit_intent.cookie_key,
                                true,
                                exit_intent.cookie_time,
                                exit_intent.cookie_path
                            );
                        }
                    });


                    switch (exit_intent.cookie_trigger) {
                        case "open":
                            $this.on('popmakeAfterOpen', function () {
                                $this.trigger('popmakeSetCookie');
                            });
                            break;

                        case "close":
                            $this.on('popmakeBeforeClose', function () {
                                $this.trigger('popmakeSetCookie');
                            });
                            break;
                    }

                    switch (exit_intent.type) {
                        case "soft":
                            jQuery(document).on('mouseleave', function (event) {
                                if (event.clientY > 10 || !noCookieCheck() || opened) {
                                    return;
                                }
                                jQuery.fn.popmake.last_open_trigger = 'Exit Popup ID-' + settings.id;
                                opened = true;
                                $this.popmake('open');
                            });
                            break;
                        case "hard":
                            window.onbeforeunload = function () {
                                if (!noCookieCheck() || allow_unload || opened) {
                                    jQuery.fn.popmake.last_open_trigger = 'Exit Popup ID-' + settings.id;
                                    opened = true;
                                    $this.popmake('open');
                                    allow_unload = true;
                                    return exit_intent.hard_message;
                                }
                            };
                            break;
                    }

                }
            });
    });
}());
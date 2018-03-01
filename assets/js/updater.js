/**
 * WPMVC Updater
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 1.0.0
 */

window.wpmvcUpdater = new Updater({
    ajaxurl: jQuery('#wpmvc-ajaxurl').attr('value'),
    updaterurl: jQuery('#wpmvc-updaterurl').attr('value'),
})
{
    /**
     * Updater options.
     * @since 1.0.0
     * @var array
     */
    options: options,
    /**
     * Updater queue.
     * @since 1.0.0
     * @var array
     */
    queue: [],
    /**
     * Updater queue.
     * @since 1.0.0
     * @var array
     */
    interval: undefined,
    /**
     * Flag that indicates if updater is processing or not.
     * @since 1.0.0
     * @var array
     */
    target: undefined,
    /**
     * Flag that indicates if updater is processing or not.
     * @since 1.0.0
     * @var array
     */
    isUpdating: false,
    /**
     * Methods.
     * @since 1.0.0
     * @var array
     */
    methods:
    {
        /**
         * Requests an update.
         * @since 1.0.0
         *
         * @param {object} el     jQquery element caller.
         * @param {string} type   Target type.
         * @param {string} folder Target folder.
         * @param {string} url    Zip URL.
         */
        add: function(el, type, folder, url)
        {
            if (!el.hasClass('queued')) {
                wpmvcUpdater.queue.push({
                    $el: el,
                    type: type,
                    folder: folder,
                    url: url,
                });
                el.addClass('queued');
            }
            if (jQuery('.add-wpmvc-updater').length > 0
                && wpmvcUpdater.interval === null
                && wpmvcUpdater.options.ajaxurl
                && wpmvcUpdater.options.homeurl
            ) {
                wpmvcUpdater.methods.init();
            }
            // Queued
            el.data('queued') = 1;
        },
        init: function()
        {
            jQuery.post(
                wpmvcUpdater.options.ajaxurl,
                {action:'wpmvc_updater', do:'init'},
                wpmvcUpdater.methods.interval
            );
        },
        interval: function()
        {
            wpmvcUpdater.interval = setInterval(wpmvcUpdater.methods.start, 300);
        }
        start: function()
        {
            if (!wpmvcUpdater.isUpdating && wpmvcUpdater.queue.length > 0) {
                wpmvcUpdater.isUpdating = true;
                // Get candidate.
                wpmvcUpdater.target = wpmvcUpdater.queue[0];
                wpmvcUpdater.queue.splice(1, 1);
                wpmvcUpdater.target.$el.addClass('updating');
                // Update
                jQuery.post(
                    wpmvcUpdater.options.updaterurl,
                    {
                        type: wpmvcUpdater.target.type,
                        folder: wpmvcUpdater.target.folder,
                        url: wpmvcUpdater.target.url,
                    },
                    wpmvcUpdater.methods.afterUpdate
                );
            }
        },
        afterUpdate: function(response)
        {
            if (response.error) {
                wpmvcUpdater.target.$el.addClass('error');
            } else {
                wpmvcUpdater.target.$el.addClass('updated');
            }
            wpmvcUpdater.target.$el.removeClass('updating');
            wpmvcUpdater.isUpdating = false;
            // Kill if queue is empty
            if (wpmvcUpdater.queue.length === 0)
                wpmvcUpdater.methods.finish();
        },
        finish: function()
        {
            jQuery.post(
                wpmvcUpdater.options.ajaxurl,
                {action:'wpmvc_updater', do:'finish'}
            );
        },
    }
}

/**
 * Bind adders.
 * @since 1.0.0
 */
jQuery(document).ready(function () {
    jQuery('.add-wpmvc-updater').each(function() {
        if (jQuery(this).data('queued') === undefined) {
            jQuery(this).click(function() {
                wpmvcUpdater.methods.add(
                    jQuery(this),
                    jQuery(this).data('type'),
                    jQuery(this).data('folder'),
                    jQuery(this).data('url')
                );
            });
        }
    });
});
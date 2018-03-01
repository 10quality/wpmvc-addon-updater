/**
 * WPMVC Updater
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 1.0.0
 */

/**
 * Updatr function.
 * @since 1.0.0
 *
 * @param {object} options Passes urls.
 */
var WPMVCUpdater = function(options) {
    return {
        /**
         * Updater options.
         * @since 1.0.0
         * @var object
         */
        options: options,
        /**
         * Updater queue.
         * @since 1.0.0
         * @var array
         */
        queue: [],
        /**
         * Updater interval.
         * @since 1.0.0
         * @var array
         */
        interval: undefined,
        /**
         * Target being updated.
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
                    el.find('.dashicons').show();
                    el.find('.dashicons').addClass('dashicons-image-rotate');
                    el.find('.dashicons').addClass('spin');
                }
                if (jQuery('.add-wpmvc-updater').length > 0
                    && wpmvcUpdater.interval === undefined
                    && wpmvcUpdater.options.ajaxurl
                    && wpmvcUpdater.options.updaterurl
                ) {
                    wpmvcUpdater.methods.init();
                }
                // Queued
                el.data('queued', 1);
            },
            /**
             * Inits updater.
             * Calls to updated via ajax.
             * @since 1.0.0
             */
            init: function()
            {
                jQuery.post(
                    wpmvcUpdater.options.ajaxurl,
                    {action:'wpmvc_updater', do:'init'},
                    wpmvcUpdater.methods.interval
                );
            },
            /**
             * Creates and starts queue interval.
             * Calls to updated via ajax.
             * @since 1.0.0
             *
             * @param {object} Response from call.
             */
            interval: function(response)
            {
                wpmvcUpdater.interval = setInterval(wpmvcUpdater.methods.start, 300);
            },
            /**
             * Starts processing queue.
             * Performs updates.
             * Calls to updated via ajax.
             * @since 1.0.0
             */
            start: function()
            {
                if (!wpmvcUpdater.isUpdating && wpmvcUpdater.queue.length > 0) {
                    wpmvcUpdater.isUpdating = true;
                    // Get candidate.
                    wpmvcUpdater.target = wpmvcUpdater.queue[0];
                    wpmvcUpdater.queue = wpmvcUpdater.queue.splice(1, 1);
                    wpmvcUpdater.target.$el.addClass('updating');
                    // Update
                    jQuery.get(
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
            /**
             * Handles update response.
             * Ends target update.
             * Finishes updating process if queue is empty.
             * @since 1.0.0
             *
             * @param {object} Response from call.
             */
            afterUpdate: function(response)
            {
                if (response === '' || response.error) {
                    wpmvcUpdater.target.$el.addClass('error');
                    wpmvcUpdater.target.$el.find('.dashicons').addClass('dashicons-no');
                } else {
                    wpmvcUpdater.target.$el.addClass('updated');
                    wpmvcUpdater.target.$el.find('.dashicons').addClass('dashicons-yes');
                }
                wpmvcUpdater.target.$el.find('.dashicons').removeClass('dashicons-image-rotate');
                wpmvcUpdater.target.$el.find('.dashicons').removeClass('spin');
                wpmvcUpdater.target.$el.removeClass('updating');
                wpmvcUpdater.target.$el.removeClass('queued');
                wpmvcUpdater.target = undefined;
                wpmvcUpdater.isUpdating = false;
                // Kill if queue is empty
                if (wpmvcUpdater.queue.length === 0)
                    wpmvcUpdater.methods.finish();
            },
            /**
             * Finishes updater.
             * Calls to updater via ajax.
             * @since 1.0.0
             */
            finish: function()
            {
                jQuery.post(
                    wpmvcUpdater.options.ajaxurl,
                    {action:'wpmvc_updater', do:'finish'},
                    wpmvcUpdater.methods.kill
                );
            },
            /**
             * Kills interval.
             * @since 1.0.0
             *
             * @param {object} Response from call.
             */
            kill: function(response)
            {
                clearInterval(wpmvcUpdater.interval);
                wpmvcUpdater.interval = undefined;
            },
        }
    };
}
/**
 * Instatiate updater.
 * @since 1.0.0
 */
window.wpmvcUpdater = new WPMVCUpdater({
    ajaxurl: jQuery('#wpmvc-ajaxurl').attr('value'),
    updaterurl: jQuery('#wpmvc-updaterurl').attr('value'),
});

/**
 * Bind adders.
 * @since 1.0.0
 */
jQuery(document).ready(function () {
    jQuery('.add-wpmvc-updater').each(function() {
        jQuery(this).find('.dashicons').hide();
        jQuery(this).click(function(e) {
            e.preventDefault();
            if (!jQuery(this).hasClass('updated')) {
                var options = {type:undefined,folder:undefined};
                var classes = jQuery(this).attr('class').split(/\s+/);
                for (var i in classes) {
                    if (classes[i].match(/updater-type-[a-zA-Z]+/g))
                       options.type = classes[i].replace('updater-type-', '');
                    if (classes[i].match(/updater-folder-[a-zA-Z]+/g))
                       options.folder = classes[i].replace('updater-folder-', '');
                }
                wpmvcUpdater.methods.add(
                    jQuery(this),
                    options.type,
                    options.folder,
                    jQuery(this).attr('href')
                );
            }
        });
    });
});
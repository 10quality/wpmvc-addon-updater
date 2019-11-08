<?php

namespace WPMVC\Addons\Updater;

use WPMVC\Addon;

/**
 * Updater addon class.
 * Wordpress MVC.
 *
 * @link http://www.wordpress-mvc.com/v1/add-ons/
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 2.0.0
 */
class UpdaterAddon extends Addon
{
    /**
     * Addon init.
     * @since 2.0.0
     */
    public function init()
    {
        add_filter(
            $this->main->config->get( 'type' ) === 'theme'
                ? 'pre_set_site_transient_update_themes'
                : 'pre_set_site_transient_update_plugins',
            [&$this, 'update_package']
        );
    }
    /**
     * Returns update transient data.
     * @since 2.0.0
     * 
     * @hook pre_set_site_transient_update_plugins
     * @hook pre_set_site_transient_update_themes
     * 
     * @param object $transient
     * 
     * @return object
     */
    public function update_package( $transient )
    {
        $transient = $this->mvc->action( 'UpdaterController@check', $transient, $this->main );
        return $transient;
    }
}
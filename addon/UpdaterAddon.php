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
    public function int()
    {
        add_filter( 'pre_set_site_transient_update_plugins', [&$this, 'update_plugin'] );
    }
    /**
     * Returns update transient data.
     * @since 2.0.0
     * 
     * @hook pre_set_site_transient_update_plugins
     * 
     * @param object $transient
     * 
     * @return object
     */
    public function update_plugin( $transient )
    {
        $transient = $this->mvc->action( 'UpdaterController@check', $transient, $this->main );
        return $transient;
    }
}
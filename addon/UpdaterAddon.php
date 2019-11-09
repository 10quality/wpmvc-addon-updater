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
        if ( $this->main->config->get( 'type' ) === 'plugin' )
            add_filter( 'plugins_api', [&$this, 'plugins_api'], 10, 3 );
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
    /**
     * Returns plugin's api response.
     * @since 2.0.0
     * 
     * @hook plugins_api
     * 
     * @param array  $response
     * @param string $action
     * @param array  $args
     * 
     * @return array
     */
    public function plugins_api( $response, $action, $args )
    {
        $response = $this->mvc->action( 'UpdaterController@response', $response, $action, $args, $this->main );
        return $response;
    }
}
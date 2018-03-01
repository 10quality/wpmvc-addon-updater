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
 * @version 1.0.1
 */
class UpdaterAddon extends Addon
{
    /**
     * Function called when user is on admin dashboard.
     * Add wordpress hooks (actions, filters) here.
     * @since 1.0.0
     */
    public function on_admin()
    {
        add_action( 'wp_ajax_wpmvc_updater', [&$this, 'updater'] );
        add_action( 'admin_enqueue_scripts', [&$this, 'enqueue'] );
        add_action( 'admin_head', [&$this, 'admin_head'] );
    }
    /**
     * Calls updater via ajax.
     * @since 1.0.0
     */
    public function updater()
    {
        $this->mvc->call( 'UpdaterController@call' );
    }
    /**
     * Displays updater button.
     * @since 1.0.0
     * @since 1.0.1 Adds namespace.
     *
     * @param string $url    ZIP url.
     * @param string $class  CSS class.
     */
    public function updater_button( $url, $class = '' )
    {
        $this->mvc->view->show( 'button', [
            'type'      => $this->main->config->get( 'type' ),
            'folder'    => $this->main->config->get( 'paths.root_folder' ),
            'namespace' => $this->main->config->get( 'namespace' ),
            'url'       => $url,
            'class'     => $class
        ] );
    }
    /**
     * Enqueues admin assets.
     * @since 1.0.0
     */
    public function enqueue()
    {
        $this->mvc->call( 'UpdaterController@enqueue', $this->main );
    }
    /**
     * Admin head.
     * @since 1.0.0
     */
    public function admin_head()
    {
        $this->mvc->call( 'UpdaterController@head' );
    }
}
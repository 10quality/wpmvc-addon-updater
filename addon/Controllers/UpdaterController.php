<?php

namespace WPMVC\Addons\Updater\Controllers;

use WPMVC\MVC\Controller;
use WPMVC\Addons\Updater\Models\UpdateData;

/**
 * Updater controller.
 * Wordpress MVC.
 * 
 * @link https://code.tutsplus.com/tutorials/a-guide-to-the-wordpress-http-api-automatic-plugin-updates--wp-25181
 * @link https://florianbrinkmann.com/en/automatic-updates-for-wordpress-themes-which-are-not-in-the-theme-directory-3388/
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 2.0.0
 */
class UpdaterController extends Controller
{
    /**
     * Checks addon current version and updates transient with update response if needed.
     * @since 2.0.0
     * 
     * @hook pre_set_site_transient_update_plugins
     * @hook pre_set_site_transient_update_themes
     * 
     * @param object $transient
     * @param object $main
     * 
     * @return object
     */
    public function check( $transient, $main )
    {
        if ( is_object( $transient ) && empty( $transient->checked ) )
            return $transient;
        $current_version = $main->config->get( 'version' );
        $update = new UpdateData( [
            'version'   => $current_version,
            'slug'      => $main->config->get( 'localize.textdomain' ),
            'target'    => $main->config->get( 'paths.base_file' ),
            'package'   => '',
            'url'       => null,
            'icon'      => null,
        ] );
        $update = apply_filters( 'wpmvc_update_data_' . $update->get_slug(), $update );
        if ( is_a( $update, 'WPMVC\\Addons\\Updater\\Models\\UpdateData' )
            && $update->is_valid()
            && version_compare( $current_version, $update->get_version(), '<' )
        ) {
            $transient->response[$update->get_target()] = $update->to_std();
        }
        return $transient;
    }
    /**
     * Returns latest full API response.
     * @since 2.0.0
     * 
     * @hook plugins_api
     * 
     * @param array  $response
     * @param string $action
     * @param array  $args
     * @param object $main
     * 
     * @return array
     */
    public function response( $response, $action, $args, $main )
    {
        if ( $action !== 'plugin_information'
            || $args->slug !== $main->config->get( 'localize.textdomain' )
        )
            return false;
        $current_version = $main->config->get( 'version' );
        $update = new UpdateData( [
            'version'   => $current_version,
            'slug'      => $main->config->get( 'localize.textdomain' ),
            'target'    => $main->config->get( 'paths.base_file' ),
            'package'   => '',
            'url'       => null,
            'icon'      => null,
        ] );
        $update = apply_filters( 'wpmvc_update_info_' . $update->get_slug(), $update );
        if ( is_wp_error( $update ) )
            return $update;
        return is_a( $update, 'WPMVC\\Addons\\Updater\\Models\\UpdateData' ) && $update->is_valid()
            ? $update->to_res();
            : $response;
    }
}
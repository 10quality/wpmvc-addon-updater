<?php

namespace WPMVC\Addons\Updater\Models;

use stdClass;

/**
 * Update data model.
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 2.0.0
 */
class UpdateData
{
    /**
     * Newest update version.
     * Format: /[1-0]+\.*
     * @since 2.0.0
     * @var string
     */
    protected $version;
    /**
     * Slug (domain text could work)
     * @since 2.0.0
     * @var string
     */
    protected $slug;
    /**
     * Download url.
     * @since 2.0.0
     * @var string
     */
    protected $url;
    /**
     * Plugin root file.
     * @since 2.0.0
     * @var string
     */
    protected $target;
    /**
     * Download url.
     * @since 2.0.0
     * @var string
     */
    protected $package_url;
    /**
     * Default constructor.
     * @since 2.0.0
     * 
     * @param string $version
     * @param string $url
     * @param string $slug
     */
    public function __construct( $args )
    {
        $this->set_version( array_key_exists( 'version' , $args ) ? $args['version'] : null );
        $this->set_url( array_key_exists( 'url' , $args ) ? $args['url'] : null );
        $this->set_slug( array_key_exists( 'slug' , $args ) ? $args['slug'] : null );
        $this->set_package( array_key_exists( 'package' , $args ) ? $args['package'] : null );
        $this->target = array_key_exists( 'target' , $args ) ? $args['target'] : null;
    }
    /**
     * Returns version.
     * @since 2.0.0
     * 
     * @return string
     */
    public function get_version()
    {
        return $this->version;
    }
    /**
     * Returns slug.
     * @since 2.0.0
     * 
     * @return string
     */
    public function get_slug()
    {
        return $this->slug;
    }
    /**
     * Returns slug.
     * @since 2.0.0
     * 
     * @return string
     */
    public function get_target()
    {
        return $this->target;
    }
    /**
     * Sets update version.
     * @since 2.0.0
     * 
     * @param string $version
     */
    public function set_version( $version )
    {
        if ( empty( $version ) ) return;
        $version = trim( preg_replace( '/[a-zA-Z]+/', '',  $version ) );
        $this->version = $version;
    }
    /**
     * Sets update url.
     * @since 2.0.0
     * 
     * @param string $url
     */
    public function set_url( $url )
    {
        if ( empty( $url ) ) return;
        $this->url = esc_url_raw( $url );
    }
    /**
     * Sets update package_url.
     * @since 2.0.0
     * 
     * @param string $package_url
     */
    public function set_package( $package_url )
    {
        if ( empty( $package_url ) ) return;
        $this->package_url = esc_url_raw( $package_url );
    }
    /**
     * Sets update slug.
     * @since 2.0.0
     * 
     * @param string $slug
     */
    public function set_slug( $slug )
    {
        if ( empty( $slug ) ) return;
        $this->slug = $slug;
    }
    /**
     * Returns flag indicating if update data is valid.
     * @since 2.0.0
     * 
     * @return bool
     */
    public function is_valid()
    {
        return ! empty( $this->version )
            && ! empty( $this->package_url )
            && ! empty( $this->slug )
            && ! empty( $this->target );
    }
    /**
     * Returns class a standard object.
     * @since 2.0.0
     * 
     * @return \stdClass
     */
    public function to_std()
    {
        $obj = new stdClass;
        $obj->slug = $this->slug;
        $obj->new_version = $this->version;
        $obj->package = $this->package_url;
        if ( isset( $this->url ) && ! empty( $this->url ) )
            $obj->url = $this->url;
        return $obj;
    }
}
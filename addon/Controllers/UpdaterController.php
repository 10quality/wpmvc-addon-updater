<?php

namespace WPMVC\Addons\Updater\Controllers;

use WPMVC\Log;
use WPMVC\Request;
use WPMVC\Response;
use WPMVC\MVC\Controller;
use TenQuality\WP\File;

/**
 * Updater controller.
 * Wordpress MVC.
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 1.0.0
 */
class UpdaterController extends Controller
{
    /**
     * Updater filename.
     * @since 1.0.0
     *
     * @var string
     */
    const FILENAME = ABSPATH.'wpmvc-updater.php';
    /**
     * Adds meta values in head.
     * Action "admin_head".
     * Wordpress hook.
     * @since 1.0.0
     */
    public function head()
    {
        $this->view->show('head-meta');
    }
    /**
     * Enqueues assets.
     * Action "admin_enqueue_scripts".
     * Wordpress hook.
     * @since 1.0.0
     */
    public function enqueue( $main )
    {
        wp_enqueue_script(
            'wpmvc-updater',
            addon_assets_url( 'js/updater.js', __FILE__ ),
            ['jquery'],
            $main->config->get( 'version' ),
            true
        );
        wp_enqueue_style(
            'wpmvc-updater',
            addon_assets_url( 'css/updater.css', __FILE__ ),
            [],
            $main->config->get( 'version' )
        );
    }
    /**
     * Routes request.
     * Action "wp_ajax_wpmvc_updater".
     * Wordpress hook.
     * @since 1.0.0
     */
    public function call()
    {
        switch ( Request::input( 'do' , false) ) {
            case 'init':
                return $this->init();
            case 'finish':
                return $this->finish();
        }
    }
    /**
     * Copies a version of the updater into Wordpress ABSPATH.
     * @since 1.0.0
     */
    public function init()
    {
        $response = new Response;
        try {
            $file = File::auth();
            if ( $file->exists( self::FILENAME ) )
                unlink( self::FILENAME );
            // Create
            $file->write(
                self::FILENAME,
                '<?php ignore_user_abort(true);if(!empty($_POST)||defined(\'DOING_AJAX\'))die();define(\'DOING_AJAX\',true);if(!defined(\'ABSPATH\')){require_once(dirname(__FILE__).\'/wp-load.php\');}use WPMVC\Log;use WPMVC\Request;use WPMVC\Response;use TenQuality\WP\File;$response=new Response;$file=File::auth();$path=$filename=\'\';try{$url=Request::input(\'url\',false);$type=Request::input(\'type\',false);$folder=Request::input(\'folder\',false);if($url===false) throw new Exception(\'No url given.\');if($type!==\'theme\'&&$type!==\'plugin\') throw new Exception(\'Invalid type.\');if($folder===false) throw new Exception(\'No folder given.\');$zip=wp_remote_get(esc_url($url));if(!isset($zip[\'body\'])) throw new Exception(\'No body found on update file.\');preg_match(\'/.datafeed_([0-9]*)\../\',$zip[\'headers\'][\'content-disposition\'],$match);$path=ABSPATH.\'tmp-update/\';$filename=isset($zip[\'headers\'][\'etag\'])?preg_replace(\'/"/\',\'\',$zip[\'headers\'][\'etag\']).\'.zip\':\'datafeed_\'.$match[0].\'.zip\';if(!$file->is_dir($path))mkdir($path);$file->write($path.$filename,$zip[\'body\']);$DST_PATH=ABSPATH.\'wp-content/\'.($type===\'theme\'?\'themes\':\'plugins\');if($file->is_dir($DST_PATH.\'/\'.$folder))$file->rmdir($DST_PATH.\'/\'.$folder);WP_Filesystem();if(unzip_file($path.$filename,$DST_PATH)){$response->success=true;$response->message=__(\'Updated successfully!\');}else { throw new Exception(\'Unzip failed.\');}}catch(Exception$e){Log::error($e);$response->message=$e->getMessage();}finally{if(!empty($path)){if($file->exists($path.$filename))unlink($path.$filename);if($file->is_dir($path))$file->rmdir($path);}}$response->json();'
            );
            $response->success = true;
        } catch (Exception $e) {
            Log::error( $e );
        }
        $response->json();
    }
    /**
     * Removes updater from Wordpress.
     * @since 1.0.0
     */
    public function finish()
    {
        $response = new Response;
        try {
            $file = File::auth();
            if ( $file->exists( self::FILENAME ) )
                unlink( self::FILENAME );
            $response->success = true;
        } catch (Exception $e) {
            Log::error( $e );
        }
        $response->json();
    }
}
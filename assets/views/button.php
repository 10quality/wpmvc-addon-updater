<?php
/**
 * Updater button.
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 1.0.3
 */
?>
<a role="button"
    href="<?= $url ?>"
    class="button add-wpmvc-updater <?= $class ?> updater-type-<?= $type ?> updater-folder-<?= $folder ?> updater-namespace-<?= $namespace ?>"
><span class="dashicons"></span><?php _e( 'Update' ) ?></a>
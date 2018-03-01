<?php
/**
 * Updater button.
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\Updater
 * @license MIT
 * @version 1.0.0
 */
?>
<a href="#"
    role="button"
    class="button add-wpmvc-updater <?= $class ?>"
    data-type="<?= $type ?>"
    data-folder="<?= $folder ?>"
    data-url="<?= $url ?>"
>
    <?php _e( 'Update', 'addon' ) ?>
</a>
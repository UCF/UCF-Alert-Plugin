<?php
/**
 * Handles uninstallation logic.
 **/
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    die;
}


require_once 'includes/ucf-alert-config.php';

// Delete options
UCF_Alert_Config::delete_options();

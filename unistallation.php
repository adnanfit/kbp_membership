<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

// Include the global $wpdb variable to access the database
global $wpdb;

// Specify the table name
$table_name = $wpdb->prefix . 'membership_data';

// Drop the table
$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
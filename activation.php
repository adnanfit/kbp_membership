<?php
function my_plugin_activate()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "membership_data";
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    full_name tinytext NOT NULL,
    phone_number tinytext NOT NULL,
    address text NOT NULL,
    birth_year int(4) NOT NULL,
    district tinytext NOT NULL,
    tehsil tinytext NOT NULL,  -- Added Tehsil field
    image_url text NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

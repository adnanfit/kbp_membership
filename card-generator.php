<?php
/*
 * Plugin Name:       Card Generator KBP
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A custom plugin to generate membership ID cards for Kisan Board Pakistan. 
 * Version:           1.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            M Adnan Ajmal
 * Author URI:        https://www.linkedin.com/in/adi18f/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       KBP
 * Domain Path:       /languages
 */


// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define constants
define( 'MY_PLUGIN_VERSION', '1.0' );
define( 'MY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

function kbp_enqueue_scripts() {
    wp_enqueue_style('bootstrap-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap-js', 'https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-toast', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js', array('jquery'), null, true);
    wp_enqueue_script('jquery-toast', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.js', array('jquery'), null, true);
    wp_enqueue_style('jquery-toast-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css');
    wp_enqueue_style('jquery-toast-css', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css');
    // wp_enqueue_script('pdf', MY_PLUGIN_URL . 'assets/js/pdf.js', array('jquery', 'jquery-toast'), null, true);


    wp_enqueue_script('form', MY_PLUGIN_URL . 'assets/js/form.js', array('jquery', 'jquery-toast'), null, true);
    wp_localize_script('form', 'ajax_object', array(
        'ajax_url' => admin_url('admin-ajax.php')
    ));
}



add_action('wp_enqueue_scripts', 'kbp_enqueue_scripts');

// Include the form file
require_once plugin_dir_path( __FILE__ ) . 'includes/form.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/handelSubmission.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/cardmaker.php';


// Register shortcode
add_shortcode('kbp_membership_form', 'mc_membership_form');

//admin scripts
require_once MY_PLUGIN_DIR . 'admin/form-data.php';
// require_once MY_PLUGIN_DIR . 'includes/hooks.php';

// Activation and 
require_once MY_PLUGIN_DIR . 'activation.php';
register_activation_hook( __FILE__, 'my_plugin_activate' );
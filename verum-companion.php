<?php
/*
Plugin Name: Verum Companion Plugin
Plugin URI:
Description: Companion Plugin for Verum Theme
Version: 1.0
Author: SSP
Author URI:
License: GPLv2 or later
Text Domain: verum-companion
Domain Path: /languages/
*/

require_once plugin_dir_path(__FILE__)."/widgets/verum-social-widget.php";
require_once plugin_dir_path(__FILE__)."/widgets/verum-about-widget.php";
require_once plugin_dir_path(__FILE__)."/widgets/verum-latest-posts-widget.php";
require_once plugin_dir_path(__FILE__)."/widgets/verum-advertizement-widget.php";
require_once plugin_dir_path(__FILE__)."/widgets/verum-newsletter-widget.php";

function verum_load_textdomain(){
    load_plugin_textdomain('verum-companion',false,dirname(__FILE__)."/languages");
}
add_action('plugins_loaded','verum_load_textdomain');

function verum_plugin_init(){
    add_image_size('verum-sidebar-thumbnail',90,75,true);
}
add_action('init','verum_plugin_init');
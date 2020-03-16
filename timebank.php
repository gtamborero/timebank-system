<?php

/*
Plugin Name: TimeBank
Plugin URI: http://www.time-bank.info/
Description: The timebank-sharing system for your wordpress users! Read install documentation on www.time-bank.info. <br /> Support us at <a href="https://www.teaming.net/wordpresstime-bank-bancodeltiempo">www.teaming.net</a>
Author: Guillermo Tamborero
Domain Path: /languages
Version: 1.70
Author URI: http://www.time-bank.info
*/

if ( ! defined('ABSPATH')){
  die;
}

if (!function_exists ('add_action')){
  echo "Algo estas haciendo mal"; exit();
}

define( 'TB_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) );
define( 'TB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

include_once( plugin_dir_path( __FILE__ ) . 'admin/admin_buttons.php');

/* LOAD JQUERY */
function theme_scripts() {
  wp_enqueue_script('jquery');
}
add_action('wp_enqueue_scripts', 'theme_scripts');

// INSTALL HOOK when plugin is activated
function timebank_install(){
	include_once "admin/install.php";
	jal_install();
	jal_install_data();
	timebankUserCreateLoop();
}
register_activation_hook( __FILE__ ,'timebank_install');

// UPDATE HOOK when plugin is updated / reactivated
add_action( 'plugins_loaded', 'timebank_update' );
function timebank_update(){
	include_once "admin/install.php";
	jal_install();
}

// UNINSTALL hook
register_deactivation_hook( __FILE__ ,'timebank_uninstall');
function timebank_uninstall(){
	include_once "admin/install.php";
	jal_uninstall();
}

// Save errors on log file
add_action('activated_plugin','save_error');
function save_error(){
file_put_contents(plugin_dir_path( __FILE__ ). 'log_error_activation.txt', ob_get_contents());
}

// Create user on Timebank database when wordpress user creation
add_action('user_register','timebankUserCreate');
function timebankUserCreate($user_id){
	createUser($user_id);
}

function userCanManageOptions(){
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
}
//ADMIN SHOW EXCHANGES
function timebank_exchanges() {
  userCanManageOptions();
	include_once "admin/show_exchanges.php";
}

function timebank_editexchange() {
	userCanManageOptions();
  include_once "admin/edit_exchange.php";
}

function timebank_newexchange() {
	userCanManageOptions();
  include_once "admin/new_exchange.php";
}

//ADMIN SHOW / EDIT USERS
function timebank_users() {
	userCanManageOptions();
  include_once "admin/show_users.php";
}

function timebank_edituser() {
  userCanManageOptions();
	include_once "admin/edit_user.php";
}

//ADMIN GENERAL CONFIGURATION
function timebank_options() {
  userCanManageOptions();
	include_once "admin/edit_configuration.php";
}

//USER FUNCTIONS (public)
function timebank_user_exchanges_view(){
  if(!is_admin()) include_once "user/exchanges_view.php";
}
// HOOKS :  https://codex.wordpress.org/Plugin_API/Action_Reference



    //CSS STYLE FOR PUBLIC
    add_action( 'wp_enqueue_scripts', 'timebank_stylesheet' );
    function timebank_stylesheet(){
        wp_register_style( 'timebank-style', plugins_url('css/style.css', __FILE__) );
        wp_enqueue_style( 'timebank-style' );
    }

    // Add RateIt hook for front-end (wp_head) and backend (admin_footer)
    add_action('wp_head', 'rateClass');
    add_action('admin_footer', 'rateClass');
    function rateClass() {
    	echo '
    	<!-- Add RateIt Plugin Jquery -->
    	<script type="text/javascript" src="';
    	echo plugins_url( 'js/rateit/src/jquery.rateit.js', __FILE__ );
    	echo '"></script>
    	<link rel="stylesheet" type="text/css" href="';
    	echo plugins_url( 'js/rateit/src/rateit.css', __FILE__ );
    	echo '" media="screen" />';
    }

 include_once "admin/tbank_widget.php";

//BUDDY PRESS HOOK
add_action( 'bp_setup_nav', 'add_timebank_nav_tab' , 100 );
function add_timebank_nav_tab() {
bp_core_new_nav_item( array(
    'name' => __( 'TimeBank', 'timebank' ),
    'slug' => 'timebank',
    'position' => 80,
    'screen_function' => 'timebank_info',
    'default_subnav_slug' => 'timebank'
) );
}

// show feedback when 'Feedback’ tab is clicked
function timebank_info() {
bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
//tiene que ir detras este ad action. busca el bp_post template
add_action( 'bp_template_content','timebank_user_exchanges_view' );
}

    // AJAX FUNCTIONS
    include( plugin_dir_path( __FILE__ ) . 'common/constants.php');
    include( plugin_dir_path( __FILE__ ) . 'common/mysql_functions.php');
    include( plugin_dir_path( __FILE__ ) . 'user/ajax.php');

    function new_transfer_ajax() {
        ajax_new_transfer();
    }
    add_action( 'wp_ajax_new_transfer', 'new_transfer_ajax' );

    function validate_transfer_ajax() {
        ajax_validate_transfer();
    }
    add_action( 'wp_ajax_validate_transfer', 'validate_transfer_ajax' );

    function reject_transfer_ajax() {
        ajax_reject_transfer();
    }
    add_action( 'wp_ajax_reject_transfer', 'reject_transfer_ajax' );

    function comment_transfer_ajax() {
        ajax_comment_transfer();
    }
    add_action( 'wp_ajax_comment_transfer', 'comment_transfer_ajax' );

    function list_given_exchanges_ajax() {
        ajax_list_given_exchanges();
    }
    add_action( 'wp_ajax_list_given_exchanges', 'list_given_exchanges_ajax' );

    function list_received_exchanges_ajax() {
        ajax_list_received_exchanges();
    }
    add_action( 'wp_ajax_list_received_exchanges', 'list_received_exchanges_ajax' );

// TRANSLATION
add_action( 'plugins_loaded', 'timebank_load_textdomain' );
function timebank_load_textdomain() {
  load_plugin_textdomain( 'timebank', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

// CALL GUTENBERG BLOCK
include_once( plugin_dir_path( __FILE__ ) . 'blocks/timebank.php');

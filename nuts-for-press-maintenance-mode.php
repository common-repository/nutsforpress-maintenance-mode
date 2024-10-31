<?php
/*
Plugin Name: 	NutsForPress Maintenance Mode
Plugin URI:		https://www.nutsforpress.com/
Description: 	With NutsForPress Maintenance Mode you can redirect not logged users to a defined page or hide website content at defined breakpoints.
Version:     	1.8
Author:			Christian Gatti
Author URI:		https://profiles.wordpress.org/christian-gatti/
License:		GPL-2.0+
License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain:	nutsforpress-maintenance-mode
*/

//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');


//DEFINITIONS

if(!defined('NFPROOT_BASE_RELATIVE')) {define('NFPROOT_BASE_RELATIVE', dirname(plugin_basename( __FILE__ )).'/root');}
define('NFPMNM_BASE_PATH', plugin_dir_path( __FILE__ ));
define('NFPMNM_BASE_URL', plugins_url().'/'.plugin_basename( __DIR__ ).'/');
define('NFPMNM_BASE_RELATIVE', dirname( plugin_basename( __FILE__ )));
define('NFPMNM_DEBUG', false);


//NUTSFORPRESS ROOT CONTENT
	
//add NutsForPress parent menu page
require_once NFPMNM_BASE_PATH.'root/nfproot-settings.php';
add_action('admin_menu', 'nfproot_settings');

//add NutsForPress save settings function and make it available through ajax
require_once NFPMNM_BASE_PATH.'root/nfproot-save-settings.php';
add_action('wp_ajax_nfproot_save_settings', 'nfproot_save_settings');

//add NutsForPress saved settings and make them available through the global varibales $nfproot_current_language_settings and $nfproot_options_name
require_once NFPMNM_BASE_PATH.'root/nfproot-saved-settings.php';
add_action('plugins_loaded', 'nfproot_saved_settings');

//register NutsForPress styles and scripts
require_once NFPMNM_BASE_PATH.'root/nfproot-styles-and-scripts.php';
add_action('admin_enqueue_scripts', 'nfproot_styles_and_scripts');
	
//add NutsForPress settings structure that contains nfproot_options_structure function invoked by plugin settings
require_once NFPMNM_BASE_PATH.'root/nfproot-settings-structure.php';


//PLUGIN INCLUDES

//add activate actions
require_once NFPMNM_BASE_PATH.'includes/nfpmnm-plugin-activate.php';
register_activation_hook(__FILE__, 'nfpmnm_plugin_activate');

//add deactivate actions
require_once NFPMNM_BASE_PATH.'includes/nfpmnm-plugin-deactivate.php';
register_deactivation_hook(__FILE__, 'nfpmnm_plugin_deactivate');

//add uninstall actions
require_once NFPMNM_BASE_PATH.'includes/nfpmnm-plugin-uninstall.php';
register_uninstall_hook(__FILE__, 'nfpmnm_plugin_uninstall');


//PLUGIN SETTINGS

//add plugin settings
require_once NFPMNM_BASE_PATH.'admin/nfpmnm-settings.php';
add_action('admin_menu', 'nfpmnm_settings');


//PUBLIC INCLUDES CONDITIONALLY

//load redirect function
require_once NFPMNM_BASE_PATH.'public/includes/nfpmnm-redirect.php';
add_action('template_redirect', 'nfpmnm_redirect');

//load redirect sitemap function
require_once NFPMNM_BASE_PATH.'public/includes/nfpmnm-redirect-sitemap.php';
add_action('template_redirect', 'nfpmnm_redirect_sitemap', 5);

//restrict API REST
require_once NFPMNM_BASE_PATH.'public/includes/nfpmnm-restrict-rest.php';
add_filter('rest_authentication_errors', 'nfpmnm_restrict_rest');

//public script function
function nfpmnm_register_public_script() {

	wp_enqueue_script('nfpmnm-script', NFPMNM_BASE_URL.'/public/js/nfpmnm-script.js', array('jquery'));
				
}

//load public script
add_action('wp_enqueue_scripts', 'nfpmnm_register_public_script');

//load hide function
require_once NFPMNM_BASE_PATH.'public/includes/nfpmnm-hide.php';
add_action('wp_head', 'nfpmnm_hide');

//ADMIN INCLUDES CONDITIONALLY

//show notice on admin bar
require_once NFPMNM_BASE_PATH.'admin/includes/nfpmnm-add-notice.php';
add_action('admin_bar_menu', 'nfpmnm_add_notice', 999);

//add user custom fields
require_once NFPMNM_BASE_PATH.'admin/includes/nfpmnm-add-user-fields.php';
add_action('show_user_profile', 'nfpmnm_add_user_fields');
add_action('edit_user_profile', 'nfpmnm_add_user_fields');
add_action('user_new_form', 'nfpmnm_add_user_fields');

//save user custom fields
require_once NFPMNM_BASE_PATH.'admin/includes/nfpmnm-save-user-fields.php';
add_action('personal_options_update', 'nfpmnm_save_user_fields');
add_action('edit_user_profile_update', 'nfpmnm_save_user_fields');
add_action('user_register', 'nfpmnm_save_user_fields');

//check after login
require_once NFPMNM_BASE_PATH.'admin/includes/nfpmnm-check-after-login.php';
add_filter('wp_authenticate_user', 'nfpmnm_check_after_login');

//check force logout
require_once NFPMNM_BASE_PATH.'admin/includes/nfpmnm-force-logout.php';
add_action('init', 'nfpmnm_force_logout');
add_action('admin_init', 'nfpmnm_force_logout');
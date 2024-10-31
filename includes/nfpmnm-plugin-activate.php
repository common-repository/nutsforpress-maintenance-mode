<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//ACTIVATE

//plugin activate function
if(!function_exists('nfpmnm_plugin_activate')){

	function nfpmnm_plugin_activate() {
				
		//get NutsForPress setting
		global $nfproot_plugins_settings;
		
		//define plugin installaton type
		$nfproot_plugins_settings['nfpmnm']['prefix'] = 'nfpmnm';
		$nfproot_plugins_settings['nfpmnm']['slug'] = 'nfpmnm-settings';
		$nfproot_plugins_settings['nfpmnm']['edition'] = 'repository';
		$nfproot_plugins_settings['nfpmnm']['name'] = 'Maintenance Mode';
		
		//update NutsForPress setting
		update_option('_nfproot_plugins_settings', $nfproot_plugins_settings, false);
		
		//allow this Administrator to keep on working and login even when the Maintenance Mode is switched on
		$nfpmnm_user_object = wp_get_current_user();
		$nfpmnm_user_id = $nfpmnm_user_object->ID;
		
		update_user_meta(
			
			$nfpmnm_user_id, 
			'_nfpmnm_can_login_on_maintenance', 
			'1'
			
		);	
	
	}
		
}  else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_plugin_activate" already exists');
	
}
<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//UNINSTALL

//plugin uninstall function
if(!function_exists('nfpmnm_plugin_uninstall')){

	function nfpmnm_plugin_uninstall() {
		
		require_once NFPMNM_BASE_PATH.'root/nfproot-saved-settings.php';
		nfproot_saved_settings();
				
		global $nfproot_root_settings;
		global $nfproot_root_settings_name;
		
		if(!empty($nfproot_root_settings['nfpmnm'])) {
			
			//unset plugin installaton
			unset($nfproot_root_settings['nfpmnm']);
			
		}

		//if, after cleaning nfpmnm settings, base settings is empty, delete it (no more NutsForPress plugins are installed)
		if(empty($nfproot_root_settings)) {

			//delete base settings
			delete_option($nfproot_root_settings_name);			
			
		} else {
			
			//update base settings
			update_option($nfproot_root_settings_name, $nfproot_root_settings, false);
			
		}

		//get all WPML active languages
		$nfpmnm_get_wpml_active_languages = apply_filters('wpml_active_languages', false);

		//if WPML has active languages
		if(!empty($nfpmnm_get_wpml_active_languages)) {
		  
			//loop into languages
			foreach($nfpmnm_get_wpml_active_languages as $nfpmnm_wpml_language) {

				$nfpmnm_wpml_language_code = $nfpmnm_wpml_language['language_code'];

				$nfproot_current_language_settings_name = '_nfproot_settings_'.$nfpmnm_wpml_language_code;
				$nfproot_current_language_settings = get_option($nfproot_current_language_settings_name, false);
				
				if(!empty($nfproot_current_language_settings['nfpmnm'])) {
					
					//unset plugin installaton
					unset($nfproot_current_language_settings['nfpmnm']);
					
				}	
				
				//if, after cleaning nfpmnm settings, language settings is empty, delete it (no more NutsForPress plugins are installed)
				if(empty($nfproot_current_language_settings)) {

					//delete language settings
					delete_option($nfproot_current_language_settings_name);			
					
				} else {
					
					//update language settings
					update_option($nfproot_current_language_settings_name, $nfproot_current_language_settings, false);
					
				}
								
			}
			
		}	
		
		//delete settings from the old plugin structure
		delete_option('_nfp_root_settings');
		delete_option('_nfp_settings');

		//delete all user meta set by this plugin
		$nfpmnm_meta_type = 'user';
		$nfpmnm_object_id = 0;                
		$nfpmnm_meta_key = '_nfpmnm_can_login_on_maintenance';
		$nfpmnm_meta_value = null;
		$nfpmnm_delete_all = true;
		 
		delete_metadata(
		
			$nfpmnm_meta_type, 
			$nfpmnm_object_id, 
			$nfpmnm_meta_key, 
			$nfpmnm_meta_value, 
			$nfpmnm_delete_all
			
		);

	}
		
}  else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_plugin_uninstall" already exists');
	
}
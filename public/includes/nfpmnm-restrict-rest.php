<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//get custom title tag
if(!function_exists('nfpmnm_restrict_rest')) {
	
	function nfpmnm_restrict_rest($nfpmnm_rest_errors) {

		//this function is public only
		if(is_admin()){
			
			return;
			
		}
		
		if(
		
			//if user is not logged in or is not an Administrator (not alloewd Administrators ara automatically logged out, so not further check is needed)
			!is_user_logged_in()
			|| !current_user_can('administrator')
			
		){
			
			if(!wp_doing_ajax()){

				//get options 
				global $nfproot_current_language_settings;

				//if Maintenance Mode is enabled and landing page exists
				if(

					!empty($nfproot_current_language_settings['nfpmnm']['nfproot_maintenance_mode'])
					&& $nfproot_current_language_settings['nfpmnm']['nfproot_maintenance_mode'] === '1'
					&& !empty($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'])
					&& is_numeric($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'])
					&& get_post_status($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page']) === 'publish'
										
				) {

					//if restrict REST API is switched on and no error is thrown
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_restrict_rest'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_restrict_rest'] === '1'						
						&& !is_wp_error($nfpmnm_rest_errors)
					
					){ 
					
						//add new error
						$nfpmnm_rest_errors = new WP_Error(
						
							'user_not_allowed',
							__('Website is currently in maintenance mode', 'nutsforpress-maintenance-mode'),
							array(
							
								'status' => rest_authorization_required_code()
								
							)
						);
						
					}

					return $nfpmnm_rest_errors;
					
				}
				
			}
			
		}

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_restrict_rest" already exists');
	
}
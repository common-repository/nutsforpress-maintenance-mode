<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//force logout when Maintenance Mode is switched on and user is not allowed to keep on working
if(!function_exists('nfpmnm_force_logout')) {
	
	function nfpmnm_force_logout() {
		
		//get options 
		global $nfproot_current_language_settings;

		//if Maintenance Mode is enabled and landing page exists
		if(

			!empty($nfproot_current_language_settings['nfpmnm']['nfproot_maintenance_mode'])
			&& $nfproot_current_language_settings['nfpmnm']['nfproot_maintenance_mode'] === '1'
			&& !empty($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'])
			&& is_numeric($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'])
			&& get_post_status($nfproot_current_language_settings['nfpmnm']['nfproot_landing_page']) === 'publish'
								
		){
		
			if(is_user_logged_in()){
					
				$nfpmnm_user_object = wp_get_current_user();
				$nfpmnm_user_id = $nfpmnm_user_object->ID;
				$nfpmnm_user_roles = $nfpmnm_user_object->roles;
				
				$nfpmnm_can_login_on_maintenance = get_user_meta(
				
					$nfpmnm_user_id,
					'_nfpmnm_can_login_on_maintenance',
					true
					
				);
				
				//if user is not allowed
				if($nfpmnm_can_login_on_maintenance !== '1'){

					if(in_array('administrator', $nfpmnm_user_roles)){

						//get administrators allowed to login
						$nfpmnm_login_allowed_users = get_users(   
							
							array(   
							
								'role' => 'administrator',
								'meta_key' => '_nfpmnm_can_login_on_maintenance',
								'meta_value' => '1',		
								'fields' => array(
								
									'ID',
									'display_name',
									'user_email',
								
								)
							
							) 
							
						);		
						
						if(
						
							empty($nfpmnm_login_allowed_users)
							|| !is_array($nfpmnm_login_allowed_users)
							
						){
							
							//if current user is not alloewd but no others administrators are, do not destroy session
							return;
							
						} 						
						
					} 

					//destroy current session
					wp_destroy_current_session();
					
				}
			
			}
			
		}
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_force_logout" already exists');
	
}
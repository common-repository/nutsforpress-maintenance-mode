<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//check after login if user is allowed
if(!function_exists('nfpmnm_check_after_login')) {
	
	function nfpmnm_check_after_login($nfpmnm_user_object) {
		
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
						
						//if current user is not alloewd but no others administrators are, do not block him
						return $nfpmnm_user_object;
						
						
					} else {
				
						//if current user is an Administrator and he is not allowed to login, tell him who are the allowed Administators
						$nfpmnm_logout_message = __('Until Maintenance Mode is active, only these Administrators are allowed to login', 'nutsforpress-maintenance-mode').':';
						
						$nfpmnm_logout_message .= '<ul class="nfpmnm-administrators-list" style="padding:20px;">';

						foreach($nfpmnm_login_allowed_users as $nfpmnm_login_allowed_user){
							
							$nfpmnm_logout_message .= '<li>'.$nfpmnm_login_allowed_user->display_name.' ('.$nfpmnm_login_allowed_user->user_email.')</li>';		
							
						}
						
						$nfpmnm_logout_message .= '</ul>';				

					}							
					
				} else {
					
					//if current user is not an adinistrator and he is a temporary user coming from the NutsForPressChat, do not block him in order to let him enter the chat
					if(in_array('nfpc-user', $nfpmnm_user_roles)){

						return $nfpmnm_user_object;
						
					} else {
					
						//if current user is not an Administratore, display a generic message
						$nfpmnm_logout_message = __('Until Maintenance Mode is active, you are not allowed to login', 'nutsforpress-maintenance-mode');
						
					}
				
					
				}

				//create error
				$nfpmnm_user_object = new WP_Error('user_not_verified', $nfpmnm_logout_message);
				
			}
			
		}
		
		return $nfpmnm_user_object;

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_check_after_login" already exists');
	
}
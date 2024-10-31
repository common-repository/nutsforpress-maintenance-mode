<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//save user profile custom field
if(!function_exists('nfpmnm_save_user_fields')){

	function nfpmnm_save_user_fields($nfpmnm_user_id) {
		
		if(
		
			is_user_logged_in()
			&& is_admin() 
			&& current_user_can('edit_user')
			&& !empty($nfpmnm_user_id)
		
		) {		
						
			if(
			
				isset($_POST['nfpmnm-login-on-maintenance'])
				&& $_POST['nfpmnm-login-on-maintenance'] === '1'

			){		
						
				update_user_meta(
					
					$nfpmnm_user_id, 
					'_nfpmnm_can_login_on_maintenance', 
					'1'
					
				);					
				
			} else {
				
				delete_user_meta(
					
					$nfpmnm_user_id, 
					'_nfpmnm_can_login_on_maintenance'
					
				);	

			}
					
		}
						
	}
		
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_save_user_fields" already exists');

}
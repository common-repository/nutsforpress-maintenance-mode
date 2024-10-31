<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//get custom title tag
if(!function_exists('nfpmnm_redirect')) {
	
	function nfpmnm_redirect() {
		
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
															
					//get current page id
					$nfpmnm_current_page_id = get_the_ID();
					
					//get landing page id
					$nfpmnm_landing_page_id = $nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'];
					
					//if i'm not on the landing page
					if((int)$nfpmnm_current_page_id !== (int)$nfpmnm_landing_page_id){
								
						$nfpmnm_landing_page_url = get_the_permalink($nfpmnm_landing_page_id);
						
						nocache_headers();

						wp_safe_redirect($nfpmnm_landing_page_url, 302);
						
						exit;
						
					//if I'm in the landing page
					} else {
						
						 header($_SERVER["SERVER_PROTOCOL"] . " 503 Service Temporarily Unavailable", true, 503);
						
					}
					
				}
				
			}
			
		}

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_redirect" already exists');
	
}
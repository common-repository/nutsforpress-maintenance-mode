<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//hide sitemap
if(!function_exists('nfpmnm_redirect_sitemap')) {
	
	function nfpmnm_redirect_sitemap() {

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
										
					//if redirect sitemap is switched on
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_redirect_sitemap'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_redirect_sitemap'] === '1'					
					
					){
					
						//define current slug for further use
						$nfproot_current_slug = null;						

						//deactivate WP sitemap
						add_filter('wp_sitemaps_enabled', '__return_false');
						
						//get data for deactivating sitemap.xml too
						global $wp;
						$nfproot_current_slug = $wp->request;	

						//if it is NutsForPress Indexing and SEO sitemap page
						if($nfproot_current_slug === 'sitemap.xml'){
							
							//get landing page id
							$nfpmnm_landing_page_id = $nfproot_current_language_settings['nfpmnm']['nfproot_landing_page'];		
		
							$nfpmnm_landing_page_url = get_the_permalink($nfpmnm_landing_page_id);
							
							nocache_headers();

							wp_safe_redirect($nfpmnm_landing_page_url, 302);
							
							exit;
							
						} 						
						
					}
					
				}
				
			}
			
		}

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_redirect_sitemap" already exists');
	
}
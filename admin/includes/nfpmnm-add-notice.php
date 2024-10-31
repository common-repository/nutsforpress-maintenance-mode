<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//add a notice to the admin bar when Maintenance mode is switched on
if(!function_exists('nfpmnm_add_notice')) {
	
	function nfpmnm_add_notice($nfpmnm_wp_admin_bar) {
		
		if(

			is_admin()
			&& current_user_can('manage_options')
			
		){
			
			//a small script to hide notice when maintenance mode is deactivated
			echo '
			
				<script>

				jQuery(document).ready(function(){
					
					if(jQuery("#nfpmnm_maintenance_mode").length){
					
						jQuery("#nfpmnm_maintenance_mode").on("click", function(){
													
							if(jQuery(this).prop("checked") === true){
								
								jQuery(".nfpmnm-maintenance-notice").css("display","block");
								
							} else {
								
								jQuery(".nfpmnm-maintenance-notice").css("display","none");
								
							}
							
						})	

					}		

					if(jQuery("#nfpmnm_screen_check").length){
					
						jQuery("#nfpmnm_screen_check").on("click", function(){
													
							if(jQuery(this).prop("checked") === true){
								
								jQuery(".nfpmnm-screen-check-notice").css("display","block");
								
							} else {
								
								jQuery(".nfpmnm-screen-check-notice").css("display","none");
								
							}
							
						})	

					}					
					
				})

				</script>
				
			';			

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

				//add node
				$nfpmnm_splash_page_args = array(
				
					'id'    => 'nfpmnm-maintenance-notice',
					'title' => '<span style="color:yellow;">'.__('Maintenance Mode is active','nutsforpress-maintenance-mode').'</span>',
					'href'  => site_url().'/wp-admin/admin.php?page=nfpmnm-settings',
					'meta'  => array('class' => 'nfpmnm-maintenance-notice')
					
				);
				
				$nfpmnm_wp_admin_bar->add_node($nfpmnm_splash_page_args);
				
			}
			
			//if Screen Resolution Check is enabled
			if(

				!empty($nfproot_current_language_settings['nfpmnm']['nfproot_screen_check'])
				&& $nfproot_current_language_settings['nfpmnm']['nfproot_screen_check'] === '1'
									
			) {

				//add node
				$nfpmnm_splash_page_args = array(
				
					'id'    => 'nfpmnm-screen-check-notice',
					'title' => '<span style="color:yellow;">'.__('Screen Resolution Check is active','nutsforpress-maintenance-mode').'</span>',
					'href'  => site_url().'/wp-admin/admin.php?page=nfpmnm-settings',
					'meta'  => array('class' => 'nfpmnm-screen-check-notice')
					
				);
				
				$nfpmnm_wp_admin_bar->add_node($nfpmnm_splash_page_args);
				
			}
			
		}

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_add_notice" already exists');
	
}
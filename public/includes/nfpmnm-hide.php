<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//get custom title tag
if(!function_exists('nfpmnm_hide')) {
	
	function nfpmnm_hide() {

		//this function is public only
		if(is_admin()){
			
			return;
			
		}
		
		if(
		
			//if user is not logged in or is not an administrator (not allowed administrators ara automatically logged out, so not further check is needed)
			!is_user_logged_in()
			|| !current_user_can('administrator')
			
		){
			
			if(!wp_doing_ajax()){

				//get options 
				global $nfproot_current_language_settings;

				//if screen resolution check is enabled
				if(

					!empty($nfproot_current_language_settings['nfpmnm']['nfproot_screen_check'])
					&& $nfproot_current_language_settings['nfpmnm']['nfproot_screen_check'] === '1'
										
				) {

					//firstly hide the content , then eventually show it if the resolution is not subjet to restriction
					echo '<style>
					
						body > * {
							opacity: 0;
						}
						
					</style>';					
					
					$nfpmnm_devices = array();					
																			
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_smartphone'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_smartphone'] === '1'					
					
					){
						
						echo ' <script>
											
							jQuery(document).ready(function() {
								
								nfpmnmCheckResolution(768, 0);
								
							});
							
							jQuery(window).resize(function(){
									
								nfpmnmCheckResolution(768, 0);
									
							});
							
						</script>';								
						
					} else {
						
						$nfpmnm_devices[] = __('Smartphone','nutsforpress-maintenance-mode');
						
					}
					
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_tablet'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_tablet'] === '1'
					
					){

						echo ' <script>
						
							jQuery(document).ready(function() {
															
								nfpmnmCheckResolution(1024, 768);
								
							});
							
							jQuery(window).resize(function(){
							
								nfpmnmCheckResolution(1024, 768);
									
							});		
														
						</script>';								
						
					} else {
						
						$nfpmnm_devices[] = __('Tablet','nutsforpress-maintenance-mode');
						
					}
					
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_netbook'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_netbook'] === '1'			
					
					){
						
						echo ' <script>
														
							jQuery(document).ready(function() {
								
								nfpmnmCheckResolution(1200, 1024);
								
							});
							
							jQuery(window).resize(function(){
									
								nfpmnmCheckResolution(1200, 1024);
									
							});
							
						</script>';	
						
					} else {
						
						$nfpmnm_devices[] = __('Netbook','nutsforpress-maintenance-mode');
						
					}
					
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_notebook'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_notebook'] === '1'					
					
					){
						
						echo ' <script>
							
							jQuery(document).ready(function() {
																
								nfpmnmCheckResolution(1600, 1200);
								
							});
							
							jQuery(window).resize(function(){
									
								nfpmnmCheckResolution(1600, 1200);
									
							});						
							
						</script>';								
					
					} else {
						
						$nfpmnm_devices[] = __('Notebook','nutsforpress-maintenance-mode');
						
					}
						
					if(
					
						!empty($nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_desktop'])
						&& $nfproot_current_language_settings['nfpmnm']['nfproot_hide_to_desktop'] === '1'					
					
					){
						
						echo ' <script>
							
							jQuery(document).ready(function() {
								
								nfpmnmCheckResolution(9999, 1600);
								
							});
							
							jQuery(window).resize(function(){
									
								nfpmnmCheckResolution(9999, 1600);
									
							});								
							
						</script>';								
					
					} else {
						
						$nfpmnm_devices[] = __('Laptop and Desktop','nutsforpress-maintenance-mode');
						
					}
					
					$nfpmnm_warning_text = __('Website currently under development: your device screen resolution is not supported yet','nutsforpress-maintenance-mode').'. ';
					
					$nfpmnm_device_list = __('Devices supported at this stage','nutsforpress-maintenance-mode').': ';
					
					$nfpmnm_count_devices = count($nfpmnm_devices);
					
					$nfpmnm_device_loop = 1;
					
					foreach($nfpmnm_devices as $nfpmnm_device){
						
						$nfpmnm_device_list .= $nfpmnm_device;
						
						if($nfpmnm_device_loop < $nfpmnm_count_devices){
							
							$nfpmnm_device_list .= ', ';
							
						} else {
							
							$nfpmnm_device_list .= '.';
						}
						
						$nfpmnm_device_loop++;
					}
				
					echo ' <script>
					
						let nfpmnmWarningText = "'.$nfpmnm_warning_text.'";
						let nfpmnmDeviceList = "'.$nfpmnm_device_list.'";
					
						jQuery(document).ready(function() {
														
							nfpmnmWarning(nfpmnmWarningText,nfpmnmDeviceList);
								
						});
						
						jQuery(window).resize(function(){
															
							nfpmnmWarning(nfpmnmWarningText,nfpmnmDeviceList);
								
						});						
												
					</script>';	
	
				}
				
			}
			
		}

	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_hide" already exists');
	
}
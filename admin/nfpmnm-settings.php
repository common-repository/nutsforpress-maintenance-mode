<?php
//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//with this function we will create the NutsForPress menu page
if(!function_exists('nfpmnm_settings')) {
	
	function nfpmnm_settings() {	
		
		global $nfproot_plugins_settings;
		$nfpmnm_pro = null;
		
		if(
		
			!empty($nfproot_plugins_settings) 
			&& !empty($nfproot_plugins_settings['installed_plugins']['nfpmnm']['edition'])
			&& $nfproot_plugins_settings['installed_plugins']['nfpmnm']['edition'] === 'registered'
			
		) {
			
			$nfpmnm_pro = ' <span class="dashicons dashicons-saved"></span>';
			
		}
		
		add_submenu_page(
	
			'nfproot-settings',
			'Maintenance Mode',
			'Maintenance Mode'.$nfpmnm_pro,
			'manage_options',
			'nfpmnm-settings',
			'nfpmnm_settings_callback'
		
		);
		
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_base_options" already exists');
	
}
	
//with this function we will define the NutsForPress menu page content
if(!function_exists('nfpmnm_settings_callback')) {
	
	function nfpmnm_settings_callback() {
		
		?>
		
		<div class="wrap nfproot-settings-wrap">
			
			<h1>Maintenance Mode settings</h1>
			
			<div class="nfproot-settings-main-container">
		
				<?php
				
				//include option content page
				require_once NFPMNM_BASE_PATH.'admin/nfpmnm-settings-content.php';
				
				//define contents as result of the function nfpmnm_settings_content
				$nfpmnm_settings_content = nfpmnm_settings_content();
				
				//invoke nfproot_options_structure functions included into /root/options/nfproot-options-structure.php
				nfproot_settings_structure($nfpmnm_settings_content);
				
				?>
			
			</div>
		
		</div>
		
		<?php
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_settings" already exists');
	
}
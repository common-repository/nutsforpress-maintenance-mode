<?php
//if this file is called directly, die.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//define a function to be used further
if(!function_exists('nfpmnm_post_type_to_include')) {
	
	function nfpmnm_post_type_to_include() {

		$nfpmnm_registered_post_types_args = array(
		
			'exclude_from_search' => false,
			'public'   => true,
			'_builtin' => false,
			'publicly_queryable' => true
			
		);
			
		$nfpmnm_registered_post_types = get_post_types($nfpmnm_registered_post_types_args);
	
		//define builtin post types
		$nfpmnm_post_types_to_search = array('post','page');
		
		foreach($nfpmnm_registered_post_types as $nfpmnm_registered_post_type){
			
			//add custom post types
			$nfpmnm_post_types_to_search[] = $nfpmnm_registered_post_type;
			
		}
		
		return $nfpmnm_post_types_to_search;
		
	}
		
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_post_type_to_include" already exists');
	
}
	
//with this function we will define the NutsForPress menu page content
if(!function_exists('nfpmnm_settings_content')) {
	
	function nfpmnm_settings_content() {

		//create steps for page dropdown
		$nfpmnm_page_dropdown_values = array();
		$nfpmnm_page_dropdown_step = 1;
				
		$nfpmnm_home_page_id = get_option('page_on_front');
				
		if(!empty($nfpmnm_home_page_id)) {
			
			$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-value'] = $nfpmnm_home_page_id;
			$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-text'] = 'Homepage (id: '.$nfpmnm_home_page_id.')';
			$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-selected'] = 'selected';
			$nfpmnm_page_dropdown_step++;
			
		}
		
		$nfpmnm_pages_query_args = array(
		
			'post_type' => nfpmnm_post_type_to_include(),
			'post_status' => array('publish'),
			'post__not_in' => array($nfpmnm_home_page_id),
			'orderby' => 'post_title',
			'order' => 'asc',
			'posts_per_page' => -1,	
			'meta_query' => array(
			
				'relation' => 'AND',
								
				array(
				
					'key' => '_nfpmnm_is_restricted',
					'compare' => 'NOT EXISTS'
				),

				array(
				
					'key' => '_rsmd_is_restricted',
					'compare' => 'NOT EXISTS'
					
				)
				
			)
						
		);
		 
		$nfpmnm_pages_query = new WP_Query($nfpmnm_pages_query_args);
		 
		if($nfpmnm_pages_query->have_posts()){

			while($nfpmnm_pages_query->have_posts()) {
				
				$nfpmnm_pages_query->the_post();
				
				$nfpmnm_page_id = get_the_ID();
				$nfpmnm_page_title = get_the_title();
				
				$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-value'] = $nfpmnm_page_id;
				$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-text'] = $nfpmnm_page_title.' (id: '.$nfpmnm_page_id.')';
				$nfpmnm_page_dropdown_values[$nfpmnm_page_dropdown_step]['option-selected'] = null;
				$nfpmnm_page_dropdown_step++;
				
			}
			
		} 
		
		wp_reset_postdata();
				
		//create steps for allowed administrators list
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
			
			$nfpmnm_administrators_allowed_list = __('All the administrators are currently allowed to login; if you want to allow to login only some of them, please move to their profiles and find the "Allow Login" checkbox', 'nutsforpress-maintenance-mode');
			
			
		} else {
			
			$nfpmnm_administrators_allowed_list = '<ul class="nfpmnm-administrators-list">';
			
			foreach($nfpmnm_login_allowed_users as $nfpmnm_login_allowed_user){
				
				$nfpmnm_login_allowed_user_profile_url = get_edit_user_link($nfpmnm_login_allowed_user->ID);
				
				$nfpmnm_administrators_allowed_list .= '<li><a href="'.$nfpmnm_login_allowed_user_profile_url.'#nfpmnm-login-on-maintenance">'.$nfpmnm_login_allowed_user->display_name.' ('.$nfpmnm_login_allowed_user->user_email.')</a></li>';				
			}
			
			$nfpmnm_administrators_allowed_list .= '</ul>';
			
			
		}
	
		$nfpmnm_settings_content = array(
		
			array(
			
				'container-title'	=> __('Maintenance Mode','nutsforpress-maintenance-mode'),
				
				'container-id'		=> 'nfpmnm_maintenance_mode_container',
				'container-class' 	=> 'nfpmnm-maintenance-mode-container',
				'input-name'		=> 'nfproot_maintenance_mode',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfpmnm',
				'input-id'			=> 'nfpmnm_maintenance_mode',
				'input-class'		=> 'nfpmnm-maintenance-mode',
				'input-description'	=> __('If switched on, all the visitors that are not logged in as administrator will be redirected to the page defined below','nutsforpress-maintenance-mode'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
				
				'childs'			=> array(

					array(
						
						'container-title'	=> __('Landing Page','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_landing_page_container',
						'container-class' 	=> 'nfpmnm-landing-page-container',					
						'input-name' 		=> 'nfproot_landing_page',
						'add-to-settings'	=> 'local',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_landing_page',
						'input-class'		=> 'nfpmnm-landing-page',
						'input-description' => __('Select the page you want to redirect the visitors that are not logged in as administrator','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'dropdown',
						'input-value'		=> $nfpmnm_page_dropdown_values,
						
					),
					
					array(
						
						'container-title'	=> __('Redirect Sitemap','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_redirect_sitemap_container',
						'container-class' 	=> 'nfpmnm-redirect-sitemap-container',					
						'input-name' 		=> 'nfproot_redirect_sitemap',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_redirect_sitemap',
						'input-class'		=> 'nfpmnm-redirect-sitemap',
						'input-description' => __('If switched on, the default WordPress sitemap page and the "NutsForPress Indexing and SEO" sitemap page will redirect all the visitors that are not logged in as administrator to the above page too','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
						
						'container-title'	=> __('Hide REST API','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_restrict_rest_container',
						'container-class' 	=> 'nfpmnm-restrict-rest-container',					
						'input-name' 		=> 'nfproot_restrict_rest',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_restrict_rest',
						'input-class'		=> 'nfpmnm-restrict-rest',
						'input-description' => __('If switched on, the REST API pages will show an authentication error for all the visitors that are not logged in as administrator','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
				),
				
			),
			
			array(
			
				'container-title'	=> __('Screen Resolution Check','nutsforpress-maintenance-mode'),
				
				'container-id'		=> 'nfpmnm_screen_check_container',
				'container-class' 	=> 'nfpmnm-screen-check-container',
				'input-name'		=> 'nfproot_screen_check',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfpmnm',
				'input-id'			=> 'nfpmnm_screen_check',
				'input-class'		=> 'nfpmnm-screen-check',
				'input-description'	=> __('If switched on, all the visitors that are not logged in as administrator will be prevented from browsing the website from devices with the screen resolutions defined below','nutsforpress-maintenance-mode'),
				'arrow-before'		=> true,
				'after-input'		=> '',
				'input-type' 		=> 'switch',
				'input-value'		=> '1',
				
				'childs'			=> array(

					array(
						
						'container-title'	=> __('Hide to Smartphone','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_hide_to_smartphone_container',
						'container-class' 	=> 'nfpmnm-hide-to-smartphone-container',					
						'input-name' 		=> 'nfproot_hide_to_smartphone',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_hide_to_smartphone',
						'input-class'		=> 'nfpmnm-hide-to-smartphone',
						'input-description' => __('If switched on, Smartphone devices (< 768px) will be prevented from browsing this website','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
						
						'container-title'	=> __('Hide to Tablet','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_hide_to_tablet_container',
						'container-class' 	=> 'nfpmnm-hide-to-tablet-container',					
						'input-name' 		=> 'nfproot_hide_to_tablet',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_hide_to_tablet',
						'input-class'		=> 'nfpmnm-hide-to-tablet',
						'input-description' => __('If switched on, Tablet devices (>=768px | <1024px) will be prevented from browsing this website','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
						
						'container-title'	=> __('Hide to Netbook','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_hide_to_netbook_container',
						'container-class' 	=> 'nfpmnm-hide-to-netbook-container',					
						'input-name' 		=> 'nfproot_hide_to_netbook',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_hide_to_netbook',
						'input-class'		=> 'nfpmnm-hide-to-netbook',
						'input-description' => __('If switched on, Netbook devices (>=1024px | <1200px) will be prevented from browsing this website','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
						
						'container-title'	=> __('Hide to Notebook','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_hide_to_notebook_container',
						'container-class' 	=> 'nfpmnm-hide-to-notebook-container',					
						'input-name' 		=> 'nfproot_hide_to_notebook',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_hide_to_notebook',
						'input-class'		=> 'nfpmnm-hide-to-notebook',
						'input-description' => __('If switched on, Notebook devices (>=1200px | <1600px) will be prevented from browsing this website','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
					array(
						
						'container-title'	=> __('Hide to Laptop and Desktop','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_hide_to_desktop_container',
						'container-class' 	=> 'nfpmnm-hide-to-desktop-container',					
						'input-name' 		=> 'nfproot_hide_to_desktop',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_hide_to_desktop',
						'input-class'		=> 'nfpmnm-hide-to-desktop',
						'input-description' => __('If switched on, Laptop and Desktop devices (>=1600px) will be prevented from browsing this website','nutsforpress-maintenance-mode'),
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'switch',
						'input-value'		=> '1',
						
					),
					
				),
				
			),
			
			array(
			
				'container-title'	=> __('Administrators allowed to login and browsing','nutsforpress-maintenance-mode'),
				
				'container-id'		=> 'nfpmnm_administrators_allowed_container',
				'container-class' 	=> 'nfpmnm-administrators-allowed-container',
				'input-name'		=> 'nfproot_administrators_allowed',
				'add-to-settings'	=> 'global',
				'data-save'			=> 'nfpmnm',
				'input-id'			=> 'nfpmnm_administrators_allowed',
				'input-class'		=> 'nfpmnm-administrators-allowed',
				'input-description'	=> false,
				'arrow-before'		=> true,
				'after-input'		=> array(
				
					array(
					
						'type' 		=> 'paragraph',
						'id' 		=> 'nfpmnm_administrators_allowed_description',
						'class' 	=> 'nfproot-after-input nfpmnm-administrators-allowed-description',
						'hidden' 	=> false,
						'content' 	=> __('Click on the arrow to get a list of the administrators currently allowed to login when Maintenance Mode is switched on and allowed to browse the website even if the Screen Resolution Check is switched on','nutsforpress-maintenance-mode'),
						'value'		=> ''
					
					),
				
				),
				
				'input-type' 		=> false,
				'childs'			=> array(
					
					array(
					
						'container-title'	=> __('Administrators allowed list','nutsforpress-maintenance-mode'),
					
						'container-id'		=> 'nfpmnm_administrators_allowed_list_container',
						'container-class' 	=> 'nfpmnm-administrators-allowed-list-container',					
						'input-name' 		=> 'nfproot_administrators_allowed_list',
						'add-to-settings'	=> 'global',
						'data-save'			=> 'nfpmnm',
						'input-id' 			=> 'nfpmnm_administrators_allowed_list',
						'input-class'		=> 'nfpmnm-administrators-allowed-list',
						'input-description' => false,
						'arrow-before'		=> false,
						'after-input'		=> '',
						'input-type' 		=> 'textonly',
						'input-value'		=> $nfpmnm_administrators_allowed_list,
						
					),
					
				),
				
			),
				
		);
						
		return $nfpmnm_settings_content;
		
	}
	
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_settings_content" already exists');
	
}
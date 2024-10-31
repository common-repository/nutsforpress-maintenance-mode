<?php
 //if this file is called directly, abort.
if(!defined('ABSPATH')) die('please, do not call this page directly');

//add a custom field to user profile page
if(!function_exists('nfpmnm_add_user_fields')){

	function nfpmnm_add_user_fields($nfpmnm_user) {
		
		if(
		
			is_user_logged_in()
			&& is_admin() 
			&& current_user_can('edit_user')
			&& !empty($nfpmnm_user)
			&& is_a($nfpmnm_user, 'WP_User')
		
		) {		
		
			$nfpmnm_can_login_on_maintenance = null;
			
			if(!empty($nfpmnm_user_id = $nfpmnm_user->ID)){
				
				$nfpmnm_can_login_on_maintenance = get_user_meta(
				
					$nfpmnm_user_id, 
					'_nfpmnm_can_login_on_maintenance', 
					true
					
				);
				
			}

			?>
			<h2><?php echo __('Login on Maintenance Mode', 'nutsforpress-maintenance-mode'); ?></h2>

			<table class="form-table">
				<tbody>
					
					<tr>
						<th><label for="nfpmnm-allow-login"><?php echo __('Allow Login', 'nutsforpress-maintenance-mode'); ?></label></th>
						<td>
													
							<?php
							
							$nfpmnm_structure_checked = null;
							
							if($nfpmnm_can_login_on_maintenance === '1') {
							
								$nfpmnm_structure_checked = 'checked';
								
							}
							
							echo '<input type="checkbox" id="nfpmnm-login-on-maintenance" name="nfpmnm-login-on-maintenance" value="1" '.$nfpmnm_structure_checked.'>';
							echo '<label for="nfpmnm-login-on-maintenance">'.__('This Administrator can keep on working and login even when Maintenance Mode or Screen Resolution Check is switched on', 'nutsforpress-maintenance-mode').'</label>';
							echo '<p style="margin-top: 15px"><em>'.__('If no Administrators have this checkbox flagged, all of them can keep on working and login even if the Maintenance Mode or the Screen Resolution Check is switched on; if one or some Administrators have this checkbox flagged, the others are instantly logged out and they are also prevented from login', 'nutsforpress-maintenance-mode').'.</em></p>';
								
							?>
						
						
						</td>
					</tr>
				
				</tbody>
			</table>		
			
			<?php
			
		}
						
	}
		
} else {
	
	error_log('NUTSFORPRESS ERROR: function "nfpmnm_add_user_fields" already exists');

}
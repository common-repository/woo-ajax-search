<?php
add_action( 'admin_menu', 'woosearch_admin_menu' );
add_action( 'admin_init', 'woosearch_settings_save');
// Menu and Sub Menu Page Add
function woosearch_admin_menu(){
	add_options_page(  	
						__( 'Woo AJAX Search', 'woo-ajax-search' ),
						__( 'Woo AJAX Search', 'woo-ajax-search' ),
						'administrator',
						'woosearch',
						'woosearch_general_settings'
					);
}
function woosearch_settings_save() {
	register_setting( 'woosearch-settings', 'woosearch_product_num' );
	register_setting( 'woosearch-settings', 'woosearch_keypress' );
	register_setting( 'woosearch-settings', 'woosearch_category' );
	register_setting( 'woosearch-settings', 'woosearch_redirect' );
}

// General Settings Callback
function woosearch_general_settings(){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( 'You do not have sufficient permissions to access this page.' );
	}
?>
<div class="wrap">
	<h2><?php _e('Woo AJAX Search Settings','woo-ajax-search'); ?></h2>
	<form method="post" action="options.php">
	<?php settings_fields( 'woosearch-settings' ); ?>
	<?php do_settings_sections( 'woosearch-settings' ); ?>
	<table class="form-table">

		<tr valign="top">
			<th scope="row"><?php _e('Number of Products Show','woo-ajax-search'); ?> : </th>
			<?php $woosearch_product_num = esc_attr( get_option('woosearch_product_num','5') ); ?>
			<td>
				<input type="number" name="woosearch_product_num" value="<?php echo $woosearch_product_num; ?>"/>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('After Number of Keypress Result Show','woo-ajax-search'); ?> : </th>
			<?php $woosearch_keypress = esc_attr( get_option('woosearch_keypress','2') ); ?>
			<td>
				<input type="number" name="woosearch_keypress" value="<?php echo $woosearch_keypress; ?>"/>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Show Category with Search','woo-ajax-search'); ?> : </th>
			<?php $woosearch_category = esc_attr( get_option('woosearch_category','0') ); ?>
			<td>
				<input type="checkbox" name="woosearch_category" value="1" <?php checked( $woosearch_category ); ?> />
			</td>
		</tr>

		<tr valign="top">
			<th scope="row"><?php _e('Default Search Page Redirect Off','woo-ajax-search'); ?> : </th>
			<?php $woosearch_redirect = esc_attr( get_option('woosearch_redirect','0') ); ?>
			<td>
				<input type="checkbox" name="woosearch_redirect" value="1" <?php checked( $woosearch_redirect ); ?> />
			</td>
		</tr>

	</table>
	<?php submit_button(); ?>
	</form>
</div>
<?php } 


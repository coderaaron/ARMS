<?php
/*
 * Retrieve these settings on front end in either of these ways:
 *   $my_setting = cmb2_get_option( A_TEXTDOMAIN . '-settings', 'some_setting', 'default' );
 *   $my_settings = get_option( A_TEXTDOMAIN . '-settings', 'default too' );
 * CMB2 Snippet: https://github.com/CMB2/CMB2-Snippet-Library/blob/master/options-and-settings-pages/theme-options-cmb.php
 */
?>
<div id="tabs-1" class="wrap">
	<form method="post" action="">
		<?php wp_nonce_field( 'my_plugin_action', 'my_plugin_nonce' ); ?>
		<input type="submit" name="arms_rerun_activation" value="Rerun Activation" />
	</form>
</div>

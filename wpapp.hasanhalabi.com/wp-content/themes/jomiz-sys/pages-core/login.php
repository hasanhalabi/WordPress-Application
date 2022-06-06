<?php
/*
 * Template Name: Login Page
 */

// check if user is already logged in

/*
 * if (is_user_logged_in()) {
 * wp_redirect(home_url());
 * }
 */

// Register JoMiz System Settings
jomiz_plugin_system_settings::register_global_object ();
global $jomizSystemSettings;

get_header ( "empty" );
?>
<div class="middle-box text-center loginscreen animated fadeInDown">
	<div>
		<div>
			<h1 class="logo-name"></h1>
			<img src="<?php echo $jomizSystemSettings->__get('logoLogin') ?>"
				alt="<?php printf('%1$s - %2$s', get_bloginfo('name'), get_bloginfo('description')) ?>" />
		</div>
		<?php if(!(defined('HIDE_LOGIN_DESC') && HIDE_LOGIN_DESC == true)): ?>
		<h3><?php bloginfo('name') ?></h3>
		<p>
				<?php bloginfo('description')?>
			</p>
			<?php
			endif;
			$args = array (
					'echo' => true,
					'remember' => FALSE,
					'redirect' => home_url (),
					'form_id' => 'loginform',
					'id_username' => 'user_login',
					'id_password' => 'user_pass',
					'id_remember' => 'rememberme',
					'id_submit' => 'wp-submit',
					'label_username' => __ ( 'Username' ),
					'label_password' => __ ( 'Password' ),
					'label_remember' => __ ( 'Remember Me' ),
					'label_log_in' => __ ( 'Log In' ),
					'value_username' => '',
					'value_remember' => false 
			);
			wp_login_form ( $args );
			
			if (isset ( $_GET ['failed'] )) {
				printf('<p class="text-danger text-center" style="font-size:15px">%1$s</p>','معلومات التسجيل غير صحيحة!');
				printf('<p class="text-danger text-center" style="font-size:14px">%1$s</p>','.الرجاء المحاولة مرة اخرى او الاتصال بمدير النظام');
			}
			?>
		</div>
</div>
<script>
		jQuery(document).ready(function() {
			jQuery('#loginform').addClass('m-t');
			jQuery('.login-username').addClass('form-group');
			jQuery('.login-password').addClass('form-group');

			jQuery('.form-group').find('input').addClass('form-control');
			jQuery('.form-group').find('input').prop("required", true);

			jQuery('#user_login').attr('placeholder', jQuery('label[for="user_login"]').text());
			jQuery('#user_pass').attr('placeholder', jQuery('label[for="user_pass"]').text());
			jQuery('label').hide();

			jQuery('#wp-submit').addClass('btn btn-primary block full-width m-b');
		});
	</script>
<?php
get_footer ( "empty" );
?>

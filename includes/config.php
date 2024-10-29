<?php defined( 'ABSPATH' ) or die( 'No script kiddies please!' ); ?> 

<div class="wrap">
	<h1><?php esc_html_e('Settings ASD Cookie Consent', 'asd-cookie-consent'); ?></h1>
	<form method="POST" action="options.php">
		<?php settings_fields( 'general_setting' ); ?>
		<?php do_settings_sections( 'asd-cookie-consent' ); ?>
		<p class="submit">
			<?php submit_button( esc_attr__('Save change', 'asd-cookie-consent'), 'primary', 'submit', false); ?>
			<?php submit_button( esc_attr__('Default', 'asd-cookie-consent'), 'secondary', 'reset', false ); ?>
		</p>
	</form>
</div>

<style>
	.wp-scanner-form .status {
		display: inline-block;
		padding: 3px 6px;
		color: #fff;
		font-size: 12px;
		font-weight: bold;
		text-transform: uppercase;
	}

	.wp-scanner-form .spinner {
		float: none;
		margin-top: 0;
	}

	.wp-scanner-form .connected {
		background: #32cd32;
	}

	.wp-scanner-form .disconnected {
		background: red;
	}

	.wp-scanner-form .submit .button {
		margin-right: 5px;
	}
</style>
<div class="wrap">
	<h1>WP Scanner</h1>

	<p>
		<?php $link = sprintf( '<a href="https://wpscanner.io" target="_blank">%s</a>', __( 'wpscanner.io', 'wp-scanner' ) ); ?>
		<?php printf( __( "Monitor your site's performance and security by creating a <strong>free</strong> account at %s.", 'wp-scanner' ), $link ); ?>
	</p>

	<form action="options.php" method="post" class="wp-scanner-form">
		<?php if ( $this->settings->is_secret_set() ) : ?>
			<input type="hidden" name="<?php echo $this->settings->get_field_name( 'delete' ); ?>" value="1">
		<?php endif; ?>

		<?php
		settings_fields( $this->settings->group );
		do_settings_sections( $this->settings->group );
		?>

		<p class="submit">
			<?php if ( $this->settings->is_secret_set() ) : ?>
				<button class="button button-primary"><?php _e( 'Delete Keys', 'wp-scanner' ); ?></button>
				<button class="button button-secondary" id="wp-scanner-refresh"><?php _e( 'Refresh Status' ); ?></button>
			<?php else: ?>
				<button class="button button-primary"><?php _e( 'Save Keys', 'wp-scanner' ); ?></button>
			<?php endif; ?>
		</p>
	</form>
</div>
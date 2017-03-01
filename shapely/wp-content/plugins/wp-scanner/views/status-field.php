<span class="status <?php echo ( $this->settings->get( 'active', false ) ) ? 'connected' : 'disconnected'; ?>">
	<?php echo ( $this->settings->get( 'active', false ) ) ? __( 'Connected', 'wp-scanner' ) : __( 'Disconnected', 'wp-scanner' ); ?>
</span>
<span class="spinner"></span>
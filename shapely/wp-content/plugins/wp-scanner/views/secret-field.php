<?php
$readonly = ( $this->settings->is_secret_set() ) ? 'readonly' : '';
?>
<input name="<?php echo $this->settings->get_field_name( 'secret' ); ?>" type="text" id="secret" value="<?php echo $this->settings->get_masked_secret(); ?>" class="regular-text" <?php echo $readonly; ?>>
<?php
$readonly = ( $this->settings->is_secret_set() ) ? 'readonly' : '';
?>
<input name="<?php echo $this->settings->get_field_name( 'key' ); ?>" type="text" id="key" value="<?php echo $this->settings->get( 'key' ); ?>" class="regular-text" <?php echo $readonly; ?>>
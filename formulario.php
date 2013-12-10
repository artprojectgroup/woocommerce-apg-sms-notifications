<?php global $apg_sms; ?>

<div class="wrap woocommerce">
  <div id="icon-woocommerce" class="icon32 icon32-woocommerce-settings">&nbsp;</div>
  <h2>
    <?php _e('APG SMS Notifications Options.', 'apg_sms'); ?>
  </h2>
  <hr />
  <?php 
		settings_errors(); 
		$tab = 1;
		$configuracion = get_option('apg_sms_settings');
  ?>
  <h3><a href="<?php echo $apg_sms['plugin_url']; ?>" title="Art Project Group"><?php echo $apg_sms['plugin']; ?></a></h3>
  <p>
    <?php _e('Add to WooCommerce the possibility to send <abbr title="Short Message Service" lang="en">SMS</abbr> notifications to the client each time you change the order status. Notifies the owner, if desired, when the store has a new order. You can also send customer notes.', 'apg_sms'); ?>
  </p>
  <?php include('cuadro-donacion.php'); ?>
  <form method="post" action="options.php">
    <?php settings_fields('apg_sms_settings_group'); ?>
    <div class="cabecera"> <a href="<?php echo $apg_sms['plugin_url']; ?>" title="<?php echo $apg_sms['plugin']; ?>"><img src="<?php echo $apg_sms['imagen']; ?>" width="582" height="139" /></a> </div>
    <table class="form-table">
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[servicio]">
            <?php _e('<abbr title="Short Message Service" lang="en">SMS</abbr> gateway:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('Select your SMS gateway', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><select id="apg_sms_settings[servicio]" name="apg_sms_settings[servicio]" tabindex="<?php echo $tab++; ?>">
            <?php
            $proveedores = array("solutions_infini" => "Solutions Infini", "twillio" => "Twillio", "clickatell" => "Clickatell", "clockwork" => "Clockwork", "bulksms" => "BulkSMS", "open_dnd" => "OPEN DND");
            foreach ($proveedores as $valor => $proveedor) 
            {
				$chequea = '';
				if (isset($configuracion['servicio']) && $configuracion['servicio'] == $valor) $chequea = ' selected="selected"';
				echo '<option value="' . $valor . '"' . $chequea . '>' . $proveedor . '</option>' . PHP_EOL;
            }
            ?>
          </select></td>
      </tr>
      <tr valign="top" class="solutions_infini"><!-- Solutions Infini -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[clave_solutions_infini]">
            <?php _e('Key:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('key', 'apg_sms'), "Solutions Infini"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[clave_solutions_infini]" name="apg_sms_settings[clave_solutions_infini]" size="50" value="<?php echo (isset($configuracion['clave_solutions_infini']) ? $configuracion['clave_solutions_infini'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="solutions_infini"><!-- Solutions Infini -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[identificador_solutions_infini]">
            <?php _e('Sender ID:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('sender ID', 'apg_sms'), "Solutions Infini"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[identificador_solutions_infini]" name="apg_sms_settings[identificador_solutions_infini]" size="50" value="<?php echo (isset($configuracion['identificador_solutions_infini']) ? $configuracion['identificador_solutions_infini'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="twillio"><!-- Twillio -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[clave_twillio]">
            <?php _e('Account Sid:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('account Sid', 'apg_sms'), "Twillio"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[clave_twillio]" name="apg_sms_settings[clave_twillio]" size="50" value="<?php echo (isset($configuracion['clave_twillio']) ? $configuracion['clave_twillio'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="twillio"><!-- Twillio -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[identificador_twillio]">
            <?php _e('Auth Token:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('auth token', 'apg_sms'), "Twillio"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[identificador_twillio]" name="apg_sms_settings[identificador_twillio]" size="50" value="<?php echo (isset($configuracion['identificador_twillio']) ? $configuracion['identificador_twillio'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="clickatell"><!-- Clickatell -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[identificador_clickatell]">
            <?php _e('Sender ID:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('sender ID', 'apg_sms'), "Clickatell"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[identificador_clickatell]" name="apg_sms_settings[identificador_clickatell]" size="50" value="<?php echo (isset($configuracion['identificador_clickatell']) ? $configuracion['identificador_clickatell'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="clickatell"><!-- Clickatell -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[usuario_clickatell]">
            <?php _e('Username:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('username', 'apg_sms'), "Clickatell"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[usuario_clickatell]" name="apg_sms_settings[usuario_clickatell]" size="50" value="<?php echo (isset($configuracion['usuario_clickatell']) ? $configuracion['usuario_clickatell'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="clickatell"><!-- Clickatell -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[contrasena_clickatell]">
            <?php _e('Password:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('password', 'apg_sms'), "Clickatell"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[contrasena_clickatell]" name="apg_sms_settings[contrasena_clickatell]" size="50" value="<?php echo (isset($configuracion['contrasena_clickatell']) ? $configuracion['contrasena_clickatell'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="clockwork"><!-- Clockwork -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[identificador_clockwork]">
            <?php _e('Key:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('key', 'apg_sms'), "Clockwork"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[identificador_clockwork]" name="apg_sms_settings[identificador_clockwork]" size="50" value="<?php echo (isset($configuracion['identificador_clockwork']) ? $configuracion['identificador_clockwork'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="bulksms"><!-- BulkSMS -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[usuario_bulksms]">
            <?php _e('Username:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('username', 'apg_sms'), "BulkSMS"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[usuario_bulksms]" name="apg_sms_settings[usuario_bulksms]" size="50" value="<?php echo (isset($configuracion['usuario_bulksms']) ? $configuracion['usuario_bulksms'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="bulksms"><!-- BulkSMS -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[contrasena_bulksms]">
            <?php _e('Password:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('password', 'apg_sms'), "BulkSMS"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[contrasena_bulksms]" name="apg_sms_settings[contrasena_bulksms]" size="50" value="<?php echo (isset($configuracion['contrasena_bulksms']) ? $configuracion['contrasena_bulksms'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="open_dnd"><!-- OPEN DND -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[identificador_open_dnd]">
            <?php _e('Sender ID:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('sender ID', 'apg_sms'), "OPEN DND"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[identificador_open_dnd]" name="apg_sms_settings[identificador_open_dnd]" size="50" value="<?php echo (isset($configuracion['identificador_open_dnd']) ? $configuracion['identificador_open_dnd'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="open_dnd"><!-- OPEN DND -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[usuario_open_dnd]">
            <?php _e('Username:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('username', 'apg_sms'), "OPEN DND"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[usuario_open_dnd]" name="apg_sms_settings[usuario_open_dnd]" size="50" value="<?php echo (isset($configuracion['usuario_open_dnd']) ? $configuracion['usuario_open_dnd'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="open_dnd"><!-- OPEN DND -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[contrasena_open_dnd]">
            <?php _e('Password:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php echo sprintf(__('The %s for your account in %s', 'apg_sms'), __('password', 'apg_sms'), "OPEN DND"); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[contrasena_open_dnd]" name="apg_sms_settings[contrasena_open_dnd]" size="50" value="<?php echo (isset($configuracion['contrasena_open_dnd']) ? $configuracion['contrasena_open_dnd'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[telefono]">
            <?php _e('Your mobile number:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('The mobile number registered in your SMS gateway account and where you receive the SMS messages', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="number" id="apg_sms_settings[telefono]" name="apg_sms_settings[telefono]" size="50" value="<?php echo (isset($configuracion['telefono']) ? $configuracion['telefono'] : ''); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[notificacion]">
            <?php _e('New order notification:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e("Check if you want to receive a SMS message when there's a new order", 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[notificacion]" name="apg_sms_settings[notificacion]" type="checkbox" value="1" <?php echo (isset($configuracion['notificacion']) && $configuracion['notificacion'] == "1" ? 'checked="checked"' : ''); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[internacional]">
            <?php _e('Send international <abbr title="Short Message Service" lang="en">SMS</abbr>?:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('Check if you want to send international SMS messages', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[internacional]" name="apg_sms_settings[internacional]" type="checkbox" value="1" <?php echo (isset($configuracion['internacional']) && $configuracion['internacional'] == "1" ? 'checked="checked"' : ''); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_pedido]">
            <?php _e('Owner custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_pedido]" name="apg_sms_settings[mensaje_pedido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_pedido']) ? $configuracion['mensaje_pedido'] : sprintf(__("Order No. %s received on ", 'apg_sms'), "%id%") . "%shop_name%" . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_recibido]">
            <?php _e('Order received custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_recibido]" name="apg_sms_settings[mensaje_recibido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_recibido']) ? $configuracion['mensaje_recibido'] : sprintf(__('Your order No. %s is received on %s. Thank you for shopping with us!', 'apg_sms'), "%id%", "%shop_name%")); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_procesando]">
            <?php _e('Order processing custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_procesando]" name="apg_sms_settings[mensaje_procesando]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_procesando']) ? $configuracion['mensaje_procesando'] : sprintf(__('Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms'), "%id%") . __('Processing', 'apg_sms') . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_completado]">
            <?php _e('Order completed custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_completado]" name="apg_sms_settings[mensaje_completado]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_completado']) ? $configuracion['mensaje_completado'] : sprintf(__('Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms'), "%id%") . __('Completed', 'apg_sms') . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_nota]">
            <?php _e('Notes custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_nota]" name="apg_sms_settings[mensaje_nota]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_nota']) ? $configuracion['mensaje_nota'] : sprintf(__('A note has just been added to your order No. %s: ', 'apg_sms'), "%id%") . "%note%"); ?></textarea></td>
      </tr>
    </table>
    <p class="submit">
      <input type="submit" class="button-primary" value="<?php _e('Save &raquo;', 'apg_sms'); ?>" name="submit" id="submit" tabindex="<?php echo $tab++; ?>" />
    </p>
  </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {	
	$('select').on('change', function () {
		control($(this).val());
	});

	var control = function(capa) {
		var proveedores= new Array();
		<?php foreach($proveedores as $indice => $valor) echo "proveedores['$indice'] = '$valor';" . PHP_EOL; ?>
		
		for (var valor in proveedores) {
    		if (valor == capa) $('.' + capa).show();
			else $('.' + valor).hide();
		}
	};
	control($('select').val());
});
</script> 

<?php global $apg_sms; ?>

<div class="wrap woocommerce">
  <h2>
    <?php _e('APG SMS Notifications Options.', 'apg_sms'); ?>
  </h2>
  <?php 
		settings_errors(); 
		$tab = 1;
		$configuracion = get_option('apg_sms_settings');
       //Traducciones ocultas    
		__('account Sid', 'apg_sms');
		__('Account Sid:', 'apg_sms');
		__('authentication Token', 'apg_sms');
		__('Authentication Token:', 'apg_sms');
		__('key', 'apg_sms');
		__('Key:', 'apg_sms');
		__('authentication key', 'apg_sms');
		__('Authentication key:', 'apg_sms');
		__('sender ID', 'apg_sms');
		__('Sender ID:', 'apg_sms');
		__('route', 'apg_sms');
		__('Route:', 'apg_sms');
		__('sender ID', 'apg_sms');
		__('Sender ID:', 'apg_sms');
		__('username', 'apg_sms');
		__('Username:', 'apg_sms');
		__('password', 'apg_sms');
		__('Password:', 'apg_sms');
		__('mobile number', 'apg_sms');
		__('Mobile number:', 'apg_sms');
		__('campaign ID', 'apg_sms');
		__('Campaign ID:', 'apg_sms');

	global $woocommerce;
  ?>
  <h3><a href="<?php echo $apg_sms['plugin_url']; ?>" title="Art Project Group"><?php echo $apg_sms['plugin']; ?></a></h3>
  <p>
    <?php _e('Add to WooCommerce the possibility to send <abbr title="Short Message Service" lang="en">SMS</abbr> notifications to the client each time you change the order status. Notifies the owner, if desired, when the store has a new order. You can also send customer notes.', 'apg_sms'); ?>
  </p>
  <?php include('cuadro-donacion.php'); ?>
  <form method="post" action="options.php">
    <?php settings_fields('apg_sms_settings_group'); ?>
    <div class="cabecera"> <a href="<?php echo $apg_sms['plugin_url']; ?>" title="<?php echo $apg_sms['plugin']; ?>" target="_blank"><span class="imagen"></span></a> </div>
    <table class="form-table">
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[servicio]">
            <?php _e('<abbr title="Short Message Service" lang="en">SMS</abbr> gateway:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('Select your SMS gateway', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><select class="chosen_select servicio" id="apg_sms_settings[servicio]" name="apg_sms_settings[servicio]" style="width: 450px;" tabindex="<?php echo $tab++; ?>">
            <?php
            $proveedores = array(
				"voipstunt" 			=> "VoipStunt", 
				"solutions_infini" 	=> "Solutions Infini", 
				"twilio" 			=> "Twilio", 
				"clickatell" 		=> "Clickatell", 
				"clockwork" 			=> "Clockwork", 
				"bulksms" 			=> "BulkSMS", 
				"open_dnd" 			=> "OPEN DND", 
				"msg91" 				=> "MSG91", 
				"mvaayoo" 			=> "mVaayoo", 
				"esebun" 			=> "Esebun Business (Enterprise & Developers only)",
				"isms" 				=> "iSMS Malaysia",
				"smslane" 			=> "SMS Lane (Transactional SMS only)",
            );
            foreach ($proveedores as $valor => $proveedor) 
            {
				$chequea = (isset($configuracion['servicio']) && $configuracion['servicio'] == $valor) ? ' selected="selected"' : '';
				echo '<option value="' . $valor . '"' . $chequea . '>' . $proveedor . '</option>' . PHP_EOL;
            }
            ?>
          </select></td>
      </tr>
      <?php             
	  $proveedores_campos = array(
	  	"voipstunt" 			=> array(
			"usuario_voipstunt" 				=> 'username',
			"contrasena_voipstunt" 				=> 'password',
		), 
	  	"solutions_infini" 	=> array(
			"clave_solutions_infini" 			=> 'key',
			"identificador_solutions_infini" 	=> 'sender ID',
		),
	  	"twilio" 			=> array(
			"clave_twilio" 						=> 'account Sid',
			"identificador_twilio" 				=> 'authentication Token',
			"telefono_twilio" 					=> 'mobile number',
		),
	  	"clickatell" 		=> array(
			"identificador_clickatell" 			=> 'sender ID',
			"usuario_clickatell" 				=> 'username',
			"contrasena_clickatell" 			=> 'password',
		),
	  	"clockwork" 			=> array(
			"identificador_clockwork" 			=> 'key',
		),
	  	"bulksms" 			=> array(
			"usuario_bulksms" 					=> 'username',
			"contrasena_bulksms" 				=> 'password',
		),
	  	"open_dnd" 			=> array(
			"identificador_open_dnd" 			=> 'sender ID',
			"usuario_open_dnd" 					=> 'username',
			"contrasena_open_dnd" 				=> 'password',
		),
	  	"msg91" 				=> array(
			"clave_msg91" 						=> 'authentication key',
			"identificador_msg91" 				=> 'sender ID',
			"ruta_msg91" 						=> 'route',
		),
	  	"mvaayoo" 			=> array(
			"usuario_mvaayoo" 					=> 'username',
			"contrasena_mvaayoo" 				=> 'password',
			"identificador_mvaayoo" 			=> 'sender ID',
			"campana_mvaayoo" 					=> 'campaign ID',
		),
	  	"esebun" 			=> array(
			"usuario_esebun" 					=> 'username',
			"contrasena_esebun" 				=> 'password',
			"identificador_esebun" 				=> 'sender ID',
		),
	  	"isms" 				=> array(
			"usuario_isms" 						=> 'username',
			"contrasena_isms" 					=> 'password',
			"telefono_isms" 					=> 'mobile number',
		),
	  	"smslane" 			=> array(
			"usuario_smslane" 					=> 'username',
			"contrasena_smslane" 				=> 'password',
			"sid_smslane" 						=> 'sender ID',
		),
	  );
	  
	  //Pinta los campos de los proveedores
	  foreach ($proveedores as $valor => $proveedor) 
	  {
		  foreach ($proveedores_campos[$valor] as $valor_campo => $campo)
		  {
			  if ($valor_campo == "ruta_msg91") {
				  echo '
      <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' . __(ucfirst($campo) . ":", "apg_sms") . '</label>
          <img class="help_tip" data-tip="' . sprintf(__("The %s for your account in %s", "apg_sms"), __($campo, "apg_sms"), $proveedor) . '" src="' . plugins_url( "woocommerce/assets/images/help.png") . '" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><select id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" tabindex="' . $tab++ . '">
			  ';
				  $opciones = array("default" => __("Default", "apg_sms"), 1 => 1, 4 => 4);
				  foreach ($opciones as $valor => $opcion) {
				  	$chequea = (isset($configuracion['ruta_msg91']) && $configuracion['ruta_msg91'] == $valor) ? ' selected="selected"' : '';
				  	echo '<option value="' . $valor . '"' . $chequea . '>' . $opcion . '</option>' . PHP_EOL;
				  }
				  echo '          </select></td>
      </tr>
			  ';
              }
			  else echo '
      <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' . __(ucfirst($campo) . ":", "apg_sms") . '</label>
          <img class="help_tip" data-tip="' . sprintf(__("The %s for your account in %s", "apg_sms"), __($campo, "apg_sms"), $proveedor) . '" src="' . plugins_url( "woocommerce/assets/images/help.png") . '" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" size="50" value="' . (isset($configuracion[$valor_campo]) ? $configuracion[$valor_campo] : '') . '" tabindex="' . $tab++ . '" /></td>
      </tr>
			  ';
		  }
	  }
      ?>
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
      <?php if (function_exists('wc_custom_status_init') || function_exists('AppZab_woo_advance_order_status_init')) { ?>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[estados_personalizados]">
            <?php _e('Custom Order Statuses & Actions:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('Select your own statuses.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><select multiple="multiple" class="multiselect chosen_select estados_personalizados" id="apg_sms_settings[estados_personalizados]" name="apg_sms_settings[estados_personalizados][]" style="width: 450px;" tabindex="<?php echo $tab++; ?>">
            <?php
				if (function_exists('wc_custom_status_init'))
				{
					$lista_de_estados =  WC_Custom_Status::get_status_list();
					foreach ($lista_de_estados as $estado)
					{
						if ($estado)
						{
							$estados_personalizados = new WC_Custom_Status();
							$estados_personalizados->load_status_from_db($estado);
							if ($estados_personalizados->sends_email)
							{
								$chequea = '';
								foreach ($configuracion['estados_personalizados'] as $estado_personalizado) 
								{
									if ($estado_personalizado == $estado) $chequea = ' selected="selected"';
								}
								echo '<option value="' . $estado . '"' . $chequea . '>' . ucfirst($estado) . '</option>' . PHP_EOL;
							}
						}
					}
				}
				else
				{
					$estados_originales = array('pending','failed','on-hold','processing','completed','refunded','cancelled');
					$lista_de_estados = (array) get_terms('shop_order_status', array('hide_empty' => 0, 'orderby' => 'id'));
					$lista_nueva = array();
					foreach( $lista_de_estados as $estado)
					{
						if (!in_array($estado->slug, $estados_originales)) 
						{
							$estados_personalizados = get_option('taxonomy_' . $estado->term_id, false);
							if ($estados_personalizados && isset($estados_personalizados['woocommerce_woo_advance_order_status_email'] ) && ( '1' == $estados_personalizados['woocommerce_woo_advance_order_status_email'] || 'yes' == $estados_personalizados['woocommerce_woo_advance_order_status_email']))
							{
								$chequea = '';
								foreach ($configuracion['estados_personalizados'] as $estado_personalizado) 
								{
									if ($estado_personalizado == $estado->slug) $chequea = ' selected="selected"';
								}
								echo '<option value="' . $estado->slug . '"' . $chequea . '>' . $estado->name . '</option>' . PHP_EOL;
								$lista_nueva[] = $estado->slug;
							}
						}
					}
					$lista_de_estados = $lista_nueva;
				}
            ?>
          </select></td>
      </tr>
      <?php foreach ($lista_de_estados as $estados_personalizados) { ?>
      <tr valign="top" class="<?php echo $estados_personalizados; ?>"><!-- <?php echo ucfirst($estados_personalizados); ?> -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[<?php echo $estados_personalizados; ?>]"> <?php echo sprintf(__('%s state custom message:', 'apg_sms'), ucfirst($estados_personalizados)); ?> </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[<?php echo $estados_personalizados; ?>]" name="apg_sms_settings[<?php echo $estados_personalizados; ?>]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion[$estados_personalizados]) ? $configuracion[$estados_personalizados] : ""); ?></textarea></td>
      </tr>
      <?php } ?>
      <?php } ?>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[variables]">
            <?php _e('Custom variables:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can add your own variables. Each variable must be entered onto a new line without percentage character (%). Example: <code>_custom_variable_name</code><br /><code>_another_variable_name</code>.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[variables]" name="apg_sms_settings[variables]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['variables']) ? $configuracion['variables'] : ''); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_pedido]">
            <?php _e('Owner custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_pedido]" name="apg_sms_settings[mensaje_pedido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_pedido']) ? $configuracion['mensaje_pedido'] : sprintf(__("Order No. %s received on ", 'apg_sms'), "%id%") . "%shop_name%" . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_recibido]">
            <?php _e('Order received custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_recibido]" name="apg_sms_settings[mensaje_recibido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_recibido']) ? $configuracion['mensaje_recibido'] : sprintf(__('Your order No. %s is received on %s. Thank you for shopping with us!', 'apg_sms'), "%id%", "%shop_name%")); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_procesando]">
            <?php _e('Order processing custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_procesando]" name="apg_sms_settings[mensaje_procesando]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_procesando']) ? $configuracion['mensaje_procesando'] : sprintf(__('Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms'), "%id%") . __('Processing', 'apg_sms') . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_completado]">
            <?php _e('Order completed custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_completado]" name="apg_sms_settings[mensaje_completado]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_completado']) ? $configuracion['mensaje_completado'] : sprintf(__('Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms'), "%id%") . __('Completed', 'apg_sms') . "."); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_nota]">
            <?php _e('Notes custom message:', 'apg_sms'); ?>
          </label>
          <img class="help_tip" data-tip="<?php _e('You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name% and %note%.', 'apg_sms'); ?>" src="<?php echo plugins_url( 'woocommerce/assets/images/help.png');?>" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_nota]" name="apg_sms_settings[mensaje_nota]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes(isset($configuracion['mensaje_nota']) ? $configuracion['mensaje_nota'] : sprintf(__('A note has just been added to your order No. %s: ', 'apg_sms'), "%id%") . "%note%"); ?></textarea></td>
      </tr>
    </table>
    <p class="submit">
      <input class="button-primary" type="submit" value="<?php _e('Save Changes', 'apg_sms'); ?>"  name="submit" id="submit" tabindex="<?php echo $tab++; ?>" />
    </p>
  </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {	
	$('.servicio').on('change', function () { control($(this).val()); });
	var control = function(capa) 
	{
		var proveedores= new Array();
		<?php foreach($proveedores as $indice => $valor) echo "proveedores['$indice'] = '$valor';" . PHP_EOL; ?>
		
		for (var valor in proveedores) 
		{
    		if (valor == capa) $('.' + capa).show();
			else $('.' + valor).hide();
		}
	};
	control($('.servicio').val());

	jQuery("select.chosen_select").chosen();
<?php if (function_exists('wc_custom_status_init') || function_exists('AppZab_woo_advance_order_status_init')) { ?>	
	$('.estados_personalizados').on('change', function () { control_personalizados($(this).val()); });
	var control_personalizados = function(capa) 
	{
		var estados= new Array();
		<?php foreach($lista_de_estados as $valor) echo "estados['$valor'] = '$valor';" . PHP_EOL; ?>

		for (var valor in estados) 
		{
			$('.' + valor).hide();
			for (var valor_capa in capa) if (valor == capa[valor_capa]) $('.' + valor).show();
		}
	};

	control_personalizados($('.estados_personalizados').val());
<?php } ?>	
});
</script> 

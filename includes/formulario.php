<?php global $configuracion, $apg_sms, $wpml_activo; ?>

<div class="wrap woocommerce">
  <h2>
    <?php _e( 'APG SMS Notifications Options.', 'apg_sms' ); ?>
  </h2>
  <?php 
	settings_errors(); 
	$tab = 1;
	//Traducciones ocultas    
	__( 'account Sid', 'apg_sms' );
	__( 'Account Sid:', 'apg_sms' );
	__( 'authentication Token', 'apg_sms' );
	__( 'Authentication Token:', 'apg_sms' );
	__( 'key', 'apg_sms' );
	__( 'Key:', 'apg_sms' );
	__( 'authentication key', 'apg_sms' );
	__( 'Authentication key:', 'apg_sms' );
	__( 'sender ID', 'apg_sms' );
	__( 'Sender ID:', 'apg_sms' );
	__( 'route', 'apg_sms' );
	__( 'Route:', 'apg_sms' );
	__( 'sender ID', 'apg_sms' );
	__( 'Sender ID:', 'apg_sms' );
	__( 'username', 'apg_sms' );
	__( 'Username:', 'apg_sms' );
	__( 'password', 'apg_sms' );
	__( 'Password:', 'apg_sms' );
	__( 'mobile number', 'apg_sms' );
	__( 'Mobile number:', 'apg_sms' );
	__( 'client', 'apg_sms' );
	__( 'Client:', 'apg_sms' );
	__( 'authentication ID', 'apg_sms' );
	__( 'Authentication ID:', 'apg_sms' );
	__( 'project', 'apg_sms' );
	__( 'Project:', 'apg_sms' );
	
	//WPML
	if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
		$mensaje_pedido		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_pedido', $configuracion['mensaje_pedido'] ) : $configuracion['mensaje_pedido'];
		$mensaje_recibido	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_recibido', $configuracion['mensaje_recibido'] ) : $configuracion['mensaje_recibido'];
		$mensaje_procesando	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_procesando', $configuracion['mensaje_procesando'] ) : $configuracion['mensaje_procesando'];
		$mensaje_completado	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_completado', $configuracion['mensaje_completado'] ) : $configuracion['mensaje_completado'];
		$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_nota', $configuracion['mensaje_nota'] ) : $configuracion['mensaje_nota'];
	} else if ( $wpml_activo ) { //Versión 3.2 o superior
		$mensaje_pedido		= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_pedido'], 'apg_sms', 'mensaje_pedido' );
		$mensaje_recibido	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_recibido'], 'apg_sms', 'mensaje_recibido' );
		$mensaje_procesando	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_procesando'], 'apg_sms', 'mensaje_procesando' );
		$mensaje_completado	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_completado'], 'apg_sms', 'mensaje_completado' );
		$mensaje_nota		= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_nota'], 'apg_sms', 'mensaje_nota' );
	}
  ?>
  <h3><a href="<?php echo $apg_sms['plugin_url']; ?>" title="Art Project Group"><?php echo $apg_sms['plugin']; ?></a></h3>
  <p>
    <?php _e( 'Add to WooCommerce the possibility to send <abbr title="Short Message Service" lang="en">SMS</abbr> notifications to the client each time you change the order status. Notifies the owner, if desired, when the store has a new order. You can also send customer notes.', 'apg_sms' ); ?>
  </p>
  <?php include( 'cuadro-informacion.php' ); ?>
  <form method="post" action="options.php">
    <?php settings_fields( 'apg_sms_settings_group' ); ?>
    <div class="cabecera"> <a href="<?php echo $apg_sms['plugin_url']; ?>" title="<?php echo $apg_sms['plugin']; ?>" target="_blank"><img src="<?php echo plugins_url( '../assets/images/cabecera.jpg', __FILE__ ); ?>" class="imagen" alt="<?php echo $apg_sms['plugin']; ?>" /></a> </div>
    <table class="form-table apg-table">
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[servicio]">
            <?php _e( '<abbr title="Short Message Service" lang="en">SMS</abbr> gateway:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your SMS gateway', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><select class="wc-enhanced-select servicio" id="apg_sms_settings[servicio]" name="apg_sms_settings[servicio]" style="width: 450px;" tabindex="<?php echo $tab++; ?>">
            <?php
			$proveedores = array( 
				"voipstunt" 		=> "VoipStunt", 
				"voipbusterpro" 	=> "VoipBusterPro", 
				"voipbuster" 		=> "VoipBuster", 
				"smsdiscount" 		=> "SMS Discount", 
				"sipdiscount" 		=> "SIP Discount", 
				"solutions_infini" 	=> "Solutions Infini", 
				"twilio" 			=> "Twilio", 
				"clickatell" 		=> "Clickatell", 
				"clockwork" 		=> "Clockwork", 
				"bulksms" 			=> "BulkSMS", 
				"open_dnd" 			=> "OPEN DND", 
				"msg91" 			=> "MSG91", 
				"mvaayoo" 			=> "mVaayoo", 
				"esebun" 			=> "Esebun Business ( Enterprise & Developers only )",
				"isms" 				=> "iSMS Malaysia",
				"smslane" 			=> "SMS Lane ( Transactional SMS only )",
				"smscountry" 		=> "SMS Country",
				"labsmobile" 		=> "LabsMobile Spain",
				"plivo" 			=> "Plivo",
				"springedge" 		=> "Spring Edge",
				"moreify" 			=> "Moreify",
				"nexmo"				=> "Nexmo",

			);
			asort( $proveedores, SORT_NATURAL | SORT_FLAG_CASE ); //Ordena alfabeticamente los proveedores
            foreach ( $proveedores as $valor => $proveedor ) {
				$chequea = ( isset( $configuracion['servicio'] ) && $configuracion['servicio'] == $valor ) ? ' selected="selected"' : '';
				echo '<option value="' . $valor . '"' . $chequea . '>' . $proveedor . '</option>' . PHP_EOL;
            }
            ?>
          </select></td>
      </tr>
      <?php             
		$proveedores_campos = array( 
			"voipstunt"			=> array( 
				"usuario_voipstunt" 				=> 'username',
				"contrasena_voipstunt" 				=> 'password',
			), 
			"voipbusterpro"		=> array( 
				"usuario_voipbusterpro"				=> 'username',
				"contrasena_voipbusterpro"			=> 'password',
			), 
			"voipbuster"		=> array( 
				"usuario_voipbuster" 				=> 'username',
				"contrasena_voipbuster"				=> 'password',
			), 
			"smsdiscount"		=> array( 
				"usuario_smsdiscount" 				=> 'username',
				"contrasena_smsdiscount"			=> 'password',
			), 
			"sipdiscount"		=> array( 
				"usuario_sipdiscount" 				=> 'username',
				"contrasena_sipdiscount"			=> 'password',
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
			"clockwork" 		=> array( 
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
			"msg91" 			=> array( 
				"clave_msg91" 						=> 'authentication key',
				"identificador_msg91" 				=> 'sender ID',
				"ruta_msg91" 						=> 'route',
			),
			"mvaayoo" 			=> array( 
				"usuario_mvaayoo" 					=> 'username',
				"contrasena_mvaayoo" 				=> 'password',
				"identificador_mvaayoo" 			=> 'sender ID',
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
			"smscountry" 		=> array( 
				"usuario_smscountry"				=> 'username',
				"contrasena_smscountry" 			=> 'password',
				"sid_smscountry" 					=> 'sender ID',
			),
			"labsmobile"       => array(
				"identificador_labsmobile"			=> 'client',
				"usuario_labsmobile"				=> 'username',
				"contrasena_labsmobile"				=> 'password',
				"sid_labsmobile"					=> 'sender ID',
			),
			"plivo"				=> array(
				"usuario_plivo"						=> 'authentication ID',
				"clave_plivo"						=> 'authentication Token',
				"identificador_plivo"				=> 'sender ID',
			),
			"springedge" 		=> array( 
				"clave_springedge" 					=> 'key',
				"identificador_springedge"		 	=> 'sender ID',
			),
			"moreify" 			=> array( 
				"proyecto_moreify"					=> 'project',
				"identificador_moreify" 			=> 'authentication Token',
			),
			"nexmo" 			=> array( 
 				"clave_nexmo"						=> 'key',
				"identificador_nexmo"				=> 'authentication Token',
			),
		);
	  
		//Pinta los campos de los proveedores
		foreach ( $proveedores as $valor => $proveedor ) {
			foreach ( $proveedores_campos[$valor] as $valor_campo => $campo ) {
				if ( $valor_campo == "ruta_msg91" ) {
					echo '
      <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' . __( ucfirst( $campo ) . ":", "apg_sms" ) . '</label>
          <span class="woocommerce-help-tip" data-tip="' . sprintf( __( "The %s for your account in %s", "apg_sms" ), __( $campo, "apg_sms" ), $proveedor ) . '" src="' . plugins_url(  "woocommerce/assets/images/help.png" ) . '" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><select id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" tabindex="' . $tab++ . '">
					';
					$opciones = array( "default" => __( "Default", "apg_sms" ), 1 => 1, 4 => 4 );
					foreach ( $opciones as $valor => $opcion ) {
						$chequea = ( isset( $configuracion['ruta_msg91'] ) && $configuracion['ruta_msg91'] == $valor ) ? ' selected="selected"' : '';
				  		echo '<option value="' . $valor . '"' . $chequea . '>' . $opcion . '</option>' . PHP_EOL;
					}
					echo '          </select></td>
      </tr>
					';
				} else {
					echo '
      <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' . __( ucfirst( $campo ) . ":", "apg_sms" ) . '</label>
          <span class="woocommerce-help-tip" data-tip="' . sprintf( __( "The %s for your account in %s", "apg_sms" ), __( $campo, "apg_sms" ), $proveedor ) . '" src="' . plugins_url(  "woocommerce/assets/images/help.png" ) . '" height="16" width="16" /> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" size="50" value="' . ( isset( $configuracion[$valor_campo] ) ? $configuracion[$valor_campo] : '' ) . '" tabindex="' . $tab++ . '" /></td>
      </tr>
					';
				}
			}
		}
      ?>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[telefono]">
            <?php _e( 'Your mobile number:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'The mobile number registered in your SMS gateway account and where you receive the SMS messages. You can add multiple mobile numbers separeted by | character. Example: xxxxxxxxx|yyyyyyyyy', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[telefono]" name="apg_sms_settings[telefono]" size="50" value="<?php echo ( isset( $configuracion['telefono'] ) ? $configuracion['telefono'] : '' ); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[notificacion]">
            <?php _e( 'New order notification:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( "Check if you want to receive a SMS message when there's a new order", 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[notificacion]" name="apg_sms_settings[notificacion]" type="checkbox" value="1" <?php echo ( isset( $configuracion['notificacion'] ) && $configuracion['notificacion'] == "1" ? 'checked="checked"' : '' ); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[internacional]">
            <?php _e( 'Send international <abbr title="Short Message Service" lang="en">SMS</abbr>?:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send international SMS messages', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[internacional]" name="apg_sms_settings[internacional]" type="checkbox" value="1" <?php echo ( isset( $configuracion['internacional'] ) && $configuracion['internacional'] == "1" ? 'checked="checked"' : '' ); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[envio]">
            <?php _e( 'Send <abbr title="Short Message Service" lang="en">SMS</abbr> to shipping mobile?:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to send SMS messages to shipping mobile numbers, only if it is different from billing mobile number', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[envio]" name="apg_sms_settings[envio]" type="checkbox" class="envio" value="1" <?php echo ( isset( $configuracion['envio'] ) && $configuracion['envio'] == "1" ? 'checked="checked"' : '' ); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="campo_envio">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[campo_envio]">
            <?php _e( 'Shipping mobile field:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Select the shipping mobile field', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><select id="apg_sms_settings[campo_envio]" name="apg_sms_settings[campo_envio]" class="wc-enhanced-select" tabindex="<?php echo $tab++; ?>">
        <?php
			$pais	= new WC_Countries();
			$campos	= $pais->get_address_fields( $pais->get_base_country(), 'shipping_' ); //Campos ordinarios
			$campos_personalizados = apply_filters( 'woocommerce_checkout_fields', array() );
			if ( isset( $campos_personalizados['shipping'] ) ) {
				$campos += $campos_personalizados['shipping'];
			}
            foreach ( $campos as $valor => $campo ) {
				$chequea = ( isset( $configuracion['campo_envio'] ) && $configuracion['campo_envio'] == $valor ) ? ' selected="selected"' : '';
				if ( isset( $campo['label'] ) ) {
					echo '<option value="' . $valor . '"' . $chequea . '>' . $campo['label'] . '</option>' . PHP_EOL;
				}
            }
		?>
        </select></td>
      </tr>
      <?php if ( class_exists( 'WC_SA' ) || function_exists( 'AppZab_woo_advance_order_status_init' ) || isset( $GLOBALS['advorder_lite_orderstatus'] ) ) : //Comprueba la existencia de los plugins de estado personalizado ?>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[estados_personalizados]">
            <?php _e( 'Custom Order Statuses & Actions:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Select your own statuses.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><select multiple="multiple" class="multiselect chosen_select estados_personalizados" id="apg_sms_settings[estados_personalizados]" name="apg_sms_settings[estados_personalizados][]" style="width: 450px;" tabindex="<?php echo $tab++; ?>">
            <?php
				if ( class_exists( 'WC_SA' ) ) { //WooCommerce Order Status & Actions Manager
					$lista_de_estados_temporal = array();
					$lista_de_estados = wc_sa_get_statuses();
					foreach ( $lista_de_estados as $clave => $estado ) {
						if ( $estado->label ) {
							$estados_personalizados = new WC_SA_Status( $clave );
							if ( $estados_personalizados->email_notification ) {
								$chequea = '';
								if ( $configuracion['estados_personalizados'] ) {
									foreach ( $configuracion['estados_personalizados'] as $estado_personalizado ) {
										if ( $estado_personalizado == $estado->label ) {
											$chequea = ' selected="selected"';
										}
									}
								}
								echo '<option value="' . $estado->label . '"' . $chequea . '>' . ucfirst( $estado->label ) . '</option>' . PHP_EOL;
							}
							$lista_de_estados_temporal[$clave] = $estado->label;
						}
					}
					$lista_de_estados = $lista_de_estados_temporal;
				} else {
					$estados_originales = array( 
						'pending',
						'failed',
						'on-hold',
						'processing',
						'completed',
						'refunded',
						'cancelled',
					);
					if ( isset( $GLOBALS['advorder_lite_orderstatus'] ) ) { //WooCommerce Advance Order Status
						$lista_de_estados = ( array ) $GLOBALS['advorder_lite_orderstatus']->get_terms( 'shop_order_status', array( 
							'hide_empty' => 0, 
							'orderby' => 'id' 
						) );
					} else {
						$lista_de_estados = ( array ) get_terms( 'shop_order_status', array( 
							'hide_empty' => 0, 
							'orderby' => 'id' 
						) );
					}
					$lista_nueva = array();
					foreach( $lista_de_estados as $estado ) {
						$estado_nombre = str_replace( "wc-", "", $estado->slug );
						if ( !in_array( $estado_nombre, $estados_originales ) ) {
							$muestra_estado = false;
							$estados_personalizados = get_option( 'taxonomy_' . $estado->term_id, false );
							if ( $estados_personalizados && ( isset( $estados_personalizados['woocommerce_woo_advance_order_status_email'] ) ) && (  '1' == $estados_personalizados['woocommerce_woo_advance_order_status_email'] || 'yes' == $estados_personalizados['woocommerce_woo_advance_order_status_email'] ) ) {
								$muestra_estado = true;
							}
							if ( get_option( 'az_custom_order_status_meta_' . $estado->slug, true ) ) { //WooCommerce Advance Order Status
								$estados_personalizados = get_option( 'az_custom_order_status_meta_' . $estado->slug, true );
								if ( $estados_personalizados ) { //Ya no hay que controlar si se notifica por correo electrónico o no
									$muestra_estado = true;
								}
							}
							if ( $muestra_estado ) {
								$chequea = '';
								if ( isset( $configuracion['estados_personalizados'] ) ) {
									foreach ( $configuracion['estados_personalizados'] as $estado_personalizado ) {
										if ( $estado_personalizado == $estado_nombre ) {
											$chequea = ' selected="selected"';
										}
									}
								}
								echo '<option value="' . $estado_nombre . '"' . $chequea . '>' . $estado->name . '</option>' . PHP_EOL;
								$lista_nueva[] = $estado_nombre;
							}
						}
					}
					$lista_de_estados = $lista_nueva;
				}
            ?>
          </select></td>
      </tr>
      <?php foreach ( $lista_de_estados as $estados_personalizados ) : ?>
      <tr valign="top" class="<?php echo $estados_personalizados; ?>"><!-- <?php echo ucfirst( $estados_personalizados ); ?> -->
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[<?php echo $estados_personalizados; ?>]"> <?php echo sprintf( __( '%s state custom message:', 'apg_sms' ), ucfirst( $estados_personalizados ) ); ?> </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[<?php echo $estados_personalizados; ?>]" name="apg_sms_settings[<?php echo $estados_personalizados; ?>]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $configuracion[$estados_personalizados] ) ? $configuracion[$estados_personalizados] : "" ); ?></textarea></td>
      </tr>
      <?php endforeach; ?>
      <?php endif; ?>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[variables]">
            <?php _e( 'Custom variables:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can add your own variables. Each variable must be entered onto a new line without percentage character ( % ). Example: <code>_custom_variable_name</code><br /><code>_another_variable_name</code>.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[variables]" name="apg_sms_settings[variables]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $configuracion['variables'] ) ? $configuracion['variables'] : '' ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_pedido]">
            <?php _e( 'Owner custom message:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_pedido]" name="apg_sms_settings[mensaje_pedido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mensaje_pedido ) ? $mensaje_pedido : sprintf( __( "Order No. %s received on ", 'apg_sms' ), "%id%" ) . "%shop_name%" . "." ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_recibido]">
            <?php _e( 'Order received custom message:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_recibido]" name="apg_sms_settings[mensaje_recibido]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mensaje_recibido ) ? $mensaje_recibido : sprintf( __( 'Your order No. %s is received on %s. Thank you for shopping with us!', 'apg_sms' ), "%id%", "%shop_name%" ) ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_procesando]">
            <?php _e( 'Order processing custom message:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_procesando]" name="apg_sms_settings[mensaje_procesando]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mensaje_procesando ) ? $mensaje_procesando : sprintf( __( 'Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms' ), "%id%" ) . __( 'Processing', 'apg_sms' ) . "." ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_completado]">
            <?php _e( 'Order completed custom message:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_completado]" name="apg_sms_settings[mensaje_completado]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mensaje_completado ) ? $mensaje_completado : sprintf( __( 'Thank you for shopping with us! Your order No. %s is now: ', 'apg_sms' ), "%id%" ) . __( 'Completed', 'apg_sms' ) . "." ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[mensaje_nota]">
            <?php _e( 'Notes custom message:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'You can customize your message. Remember that you can use this variables: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %prices_include_tax%, %tax_display_cart%, %display_totals_ex_tax%, %display_cart_ex_tax%, %order_date%, %modified_date%, %customer_message%, %customer_note%, %post_status%, %shop_name%, %order_product% and %note%.', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><textarea id="apg_sms_settings[mensaje_nota]" name="apg_sms_settings[mensaje_nota]" cols="50" rows="5" tabindex="<?php echo $tab++; ?>"><?php echo stripcslashes( isset( $mensaje_nota ) ? $mensaje_nota : sprintf( __( 'A note has just been added to your order No. %s: ', 'apg_sms' ), "%id%" ) . "%note%" ); ?></textarea></td>
      </tr>
      <tr valign="top">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[debug]">
            <?php _e( 'Send debug information?:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Check if you want to receive debug information from your SMS gateway', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input id="apg_sms_settings[debug]" name="apg_sms_settings[debug]" type="checkbox" class="debug" value="1" <?php echo ( isset( $configuracion['debug'] ) && $configuracion['debug'] == "1" ? 'checked="checked"' : '' ); ?> tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
      <tr valign="top" class="campo_debug">
        <th scope="row" class="titledesc"> <label for="apg_sms_settings[campo_debug]">
            <?php _e( 'email address:', 'apg_sms' ); ?>
          </label>
          <span class="woocommerce-help-tip" data-tip="<?php _e( 'Add an email address where you want to receive the debug information', 'apg_sms' ); ?>"></span> </th>
        <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[campo_debug]" name="apg_sms_settings[campo_debug]" size="50" value="<?php echo ( isset( $configuracion['campo_debug'] ) ? $configuracion['campo_debug'] : '' ); ?>" tabindex="<?php echo $tab++; ?>" /></td>
      </tr>
    </table>
    <p class="submit">
      <input class="button-primary" type="submit" value="<?php _e( 'Save Changes', 'apg_sms' ); ?>"  name="submit" id="submit" tabindex="<?php echo $tab++; ?>" />
    </p>
  </form>
</div>
<script type="text/javascript">
jQuery( document ).ready( function( $ ) {
	//Cambia los campos en función del proveedor de servicios SMS
	$( '.servicio' ).on( 'change', function () { 
		control( $( this ).val() ); 
	} );
	var control = function( capa ) {
		if ( capa == '' ) {
			capa = $( '.servicio option:selected' ).val();
		}
		var proveedores= new Array();
		<?php 
		foreach( $proveedores as $indice => $valor ) {
			echo "proveedores['$indice'] = '$valor';" . PHP_EOL;
		}
		?>
		
		for ( var valor in proveedores ) {
    		if ( valor == capa ) {
				$( '.' + capa ).show();
			} else {
				$( '.' + valor ).hide();
			}
		}
	};
	control( $( '.servicio' ).val() );

	if ( typeof chosen !== 'undefined' && $.isFunction( chosen ) ) {
		jQuery( "select.chosen_select" ).chosen();
	}
	
	//Controla el campo de teléfono del formulario de envío
	$( '.campo_envio' ).hide();
	$( '.envio' ).on( 'change', function () { 
		control_envio( '.envio' ); 
	} );
	var control_envio = function( capa ) {
		if ( $( capa ).is(':checked') ){
			$( '.campo_envio' ).show();
		} else {
			$( '.campo_envio' ).hide();
		}
	};
	control_envio( '.envio' ); 
	
	//Controla el campo de correo electrónico del formulario de envío
	$( '.campo_debug' ).hide();
	$( '.debug' ).on( 'change', function () { 
		control_debug( '.debug' ); 
	} );
	var control_debug = function( capa ) {
		if ( $( capa ).is(':checked') ){
			$( '.campo_debug' ).show();
		} else {
			$( '.campo_debug' ).hide();
		}
	};
	control_debug( '.debug' ); 
	
<?php if ( class_exists( 'WC_SA' ) || function_exists( 'AppZab_woo_advance_order_status_init' ) || isset( $GLOBALS['advorder_lite_orderstatus'] ) ) : //Comprueba la existencia de los plugins de estado personalizado ?>	
	$( '.estados_personalizados' ).on( 'change', function () { 
		control_personalizados( $( this ).val() ); 
	} );
	var control_personalizados = function( capa ) {
		var estados= new Array();
		<?php 
		foreach( $lista_de_estados as $valor ) {
			echo "estados['$valor'] = '$valor';" . PHP_EOL; 
		}
		?>

		for ( var valor in estados ) {
			$( '.' + valor ).hide();
			for ( var valor_capa in capa ) {
				if ( valor == capa[valor_capa] ) {
					$( '.' + valor ).show();
				}
			}
		}
	};

	$( '.estados_personalizados' ).each( function( i, selected ) { 
	  control_personalizados( $( selected ).val() );
	} );
<?php endif; ?>	
} );
</script> 

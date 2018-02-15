<?php
global $apg_sms_settings, $wpml_activo;

//Control de tabulación
$tab = 1;

//WPML
if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
	$mensaje_pedido		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_pedido', $apg_sms_settings['mensaje_pedido'] ) : $apg_sms_settings['mensaje_pedido'];
	$mensaje_recibido	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_recibido', $apg_sms_settings['mensaje_recibido'] ) : $apg_sms_settings['mensaje_recibido'];
	$mensaje_procesando	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_procesando', $apg_sms_settings['mensaje_procesando'] ) : $apg_sms_settings['mensaje_procesando'];
	$mensaje_completado	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_completado', $apg_sms_settings['mensaje_completado'] ) : $apg_sms_settings['mensaje_completado'];
	$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_nota', $apg_sms_settings['mensaje_nota'] ) : $apg_sms_settings['mensaje_nota'];
} else if ( $wpml_activo ) { //Versión 3.2 o superior
	$mensaje_pedido		= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_pedido'], 'apg_sms', 'mensaje_pedido' );
	$mensaje_recibido	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_recibido'], 'apg_sms', 'mensaje_recibido' );
	$mensaje_procesando	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_procesando'], 'apg_sms', 'mensaje_procesando' );
	$mensaje_completado	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_completado'], 'apg_sms', 'mensaje_completado' );
	$mensaje_nota		= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_nota'], 'apg_sms', 'mensaje_nota' );
}

//Listado de proveedores SMS
$listado_de_proveedores = array( 
	"bulksms" 			=> "BulkSMS", 
	"clickatell" 		=> "Clickatell", 
	"clockwork" 		=> "Clockwork", 
	"esebun" 			=> "Esebun Business ( Enterprise & Developers only )",
	"isms" 				=> "iSMS Malaysia",
	"labsmobile" 		=> "LabsMobile Spain",
	"moreify" 			=> "Moreify",
	"msg91" 			=> "MSG91", 
	"msgwow"			=> "MSGWOW",
	"mvaayoo" 			=> "mVaayoo", 
	"nexmo"				=> "Nexmo",
	"open_dnd" 			=> "OPEN DND", 
	"plivo" 			=> "Plivo",
	"sipdiscount" 		=> "SIP Discount", 
	"smscountry" 		=> "SMS Country",
	"smsdiscount" 		=> "SMS Discount", 
	"smslane" 			=> "SMS Lane ( Transactional SMS only )",
	"solutions_infini" 	=> "Solutions Infini", 
	"springedge" 		=> "Spring Edge",
	"twilio" 			=> "Twilio", 
	"twizo"				=> "Twizo",
	"voipbuster" 		=> "VoipBuster", 
	"voipbusterpro" 	=> "VoipBusterPro", 
	"voipstunt" 		=> "VoipStunt", 
);
asort( $listado_de_proveedores, SORT_NATURAL | SORT_FLAG_CASE ); //Ordena alfabeticamente los proveedores

//Campos necesarios para cada proveedor
$campos_de_proveedores = array( 
	"bulksms" 			=> array( 
		"usuario_bulksms" 					=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_bulksms" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"servidor_bulksms"					=> __( 'host', 'woocommerce-apg-sms-notifications' ),
	),
	"clickatell" 		=> array( 
		"identificador_clickatell" 			=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"usuario_clickatell" 				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_clickatell" 			=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	),
	"clockwork" 		=> array( 
		"identificador_clockwork" 			=> __( 'key', 'woocommerce-apg-sms-notifications' ),
	),
	"esebun" 			=> array( 
		"usuario_esebun" 					=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_esebun" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"identificador_esebun" 				=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"isms" 				=> array( 
		"usuario_isms" 						=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_isms" 					=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"telefono_isms" 					=> __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
	),
	"labsmobile"       => array(
		"identificador_labsmobile"			=> __( 'client', 'woocommerce-apg-sms-notifications' ),
		"usuario_labsmobile"				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_labsmobile"				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"sid_labsmobile"					=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"moreify" 			=> array( 
		"proyecto_moreify"					=> __( 'project', 'woocommerce-apg-sms-notifications' ),
		"identificador_moreify" 			=> __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
	),
	"msg91" 			=> array( 
		"clave_msg91" 						=> __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
		"identificador_msg91" 				=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"ruta_msg91" 						=> __( 'route', 'woocommerce-apg-sms-notifications' ),
	),
	"msgwow" 			=> array( 
		"clave_msgwow"						=> __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_msgwow"				=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"ruta_msgwow" 						=> __( 'route', 'woocommerce-apg-sms-notifications' ),
		"servidor_msgwow"					=> __( 'host', 'woocommerce-apg-sms-notifications' ),
	),
	"mvaayoo" 			=> array( 
		"usuario_mvaayoo" 					=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_mvaayoo" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"identificador_mvaayoo" 			=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"nexmo" 			=> array( 
		"clave_nexmo"						=> __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_nexmo"				=> __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
	),
	"open_dnd" 			=> array( 
		"identificador_open_dnd" 			=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"usuario_open_dnd" 					=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_open_dnd" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	),
	"plivo"				=> array(
		"usuario_plivo"						=> __( 'authentication ID', 'woocommerce-apg-sms-notifications' ),
		"clave_plivo"						=> __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
		"identificador_plivo"				=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"sipdiscount"		=> array( 
		"usuario_sipdiscount" 				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_sipdiscount"			=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	), 
	"smscountry" 		=> array( 
		"usuario_smscountry"				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smscountry" 			=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"sid_smscountry" 					=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"smsdiscount"		=> array( 
		"usuario_smsdiscount" 				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smsdiscount"			=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	), 
	"smslane" 			=> array( 
		"usuario_smslane" 					=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smslane" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
		"sid_smslane" 						=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"solutions_infini" 	=> array( 
		"clave_solutions_infini" 			=> __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_solutions_infini" 	=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"springedge" 		=> array( 
		"clave_springedge" 					=> __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_springedge"		 	=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	),
	"twilio" 			=> array( 
		"clave_twilio" 						=> __( 'account Sid', 'woocommerce-apg-sms-notifications' ),
		"identificador_twilio" 				=> __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
		"telefono_twilio" 					=> __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
	),
	"twizo" 			=> array( 
		"clave_twizo"						=> __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_twizo"				=> __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"servidor_twizo"					=> __( 'host', 'woocommerce-apg-sms-notifications' ),
	),
	"voipbuster"		=> array( 
		"usuario_voipbuster" 				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipbuster"				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	), 
	"voipbusterpro"		=> array( 
		"usuario_voipbusterpro"				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipbusterpro"			=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	), 
	"voipstunt"			=> array( 
		"usuario_voipstunt" 				=> __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipstunt" 				=> __( 'password', 'woocommerce-apg-sms-notifications' ),
	), 
);

//Opciones de campos de selección de los proveedores
$opciones_de_proveedores = array(
	"ruta_msg91"		=> array(
		"default"				=> __( 'Default', 'woocommerce-apg-sms-notifications' ), 
		1						=> 1, 
		4						=> 4,
	),
	"servidor_bulksms"	=> array(
		"bulksms.vsms.net"		=> __( 'International', 'woocommerce-apg-sms-notifications' ), 
		"www.bulksms.co.uk"		=> __( 'UK', 'woocommerce-apg-sms-notifications' ),
		"usa.bulksms.com"		=> __( 'USA', 'woocommerce-apg-sms-notifications' ),
		"bulksms.2way.co.za"	=> __( 'South Africa', 'woocommerce-apg-sms-notifications' ),
		"bulksms.com.es"		=> __( 'Spain', 'woocommerce-apg-sms-notifications' ),
	),
	"servidor_twizo"	=> array(
		"api-asia-01.twizo.com"	=> __( 'Singapore', 'woocommerce-apg-sms-notifications' ), 
		"api-eu-01.twizo.com"	=> __( 'Germany', 'woocommerce-apg-sms-notifications' ), 
	),
	"ruta_msgwow"		=> array(
		1						=> 1, 
		4						=> 4,
	),
	"servidor_msgwow"	=> array(
		"0"						=> __( 'International', 'woocommerce-apg-sms-notifications' ), 
		"1"						=> __( 'USA', 'woocommerce-apg-sms-notifications' ), 
		"91"					=> __( 'India', 'woocommerce-apg-sms-notifications' ), 
	),
);

//Listado de estados de pedidos
$listado_de_estados				= wc_get_order_statuses();
$listado_de_estados_temporal	= array();
$estados_originales				= array( 
	'pending',
	'failed',
	'on-hold',
	'processing',
	'completed',
	'refunded',
	'cancelled',
);
foreach( $listado_de_estados as $clave => $estado ) {
	$nombre_de_estado = str_replace( "wc-", "", $clave );
	if ( !in_array( $nombre_de_estado, $estados_originales ) ) {
		$listado_de_estados_temporal[$estado] = $nombre_de_estado;
	}
}
$listado_de_estados = $listado_de_estados_temporal;

//Listado de mensajes personalizados
$listado_de_mensajes = array(
	'todos'					=> __( 'All messages', 'woocommerce-apg-sms-notifications' ),
	'mensaje_pedido'		=> __( 'Owner custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_recibido'		=> __( 'Order received custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_procesando'	=> __( 'Order processing custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_completado'	=> __( 'Order completed custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_nota'			=> __( 'Notes custom message', 'woocommerce-apg-sms-notifications' ),
);

/*
Pinta el campo select con el listado de proveedores
*/
function apg_sms_listado_de_proveedores( $listado_de_proveedores ) {
	global $apg_sms_settings;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		$chequea = ( isset( $apg_sms_settings['servicio'] ) && $apg_sms_settings['servicio'] == $valor ) ? ' selected="selected"' : '';
		echo '<option value="' . $valor . '"' . $chequea . '>' . $proveedor . '</option>' . PHP_EOL;
	}
}

/*
Pinta los campos de los proveedores
*/
function apg_sms_campos_de_proveedores( $listado_de_proveedores, $campos_de_proveedores, $opciones_de_proveedores ) {
	global $apg_sms_settings, $tab;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		foreach ( $campos_de_proveedores[$valor] as $valor_campo => $campo ) {
			if ( array_key_exists( $valor_campo, $opciones_de_proveedores ) ) { //Campo select
				echo '
  <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
	<th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' .ucfirst( $campo ) . ':' . '</label>
	  <span class="woocommerce-help-tip" data-tip="' . sprintf( __( 'The %s for your account in %s', 'woocommerce-apg-sms-notifications' ), $campo, $proveedor ) . '" /> </th>
	<td class="forminp forminp-number"><select class="wc-enhanced-select" id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" tabindex="' . $tab++ . '">
				';
				foreach ( $opciones_de_proveedores[$valor_campo] as $valor_opcion => $opcion ) {
					$chequea = ( isset( $apg_sms_settings[$valor_campo] ) && $apg_sms_settings[$valor_campo] == $valor_opcion ) ? ' selected="selected"' : '';
					echo '<option value="' . $valor_opcion . '"' . $chequea . '>' . $opcion . '</option>' . PHP_EOL;
				}
				echo '          </select></td>
  </tr>
				';
			} else { //Campo input
				echo '
  <tr valign="top" class="' . $valor . '"><!-- ' . $proveedor . ' -->
	<th scope="row" class="titledesc"> <label for="apg_sms_settings[' . $valor_campo . ']">' . ucfirst( $campo ) . ':' . '</label>
	  <span class="woocommerce-help-tip" data-tip="' . sprintf( __( 'The %s for your account in %s', 'woocommerce-apg-sms-notifications' ), $campo, $proveedor ) . '" /> </th>
	<td class="forminp forminp-number"><input type="text" id="apg_sms_settings[' . $valor_campo . ']" name="apg_sms_settings[' . $valor_campo . ']" size="50" value="' . ( isset( $apg_sms_settings[$valor_campo] ) ? $apg_sms_settings[$valor_campo] : '' ) . '" tabindex="' . $tab++ . '" /></td>
  </tr>
				';
			}
		}
	}
}

/*
Pinta los campos del formulario de envío
*/
function apg_sms_campos_de_envio() {
	global $apg_sms_settings;

	$pais					= new WC_Countries();
	$campos					= $pais->get_address_fields( $pais->get_base_country(), 'shipping_' ); //Campos ordinarios
	$campos_personalizados	= apply_filters( 'woocommerce_checkout_fields', array() );
	if ( isset( $campos_personalizados['shipping'] ) ) {
		$campos += $campos_personalizados['shipping'];
	}
	foreach ( $campos as $valor => $campo ) {
		$chequea = ( isset( $apg_sms_settings['campo_envio'] ) && $apg_sms_settings['campo_envio'] == $valor ) ? ' selected="selected"' : '';
		if ( isset( $campo['label'] ) ) {
			echo '<option value="' . $valor . '"' . $chequea . '>' . $campo['label'] . '</option>' . PHP_EOL;
		}
	}
}

/*
Pinta el campo select con el listado de estados de pedido
*/
function apg_sms_listado_de_estados( $listado_de_estados ) {
	global $apg_sms_settings;

	foreach( $listado_de_estados as $nombre_de_estado => $estado ) {
		$chequea = '';
		if ( isset( $apg_sms_settings['estados_personalizados'] ) ) {
			foreach ( $apg_sms_settings['estados_personalizados'] as $estado_personalizado ) {
				if ( $estado_personalizado == $estado ) {
					$chequea = ' selected="selected"';
				}
			}
		}
		echo '<option value="' . $estado . '"' . $chequea . '>' . $nombre_de_estado . '</option>' . PHP_EOL;
	}
}

/*
Pinta el campo select con el listado de mensajes personalizados
*/
function apg_sms_listado_de_mensajes( $listado_de_mensajes ) {
	global $apg_sms_settings;
	
	$chequeado = false;
	foreach ( $listado_de_mensajes as $valor => $mensaje ) {
		if ( isset( $apg_sms_settings['mensajes'] ) && in_array( $valor, $apg_sms_settings['mensajes'] ) ) {
			$chequea	= ' selected="selected"';
			$chequeado	= true;
		} else {
			$chequea	= '';
		}
		$texto = ( !isset( $apg_sms_settings['mensajes'] ) && $valor == 'todos' && !$chequeado ) ? ' selected="selected"' : '';
		echo '<option value="' . $valor . '"' . $chequea . $texto . '>' . $mensaje . '</option>' . PHP_EOL;
	}
}

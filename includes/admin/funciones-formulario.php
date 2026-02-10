<?php
// Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

global $apg_sms_settings, $wpml_activo, $mensajes;

// Control de tabulación
$tab    = 1;

// WPML
if ( $apg_sms_settings ) {    
    foreach ( $mensajes as $mensaje ) {
        if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { // Versión anterior a la 3.2
            $$mensaje		= ( $wpml_activo ) ? icl_translate( 'apg_sms', $mensaje, esc_textarea( $apg_sms_settings[ $mensaje ] ) ) : esc_textarea( $apg_sms_settings[ $mensaje ] );
        } elseif ( $wpml_activo ) { // Versión 3.2 o superior
            $$mensaje		= apply_filters( 'wpml_translate_single_string', esc_textarea( $apg_sms_settings[ $mensaje ] ), 'apg_sms', $mensaje );
        }
    }
} else { // Inicializa variables
    foreach ( $mensajes as $mensaje ) {
        $$mensaje   = '';
    }
}

// Listado de proveedores SMS
$listado_de_proveedores = [ 
        "adlinks"           => "Adlinks Labs",
        "altiria"           => "Altiria",
        "bulkgate"          => "BulkGate",
        "bulksms"           => "BulkSMS",
        "clickatell"        => "Clickatell",
        "clockwork"         => "Clockwork",
        "isms"              => "iSMS Malaysia",
        "labsmobile"        => "LabsMobile",
        "mobtexting"        => "MobTexting",
        "moplet"            => "Moplet",
        "msg91"             => "MSG91",
        "nexmo"             => "Nexmo",
        "plivo"             => "Plivo",
        "routee"            => "Routee",
        "sendsms"           => "sendSMS.ro",
        "sipdiscount"       => "SIP Discount",
        "smscx"             => "SMS.CX (SMS Connexion)",
        "smscountry"        => "SMS Country",
        "smsdiscount"       => "SMS Discount",
        "smslane"           => "SMS Lane ( Transactional SMS only )",
        "solutions_infini"  => "Solutions Infini",
        "springedge"        => "Spring Edge",
        "twilio"            => "Twilio",
        "twizo"             => "Twizo",
        "voipbuster"        => "VoipBuster",
        "voipbusterpro"     => "VoipBusterPro",
        "voipstunt"         => "VoipStunt",
];
asort( $listado_de_proveedores, SORT_NATURAL | SORT_FLAG_CASE ); // Ordena alfabeticamente los proveedores

// Campos necesarios para cada proveedor
$campos_de_proveedores      = [
	"adlinks"			=> [
 		"usuario_adlinks"                 => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
 		"ruta_adlinks"                    => __( 'route', 'woocommerce-apg-sms-notifications' ),
 		"identificador_adlinks"           => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
 	],
	"altiria"			=> [
 		"usuario_altiria"                 => __( 'username', 'woocommerce-apg-sms-notifications' ),
 		"contrasena_altiria"              => __( 'password', 'woocommerce-apg-sms-notifications' ),
 	],
	"bulkgate"			=> [
 		"usuario_bulkgate"                => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
 		"clave_bulkgate"                  => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
 		"identificador_bulkgate"          => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
 		"unicode_bulkgate"                => __( 'unicode', 'woocommerce-apg-sms-notifications' ),
    ],
	"bulksms" 			=> [ 
		"usuario_bulksms"                 => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_bulksms"              => __( 'password', 'woocommerce-apg-sms-notifications' ),
		"servidor_bulksms"                => __( 'host', 'woocommerce-apg-sms-notifications' ),
	],
	"clickatell" 		=> [ 
		"identificador_clickatell"        => __( 'key', 'woocommerce-apg-sms-notifications' ),
	],
	"clockwork" 		=> [ 
		"identificador_clockwork"         => __( 'key', 'woocommerce-apg-sms-notifications' ),
	],
	"isms" 				=> [ 
		"usuario_isms"                    => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_isms"                 => __( 'password', 'woocommerce-apg-sms-notifications' ),
		"telefono_isms"                   => __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
	],
	"labsmobile"		=> [
		"usuario_labsmobile"              => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_labsmobile"           => __( 'password', 'woocommerce-apg-sms-notifications' ),
		"sid_labsmobile"                  => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"mobtexting"		=> [ 
		"clave_mobtexting"                => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_mobtexting"        => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"moplet" 			=> [ 
		"clave_moplet"                    => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
		"identificador_moplet"            => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"ruta_moplet"                     => __( 'route', 'woocommerce-apg-sms-notifications' ),
		"servidor_moplet"                 => __( 'host', 'woocommerce-apg-sms-notifications' ),
		"dlt_moplet"                      => __( 'template ID', 'woocommerce-apg-sms-notifications' ),
	],
	"msg91" 			=> [ 
		"clave_msg91"                     => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
		"identificador_msg91"             => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"ruta_msg91"                      => __( 'route', 'woocommerce-apg-sms-notifications' ),
		"dlt_msg91"                       => __( 'template ID', 'woocommerce-apg-sms-notifications' ),
    ],
	"nexmo" 			=> [ 
		"clave_nexmo"                     => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_nexmo"             => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
	],
	"plivo"				=> [
		"usuario_plivo"                   => __( 'authentication ID', 'woocommerce-apg-sms-notifications' ),
		"clave_plivo"                     => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
		"identificador_plivo"             => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"routee"			=> [ 
		"usuario_routee"                  => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
		"contrasena_routee"               => __( 'application secret', 'woocommerce-apg-sms-notifications' ),
		"identificador_routee"            => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	], 
	"sendsms"           => [ 
		"usuario_sendsms"                 => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_sendsms"              => __( 'password', 'woocommerce-apg-sms-notifications' ),
		"short_sendsms"                   => __( 'short URL', 'woocommerce-apg-sms-notifications' ),
		"gdpr_sendsms"                    => __( 'unsubscribe link', 'woocommerce-apg-sms-notifications' ),
	], 
	"sipdiscount"		=> [ 
		"usuario_sipdiscount"             => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_sipdiscount"          => __( 'password', 'woocommerce-apg-sms-notifications' ),
	], 
	"smscx"            => [ 
		"usuario_smscx"                   => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smscx"                => __( 'application secret', 'woocommerce-apg-sms-notifications' ),
		"identificador_smscx"             => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"smscountry" 		=> [ 
		"usuario_smscountry"              => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smscountry"           => __( 'password', 'woocommerce-apg-sms-notifications' ),
		"sid_smscountry"                  => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"smsdiscount"		=> [ 
		"usuario_smsdiscount"             => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smsdiscount"          => __( 'password', 'woocommerce-apg-sms-notifications' ),
	], 
	"smslane" 			=> [ 
		"usuario_smslane"                 => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"contrasena_smslane"              => __( 'client ID', 'woocommerce-apg-sms-notifications' ),
		"sid_smslane"                     => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"solutions_infini" 	=> [ 
		"clave_solutions_infini"          => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_solutions_infini"  => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"springedge" 		=> [ 
		"clave_springedge"                => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_springedge"        => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
	],
	"twilio" 			=> [ 
		"clave_twilio"                    => __( 'account Sid', 'woocommerce-apg-sms-notifications' ),
		"identificador_twilio"            => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
		"telefono_twilio"                 => __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
	],
	"twizo" 			=> [ 
		"clave_twizo"                     => __( 'key', 'woocommerce-apg-sms-notifications' ),
		"identificador_twizo"             => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		"servidor_twizo"                  => __( 'host', 'woocommerce-apg-sms-notifications' ),
	],
	"voipbuster"		=> [ 
		"usuario_voipbuster"              => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipbuster"           => __( 'password', 'woocommerce-apg-sms-notifications' ),
	], 
	"voipbusterpro"		=> [ 
		"usuario_voipbusterpro"           => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipbusterpro"        => __( 'password', 'woocommerce-apg-sms-notifications' ),
	], 
	"voipstunt"			=> [ 
		"usuario_voipstunt"               => __( 'username', 'woocommerce-apg-sms-notifications' ),
		"contrasena_voipstunt"            => __( 'password', 'woocommerce-apg-sms-notifications' ),
	], 
];

// Opciones de campos de selección de los proveedores
$opciones_de_proveedores        = [
	"ruta_adlinks"		=> [
		1						=> 1, 
		4						=> 4,
	],
	"servidor_bulksms"	=> [
		"bulksms.vsms.net"		=> __( 'International', 'woocommerce-apg-sms-notifications' ), 
		"www.bulksms.co.uk"		=> __( 'UK', 'woocommerce-apg-sms-notifications' ),
		"usa.bulksms.com"		=> __( 'USA', 'woocommerce-apg-sms-notifications' ),
		"bulksms.2way.co.za"	=> __( 'South Africa', 'woocommerce-apg-sms-notifications' ),
		"bulksms.com.es"		=> __( 'Spain', 'woocommerce-apg-sms-notifications' ),
	],
	"servidor_moplet"	=> [
		"0"						=> __( 'International', 'woocommerce-apg-sms-notifications' ), 
		"1"						=> __( 'USA', 'woocommerce-apg-sms-notifications' ), 
		"91"					=> __( 'India', 'woocommerce-apg-sms-notifications' ),
	],	
	"ruta_moplet"		=> [
		1						=> 1, 
		4						=> 4,
	],
	"ruta_msg91"		=> [
		"default"				=> __( 'Default', 'woocommerce-apg-sms-notifications' ), 
		1						=> 1, 
		4						=> 4,
	],
	"servidor_twizo"	=> [
		"api-asia-01.twizo.com"	=> __( 'Singapore', 'woocommerce-apg-sms-notifications' ), 
		"api-eu-01.twizo.com"	=> __( 'Germany', 'woocommerce-apg-sms-notifications' ), 
	],
    "unicode_bulkgate"  => [
 		1                       => __( 'Yes', 'woocommerce-apg-sms-notifications' ),
 		0                       => __( 'No', 'woocommerce-apg-sms-notifications' ),
 	],
];

// Campos de verificación
$verificacion_de_proveedores    = [
    "short_sendsms",
    "gdpr_sendsms",
    "dlt_moplet",
    "dlt_msg91",
];

// Listado de estados de pedidos
$listado_de_estados				= wc_get_order_statuses();
$listado_de_estados_temporal	= [];
$estados_originales				= [ 
	'pending',
	'failed',
	'on-hold',
	'processing',
	'completed',
	'refunded',
	'cancelled',
];
foreach ( $listado_de_estados as $clave => $estado ) {
	$nombre_de_estado = str_replace( "wc-", "", $clave );
	if ( ! in_array( $nombre_de_estado, $estados_originales ) ) {
		$listado_de_estados_temporal[ $estado ] = $nombre_de_estado;
	}
}
$listado_de_estados = array_merge( array_flip( $listado_de_estados ), $listado_de_estados_temporal );

// Listado de mensajes personalizados
$listado_de_mensajes = [
	'todos'					=> __( 'All messages', 'woocommerce-apg-sms-notifications' ),
	'mensaje_pedido'		=> __( 'Owner custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_pendiente'		=> __( 'Order pending custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_fallido'		=> __( 'Order failed custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_recibido'		=> __( 'Order on-hold custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_procesando'	=> __( 'Order processing custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_completado'	=> __( 'Order completed custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_devuelto'		=> __( 'Order refunded custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_cancelado'		=> __( 'Order cancelled custom message', 'woocommerce-apg-sms-notifications' ),
	'mensaje_nota'			=> __( 'Notes custom message', 'woocommerce-apg-sms-notifications' ),
];

/**
 * Pinta el campo select con el listado de proveedores.
 *
 * @param array<string,string> $listado_de_proveedores Listado de proveedores.
 * @return void
 */
function apg_sms_listado_de_proveedores( $listado_de_proveedores ) {
	global $apg_sms_settings;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		$chequea = isset( $apg_sms_settings['servicio'] ) ? $apg_sms_settings['servicio'] : '';
		echo '<option value="' . esc_attr( $valor ) . '"' . selected( $chequea, $valor, false ) . '>' . esc_html( $proveedor ) . '</option>' . PHP_EOL;
	}
}

/**
 * Pinta los campos de los proveedores.
 *
 * @param array<string,string> $listado_de_proveedores    Listado de proveedores.
 * @param array<string,array>  $campos_de_proveedores     Campos por proveedor.
 * @param array<string,array>  $opciones_de_proveedores   Opciones por proveedor.
 * @param array<int,string>    $verificacion_de_proveedores Campos tipo checkbox.
 * @return void
 */
function apg_sms_campos_de_proveedores( $listado_de_proveedores, $campos_de_proveedores, $opciones_de_proveedores, $verificacion_de_proveedores ) {
	global $apg_sms_settings, $tab;
	
	foreach ( $listado_de_proveedores as $valor => $proveedor ) {
		foreach ( $campos_de_proveedores[$valor] as $valor_campo => $campo ) {
			$valor_attr       = $valor;
			$proveedor_esc    = $proveedor;
			$valor_campo_attr = $valor_campo;
			$campo_label      = ucfirst( $campo );
			// translators: 1: Field label, 2: Provider name.
			$tooltip_cuenta = sprintf( esc_attr__( 'The %1$s for your account in %2$s', 'woocommerce-apg-sms-notifications' ), $campo, $proveedor );
			$tooltip_cuenta = esc_attr( $tooltip_cuenta );
			if ( array_key_exists( $valor_campo, $opciones_de_proveedores ) ) { // Campo select
				echo '
  <tr valign="top" class="' . esc_attr( $valor_attr ) . '"><!-- ' . esc_html( $proveedor_esc ) . ' -->
	<th scope="row" class="titledesc"> <label for="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']">' . esc_html( $campo_label ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . esc_attr( $tooltip_cuenta ) . '"></span></label></th>
	<td class="forminp forminp-number"><select class="wc-enhanced-select" id="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" name="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" tabindex="' . esc_attr( $tab++ ) . '">
				';
				foreach ( $opciones_de_proveedores[$valor_campo] as $valor_opcion => $opcion ) {
					$seleccion = isset( $apg_sms_settings[ $valor_campo ] ) ? $apg_sms_settings[ $valor_campo ] : '';
					echo '<option value="' . esc_attr( $valor_opcion ) . '"' . selected( $seleccion, $valor_opcion, false ) . '>' . esc_html( $opcion ) . '</option>' . PHP_EOL;
				}
				echo '          </select></td>
  </tr>
				';
			} elseif ( in_array( $valor_campo, $verificacion_de_proveedores ) ) { // Campo checkbox
                $dlt_class  = ( strpos( $valor_campo, 'dlt_' ) !== false ) ? 'dlt' : '';
                $chequea    = isset( $apg_sms_settings[ $valor_campo ] ) ? $apg_sms_settings[ $valor_campo ] : '';
				echo '
  <tr valign="top" class="' . esc_attr( $valor_attr ) . '"><!-- ' . esc_html( $proveedor_esc ) . ' -->
	<th scope="row" class="titledesc"> <label for="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']">' . esc_html( $campo_label ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . esc_attr( $tooltip_cuenta ) . '"></span></label></th>
	<td class="forminp forminp-number"><input type="checkbox" class="' . esc_attr( $dlt_class ) . '" id="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" name="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" value="1"' . checked( $chequea, 1, false ) . ' tabindex="' . esc_attr( $tab++ ) . '" ></td>
  </tr>
				';
            } else { // Campo input
				echo '
  <tr valign="top" class="' . esc_attr( $valor_attr ) . '"><!-- ' . esc_html( $proveedor_esc ) . ' -->
	<th scope="row" class="titledesc"> <label for="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']">' . esc_html( $campo_label ) . ':' . '
	  <span class="woocommerce-help-tip" data-tip="' . esc_attr( $tooltip_cuenta ) . '"></span></label></th>
	<td class="forminp forminp-number"><input type="text" id="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" name="apg_sms_settings[' . esc_attr( $valor_campo_attr ) . ']" size="50" value="' . ( isset( $apg_sms_settings[ $valor_campo ] ) ? esc_attr( $apg_sms_settings[ $valor_campo ] ) : '' ) . '" tabindex="' . esc_attr( $tab++ ) . '" /></td>
  </tr>
				';
			}
		}
	}
}

/**
 * Pinta los campos del formulario de envio.
 *
 * @return void
 */
function apg_sms_campos_de_envio() {
	global $apg_sms_settings;

	$pais					= new WC_Countries();
	$campos					= $pais->get_address_fields( $pais->get_base_country(), 'shipping_' ); // Campos ordinarios
	$campos_personalizados	= apply_filters( 'woocommerce_checkout_fields', [] );
	if ( isset( $campos_personalizados[ 'shipping' ] ) ) {
		$campos += $campos_personalizados[ 'shipping' ];
	}
	foreach ( $campos as $valor => $campo ) {
		$seleccion = isset( $apg_sms_settings['campo_envio'] ) ? $apg_sms_settings['campo_envio'] : '';
		if ( isset( $campo[ 'label' ] ) ) {
			echo '<option value="' . esc_attr( $valor ) . '"' . selected( $seleccion, $valor, false ) . '>' . esc_html( $campo['label'] ) . '</option>' . PHP_EOL;
		}
	}
}

/**
 * Pinta el campo select con el listado de estados de pedido.
 *
 * @param array<string,string> $listado_de_estados Listado de estados.
 * @return void
 */
function apg_sms_listado_de_estados( $listado_de_estados ) {
	global $apg_sms_settings;

	foreach ( $listado_de_estados as $nombre_de_estado => $estado ) {
		$seleccionados = isset( $apg_sms_settings['estados_personalizados'] ) ? (array) $apg_sms_settings['estados_personalizados'] : array();
		$chequea       = in_array( $estado, $seleccionados, true );
		echo '<option value="' . esc_attr( $estado ) . '"' . selected( $chequea, true, false ) . '>' . esc_html( $nombre_de_estado ) . '</option>' . PHP_EOL;
	}
}

/**
 * Pinta el campo select con el listado de mensajes personalizados.
 *
 * @param array<string,string> $listado_de_mensajes Listado de mensajes.
 * @return void
 */
function apg_sms_listado_de_mensajes( $listado_de_mensajes ) {
	global $apg_sms_settings;
	
	$chequeado = false;
	foreach ( $listado_de_mensajes as $valor => $mensaje ) {
		if ( isset( $apg_sms_settings[ 'mensajes' ] ) && in_array( $valor, $apg_sms_settings[ 'mensajes' ] ) ) {
			$chequea	= true;
			$chequeado	= true;
		} else {
			$chequea	= false;
		}
		$texto    = ( ! isset( $apg_sms_settings['mensajes'] ) && $valor === 'todos' && ! $chequeado );
		$selected = ( $chequea || $texto );
		echo '<option value="' . esc_attr( $valor ) . '"' . selected( $selected, true, false ) . '>' . esc_html( $mensaje ) . '</option>' . PHP_EOL;
	}
}

/**
 * Pinta los campos de mensajes personalizados.
 *
 * @param string              $campo           Identificador del campo.
 * @param string              $campo_cliente   Texto personalizado guardado.
 * @param array<string,string> $listado_de_mensajes Listado de mensajes.
 * @return void
 */
function apg_sms_campo_de_mensaje_personalizado( $campo, $campo_cliente, $listado_de_mensajes ) {
    global $tab, $apg_sms_settings;

    // Listado de mensajes personalizados
    $listado_de_mensajes_personalizados = [
		// translators: %1$s: Order number.
		'mensaje_pedido'     => esc_html__( 'Order No. %1$s received on ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_pendiente'  => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_fallido'    => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: 1: Order number, 2: Order date.
		'mensaje_recibido'   => esc_html__( 'Your order No. %1$s is received on %2$s. Thank you for shopping with us!', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_procesando' => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_completado' => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_devuelto'   => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_cancelado'  => esc_html__( 'Thank you for shopping with us! Your order No. %1$s is now: ', 'woocommerce-apg-sms-notifications' ),
		// translators: %1$s: Order number.
		'mensaje_nota'       => esc_html__( 'A note has just been added to your order No. %1$s: ', 'woocommerce-apg-sms-notifications' ),
    ];

    // Listado de textos personalizados
    $listado_de_textos_personalizados = [
		'mensaje_pendiente'  => esc_html__( 'Pending', 'woocommerce-apg-sms-notifications' ),
		'mensaje_fallido'    => esc_html__( 'Failed', 'woocommerce-apg-sms-notifications' ),
		'mensaje_procesando' => esc_html__( 'Processing', 'woocommerce-apg-sms-notifications' ),
		'mensaje_completado' => esc_html__( 'Completed', 'woocommerce-apg-sms-notifications' ),
		'mensaje_devuelto'   => esc_html__( 'Refunded', 'woocommerce-apg-sms-notifications' ),
		'mensaje_cancelado'  => esc_html__( 'Cancelled', 'woocommerce-apg-sms-notifications' ),
    ];

    if ( $campo == 'mensaje_pedido'  ) {
        $texto  = wp_unslash( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( $listado_de_mensajes_personalizados[ $campo ], '%id%' ) . '%shop_name%.' );
    } elseif ( $campo == 'mensaje_recibido'  ) {
        $texto  = wp_unslash( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( $listado_de_mensajes_personalizados[ $campo ], '%id%', '%shop_name%' ) );
    } elseif ( $campo == 'mensaje_nota'  ) {
        $texto  = wp_unslash( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( $listado_de_mensajes_personalizados[ $campo ], '%id%' ) . '%note%' );
    } else {
        $texto  = wp_unslash( ! empty( $campo_cliente ) ? $campo_cliente : sprintf( $listado_de_mensajes_personalizados[ $campo ], '%id%' ) . $listado_de_textos_personalizados[ $campo ] . '.' );
    }
    
    // Listado de mensajes personalizados - DLT
    $listado_de_mensajes_dlt = [
		'mensaje_pedido'     => esc_html__( 'Owner custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_pendiente'  => esc_html__( 'Order pending custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_fallido'    => esc_html__( 'Order failed custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_recibido'   => esc_html__( 'Order on-hold custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_procesando' => esc_html__( 'Order processing custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_completado' => esc_html__( 'Order completed custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_devuelto'   => esc_html__( 'Order refunded custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_cancelado'  => esc_html__( 'Order cancelled custom message template ID', 'woocommerce-apg-sms-notifications' ),
		'mensaje_nota'       => esc_html__( 'Notes custom message template ID', 'woocommerce-apg-sms-notifications' ),
    ];
    
    $texto_dlt  = isset( $apg_sms_settings[ 'dlt_' . $campo ] ) ? $apg_sms_settings[ 'dlt_' . $campo ] : '';
    
    // translators: The %%tokens%% below are placeholders the user can add to SMS templates.
    $ayuda_variables = esc_attr__( 'You can customize your message. Remember that you can use this variables: %%id%%, %%order_key%%, %%billing_first_name%%, %%billing_last_name%%, %%billing_company%%, %%billing_address_1%%, %%billing_address_2%%, %%billing_city%%, %%billing_postcode%%, %%billing_country%%, %%billing_state%%, %%billing_email%%, %%billing_phone%%, %%shipping_first_name%%, %%shipping_last_name%%, %%shipping_company%%, %%shipping_address_1%%, %%shipping_address_2%%, %%shipping_city%%, %%shipping_postcode%%, %%shipping_country%%, %%shipping_state%%, %%shipping_method%%, %%shipping_method_title%%, %%payment_method%%, %%payment_method_title%%, %%order_discount%%, %%cart_discount%%, %%order_tax%%, %%order_shipping%%, %%order_shipping_tax%%, %%order_total%%, %%status%%, %%prices_include_tax%%, %%tax_display_cart%%, %%display_totals_ex_tax%%, %%display_cart_ex_tax%%, %%order_date%%, %%modified_date%%, %%customer_message%%, %%customer_note%%, %%post_status%%, %%shop_name%%, %%order_product%% and %%note%%.', 'woocommerce-apg-sms-notifications' );
	// translators: %s: Custom message label.
	$etiqueta_mensaje = sprintf( __( '%s:', 'woocommerce-apg-sms-notifications' ), $listado_de_mensajes[ $campo ] );
	// translators: %s: Custom message label.
	$etiqueta_dlt = sprintf( __( '%s:', 'woocommerce-apg-sms-notifications' ), $listado_de_mensajes_dlt[ $campo ] );
	// translators: %s: Custom message label.
	$tooltip_dlt = sprintf( __( 'Template ID for %s', 'woocommerce-apg-sms-notifications' ), $listado_de_mensajes[ $campo ] );
	$campo_attr  = $campo;

    echo '
        <tr valign="top" class="' . esc_attr( $campo_attr ) . '">
            <th scope="row" class="titledesc">
                <label for="apg_sms_settings[' . esc_attr( $campo_attr ) . ']">
                    ' . esc_html( $etiqueta_mensaje ) . '
                    <span class="woocommerce-help-tip" data-tip="' . esc_attr( $ayuda_variables ) . '"></span>
                </label>
            </th>
            <td class="forminp forminp-number"><textarea id="apg_sms_settings[' . esc_attr( $campo_attr ) . ']" name="apg_sms_settings[' . esc_attr( $campo_attr ) . ']" cols="50" rows="5" tabindex="' . esc_attr( $tab++ ) . '">' . esc_textarea( $texto ) . '</textarea>
            </td>
        </tr>
        <tr valign="top" class="mensaje_dlt dlt_' . esc_attr( $campo_attr ) . '">
            <th scope="row" class="titledesc">
                <label for="apg_sms_settings[dlt_' . esc_attr( $campo_attr ) . ']">
                    ' . esc_html( $etiqueta_dlt ) . '
                    <span class="woocommerce-help-tip" data-tip="' . esc_attr( $tooltip_dlt ) . '"></span>
                </label>
            </th>
            <td class="forminp forminp-number"><input type="text" id="apg_sms_settings[dlt_' . esc_attr( $campo_attr ) . ']" name="apg_sms_settings[dlt_' . esc_attr( $campo_attr ) . ']" size="50" value="' . esc_attr( $texto_dlt ) . '" tabindex="' . esc_attr( $tab++ ) . '"/>
            </td>
        </tr>';
}

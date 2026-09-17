<?php
/**
 * Definicion y saneado de los ajustes del plugin.
 *
 * Centraliza el listado de pasarelas, sus campos y las opciones validas de cada
 * uno para poder validarlos por lista blanca al guardar. El formulario consume
 * estas mismas listas, de forma que lo que se pinta y lo que se acepta al
 * guardar no pueden separarse.
 *
 * @package WC_APG_SMS_Notifications
 */

// Igual no deberias poder abrirme.
defined( 'ABSPATH' ) || exit;

/**
 * Listado de pasarelas SMS disponibles.
 *
 * @return array<string,string> Mapa identificador => nombre comercial.
 */
function apg_sms_proveedores_disponibles() {
	return [
		'adlinks'          => 'Adlinks Labs',
		'altiria'          => 'Altiria',
		'bulkgate'         => 'BulkGate',
		'bulksms'          => 'BulkSMS',
		'clickatell'       => 'Clickatell',
		'clockwork'        => 'TextAnywhere (Clockwork)',
		'isms'             => 'iSMS Malaysia',
		'labsmobile'       => 'LabsMobile',
		'moplet'           => 'Moplet',
		'msg91'            => 'MSG91',
		'nexmo'            => 'Vonage (Nexmo)',
		'plivo'            => 'Plivo',
		'routee'           => 'Routee',
		'sendsms'          => 'sendSMS.ro',
		'sipdiscount'      => 'SIP Discount',
		'smscx'            => 'SMS.CX (SMS Connexion)',
		'smscountry'       => 'SMS Country',
		'smsdiscount'      => 'SMS Discount',
		'smslane'          => 'SMS Lane ( Transactional SMS only )',
		'solutions_infini' => 'Kaleyra (Solutions Infini)',
		'springedge'       => 'Spring Edge',
		'twilio'           => 'Twilio',
		'twizo'            => 'Silverstreet (Twizo)',
		'voipbuster'       => 'VoipBuster',
		'voipbusterpro'    => 'VoipBusterPro',
		'voipstunt'        => 'VoipStunt',
	];
}

/**
 * Campos de configuracion que necesita cada pasarela.
 *
 * @return array<string,array<string,string>> Mapa proveedor => campos.
 */
function apg_sms_campos_de_cada_proveedor() {
	return [
		'adlinks'          => [
			'usuario_adlinks'       => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
			'ruta_adlinks'          => __( 'route', 'woocommerce-apg-sms-notifications' ),
			'identificador_adlinks' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'altiria'          => [
			'usuario_altiria'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_altiria' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
		'bulkgate'         => [
			'usuario_bulkgate'       => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
			'clave_bulkgate'         => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
			'identificador_bulkgate' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
			'unicode_bulkgate'       => __( 'unicode', 'woocommerce-apg-sms-notifications' ),
		],
		'bulksms'          => [
			'usuario_bulksms'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_bulksms' => __( 'password', 'woocommerce-apg-sms-notifications' ),
			'servidor_bulksms'   => __( 'host', 'woocommerce-apg-sms-notifications' ),
		],
		'clickatell'       => [
			'identificador_clickatell' => __( 'key', 'woocommerce-apg-sms-notifications' ),
		],
		'clockwork'        => [
			'usuario_clockwork'       => __( 'client ID', 'woocommerce-apg-sms-notifications' ),
			'contrasena_clockwork'    => __( 'client password', 'woocommerce-apg-sms-notifications' ),
			'identificador_clockwork' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'isms'             => [
			'usuario_isms'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_isms' => __( 'password', 'woocommerce-apg-sms-notifications' ),
			'telefono_isms'   => __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
		],
		'labsmobile'       => [
			'usuario_labsmobile'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_labsmobile' => __( 'password', 'woocommerce-apg-sms-notifications' ),
			'sid_labsmobile'        => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'moplet'           => [
			'clave_moplet'         => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
			'identificador_moplet' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
			'ruta_moplet'          => __( 'route', 'woocommerce-apg-sms-notifications' ),
			'servidor_moplet'      => __( 'host', 'woocommerce-apg-sms-notifications' ),
			'dlt_moplet'           => __( 'template ID', 'woocommerce-apg-sms-notifications' ),
		],
		'msg91'            => [
			'clave_msg91'         => __( 'authentication key', 'woocommerce-apg-sms-notifications' ),
			'identificador_msg91' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
			'ruta_msg91'          => __( 'route', 'woocommerce-apg-sms-notifications' ),
			'dlt_msg91'           => __( 'template ID', 'woocommerce-apg-sms-notifications' ),
		],
		'nexmo'            => [
			'clave_nexmo'         => __( 'key', 'woocommerce-apg-sms-notifications' ),
			'identificador_nexmo' => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
		],
		'plivo'            => [
			'usuario_plivo'       => __( 'authentication ID', 'woocommerce-apg-sms-notifications' ),
			'clave_plivo'         => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
			'identificador_plivo' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'routee'           => [
			'usuario_routee'       => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
			'contrasena_routee'    => __( 'application secret', 'woocommerce-apg-sms-notifications' ),
			'identificador_routee' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'sendsms'          => [
			'usuario_sendsms'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_sendsms' => __( 'password', 'woocommerce-apg-sms-notifications' ),
			'short_sendsms'      => __( 'short URL', 'woocommerce-apg-sms-notifications' ),
			'gdpr_sendsms'       => __( 'unsubscribe link', 'woocommerce-apg-sms-notifications' ),
		],
		'sipdiscount'      => [
			'usuario_sipdiscount'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_sipdiscount' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
		'smscx'            => [
			'usuario_smscx'       => __( 'application ID', 'woocommerce-apg-sms-notifications' ),
			'contrasena_smscx'    => __( 'application secret', 'woocommerce-apg-sms-notifications' ),
			'identificador_smscx' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'smscountry'       => [
			'usuario_smscountry'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_smscountry' => __( 'password', 'woocommerce-apg-sms-notifications' ),
			'sid_smscountry'        => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'smsdiscount'      => [
			'usuario_smsdiscount'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_smsdiscount' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
		'smslane'          => [
			'usuario_smslane'    => __( 'key', 'woocommerce-apg-sms-notifications' ),
			'contrasena_smslane' => __( 'client ID', 'woocommerce-apg-sms-notifications' ),
			'sid_smslane'        => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'solutions_infini' => [
			'clave_solutions_infini'         => __( 'key', 'woocommerce-apg-sms-notifications' ),
			'identificador_solutions_infini' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'springedge'       => [
			'clave_springedge'         => __( 'key', 'woocommerce-apg-sms-notifications' ),
			'identificador_springedge' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
		],
		'twilio'           => [
			'clave_twilio'         => __( 'account Sid', 'woocommerce-apg-sms-notifications' ),
			'identificador_twilio' => __( 'authentication Token', 'woocommerce-apg-sms-notifications' ),
			'telefono_twilio'      => __( 'mobile number', 'woocommerce-apg-sms-notifications' ),
		],
		'twizo'            => [
			'clave_twizo'         => __( 'key', 'woocommerce-apg-sms-notifications' ),
			'identificador_twizo' => __( 'sender ID', 'woocommerce-apg-sms-notifications' ),
			'servidor_twizo'      => __( 'host', 'woocommerce-apg-sms-notifications' ),
		],
		'voipbuster'       => [
			'usuario_voipbuster'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_voipbuster' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
		'voipbusterpro'    => [
			'usuario_voipbusterpro'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_voipbusterpro' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
		'voipstunt'        => [
			'usuario_voipstunt'    => __( 'username', 'woocommerce-apg-sms-notifications' ),
			'contrasena_voipstunt' => __( 'password', 'woocommerce-apg-sms-notifications' ),
		],
	];
}

/**
 * Valores admitidos por los campos de tipo seleccion.
 *
 * Son lista blanca en los dos sentidos: se pintan como <option> y son los
 * unicos valores que se aceptan al guardar. Los campos 'servidor_*' acaban
 * formando la URL a la que se envian las credenciales de la pasarela, asi que
 * su validacion es lo que impide apuntar el envio a un host arbitrario.
 *
 * @return array<string,array<string|int,string>> Mapa campo => opciones.
 */
function apg_sms_opciones_de_cada_campo() {
	return [
		'ruta_adlinks'     => [
			1 => 1,
			4 => 4,
		],
		'servidor_bulksms' => [
			'bulksms.vsms.net'    => __( 'International', 'woocommerce-apg-sms-notifications' ),
			'www.bulksms.co.uk'   => __( 'UK', 'woocommerce-apg-sms-notifications' ),
			'usa.bulksms.com'     => __( 'USA', 'woocommerce-apg-sms-notifications' ),
			'bulksms.2way.co.za'  => __( 'South Africa', 'woocommerce-apg-sms-notifications' ),
			'bulksms.com.es'      => __( 'Spain', 'woocommerce-apg-sms-notifications' ),
		],
		'servidor_moplet'  => [
			'0'  => __( 'International', 'woocommerce-apg-sms-notifications' ),
			'1'  => __( 'USA', 'woocommerce-apg-sms-notifications' ),
			'91' => __( 'India', 'woocommerce-apg-sms-notifications' ),
		],
		'ruta_moplet'      => [
			1 => 1,
			4 => 4,
		],
		'ruta_msg91'       => [
			'default' => __( 'Default', 'woocommerce-apg-sms-notifications' ),
			1         => 1,
			4         => 4,
		],
		'servidor_twizo'   => [
			'api-asia-01.silverstreet.com' => __( 'Singapore', 'woocommerce-apg-sms-notifications' ),
			'api-eu-01.silverstreet.com'   => __( 'Germany', 'woocommerce-apg-sms-notifications' ),
		],
		'unicode_bulkgate' => [
			1 => __( 'Yes', 'woocommerce-apg-sms-notifications' ),
			0 => __( 'No', 'woocommerce-apg-sms-notifications' ),
		],
	];
}

/**
 * Campos de proveedor que se pintan como casilla de verificacion.
 *
 * @return array<int,string> Lista de campos.
 */
function apg_sms_campos_de_verificacion() {
	return [
		'short_sendsms',
		'gdpr_sendsms',
		'dlt_moplet',
		'dlt_msg91',
	];
}

/**
 * Mensajes personalizados que ofrece el plugin.
 *
 * @return array<string,string> Mapa identificador => etiqueta.
 */
function apg_sms_listado_de_mensajes_disponibles() {
	return [
		'todos'              => __( 'All messages', 'woocommerce-apg-sms-notifications' ),
		'mensaje_pedido'     => __( 'Owner custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_pendiente'  => __( 'Order pending custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_fallido'    => __( 'Order failed custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_recibido'   => __( 'Order on-hold custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_procesando' => __( 'Order processing custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_completado' => __( 'Order completed custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_devuelto'   => __( 'Order refunded custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_cancelado'  => __( 'Order cancelled custom message', 'woocommerce-apg-sms-notifications' ),
		'mensaje_nota'       => __( 'Notes custom message', 'woocommerce-apg-sms-notifications' ),
	];
}

/**
 * Devuelve todas las claves de ajuste de proveedor conocidas.
 *
 * @return array<int,string> Lista de claves.
 */
function apg_sms_claves_de_proveedores() {
	$claves = [];
	foreach ( apg_sms_campos_de_cada_proveedor() as $campos ) {
		$claves = array_merge( $claves, array_keys( $campos ) );
	}

	return array_values( array_unique( $claves ) );
}

/**
 * Rellena con cadena vacia los ajustes que falten.
 *
 * Los proveedores leen sus claves directamente, y quien no ha configurado nunca
 * una pasarela no tiene esas claves guardadas: sin esto cada envio genera
 * avisos de indice indefinido.
 *
 * @param array<string,mixed> $ajustes Ajustes guardados.
 * @return array<string,mixed> Ajustes completos.
 */
function apg_sms_completa_ajustes( $ajustes ) {
	$claves = array_merge(
		apg_sms_claves_de_proveedores(),
		[ 'servicio', 'telefono', 'campo_envio', 'variables', 'retardo', 'temporizador', 'campo_debug' ]
	);

	return array_merge( array_fill_keys( $claves, '' ), (array) $ajustes );
}

/**
 * Sanea y valida los ajustes antes de guardarlos.
 *
 * Todo lo que llega del formulario pasa por aqui: los campos de seleccion y la
 * pasarela contra su lista blanca, los numericos a entero, el correo de
 * depuracion validado como tal y el resto saneado como texto. Las claves que no
 * conoce el plugin (las que pueda anadir un tercero mediante filtros) se
 * conservan saneadas como texto en lugar de descartarse.
 *
 * @param mixed $ajustes Ajustes recibidos del formulario.
 * @return array<string,mixed> Ajustes saneados.
 */
function apg_sms_sanea_ajustes( $ajustes ) {
	if ( ! is_array( $ajustes ) ) {
		return [];
	}

	$proveedores  = apg_sms_proveedores_disponibles();
	$opciones     = apg_sms_opciones_de_cada_campo();
	$verificacion = apg_sms_campos_de_verificacion();
	$mensajes     = apg_sms_listado_de_mensajes_disponibles();
	$claves_estado = array_keys( wc_get_order_statuses() ); // wc-pending, wc-processing, mi-estado...
	$estados       = array_values(
		array_unique(
			array_merge(
				$claves_estado,
				array_map(
					function ( $estado ) {
						return str_replace( 'wc-', '', $estado );
					},
					$claves_estado
				)
			)
		)
	); // El formulario usa la clave con prefijo para los estados de WooCommerce y sin el para los personalizados.
	$textos       = array_merge(
		array_keys( $mensajes ),
		array_map(
			function ( $estado ) {
				return 'dlt_' . $estado;
			},
			array_keys( $mensajes )
		),
		$estados,
		array_map(
			function ( $estado ) {
				return 'dlt_' . $estado;
			},
			$estados
		)
	);
	$saneados     = [];

	foreach ( $ajustes as $clave => $valor ) {
		if ( ! is_string( $clave ) || ! preg_match( '/^[A-Za-z0-9_\-]+$/', $clave ) ) { // Clave con forma inesperada: se descarta entera.
			continue;
		}

		if ( 'mensajes' === $clave ) { // Lista blanca de mensajes.
			$saneados[ $clave ] = array_values( array_intersect( array_map( 'sanitize_key', (array) $valor ), array_keys( $mensajes ) ) );

			continue;
		}

		if ( 'estados_personalizados' === $clave ) { // Lista blanca de estados existentes.
			$saneados[ $clave ] = array_values( array_intersect( array_map( 'sanitize_text_field', (array) $valor ), $estados ) );

			continue;
		}

		if ( is_array( $valor ) ) { // Ningun otro ajuste es un array.
			continue;
		}

		$valor = (string) $valor;

		if ( 'servicio' === $clave ) { // Solo pasarelas que existen.
			$saneados[ $clave ] = isset( $proveedores[ $valor ] ) ? $valor : '';
		} elseif ( isset( $opciones[ $clave ] ) ) { // Solo valores ofrecidos en el desplegable.
			$validos            = array_map( 'strval', array_keys( $opciones[ $clave ] ) );
			$saneados[ $clave ] = in_array( $valor, $validos, true ) ? $valor : (string) reset( $validos );
		} elseif ( in_array( $clave, $verificacion, true ) || in_array( $clave, [ 'notificacion', 'internacional', 'envio', 'productos', 'debug' ], true ) ) { // Casillas.
			$saneados[ $clave ] = ( '1' === $valor ) ? '1' : '';
		} elseif ( in_array( $clave, [ 'retardo', 'temporizador' ], true ) ) { // Minutos y horas.
			$saneados[ $clave ] = ( '' === trim( $valor ) ) ? '' : (string) absint( $valor );
		} elseif ( 'campo_debug' === $clave ) { // Destinatario del informe de depuracion.
			$correo             = sanitize_email( $valor );
			$saneados[ $clave ] = is_email( $correo ) ? $correo : '';
		} elseif ( in_array( $clave, $textos, true ) || 'variables' === $clave ) { // Mensajes y variables personalizadas.
			$saneados[ $clave ] = sanitize_textarea_field( $valor );
		} else { // Credenciales, telefonos y cualquier clave anadida por terceros.
			$saneados[ $clave ] = sanitize_text_field( $valor );
		}
	}

	return $saneados;
}

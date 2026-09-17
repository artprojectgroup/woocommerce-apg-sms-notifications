<?php
// Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

/**
 * Oculta las credenciales de un informe de depuracion.
 *
 * El informe se envia por correo sin cifrar, asi que las claves, contrasenas y
 * cabeceras de autorizacion no pueden viajar en claro. Recorre el array en
 * profundidad y sustituye el valor de todo campo cuyo nombre delate una
 * credencial, conservando el resto para que el informe siga sirviendo.
 *
 * @param mixed $datos Datos del informe.
 * @return mixed Datos con las credenciales sustituidas.
 */
function apg_sms_oculta_credenciales( $datos ) {
	$sensibles = [ 'pass', 'passwd', 'password', 'contrasena', 'authkey', 'auth_key', 'apikey', 'api_key', 'apisecret', 'api_secret', 'secret', 'token', 'authorization', 'clave', 'client_pass', 'application_token', 'authtoken' ];

	if ( ! is_array( $datos ) ) {
		return $datos;
	}

	foreach ( $datos as $clave => $valor ) {
		if ( is_array( $valor ) ) {
			$datos[ $clave ] = apg_sms_oculta_credenciales( $valor );

			continue;
		}

		$normalizada = strtolower( (string) $clave );
		foreach ( $sensibles as $sensible ) {
			if ( false !== strpos( $normalizada, $sensible ) ) {
				$datos[ $clave ] = '***';

				break;
			}
		}
	}

	return $datos;
}

/**
 * Envia un SMS segun el proveedor configurado.
 *
 * @param array<string,mixed> $apg_sms_settings Ajustes del plugin.
 * @param string              $telefono         Numero de destino.
 * @param string              $mensaje          Mensaje a enviar.
 * @param string              $estado           Estado del pedido.
 * @param bool                $propietario      Si es mensaje al propietario.
 * @return void
 */
function apg_sms_envia_sms( $apg_sms_settings, $telefono, $mensaje, $estado, $propietario = false ) {
    $apg_sms_settings   = apg_sms_completa_ajustes( $apg_sms_settings ); // Cada proveedor lee sus propias claves: evita avisos si aun no estan configuradas

    // Gestiona los estados
	switch ( $estado ) {
		case "on-hold":
            $estado    = ( $propietario ) ? "mensaje_pedido" : "mensaje_recibido";
            
            break;
		case "pending":
            $estado    = "mensaje_pendiente";
            
            break;
		case "failed":
            $estado    = "mensaje_fallido";
            
            break;
		case "processing":
            $estado    = ( $propietario ) ? "mensaje_pedido" : "mensaje_procesando";
            
            break;
		case "completed":
            $estado    = "mensaje_completado";
            
            break;
		case "refunded":
            $estado    = "mensaje_devuelto";
            
            break;
		case "cancelled":
            $estado    = "mensaje_cancelado";
            
            break;
    }

    // Gestiona los proveedores
    $respuesta  = null; // Queda a null si el servicio configurado ya no existe

	switch ( $apg_sms_settings[ 'servicio' ] ) {
		case "adlinks":
 			$url						= add_query_arg( [
 				'authkey'					=> $apg_sms_settings[ 'usuario_adlinks' ],
 				'mobiles'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $apg_sms_settings[ 'identificador_adlinks' ],
 				'route'						=> $apg_sms_settings[ 'ruta_adlinks' ],
 				'country'					=> 0,
 			], 'http://adlinks.websmsc.com/api/sendhttp.php' ); // No es HTTPS a proposito: el certificado del host esta caducado desde 2021-01-09 (y es de otro dominio, CN=www.walkover.in), asi que wp_remote_get() lo rechazaria. Comprobado el 2026-09-17.
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "altiria":
            $url                        = add_query_arg( [
 				'cmd'                      => 'sendsms',
 				'login'                    => $apg_sms_settings[ 'usuario_altiria' ],
 				'passwd'                   => $apg_sms_settings[ 'contrasena_altiria' ],
 				'dest'                     => $telefono,
 				'msg'                      => apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.altiria.net/api/http' ); // Certificado valido comprobado el 2026-09-17.
            
 			$respuesta					= wp_remote_post( $url );
            
			break;
		case "bulkgate":
 			$url						= add_query_arg( [
 				'application_id'			=> $apg_sms_settings[ 'usuario_bulkgate' ],
 				'application_token'			=> $apg_sms_settings[ 'clave_bulkgate' ],
 				'number'					=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'unicode'					=> intval( $apg_sms_settings[ 'unicode_bulkgate' ] ),
 				'sender_id'					=> 'gText',
 				'sender_id_value'			=> $apg_sms_settings[ 'identificador_bulkgate' ],
 			], 'https://portal.bulkgate.com/api/1.0/simple/transactional' );
            
 			$respuesta					= wp_remote_get( $url );
            
 			break;
		case "bulksms":
			$argumentos[ 'body' ]		= [ 
				'username' 					=> $apg_sms_settings[ 'usuario_bulksms' ],
				'password' 					=> $apg_sms_settings[ 'contrasena_bulksms' ],
				'message' 					=> $mensaje,
				'msisdn' 					=> $telefono,
				'allow_concat_text_sms'		=> 1,
                'concat_text_sms_max_parts'	=> 6,
            ];
            
			$respuesta					= wp_remote_post( "https://" . $apg_sms_settings[ 'servidor_bulksms' ] . "/eapi/submission/send_sms/2/2.0", $argumentos );
            
			break;
		case "clickatell":
 			$url						= add_query_arg( [
 				'apiKey'					=> $apg_sms_settings[ 'identificador_clickatell' ],
 				'to'						=> $telefono,
 				'content'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://platform.clickatell.com/messages/http/send' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "clockwork": // Migrado a TextAnywhere (HTTP SendSMSEx) tras el rebrand de Clockwork. Las credenciales son de una cuenta TextAnywhere.
			$argumentos[ 'body' ]		= [
				'Client_ID'					=> $apg_sms_settings[ 'usuario_clockwork' ],
				'Client_Pass'				=> $apg_sms_settings[ 'contrasena_clockwork' ],
				'Connection'				=> 2, // Enterprise
				'OType'						=> 1, // Originator alfanumérico
				'Originator'				=> $apg_sms_settings[ 'identificador_clockwork' ],
				'DestinationEx'				=> '+' . ltrim( $telefono, '+' ), // Formato internacional con prefijo +
				'Body'						=> apg_sms_normaliza_mensaje( $mensaje ),
				'SMS_Type'					=> 0,
				'Reply_Type'				=> 0, // Sin gestión de respuestas
			];

 			$respuesta					= wp_remote_post( "https://ws.textanywhere.net/HTTPRX/SendSMSEx.aspx", $argumentos );

			break;
		case "isms":
 			$url						= add_query_arg( [
 				'un'						=> $apg_sms_settings[ 'usuario_isms' ],
 				'pwd'						=> $apg_sms_settings[ 'contrasena_isms' ],
 				'dstno'						=> $telefono,
 				'msg'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'type'						=> 2,
 				'sendid'					=> $apg_sms_settings[ 'telefono_isms' ],
 			], 'https://www.isms.com.my/isms_send.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "labsmobile":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_labsmobile' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_labsmobile' ],
 				'msisdn'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( apg_sms_normaliza_mensaje( $mensaje ) ),
 				'sender'					=> $apg_sms_settings[ 'sid_labsmobile' ],
 			], 'https://api.labsmobile.com/get/send.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;			
		case "moplet":
            $argumentos                 = [
 				'authkey'					=> $apg_sms_settings[ 'clave_moplet' ],
 				'mobiles'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $apg_sms_settings[ 'identificador_moplet' ],
 				'route'						=> $apg_sms_settings[ 'ruta_moplet' ],
 				'country'					=> $apg_sms_settings[ 'servidor_moplet' ],
            ];
            // DLT
            if ( ! empty( $apg_sms_settings[ 'dlt_moplet' ] ) && isset( $apg_sms_settings[ 'dlt_' . $estado ] ) ) { // Sólo si existe el valor
 				$argumentos[ 'DLT_TE_ID' ] = $apg_sms_settings[ 'dlt_' . $estado ];
            }
            $url						= add_query_arg( $argumentos, 'http://sms.moplet.com/api/sendhttp.php' ); // Mismo caso que Adlinks Labs: certificado caducado desde 2021-01-09 y emitido para otro dominio. Comprobado el 2026-09-17.
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "msg91":
            $argumentos[ 'body' ]		= [ 
                'authkey' 					=> $apg_sms_settings[ 'clave_msg91' ],
                'mobiles' 					=> $telefono,
                'message' 					=> apg_sms_codifica_el_mensaje( apg_sms_normaliza_mensaje( $mensaje ) ),
                'sender' 					=> $apg_sms_settings[ 'identificador_msg91' ],
                'route' 					=> $apg_sms_settings[ 'ruta_msg91' ],
            ];
            // DLT
            if ( ! empty( $apg_sms_settings[ 'dlt_msg91' ] ) && isset( $apg_sms_settings[ 'dlt_' . $estado ] ) ) { // Sólo si existe el valor
 				$argumentos[ 'body' ][ 'DLT_TE_ID' ] = $apg_sms_settings[ 'dlt_' . $estado ];
            }
            
			$respuesta					= wp_remote_post( "https://api.msg91.com/api/sendhttp.php", $argumentos );
            
			break;
		case "nexmo":
 			$url						= add_query_arg( [
 				'api_key'					=> $apg_sms_settings[ 'clave_nexmo' ],
 				'api_secret'				=> $apg_sms_settings[ 'identificador_nexmo' ],
 				'from'						=> 'NEXMO',
 				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://rest.nexmo.com/sms/json' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "plivo":
			$argumentos[ 'headers' ]	= [
				'Authorization'				=> 'Basic ' . base64_encode( $apg_sms_settings[ 'usuario_plivo' ] . ":" . $apg_sms_settings[ 'clave_plivo' ] ),
				'Connection'				=> 'close',
				'Content-Type'				=> 'application/json',
			];
			$argumentos[ 'body' ]		= json_encode( [
				'src'						=> ( trim( $apg_sms_settings[ 'identificador_plivo' ] ) != '' ? $apg_sms_settings[ 'identificador_plivo' ] : $apg_sms_settings[ 'telefono' ] ),
				'dst'						=> $telefono,
				'text'						=> $mensaje,
				'type'						=> 'sms',
			] );
            
			$respuesta					= wp_remote_post( "https://api.plivo.com/v1/Account/" . rawurlencode( $apg_sms_settings[ 'usuario_plivo' ] ) . "/Message/", $argumentos );
            
			break;
		case "routee":
			$argumentos[ 'headers' ] 	= [
				'Authorization'				=> 'Basic ' . base64_encode( $apg_sms_settings[ 'usuario_routee' ] . ":" . $apg_sms_settings[ 'contrasena_routee' ] ),
				'Content-Type'				=> 'application/x-www-form-urlencoded',
			];
			$argumentos[ 'body' ] 		= [
				'grant_type'				=> 'client_credentials',
			];
            
			$respuesta					= wp_remote_post( "https://auth.routee.net/oauth/token", $argumentos );
			$routee						= is_wp_error( $respuesta ) ? null : json_decode( wp_remote_retrieve_body( $respuesta ) ); // Un fallo de red devuelve WP_Error, no un array

            if ( isset( $routee->access_token ) ) {
                $argumentos[ 'headers' ]	= [
                    'Authorization'				=> 'Bearer ' . $routee->access_token,
                    'Content-Type'				=> 'application/json',
                ];
                $argumentos[ 'body' ]		= json_encode( [
                    'body'						=> $mensaje,
                    'to'						=> $telefono,
                    'from'						=> $apg_sms_settings[ 'identificador_routee' ],
                ] );

                $respuesta 					= wp_remote_post( "https://connect.routee.net/sms", $argumentos );
            }
            
			break;
		case "sendsms":
            $url						= add_query_arg( [
                'action'                    => ( $apg_sms_settings[ 'gdpr_sendsms' ] == 1 ) ? 'message_send_gdpr' : 'message_send',
                'username'					=> $apg_sms_settings[ 'usuario_sendsms' ],
                'password'					=> urlencode( $apg_sms_settings[ 'contrasena_sendsms' ] ),
                'to'                        => $telefono,
                'text'                      => apg_sms_codifica_el_mensaje( $mensaje ),
                'short'                     => ( $apg_sms_settings[ 'short_sendsms' ] == 1 ) ? 'true' : 'false',
            ], 'https://api.sendsms.ro/json' );
            
 			$respuesta					= wp_remote_get( $url );
            
            break;
		case "sipdiscount":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_sipdiscount' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_sipdiscount' ],
 				'from'						=> $apg_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.sipdiscount.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "smscx":
			$argumentos[ 'headers' ] 	= [
				'Authorization'				=> 'Basic ' . base64_encode( $apg_sms_settings[ 'usuario_smscx' ] . ":" . $apg_sms_settings[ 'contrasena_smscx' ] ),
				'Content-Type'				=> 'application/x-www-form-urlencoded',
			];
			$argumentos[ 'body' ] 		= [
				'grant_type'				=> 'client_credentials',
			];
            
			$respuesta					= wp_remote_post( "https://api.sms.cx/oauth/token", $argumentos );
			$smscx						= is_wp_error( $respuesta ) ? null : json_decode( wp_remote_retrieve_body( $respuesta ) ); // Un fallo de red devuelve WP_Error, no un array
            
            if ( isset( $smscx->access_token ) ) {
                $pais                       = explode ( ":", get_option( 'woocommerce_default_country' ) );

                $argumentos[ 'headers' ]    = [
                    'Authorization'				=> 'Bearer ' . $smscx->access_token,
                    'Content-Type'				=> 'application/json',
                ];
                $argumentos[ 'body' ]		= json_encode( [
                    'text'						=> $mensaje,
                    'to'						=> $telefono,
                    'from'						=> $apg_sms_settings[ 'identificador_smscx' ],
                    'countryIso'                => $pais[ 0 ],
                ] );

                $respuesta 					= wp_remote_post( "https://api.sms.cx/sms", $argumentos );
            }
            
			break;
        case "smscountry":
			$argumentos[ 'body' ]		= [ 
				'User' 						=> $apg_sms_settings[ 'usuario_smscountry' ],
				'passwd' 					=> $apg_sms_settings[ 'contrasena_smscountry' ],
				'mobilenumber' 				=> $telefono,
				'sid' 						=> $apg_sms_settings[ 'sid_smscountry' ],
				'message' 					=> $mensaje,
				'mtype' 					=> "N",
				'DR' 						=> "Y",
			];
            
			$respuesta					= wp_remote_post( "https://api.smscountry.com/SMSCwebservice_bulk.aspx", $argumentos );
            
			break;
		case "smsdiscount":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_smsdiscount' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_smsdiscount' ],
 				'from'						=> $apg_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.smsdiscount.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "smslane":
			$argumentos[ 'body' ] 		= json_encode( [ 
				'ApiKey' 					=> $apg_sms_settings[ 'usuario_smslane' ],
				'ClientId' 					=> $apg_sms_settings[ 'contrasena_smslane' ],
				'SenderId' 					=> $apg_sms_settings[ 'sid_smslane' ],
				'Message'					=> $mensaje,
				'MobileNumbers'				=> $telefono,
            ] );
            
			$respuesta 					= wp_remote_post( "https://api.smslane.com/api/v3/SendSMS", $argumentos );
            
            break;
		case "solutions_infini": // Migrado a Kaleyra (API HTTP v4) tras el rebrand de Solutions Infini. Verificar 'api_key' y host de pod en el panel de Kaleyra.
 			$url						= add_query_arg( [
 				'api_key'					=> $apg_sms_settings[ 'clave_solutions_infini' ],
 				'method'					=> 'sms',
				'to'						=> $telefono,
 				'sender'					=> $apg_sms_settings[ 'identificador_solutions_infini' ],
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://api-alerts.kaleyra.com/v4/' );

 			$respuesta					= wp_remote_get( $url );

			break;
		case "springedge":
 			$url						= add_query_arg( [
 				'apikey'					=> $apg_sms_settings[ 'clave_springedge' ],
 				'sender'					=> $apg_sms_settings[ 'identificador_springedge' ],
				'to'						=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
				'format'					=> 'json',
 			], 'https://instantalerts.co/api/web/send/' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;			
		case "twilio":
			$argumentos[ 'headers' ]	= [
				'Accept-Charset'			=> 'utf-8',
				'Authorization'				=> 'Basic ' . base64_encode( $apg_sms_settings[ 'clave_twilio' ] . ":" . $apg_sms_settings[ 'identificador_twilio' ] ), // Autenticacion en la cabecera y no en la URL: asi el token no acaba en los registros de acceso ni en el historial de peticiones
			];
			$argumentos[ 'body' ]		= [ 
				'To' 						=> $telefono,
				'From' 						=> $apg_sms_settings[ 'telefono_twilio' ],
				'Body' 						=> $mensaje,
            ];
            
			$respuesta					= wp_remote_post( "https://api.twilio.com/2010-04-01/Accounts/" . rawurlencode( $apg_sms_settings[ 'clave_twilio' ] ) . "/Messages", $argumentos );
            
			break;
		case "twizo":
			$contenido					= json_encode( [
				'recipients'				=> [ $telefono ],
				'body'						=> $mensaje,
				'sender'					=> $apg_sms_settings[ 'identificador_twizo' ],
				'tag'						=> 'APG SMS Notifications',
			] );
			$argumentos[ 'headers' ]	= [
				'Authorization'				=> "Basic " . base64_encode( "silverstreet:" . $apg_sms_settings[ 'clave_twizo' ] ), // Migrado a Silverstreet (antes Twizo). El API key debe ser de Silverstreet.
				'Accept'					=> 'application/json',
				'Content-Type'				=> 'application/json; charset=utf8',
				'Content-Length'			=> strlen( $contenido ),
				'method'					=> 'POST',
			];
			$argumentos[ 'body' ]		= $contenido;
            
			$respuesta					= wp_remote_post( "https://" . $apg_sms_settings[ 'servidor_twizo' ] . "/v1/sms/submitsimple", $argumentos );
            
			break;
		case "voipbuster":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_voipbuster' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_voipbuster' ],
				'from'						=> $apg_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipbuster.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "voipbusterpro":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_voipbusterpro' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_voipbusterpro' ],
				'from'						=> $apg_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipbusterpro.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "voipstunt":
 			$url						= add_query_arg( [
 				'username'					=> $apg_sms_settings[ 'usuario_voipstunt' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_voipstunt' ],
				'from'						=> $apg_sms_settings[ 'telefono' ],
				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://www.voipstunt.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
	}

    // Envía el correo con el informe
	$destinatario   = isset( $apg_sms_settings[ 'campo_debug' ] ) ? sanitize_email( $apg_sms_settings[ 'campo_debug' ] ) : '';
	if ( isset( $apg_sms_settings[ 'debug' ] ) && $apg_sms_settings[ 'debug' ] == "1" && is_email( $destinatario ) ) {
		$correo	= __( 'Mobile number:', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
        if ( isset( $argumentos ) ) {
            $correo	.= __( 'Arguments: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . wp_json_encode( apg_sms_oculta_credenciales( $argumentos ), JSON_PRETTY_PRINT );
        }
		$correo	.= __( 'Gateway answer: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . wp_json_encode( is_wp_error( $respuesta ) ? $respuesta->get_error_message() : $respuesta, JSON_PRETTY_PRINT );
		wp_mail( $destinatario, 'WC - APG SMS Notifications', $correo, [ 'Content-Type: text/plain; charset=UTF-8' ] ); 
	}
}

<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Envía el mensaje SMS
function apg_sms_envia_sms( $apg_sms_settings, $telefono, $mensaje, $estado, $propietario = false ) {
    //Gestiona los estados
	switch( $estado ) {
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

    //Gestiona los proveedores
	switch ( $apg_sms_settings[ 'servicio' ] ) {
		case "adlinks":
 			$url						= add_query_arg( [
 				'authkey'					=> $apg_sms_settings[ 'usuario_adlinks' ],
 				'mobiles'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $apg_sms_settings[ 'identificador_adlinks' ],
 				'route'						=> $apg_sms_settings[ 'ruta_adlinks' ],
 				'country'					=> 0,
 			], 'http://adlinks.websmsc.com/api/sendhttp.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "altiria":
            $url                        = add_query_arg( [
 				'cmd'                      => 'sendsms',
 				'login'                    => $apg_sms_settings[ 'usuario_altiria' ],
 				'passwd'                   => $apg_sms_settings[ 'contrasena_altiria' ],
 				'dest'                     => $telefono,
 				'msg'                      => apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'http://www.altiria.net/api/http' );
            
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
		case "clockwork":
 			$url						= add_query_arg( [
 				'key'						=> $apg_sms_settings[ 'identificador_clockwork' ],
 				'to'						=> $telefono,
 				'content'					=> apg_sms_normaliza_mensaje( $mensaje ),
 			], 'https://api.clockworksms.com/http/send.aspx' );
            
 			$respuesta					= wp_remote_get( $url );
            
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
        case "mobtexting":
 			$url						= add_query_arg( [
 				'access_token'				=> $apg_sms_settings[ 'clave_mobtexting' ],
 				'to'						=> $telefono,
 				'service'					=> 'T',
 				'sender'					=> $apg_sms_settings[ 'identificador_mobtexting' ],
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://portal.mobtexting.com/api/v2/sms/send' );
            
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
            //DLT
            if ( $apg_sms_settings[ 'dlt_moplet' ] ) { //Sólo si existe el valor
 				$argumentos[ 'DLT_TE_ID' ] = $apg_sms_settings[ 'dlt_' . $estado ];
            }
            $url						= add_query_arg( $argumentos, 'http://sms.moplet.com/api/sendhttp.php' );
            
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
            //DLT
            if ( $apg_sms_settings[ 'dlt_msg91' ] ) { //Sólo si existe el valor
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
            
			$respuesta					= wp_remote_post( "https://api.plivo.com/v1/Account/" . $apg_sms_settings[ 'usuario_plivo' ] . "/Message/", $argumentos );
            
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
			$routee						= json_decode( $respuesta[ 'body' ] );
			
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
                'password'					=> urlencode( [ 'contrasena_sendsms' ] ),
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
			$smscx						= json_decode( $respuesta[ 'body' ] );
            
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
 			], 'https://www.sipdiscount.com/myaccount/sendsms.php' );
            
 			$respuesta					= wp_remote_get( $url );
            
			break;
		case "smslane":
			$argumentos[ 'body' ] 		= [ 
				'ApiKey' 					=> $apg_sms_settings[ 'usuario_smslane' ],
				'ClientId' 					=> $apg_sms_settings[ 'contrasena_smslane' ],
				'SenderId' 					=> $apg_sms_settings[ 'sid_smslane' ],
				'Message'					=> $mensaje,
				'MobileNumbers'				=> $telefono,
            ];
            
			$respuesta 					= wp_remote_post( "https://api.smslane.com/api/v2/SendSMS", $argumentos );
            
            break;
		case "solutions_infini":
 			$url						= add_query_arg( [
 				'workingkey'				=> $apg_sms_settings[ 'clave_solutions_infini' ],
				'to'						=> $telefono,
 				'sender'					=> $apg_sms_settings[ 'identificador_solutions_infini' ],
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://alerts.sinfini.com/api/web2sms.php' );
            
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
			$argumentos[ 'header' ]		= "Accept-Charset: utf-8\r\n";
			$argumentos[ 'body' ]		= [ 
				'To' 						=> $telefono,
				'From' 						=> $apg_sms_settings[ 'telefono_twilio' ],
				'Body' 						=> $mensaje,
            ];
            
			$respuesta					= wp_remote_post( "https://" . $apg_sms_settings[ 'clave_twilio' ] . ":" . $apg_sms_settings[ 'identificador_twilio' ] . "@api.twilio.com/2010-04-01/Accounts/" . $apg_sms_settings[ 'clave_twilio' ] . "/Messages", $argumentos );
            
			break;
		case "twizo":
			$contenido					= json_encode( [
				'recipients'				=> [ $telefono ],
				'body'						=> $mensaje,
				'sender'					=> $apg_sms_settings[ 'identificador_twizo' ],
				'tag'						=> 'APG SMS Notifications',
			] );
			$argumentos[ 'headers' ]	= [
				'Authorization'				=> "Basic " . base64_encode( "twizo:" . $apg_sms_settings[ 'clave_twizo' ] ),
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

    //Envía el correo con el informe
	if ( isset( $apg_sms_settings[ 'debug' ] ) && $apg_sms_settings[ 'debug' ] == "1" && isset( $apg_sms_settings[ 'campo_debug' ] ) ) {
		$correo	= __( 'Mobile number:', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
        if ( isset( $argumentos ) ) {
            $correo	.= __( 'Arguments: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . print_r( $argumentos, true );
        }
		$correo	.= __( 'Gateway answer: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . print_r( $respuesta, true );
		wp_mail( $apg_sms_settings[ 'campo_debug' ], 'WC - APG SMS Notifications', $correo, 'charset=UTF-8' . "\r\n" ); 
	}
}

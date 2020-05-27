<?php
//Envía el mensaje SMS
function apg_sms_envia_sms( $apg_sms_settings, $telefono, $mensaje ) {
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
 				'api_id'					=> $apg_sms_settings[ 'identificador_clickatell' ],
 				'user'						=> $apg_sms_settings[ 'usuario_clickatell' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_clickatell' ],
 				'to'						=> $telefono,
 				'text'						=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://api.clickatell.com/http/sendmsg' );
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
		case "esebun":
 			$url						= add_query_arg( [
 				'user'						=> $apg_sms_settings[ 'usuario_esebun' ],
 				'password'					=> $apg_sms_settings[ 'contrasena_esebun' ],
 				'sender'					=> apg_sms_codifica_el_mensaje( $apg_sms_settings[ 'identificador_esebun' ] ),
 				'SMSText'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'GSM'						=> preg_replace( '/\+/', '', $telefono ),
 			], 'http://api.cloud.bz.esebun.com/api/v3/sendsms/plain' );
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
 			$url						= add_query_arg( [
 				'authkey'					=> $apg_sms_settings[ 'clave_moplet' ],
 				'mobiles'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $apg_sms_settings[ 'identificador_moplet' ],
 				'route'						=> $apg_sms_settings[ 'ruta_moplet' ],
 				'country'					=> $apg_sms_settings[ 'servidor_moplet' ],
 			], 'http://sms.moplet.com/api/sendhttp.php' );
 			$respuesta					= wp_remote_get( $url );
			break;
		case "moreify":
 			$url						= add_query_arg( [
 				'project'					=> $apg_sms_settings[ 'proyecto_moreify' ],
 				'password'					=> $apg_sms_settings[ 'identificador_moreify' ],
 				'phonenumber'				=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 			], 'https://members.moreify.com/api/v1/sendSms' );
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
			$respuesta					= wp_remote_post( "https://control.msg91.com/sendhttp.php", $argumentos );
			break;
		case "msgwow":
 			$url						= add_query_arg( [
 				'authkey'					=> $apg_sms_settings[ 'clave_msgwow' ],
 				'mobiles'					=> $telefono,
 				'message'					=> apg_sms_codifica_el_mensaje( $mensaje ),
 				'sender'					=> $apg_sms_settings[ 'identificador_msgwow' ],
 				'route'						=> $apg_sms_settings[ 'ruta_msgwow' ],
 				'country'					=> $apg_sms_settings[ 'servidor_msgwow' ],
 			], 'http://my.msgwow.com/api/sendhttp.php' );
 			$respuesta					= wp_remote_get( $url );
			break;
		case "mvaayoo":
			$argumentos[ 'body' ]		= [ 
				'user' 						=> $apg_sms_settings[ 'usuario_mvaayoo' ] . ":" . $apg_sms_settings[ 'contrasena_mvaayoo' ],
				'senderID' 					=> $apg_sms_settings[ 'identificador_mvaayoo' ],
				'receipientno' 				=> $telefono,
				'msgtxt' 					=> $mensaje,
				'dcs' 						=> 0,
				'state' 					=> 4,
			 ];
			$respuesta					= wp_remote_post( "http://api.mVaayoo.com/mvaayooapi/MessageCompose", $argumentos );
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
				'user' 						=> $apg_sms_settings[ 'usuario_smslane' ],
				'password' 					=> $apg_sms_settings[ 'contrasena_smslane' ],
				'msisdn' 					=> $telefono,
				'sid' 						=> $apg_sms_settings[ 'sid_smslane' ],
				'msg' 						=> $mensaje,
				'fl' 						=> 0,
				'gwid' 						=> 2,
			 ];
			$respuesta 					= wp_remote_post( "https://smslane.com/vendorsms/pushsms.aspx", $argumentos );
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

	if ( isset( $apg_sms_settings[ 'debug' ] ) && $apg_sms_settings[ 'debug' ] == "1" && isset( $apg_sms_settings[ 'campo_debug' ] ) ) {
		$correo	= __( 'Mobile number:', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
		$correo	.= __( 'Gateway answer: ', 'woocommerce-apg-sms-notifications' ) . "\r\n" . print_r( $respuesta, true );
		wp_mail( $apg_sms_settings[ 'campo_debug' ], 'WC - APG SMS Notifications', $correo, 'charset=UTF-8' . "\r\n" ); 
	}
}

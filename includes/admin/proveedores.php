<?php
//Envía el mensaje SMS
function apg_sms_envia_sms( $configuracion, $telefono, $mensaje ) {
	switch ( $configuracion['servicio'] ) {
		case "voipstunt":
			$respuesta = wp_remote_get( "https://www.voipstunt.com/myaccount/sendsms.php?username=" . $configuracion['usuario_voipstunt'] . "&password=" . $configuracion['contrasena_voipstunt'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "voipbusterpro":
			$respuesta = wp_remote_get( "https://www.voipbusterpro.com/myaccount/sendsms.php?username=" . $configuracion['usuario_voipbusterpro'] . "&password=" . $configuracion['contrasena_voipbusterpro'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "voipbuster":
			$respuesta = wp_remote_get( "https://www.voipbuster.com/myaccount/sendsms.php?username=" . $configuracion['usuario_voipbuster'] . "&password=" . $configuracion['contrasena_voipbuster'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "smsdiscount":
			$respuesta = wp_remote_get( "https://www.smsdiscount.com/myaccount/sendsms.php?username=" . $configuracion['usuario_smsdiscount'] . "&password=" . $configuracion['contrasena_smsdiscount'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "sipdiscount":
			$respuesta = wp_remote_get( "https://www.sipdiscount.com/myaccount/sendsms.php?username=" . $configuracion['usuario_sipdiscount'] . "&password=" . $configuracion['contrasena_sipdiscount'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "solutions_infini":
			$respuesta = wp_remote_get( "http://alerts.sinfini.com/api/web2sms.php?workingkey=" . $configuracion['clave_solutions_infini'] . "&to=" . $telefono . "&sender=" . $configuracion['identificador_solutions_infini'] . "&message=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "clickatell":
			$respuesta = wp_remote_get( "http://api.clickatell.com/http/sendmsg?api_id=" . $configuracion['identificador_clickatell'] . "&user=" . $configuracion['usuario_clickatell'] . "&password=" . $configuracion['contrasena_clickatell'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "clockwork":
			$respuesta = wp_remote_get( "https://api.clockworksms.com/http/send.aspx?key=" . $configuracion['identificador_clockwork'] . "&to=" . $telefono . "&content=" . apg_sms_normaliza_mensaje( $mensaje ) );
			break;
		case "bulksms":
			$respuesta = wp_remote_post( "http://usa.bulksms.com/eapi/submission/send_sms/2/2.0?username=" . urlencode( $configuracion['usuario_bulksms'] ) . "&password=" . urlencode( $configuracion['contrasena_bulksms'] ) . "&message=" . apg_sms_codifica_el_mensaje( $mensaje ) . "&msisdn=" . urlencode( $telefono ) );
			break;
		case "open_dnd":
			$respuesta = wp_remote_get( "http://txn.opendnd.in/pushsms.php?username=" . $configuracion['usuario_open_dnd'] . "&password=" . $configuracion['contrasena_open_dnd'] . "&message=" . apg_sms_codifica_el_mensaje( apg_sms_normaliza_mensaje( $mensaje ) ) . "&sender=" . $configuracion['identificador_open_dnd'] . "&numbers=" . $telefono );
			break;
		case "esebun":
			$respuesta = wp_remote_get( "http://api.cloud.bz.esebun.com/api/v3/sendsms/plain?user=" . $configuracion['usuario_esebun'] . "&password=" . $configuracion['contrasena_esebun'] . "&sender=" . apg_sms_codifica_el_mensaje( $configuracion['identificador_esebun'] ) . "&SMSText=" . apg_sms_codifica_el_mensaje( $mensaje ) . "&GSM=" . preg_replace( '/\+/', '', $telefono ) );
			break;
		case "isms":
			$respuesta = wp_remote_get( "https://www.isms.com.my/isms_send.php?un=" . $configuracion['usuario_isms'] . "&pwd=" . $configuracion['contrasena_isms'] . "&dstno=" . $telefono . "&msg=" . apg_sms_codifica_el_mensaje( $mensaje ) . "&type=2" . "&sendid=" . $configuracion['telefono_isms'] );
			break;
		case "labsmobile":
			$respuesta = wp_remote_get( "https://api.labsmobile.com/get/send.php?client=" . $configuracion['identificador_labsmobile'] . "&username=" . $configuracion['usuario_labsmobile'] . "&password=" . $configuracion['contrasena_labsmobile'] . "&msisdn=" . $telefono . "&message=" . apg_sms_codifica_el_mensaje( $mensaje ) . "&sender=" . $configuracion['sid_labsmobile'] );
			break;			
		case "springedge":
			$respuesta = wp_remote_get( "http://instantalerts.co/api/web/send/?apikey=" . $configuracion['clave_springedge'] . "&sender=" . $configuracion['identificador_springedge'] . "&to=" . $telefono . "&message=" . apg_sms_codifica_el_mensaje( $mensaje ) . "&format=json" );
			break;			
		case "moreify":
			$respuesta = wp_remote_get( "https://members.moreify.com/api/v1/sendSms?project=" . $configuracion['proyecto_moreify'] . "&password=" . $configuracion['identificador_moreify'] . "&phonenumber=" . $telefono . "&message=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "nexmo":
			$respuesta = wp_remote_get( "https://rest.nexmo.com/sms/json?api_key=" . $configuracion['clave_nexmo'] . "&api_secret=" . $configuracion['identificador_nexmo'] . "&from=NEXMO&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje( $mensaje ) );
			break;
		case "twilio":
			$argumentos['header'] = "Accept-Charset: utf-8\r\n";
			$argumentos['body'] = array( 
				'To' 	=> $telefono,
				'From' 	=> $configuracion['telefono_twilio'],
				'Body' 	=> $mensaje
			 );
			$respuesta = wp_remote_post( "https://" . $configuracion['clave_twilio'] . ":" . $configuracion['identificador_twilio'] . "@api.twilio.com/2010-04-01/Accounts/" . $configuracion['clave_twilio'] . "/Messages", $argumentos );
			break;
		case "msg91":
			$argumentos['body'] = array( 
				'authkey' 	=> $configuracion['clave_msg91'],
				'mobiles' 	=> $telefono,
				'message' 	=> apg_sms_codifica_el_mensaje( apg_sms_normaliza_mensaje( $mensaje ) ),
				'sender' 	=> $configuracion['identificador_msg91'],
				'route' 	=> $configuracion['ruta_msg91']
			 );
			$respuesta = wp_remote_post( "http://control.msg91.com/sendhttp.php", $argumentos );
			break;
		case "smslane":
			$argumentos['body'] = array( 
				'user' 		=> $configuracion['usuario_smslane'],
				'password' 	=> $configuracion['contrasena_smslane'],
				'msisdn' 	=> $telefono,
				'sid' 		=> $configuracion['sid_smslane'],
				'msg' 		=> $mensaje,
				'fl' 		=> "0",
				'gwid' 		=> "2",
			 );
			$respuesta = wp_remote_post( "http://smslane.com/vendorsms/pushsms.aspx", $argumentos );
			break;
		case "mvaayoo":
			$argumentos['body'] = array( 
				'user' 			=> $configuracion['usuario_mvaayoo'] . ":" . $configuracion['contrasena_mvaayoo'],
				'senderID' 		=> $configuracion['identificador_mvaayoo'],
				'receipientno' 	=> $telefono,
				'msgtxt' 		=> $mensaje,
				'dcs' 			=> "0",
				'state' 		=> "4",
			 );
			$respuesta = wp_remote_post( "http://api.mVaayoo.com/mvaayooapi/MessageCompose", $argumentos );
			break;
		case "smscountry":
			$argumentos['body'] = array( 
				'User' 			=> $configuracion['usuario_smscountry'],
				'passwd' 		=> $configuracion['contrasena_smscountry'],
				'mobilenumber' 	=> $telefono,
				'sid' 			=> $configuracion['sid_smscountry'],
				'message' 		=> $mensaje,
				'mtype' 		=> "N",
				'DR' 			=> "Y",
			 );
			$respuesta = wp_remote_post( "http://api.smscountry.com/SMSCwebservice_bulk.aspx", $argumentos );
			break;
		case "plivo":
			$argumentos['headers'] = array(
				'Authorization'	=> 'Basic ' . base64_encode( $configuracion['usuario_plivo'] . ":" . $configuracion['clave_plivo'] ),
				'Connection'	=> 'close',
				'Content-Type'	=> 'application/json',
			);
			$argumentos['body'] = json_encode( array(
				'src'			=> ( trim( $configuracion['identificador_plivo'] ) != '' ? $configuracion['identificador_plivo'] : $configuracion['telefono'] ),
				'dst'			=> $telefono,
				'text'			=> $mensaje,
				'type'			=> 'sms',
			) );
			$respuesta = wp_remote_post( "https://api.plivo.com/v1/Account/" . $configuracion['usuario_plivo'] . "/Message/", $argumentos );
			break;
	}

	if ( isset( $configuracion['debug'] ) && $configuracion['debug'] == "1" && isset( $configuracion['campo_debug'] ) ) {
		$correo	= __( 'Mobile number:', 'apg_sms' ) . "\r\n" . $telefono . "\r\n\r\n";
		$correo	.= __( 'Message: ', 'apg_sms' ) . "\r\n" . $mensaje . "\r\n\r\n"; 
		$correo	.= __( 'Gateway answer: ', 'apg_sms' ) . "\r\n" . print_r( $respuesta, true );
		wp_mail( $configuracion['campo_debug'], 'WooCommerce - APG SMS Notifications', $correo, 'charset=UTF-8' . "\r\n" ); 
	}
}


//Mira si necesita el prefijo telefónico internacional
function apg_sms_prefijo( $servicio ) {
	$prefijo = array( 
		"voipstunt", 
		"voipbusterpro", 
		"voipbuster", 
		"smsdiscount", 
		"sipdiscount", 
		"clockwork", 
		"clickatell", 
		"bulksms", 
		"msg91", 
		"twilio", 
		"mvaayoo", 
		"esebun", 
		"isms", 
		"smslane",
		"smscountry",
		"labsmobile",
		"plivo",
		"springedge",
		"moreify",
		"nexmo",
	);
	
	return in_array( $servicio, $prefijo );
}
?>
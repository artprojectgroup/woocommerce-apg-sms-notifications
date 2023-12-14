<?php
//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Comprueba si necesita el prefijo telefónico internacional
function apg_sms_prefijo( $servicio ) {
	$prefijo = [ 
		"adlinks",
		"altiria",
		"bulkgate",
		"bulksms",
		"clickatell",
		"clockwork",
		"esebun",
		"isms",
		"labsmobile",
		"mobtexting",
		"moplet",
		"moreify",
		"msg91",
		"mvaayoo",
		"nexmo",
		"plivo",
		"routee",
		"sendsms",
		"sipdiscount",
		"smscx",
		"smscountry",
		"smsdiscount",
		"smslane",
		"springedge",
		"twilio",
		"twizo",
		"voipbuster",
		"voipbusterpro",
		"voipstunt",
        "waapi",
    ];
	
	return in_array( $servicio, $prefijo );
}

//Normalizamos el texto
function apg_sms_normaliza_mensaje( $mensaje ) {
    if ( ! apply_filters( 'apg_sms_normalize_message', true, $mensaje ) ) {
        return $mensaje;
    }
    
	$reemplazo = [ 
		'Š'			=> 'S', 
		'š'			=> 's', 
		'Đ'			=> 'Dj', 
		'đ'			=> 'dj', 
		'Ž'			=> 'Z', 
		'ž'			=> 'z', 
		'Č'			=> 'C', 
		'č'			=> 'c', 
		'Ć'			=> 'C', 
		'ć'			=> 'c', 
		'À'			=> 'A', 
		'Á'			=> 'A', 
		'Â'			=> 'A', 
		'Ã'			=> 'A', 
		'Ä'			=> 'A', 
		'Å'			=> 'A', 
		'Æ'			=> 'A', 
		'Ç'			=> 'C', 
		'È'			=> 'E', 
		'É'			=> 'E', 
		'Ê'			=> 'E', 
		'Ë'			=> 'E', 
		'Ì'			=> 'I', 
		'Í'			=> 'I', 
		'Î'			=> 'I', 
		'Ï'			=> 'I', 
		'Ñ'			=> 'N', 
		'Ò'			=> 'O', 
		'Ó'			=> 'O', 
		'Ô'			=> 'O', 
		'Õ'			=> 'O', 
		'Ö'			=> 'O', 
		'Ø'			=> 'O', 
		'Ù'			=> 'U', 
		'Ú'			=> 'U', 
		'Û'			=> 'U', 
		'Ü'			=> 'U', 
		'Ý'			=> 'Y', 
		'Þ'			=> 'B', 
		'ß'			=> 'Ss', 
		'à'			=> 'a', 
		'á'			=> 'a', 
		'â'			=> 'a', 
		'ã'			=> 'a', 
		'ä'			=> 'a', 
		'å'			=> 'a', 
		'æ'			=> 'a', 
		'ç'			=> 'c', 
		'è'			=> 'e', 
		'é'			=> 'e', 
		'ê'			=> 'e',  
		'ë'			=> 'e', 
		'ì'			=> 'i', 
		'í'			=> 'i', 
		'î'			=> 'i', 
		'ï'			=> 'i', 
		'ð'			=> 'o', 
		'ñ'			=> 'n', 
		'ò'			=> 'o', 
		'ó'			=> 'o', 
		'ô'			=> 'o', 
		'õ'			=> 'o', 
		'ö'			=> 'o', 
		'ø'			=> 'o', 
		'ù'			=> 'u', 
		'ú'			=> 'u', 
		'û'			=> 'u', 
		'ý'			=> 'y',  
		'ý'			=> 'y', 
		'þ'			=> 'b', 
		'ÿ'			=> 'y', 
		'Ŕ'			=> 'R', 
		'ŕ'			=> 'r', 
		"`"			=> "'", 
		"´"			=> "'", 
		"„"			=> ",", 
		"`"			=> "'",
		"´"			=> "'", 
		"“"			=> "\"", 
		"”"			=> "\"", 
		"´"			=> "'", 
		"&acirc;€™"	=> "'", 
		"{"			=> "", 
		"~"			=> "", 
		"–"			=> "-", 
		"’"			=> "'", 
		"!"			=> ".", 
		"¡"			=> "", 
		"?"			=> ".", 
		"¿"			=> "" 
    ];

	$mensaje = str_replace( array_keys( $reemplazo ), array_values( $reemplazo ), $mensaje );

	return $mensaje;
}

//Codifica el mensaje
function apg_sms_codifica_el_mensaje( $mensaje ) {
	return apply_filters( 'apg_sms_message_return', urlencode( html_entity_decode( $mensaje, ENT_QUOTES, "UTF-8" ) ), $mensaje);
}

//Procesa el teléfono y le añade, si lo necesita, el prefijo
function apg_sms_procesa_el_telefono( $pedido, $telefono, $servicio, $propietario = false, $envio = false ) {
	$telefono_procesado = $telefono;
	
	if ( empty( $telefono_procesado ) ) { //Control
		return apply_filters( 'apg_sms_phone_return', $telefono_procesado, $pedido, $telefono, $servicio, $propietario, $envio );
	}
	
	//Permite que otros plugins impidan que se procese el número de teléfono
	if ( apply_filters( 'apg_sms_phone_process', true, $pedido, $telefono, $servicio, $propietario, $envio ) ) {
		$billing_country		= is_callable( [ $pedido, 'get_billing_country' ] ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$shipping_country		= is_callable( [ $pedido, 'get_shipping_country' ] ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$prefijo				= apg_sms_prefijo( $servicio );
		$telefono_procesado		= str_replace( [ '+', '-' ], '', filter_var( $telefono, FILTER_SANITIZE_NUMBER_INT ) );
		if ( substr( $telefono_procesado, 0, 2 ) == '00' ) { //Código propuesto por Marco Almeida (https://wordpress.org/support/topic/problems-sending-to-international-numbers-via-plivo/)
			$telefono_procesado = substr( $telefono_procesado, 2 );
		}
		if ( ! $propietario ) {
			if ( ( ! $envio && $billing_country && ( WC()->countries->get_base_country() != $billing_country ) || $prefijo ) ) {
				$prefijo_internacional = apg_sms_dame_prefijo_pais( $billing_country ); //Teléfono de facturación
			} else if ( ( $envio && $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) || $prefijo ) ) {
				$prefijo_internacional = apg_sms_dame_prefijo_pais( $shipping_country ); //Teléfono de envío
			}
		} else if ( $propietario && $prefijo ) {
			$prefijo_internacional = apg_sms_dame_prefijo_pais( WC()->countries->get_base_country() );
		}

		preg_match( "/(\d{1,4})[0-9.\-]+/", $telefono_procesado, $prefijo_telefonico );
		if ( empty( $prefijo_telefonico ) ) { //Control
			return;
		}
		if ( isset( $prefijo_internacional ) ) {
			if ( strpos( strval( $prefijo_telefonico[ 1 ] ) , strval( $prefijo_internacional ) ) === false ) {
				$telefono_procesado = $prefijo_internacional . ltrim( $telefono_procesado, '0' );
			}
		}
        //Necesitan el símbolo +
        $simbolo_mas    = [
            "smscx",
            "moreify",
            "twilio"
        ];
        //Necesitan 00
        $doble_cero     = [
            "isms"
        ];
		if ( in_array( $servicio, $simbolo_mas ) && strpos( $telefono_procesado, "+" ) === false ) {
			$telefono_procesado = "+" . $telefono_procesado;
		} else if ( in_array( $servicio, $doble_cero ) && isset( $prefijo_internacional ) ) {
			$telefono_procesado = "00" . preg_replace( '/\+/', '', $telefono_procesado );
		}
	}
	
	//Permitir que otros plugins modifiquen el teléfono devuelto
	return apply_filters( 'apg_sms_phone_return', $telefono_procesado, $pedido, $telefono, $servicio, $propietario, $envio );
}

//Procesa las variables
function apg_sms_procesa_variables( $mensaje, $pedido, $variables, $nota = '' ) {
	global $apg_sms_settings;

	$apg_sms = [ 
		"id", 
		"status", 
		"prices_include_tax", 
		"tax_display_cart", 
		"display_totals_ex_tax", 
		"display_cart_ex_tax", 
		"order_date", 
		"modified_date", 
		"customer_message", 
		"customer_note", 
		"post_status", 
		"shop_name", 
		"note", 
		"order_product",
		"shipping_method", 
    ];
	$apg_sms_variables = [ //Hay que añadirles un guión
		"order_key", 
		"billing_first_name", 
		"billing_last_name", 
		"billing_company", 
		"billing_address_1", 
		"billing_address_2", 
		"billing_city", 
		"billing_postcode", 
		"billing_country", 
		"billing_state", 
		"billing_email", 
		"billing_phone", 
		"shipping_first_name", 
		"shipping_last_name", 
		"shipping_company", 
		"shipping_address_1", 
		"shipping_address_2", 
		"shipping_city", 
		"shipping_postcode", 
		"shipping_country", 
		"shipping_state", 
		"shipping_method_title", 
		"payment_method", 
		"payment_method_title", 
		"order_discount", 
		"cart_discount", 
		"order_tax", 
		"order_shipping", 
		"order_shipping_tax", 
		"order_total" 
    ];
	$variables_personalizadas = explode( "\n", str_replace( [ 
		"\r\n", 
		"\r" 
    ], "\n", $variables ) );

	$numero_de_pedido		= is_callable( [ $pedido, 'get_id' ] ) ? $pedido->get_id() : $pedido->id;
	$variables_de_pedido	= get_post_custom( $numero_de_pedido ); //WooCommerce 2.1
	
	preg_match_all( "/%(.*?)%/", $mensaje, $busqueda );

	foreach ( $busqueda[ 1 ] as $variable ) { 
		$variable = strtolower( $variable );

		if ( !in_array( $variable, $apg_sms ) && !in_array( $variable, $apg_sms_variables ) && !in_array( $variable, $variables_personalizadas ) ) {
			continue;
		}

		$especiales = [  //Variables especiales (no éstandar y no personalizadas)
			"order_date", 
			"modified_date", 
			"shop_name", 
			"note", 
			"id", 
			"order_product",
			"order_discount", 
			"shipping_method_title", 
        ];
		
		if ( ! in_array( $variable, $especiales ) ) {
			if ( in_array( $variable, $apg_sms ) ) {
				$mensaje = str_replace( "%" . $variable . "%", is_callable( [ $pedido, 'get_' . $variable ] ) ? $pedido->{'get_' . $variable}() : $pedido->$variable, $mensaje ); //Variables estándar - Objeto
			} else if ( in_array( $variable, $apg_sms_variables ) ) {
				$mensaje = str_replace( "%" . $variable . "%", $variables_de_pedido[ "_" . $variable ][ 0 ], $mensaje ); //Variables estándar - Array
			} else if ( isset( $variables_de_pedido[ $variable ] ) || in_array( $variable, $variables_personalizadas ) ) {
				$mensaje = str_replace( "%" . $variable . "%", $variables_de_pedido[ $variable ][ 0 ], $mensaje ); //Variables de pedido y personalizadas
			}
		} else if ( $variable == "order_date" || $variable == "modified_date" ) {
			$mensaje = str_replace( "%" . $variable . "%", date_i18n( woocommerce_date_format(), strtotime( $pedido->$variable ) ), $mensaje );
		} else if ( $variable == "shop_name" ) {
			$mensaje = str_replace( "%" . $variable . "%", get_bloginfo( 'name' ), $mensaje );
		} else if ( $variable == "note" ) {
			$mensaje = str_replace( "%" . $variable . "%", $nota, $mensaje );
		} else if ( $variable == "id" ) {
			$mensaje = str_replace( "%" . $variable . "%", $pedido->get_order_number(), $mensaje );
		} else if ( $variable == "order_discount" ) {
			$mensaje = str_replace( "%" . $variable . "%", $pedido->get_discount_total(), $mensaje );
		} else if ( $variable == "shipping_method_title" ) {
			$mensaje = str_replace( "%" . $variable . "%", $pedido->get_shipping_method(), $mensaje );
		} else if ( $variable == "order_product" ) {
			$nombre		= '';
			$productos	= $pedido->get_items();
			if ( ! isset( $apg_sms_settings[ 'productos' ] ) || $apg_sms_settings[ 'productos' ] != 1 ) {
				$nombre = $productos[ key( $productos ) ][ 'name' ];
				if ( strlen( $nombre ) > 10 ) {
					$nombre = substr( $nombre, 0, 10 ) . "...";
				}
				if ( count( $productos ) > 1 ) {
					$nombre .= " (+" .  ( count( $productos ) - 1 ) .")";
				}
			} else {
				foreach ( $productos as $producto ) {
					$nombre .= $producto[ 'quantity' ] . " x " . $producto[ 'name' ] . "\r\n";
				}
			}
			$mensaje = str_replace( "%" . $variable . "%", $nombre, $mensaje );
		}
	}
	
	//Permitir que otros plugins modifiquen el mensaje devuelto
	return apply_filters( 'apg_sms_message' , $mensaje , $numero_de_pedido );
}

//Devuelve el código de prefijo del país
function apg_sms_dame_prefijo_pais( $pais = '' ) {
	$paises = [ 
		'AC' => '247', 
		'AD' => '376', 
		'AE' => '971', 
		'AF' => '93', 
		'AG' => '1268', 
		'AI' => '1264', 
		'AL' => '355', 
		'AM' => '374', 
		'AO' => '244', 
		'AQ' => '672', 
		'AR' => '54', 
		'AS' => '1684', 
		'AT' => '43', 
		'AU' => '61', 
		'AW' => '297', 
		'AX' => '358', 
		'AZ' => '994', 
		'BA' => '387', 
		'BB' => '1246', 
		'BD' => '880', 
		'BE' => '32', 
		'BF' => '226', 
		'BG' => '359', 
		'BH' => '973', 
		'BI' => '257', 
		'BJ' => '229', 
		'BL' => '590', 
		'BM' => '1441', 
		'BN' => '673', 
		'BO' => '591', 
		'BQ' => '599', 
		'BR' => '55', 
		'BS' => '1242', 
		'BT' => '975', 
		'BW' => '267', 
		'BY' => '375', 
		'BZ' => '501', 
		'CA' => '1', 
		'CC' => '61', 
		'CD' => '243', 
		'CF' => '236', 
		'CG' => '242', 
		'CH' => '41', 
		'CI' => '225', 
		'CK' => '682', 
		'CL' => '56', 
		'CM' => '237', 
		'CN' => '86', 
		'CO' => '57', 
		'CR' => '506', 
		'CU' => '53', 
		'CV' => '238', 
		'CW' => '599', 
		'CX' => '61', 
		'CY' => '357', 
		'CZ' => '420', 
		'DE' => '49', 
		'DJ' => '253', 
		'DK' => '45', 
		'DM' => '1767', 
		'DO' => '1809', 
		'DO' => '1829', 
		'DO' => '1849', 
		'DZ' => '213', 
		'EC' => '593', 
		'EE' => '372', 
		'EG' => '20', 
		'EH' => '212', 
		'ER' => '291', 
		'ES' => '34', 
		'ET' => '251', 
		'EU' => '388', 
		'FI' => '358', 
		'FJ' => '679', 
		'FK' => '500', 
		'FM' => '691', 
		'FO' => '298', 
		'FR' => '33', 
		'GA' => '241', 
		'GB' => '44', 
		'GD' => '1473', 
		'GE' => '995', 
		'GF' => '594', 
		'GG' => '44', 
		'GH' => '233', 
		'GI' => '350', 
		'GL' => '299', 
		'GM' => '220', 
		'GN' => '224', 
		'GP' => '590', 
		'GQ' => '240', 
		'GR' => '30', 
		'GT' => '502', 
		'GU' => '1671', 
		'GW' => '245', 
		'GY' => '592', 
		'HK' => '852', 
		'HN' => '504', 
		'HR' => '385', 
		'HT' => '509', 
		'HU' => '36', 
		'ID' => '62', 
		'IE' => '353', 
		'IL' => '972', 
		'IM' => '44', 
		'IN' => '91', 
		'IO' => '246', 
		'IQ' => '964', 
		'IR' => '98', 
		'IS' => '354', 
		'IT' => '39', 
		'JE' => '44', 
		'JM' => '1876', 
		'JO' => '962', 
		'JP' => '81', 
		'KE' => '254', 
		'KG' => '996', 
		'KH' => '855', 
		'KI' => '686', 
		'KM' => '269', 
		'KN' => '1869', 
		'KP' => '850', 
		'KR' => '82', 
		'KW' => '965', 
		'KY' => '1345', 
		'KZ' => '7', 
		'LA' => '856', 
		'LB' => '961', 
		'LC' => '1758', 
		'LI' => '423', 
		'LK' => '94', 
		'LR' => '231', 
		'LS' => '266', 
		'LT' => '370', 
		'LU' => '352', 
		'LV' => '371', 
		'LY' => '218', 
		'MA' => '212', 
		'MC' => '377', 
		'MD' => '373', 
		'ME' => '382', 
		'MF' => '590', 
		'MG' => '261', 
		'MH' => '692', 
		'MK' => '389', 
		'ML' => '223', 
		'MM' => '95', 
		'MN' => '976', 
		'MO' => '853', 
		'MP' => '1670', 
		'MQ' => '596', 
		'MR' => '222', 
		'MS' => '1664', 
		'MT' => '356', 
		'MU' => '230', 
		'MV' => '960', 
		'MW' => '265', 
		'MX' => '52', 
		'MY' => '60', 
		'MZ' => '258', 
		'NA' => '264', 
		'NC' => '687', 
		'NE' => '227', 
		'NF' => '672', 
		'NG' => '234', 
		'NI' => '505', 
		'NL' => '31', 
		'NO' => '47', 
		'NP' => '977', 
		'NR' => '674', 
		'NU' => '683', 
		'NZ' => '64', 
		'OM' => '968', 
		'PA' => '507', 
		'PE' => '51', 
		'PF' => '689', 
		'PG' => '675', 
		'PH' => '63', 
		'PK' => '92', 
		'PL' => '48', 
		'PM' => '508', 
		'PR' => '1787', 
		'PR' => '1939', 
		'PS' => '970', 
		'PT' => '351', 
		'PW' => '680', 
		'PY' => '595', 
		'QA' => '974', 
		'QN' => '374', 
		'QS' => '252', 
		'QY' => '90', 
		'RE' => '262', 
		'RO' => '40', 
		'RS' => '381', 
		'RU' => '7', 
		'RW' => '250', 
		'SA' => '966', 
		'SB' => '677', 
		'SC' => '248', 
		'SD' => '249', 
		'SE' => '46', 
		'SG' => '65', 
		'SH' => '290', 
		'SI' => '386', 
		'SJ' => '47', 
		'SK' => '421', 
		'SL' => '232', 
		'SM' => '378', 
		'SN' => '221', 
		'SO' => '252', 
		'SR' => '597', 
		'SS' => '211', 
		'ST' => '239', 
		'SV' => '503', 
		'SX' => '1721', 
		'SY' => '963', 
		'SZ' => '268', 
		'TA' => '290', 
		'TC' => '1649', 
		'TD' => '235', 
		'TG' => '228', 
		'TH' => '66', 
		'TJ' => '992', 
		'TK' => '690', 
		'TL' => '670', 
		'TM' => '993', 
		'TN' => '216', 
		'TO' => '676', 
		'TR' => '90', 
		'TT' => '1868', 
		'TV' => '688', 
		'TW' => '886', 
		'TZ' => '255', 
		'UA' => '380', 
		'UG' => '256', 
		'UK' => '44', 
		'US' => '1', 
		'UY' => '598', 
		'UZ' => '998', 
		'VA' => '379', 
		'VA' => '39', 
		'VC' => '1784', 
		'VE' => '58', 
		'VG' => '1284', 
		'VI' => '1340', 
		'VN' => '84', 
		'VU' => '678', 
		'WF' => '681', 
		'WS' => '685', 
		'XC' => '991', 
		'XD' => '888', 
		'XG' => '881', 
		'XL' => '883', 
		'XN' => '857', 
		'XN' => '858', 
		'XN' => '870', 
		'XP' => '878', 
		'XR' => '979', 
		'XS' => '808', 
		'XT' => '800', 
		'XV' => '882', 
		'YE' => '967', 
		'YT' => '262', 
		'ZA' => '27', 
		'ZM' => '260', 
		'ZW' => '263' 
    ];

	return ( $pais == '' ) ? $paises : ( isset( $paises[ $pais ] ) ? $paises[ $pais ] : '' );
}

<?php
/*
Plugin Name: WooCommerce - APG SMS Notifications
Version: 2.4.2
Plugin URI: http://wordpress.org/plugins/woocommerce-apg-sms-notifications/
Description: Add to WooCommerce SMS notifications to your clients for order status changes. Also you can receive an SMS message when the shop get a new order and select if you want to send international SMS. The plugin add the international dial code automatically to the client phone number.
Author URI: http://www.artprojectgroup.es/
Author: Art Project Group

Text Domain: apg_sms
Domain Path: /lang
License: GPL2
*/

/*  Copyright 2013  artprojectgroup  (email : info@artprojectgroup.es)

    This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//Definimos las variables
$apg_sms = array(	'plugin' 		=> 'WooCommerce - APG SMS Notifications', 
					'plugin_uri' 	=> 'woocommerce-apg-sms-notifications', 
					'donacion' 	=> 'http://www.artprojectgroup.es/donacion',
					'soporte' 		=> 'http://www.artprojectgroup.es/servicios/servicios-para-wordpress-y-woocommerce/soporte-tecnico',
					'plugin_url' 	=> 'http://www.artprojectgroup.es/plugins-para-wordpress/plugins-para-woocommerce/woocommerce-apg-sms-notifications', 
					'ajustes' 		=> 'admin.php?page=apg_sms', 
					'puntuacion' 	=> 'http://wordpress.org/support/view/plugin-reviews/woocommerce-apg-sms-notifications');

//Carga la configuración del plugin
$configuracion = get_option('apg_sms_settings');
$mensaje_personalizado = array();

//Carga el idioma
load_plugin_textdomain('apg_sms', null, dirname(plugin_basename(__FILE__)) . '/lang');

//Enlaces adicionales personalizados
function apg_sms_enlaces($enlaces, $archivo) {
	global $apg_sms;
	
	$plugin = plugin_basename(__FILE__);

	if ($archivo == $plugin) 
	{
		$plugin = apg_sms_plugin($apg_sms['plugin_uri']);
		$enlaces[] = '<a href="' . $apg_sms['donacion'] . '" target="_blank" title="' . __('Make a donation by ', 'apg_sms') . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[] = '<a href="'. $apg_sms['plugin_url'] . '" target="_blank" title="' . $apg_sms['plugin'] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/artprojectgroup" title="' . __('Follow us on ', 'apg_sms') . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __('Follow us on ', 'apg_sms') . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://plus.google.com/+ArtProjectGroupES" title="' . __('Follow us on ', 'apg_sms') . 'Google+" target="_blank"><span class="genericon genericon-googleplus-alt"></span></a> <a href="http://es.linkedin.com/in/artprojectgroup" title="' . __('Follow us on ', 'apg_sms') . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[] = '<a href="http://profiles.wordpress.org/artprojectgroup/" title="' . __('More plugins on ', 'apg_sms') . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . __('Contact with us by ', 'apg_sms') . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . __('Contact with us by ', 'apg_sms') . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[] = '<div class="star-holder rate"><div style="width:' . esc_attr(str_replace(',', '.', $plugin['rating'])) . 'px;" class="star-rating"></div><div class="star-rate"><a title="' . __('***** Fantastic!', 'apg_sms') . '" href="' . $apg_sms['puntuacion'] . '?rate=5#postform" target="_blank"><span></span></a> <a title="' . __('**** Great', 'apg_sms') . '" href="' . $apg_sms['puntuacion'] . '?rate=4#postform" target="_blank"><span></span></a> <a title="' . __('*** Good', 'apg_sms') . '" href="' . $apg_sms['puntuacion'] . '?rate=3#postform" target="_blank"><span></span></a> <a title="' . __('** Works', 'apg_sms') . '" href="' . $apg_sms['puntuacion'] . '?rate=2#postform" target="_blank"><span></span></a> <a title="' . __('* Poor', 'apg_sms') . '" href="' . $apg_sms['puntuacion'] . '?rate=1#postform" target="_blank"><span></span></a></div></div>';
	}
	
	return $enlaces;
}
add_filter('plugin_row_meta', 'apg_sms_enlaces', 10, 2);

//Añade el botón de configuración
function apg_sms_enlace_de_ajustes($enlaces) { 
	global $apg_sms;
	
	$enlaces_de_ajustes = array('<a href="' . $apg_sms['ajustes'] . '" title="' . __('Settings of ', 'apg_sms') . $apg_sms['plugin'] .'">' . __('Settings', 'apg_sms') . '</a>', '<a href="' . $apg_sms['soporte'] . '" title="' . __('Support of ', 'apg_sms') . $apg_sms['plugin'] .'">' . __('Support', 'apg_sms') . '</a>');
	foreach($enlaces_de_ajustes as $enlace_de_ajustes)	array_unshift($enlaces, $enlace_de_ajustes); 
	
	return $enlaces; 
}
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'apg_sms_enlace_de_ajustes');

//Pinta el formulario de configuración
function apg_sms_tab() {
	wp_enqueue_style('apg_sms_hoja_de_estilo'); //Carga la hoja de estilo
	wp_enqueue_style('woocommerce_chosen_styles');
	wp_enqueue_script('wc-chosen');
	include('formulario.php');
}

//Añade en el menú a WooCommerce
function apg_sms_admin_menu() {
	global $configuracion;
	
	add_submenu_page('woocommerce', __('APG SMS Notifications', 'apg_sms'),  __('SMS Notifications', 'apg_sms') , 'manage_woocommerce', 'apg_sms', 'apg_sms_tab');
	
	//Estos ajustes temporales hay que borrarlos en un par de versiones
	$actualiza = false;
	if ($configuracion['servicio'] == 'twillio')
	{
		$configuracion['clave_twilio'] = $configuracion['clave_twillio'];
		unset($configuracion['clave_twillio']);
		$configuracion['identificador_twilio'] = $configuracion['identificador_twillio'];
		unset($configuracion['identificador_twillio']);
		$configuracion['servicio'] == 'twilio';
		$actualiza = true;
	}
	if (isset($configuracion['telefono']))
	{
		if (!isset($configuracion['telefono_twilio']) || empty($configuracion['telefono_twilio'])) 
		{
			$configuracion['telefono_twilio'] = $configuracion['telefono'];
			$actualiza = true;
		}
		if (!isset($configuracion['telefono_isms']) || empty($configuracion['telefono_isms'])) 
		{
			$configuracion['telefono_isms'] = $configuracion['telefono'];
			$actualiza = true;
		}
	}
	if ($actualiza) update_option('apg_sms_settings', $configuracion);
}
add_action('admin_menu', 'apg_sms_admin_menu', 15);

//Carga los scripts y CSS de WooCommerce
function apg_sms_screen_id($woocommerce_screen_ids) {
	global $woocommerce;

	$woocommerce_screen_ids[] = 'woocommerce_page_apg_sms';

	return $woocommerce_screen_ids;
}
add_filter('woocommerce_screen_ids', 'apg_sms_screen_id');

//Registra las opciones
function apg_sms_registra_opciones() {
	global $configuracion, $mensaje_personalizado;
	
	register_setting('apg_sms_settings_group', 'apg_sms_settings');

	if ((class_exists('WC_Custom_Status') || class_exists('AppZab_Woo_Advance_Order_Status')) && isset($configuracion['estados_personalizados']))
	{
		foreach ($configuracion['estados_personalizados'] as $estado) add_action("woocommerce_order_status_{$estado}", 'apg_sms_procesa_estados', 10); //Funciona cuando se ejecuta WooCommerce Custom Order Statuses & Actions
	}
}
add_action('admin_init', 'apg_sms_registra_opciones');

//Procesa el SMS
function apg_sms_procesa_estados($pedido, $notificacion = false) {
	global $woocommerce, $configuracion, $mensaje_personalizado;

	$pedido = new WC_Order($pedido);
	$estado = $pedido->status;
	$nombres_de_estado = array('on-hold' => 'Recibido', 'processing' => __('Processing', 'apg_sms'), 'completed' => __('Completed', 'apg_sms'));
	foreach ($nombres_de_estado as $nombre_de_estado => $traduccion) if ($estado == $nombre_de_estado) $estado = $traduccion;
	
	$internacional = false;
	$telefono = apg_sms_procesa_el_telefono($pedido, $pedido->billing_phone, $configuracion['servicio']);
	if ($pedido->billing_country && ($woocommerce->countries->get_base_country() != $pedido->billing_country)) $internacional = true;
	$telefono_propietario = apg_sms_procesa_el_telefono($pedido, $configuracion['telefono'], $configuracion['servicio'], true);
	
	if ($estado == 'Recibido')
	{
		if (isset($configuracion['notificacion']) && $configuracion['notificacion'] == 1) apg_sms_envia_sms($configuracion, $telefono_propietario, apg_sms_procesa_variables($configuracion['mensaje_pedido'], $pedido, $configuracion['variables'])); //Mensaje para el propietario
		$mensaje = apg_sms_procesa_variables($configuracion['mensaje_recibido'], $pedido, $configuracion['variables']);
	}
	else if ($estado == __('Processing', 'apg_sms')) 
	{
		if (isset($configuracion['notificacion']) && $configuracion['notificacion'] == 1 && $notificacion) apg_sms_envia_sms($configuracion, $telefono_propietario, apg_sms_procesa_variables($configuracion['mensaje_pedido'], $pedido, $configuracion['variables'])); //Mensaje para el propietario
		$mensaje = apg_sms_procesa_variables($configuracion['mensaje_procesando'], $pedido, $configuracion['variables']);
	}
	else if ($estado == __('Completed', 'apg_sms')) $mensaje = apg_sms_procesa_variables($configuracion['mensaje_completado'], $pedido, $configuracion['variables']);
	else $mensaje = apg_sms_procesa_variables($configuracion[$estado], $pedido, $configuracion['variables']);

	if ((!$internacional || (isset($configuracion['internacional']) && $configuracion['internacional'] == 1)) && !$notificacion) apg_sms_envia_sms($configuracion, $telefono, $mensaje);
}
add_action('woocommerce_order_status_pending_to_on-hold_notification', 'apg_sms_procesa_estados', 10);//Funciona cuando el pedido es marcado como recibido
add_action('woocommerce_order_status_processing', 'apg_sms_procesa_estados', 10);//Funciona cuando el pedido es marcado como procesando
add_action('woocommerce_order_status_completed', 'apg_sms_procesa_estados', 10);//Funciona cuando el pedido es marcado como completo

function apg_sms_notificacion($pedido) {
	apg_sms_procesa_estados($pedido, true);
}
add_action('woocommerce_order_status_pending_to_processing_notification', 'apg_sms_notificacion', 10);//Funciona cuando el pedido es marcado directamente como procesando

//Envía las notas de cliente por SMS
function apg_sms_procesa_notas($datos) {
	global $woocommerce, $configuracion;
	
	$pedido = new WC_Order($datos['order_id']);
	
	$internacional = false;
	$telefono = apg_sms_procesa_el_telefono($pedido, $pedido->billing_phone, $configuracion['servicio']);
	if ($pedido->billing_country && ($woocommerce->countries->get_base_country() != $pedido->billing_country)) $internacional = true;
	
	if (!$internacional || (isset($configuracion['internacional']) && $configuracion['internacional'] == 1)) apg_sms_envia_sms($configuracion, $telefono, apg_sms_procesa_variables($configuracion['mensaje_nota'], $pedido, $configuracion['variables'], wptexturize($datos['customer_note'])));
}
add_action('woocommerce_new_customer_note', 'apg_sms_procesa_notas', 10);

//Envía el mensaje SMS
function apg_sms_envia_sms($configuracion, $telefono, $mensaje) {
	if ($configuracion['servicio'] == "voipstunt") $respuesta = wp_remote_get("https://www.voipstunt.com/myaccount/sendsms.php?username=" . $configuracion['usuario_voipstunt'] . "&password=" . $configuracion['contrasena_voipstunt'] . "&from=" . $configuracion['telefono'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje($mensaje));
	else if ($configuracion['servicio'] == "solutions_infini") $respuesta = wp_remote_get("http://alerts.sinfini.com/api/web2sms.php?workingkey=" . $configuracion['clave_solutions_infini'] . "&to=" . $telefono . "&sender=" . $configuracion['identificador_solutions_infini'] . "&message=" . apg_sms_codifica_el_mensaje($mensaje));
	else if ($configuracion['servicio'] == "twilio")
	{
		$argumentos['header'] = "Accept-Charset: utf-8\r\n";
		$argumentos['body'] = array(
				'To' 	=> $telefono,
				'From' 	=> $configuracion['telefono_twilio'],
				'Body' 	=> $mensaje
			);
		$respuesta = wp_remote_post("https://" . $configuracion['clave_twilio'] . ":" . $configuracion['identificador_twilio'] . "@api.twilio.com/2010-04-01/Accounts/" . $configuracion['clave_twilio'] . "/Messages", $argumentos);
	}
	else if ($configuracion['servicio'] == "clickatell") $respuesta = wp_remote_get("http://api.clickatell.com/http/sendmsg?api_id=" . $configuracion['identificador_clickatell'] . "&user=" . $configuracion['usuario_clickatell'] . "&password=" . $configuracion['contrasena_clickatell'] . "&to=" . $telefono . "&text=" . apg_sms_codifica_el_mensaje($mensaje)); 
	else if ($configuracion['servicio'] == "clockwork") $respuesta = wp_remote_get("https://api.clockworksms.com/http/send.aspx?key=" . $configuracion['identificador_clockwork'] . "&to=" . $telefono . "&content=" . apg_sms_normaliza_mensaje($mensaje));
	else if ($configuracion['servicio'] == "bulksms") $respuesta = wp_remote_post("http://bulksms.vsms.net/eapi/submission/send_sms/2/2.0?username=" . $configuracion['usuario_bulksms'] . "&password=" . $configuracion['contrasena_bulksms'] . "&message=" . apg_sms_codifica_el_mensaje($mensaje) . "&msisdn=" . urlencode($telefono));
	else if ($configuracion['servicio'] == "open_dnd") $respuesta = wp_remote_get("http://txn.opendnd.in/pushsms.php?username=" . $configuracion['usuario_open_dnd'] . "&password=" . $configuracion['contrasena_open_dnd'] . "&message=" . apg_sms_codifica_el_mensaje(apg_sms_normaliza_mensaje($mensaje)) . "&sender=" . $configuracion['identificador_open_dnd'] . "&numbers=" . $telefono);
	else if ($configuracion['servicio'] == "msg91") 
	{
		$argumentos['body'] = array(
				'authkey' 	=> $configuracion['clave_msg91'],
				'mobiles' 	=> $telefono,
				'message' 	=> apg_sms_codifica_el_mensaje(apg_sms_normaliza_mensaje($mensaje)),
				'sender' 	=> $configuracion['identificador_msg91'],
				'route' 	=> $configuracion['ruta_msg91']
			);
		$respuesta = wp_remote_post("http://control.msg91.com/sendhttp.php", $argumentos);
	}
	else if ($configuracion['servicio'] == "mvaayoo")
	{
		require_once("lib/mvsms.php");

		if (!isset($mvsms)) $mvsms = new MvSMS($configuracion['usuario_mvaayoo'], $configuracion['contrasena_mvaayoo'], $configuracion['campana_mvaayoo'], $configuracion['identificador_mvaayoo']);
		$respuesta = $mvsms->sendSMS($telefono, $mensaje);
		$campana = $mvsms->campID;
		if ($configuracion['campana_mvaayoo'] !== $campana)
		{
			$configuracion['campana_mvaayoo'] = $campana;
			update_option('apg_sms_settings', $configuracion);
		}
	}	
	else if ($configuracion['servicio'] == "esebun") $respuesta = wp_remote_get("http://api.cloud.bz.esebun.com/api/v3/sendsms/plain?user=" . $configuracion['usuario_esebun'] . "&password=" . $configuracion['contrasena_esebun'] . "&sender=" . apg_sms_codifica_el_mensaje($configuracion['identificador_esebun']) . "&SMSText=" . apg_sms_codifica_el_mensaje($mensaje) . "&GSM=" . preg_replace('/\+/', '', $telefono));
	else if ($configuracion['servicio'] == "isms") $respuesta = wp_remote_get("https://www.isms.com.my/isms_send.php?un=" . $configuracion['usuario_isms'] . "&pwd=" . $configuracion['contrasena_isms'] . "&dstno=" . $telefono . "&msg=" . apg_sms_codifica_el_mensaje($mensaje) . "&type=2" . "&sendid=" . $configuracion['telefono_isms']);
	
	//wp_mail('artprojectgroup@gmail.com', 'WooCommerce - APG SMS Notifications', $telefono . print_r($respuesta, true), 'charset=UTF-8' . "\r\n"); 
}

//Normalizamos el texto
function apg_sms_normaliza_mensaje($mensaje)
{
	$reemplazo = array('Š'=>'S', 'š'=>'s', 'Đ'=>'Dj', 'đ'=>'dj', 'Ž'=>'Z', 'ž'=>'z', 'Č'=>'C', 'č'=>'c', 'Ć'=>'C', 'ć'=>'c', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e',  'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y',  'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'Ŕ'=>'R', 'ŕ'=>'r', "`" => "'", "´" => "'", "„" => ",", "`" => "'", "´" => "'", "“" => "\"", "”" => "\"", "´" => "'", "&acirc;€™" => "'", "{" => "", "~" => "", "–" => "-", "’" => "'", "!" => ".", "¡" => "", "?" => ".", "¿" => "");
 
	$mensaje = str_replace(array_keys($reemplazo), array_values($reemplazo), htmlentities($mensaje, ENT_QUOTES, "UTF-8"));
 
	return $mensaje;
}

//Codifica el mensaje
function apg_sms_codifica_el_mensaje($mensaje) {
	return urlencode(html_entity_decode($mensaje, ENT_QUOTES, "UTF-8"));
}

//Mira si necesita el prefijo telefónico internacional
function apg_sms_prefijo($servicio) {
    $prefijo = array("voipstunt", "clockwork", "clickatell", "bulksms", "msg91", "twilio", "mvaayoo", "esebun", "isms");
    
	return in_array($servicio, $prefijo);
}

//Procesa el teléfono y le añade, si lo necesita, el prefijo
function apg_sms_procesa_el_telefono($pedido, $telefono, $servicio, $propietario = false) {
	global $woocommerce;
	
	$prefijo = apg_sms_prefijo($servicio);
	
	$telefono = str_replace(array('+','-'), '', filter_var($telefono, FILTER_SANITIZE_NUMBER_INT));
	if (!$propietario && $pedido->billing_country && ($woocommerce->countries->get_base_country() != $pedido->billing_country || $prefijo)) $prefijo_internacional = dame_prefijo_pais($pedido->billing_country);
	else if ($propietario && $prefijo) $prefijo_internacional = dame_prefijo_pais($woocommerce->countries->get_base_country());

	preg_match("/(\d{1,4})[0-9.\- ]+/", $telefono, $prefijo);
	if (isset($prefijo_internacional))
	{
		if (strpos($prefijo[1], $prefijo_internacional) === false) $telefono = $prefijo_internacional . $telefono;
	}
	if ($servicio == "twilio" && strpos($prefijo[1], "+") === false) $telefono = "+" . $telefono;
	else if ($servicio == "isms" && isset($prefijo_internacional)) $telefono = "00" . preg_replace('/\+/', '', $telefono);

	return $telefono;
}

//Procesa las variables
function apg_sms_procesa_variables($mensaje, $pedido, $variables, $nota = '') {
	$apg_sms = array("id", "status", "prices_include_tax", "tax_display_cart", "display_totals_ex_tax", "display_cart_ex_tax", "order_date", "modified_date", "customer_message", "customer_note", "post_status", "shop_name", "note");
	$apg_sms_variables = array("order_key", "billing_first_name", "billing_last_name", "billing_company", "billing_address_1", "billing_address_2", "billing_city", "billing_postcode", "billing_country", "billing_state", "billing_email", "billing_phone", "shipping_first_name", "shipping_last_name", "shipping_company", "shipping_address_1", "shipping_address_2", "shipping_city", "shipping_postcode", "shipping_country", "shipping_state", "shipping_method", "shipping_method_title", "payment_method", "payment_method_title", "order_discount", "cart_discount", "order_tax", "order_shipping", "order_shipping_tax", "order_total"); //Hay que añadirles un guión
	$variables = explode("\n", str_replace(array("\r\n", "\r"), "\n", $variables));

	$variables_personalizadas = get_post_custom($pedido->id); //WooCommerce 2.1

	preg_match_all("/%(.*?)%/", $mensaje, $busqueda);
	foreach ($busqueda[1] as $variable) 
	{ 
    	$variable = strtolower($variable);

    	if (!in_array($variable, $apg_sms) && !in_array($variable, $apg_sms_variables) && !in_array($variable, $variables)) continue;

    	if ($variable != "order_date" && $variable != "modified_date" && $variable != "shop_name" && $variable != "note" && $variable != "id") 
		{
			if (in_array($variable, $apg_sms)) $mensaje = str_replace("%" . $variable . "%", $pedido->$variable, $mensaje); //Variables estándar - Objeto
			else if (in_array($variable, $apg_sms_variables)) $mensaje = str_replace("%" . $variable . "%", $variables_personalizadas["_" . $variable][0], $mensaje); //Variables estándar - Array
			else if (isset($variables_personalizadas[$variable])) $mensaje = str_replace("%" . $variable . "%", $variables_personalizadas[$variable][0], $mensaje); //Variables personalizadas
		}
		else if ($variable == "order_date" || $variable == "modified_date") $mensaje = str_replace("%" . $variable . "%", date_i18n(woocommerce_date_format(), strtotime($pedido->$variable)), $mensaje);
		else if ($variable == "shop_name") $mensaje = str_replace("%" . $variable . "%", get_bloginfo('name'), $mensaje);
		else if ($variable == "note") $mensaje = str_replace("%" . $variable . "%", $nota, $mensaje);
		else if ($variable == "id") $mensaje = str_replace("%" . $variable . "%", $pedido->get_order_number(), $mensaje);
	}
	
	return $mensaje;
}

//Devuelve el código de prefijo del país
function dame_prefijo_pais($pais = '') {
	$paises = array('AC' => '247', 'AD' => '376', 'AE' => '971', 'AF' => '93', 'AG' => '1268', 'AI' => '1264', 'AL' => '355', 'AM' => '374', 'AO' => '244', 'AQ' => '672', 'AR' => '54', 'AS' => '1684', 'AT' => '43', 'AU' => '61', 'AW' => '297', 'AX' => '358', 'AZ' => '994', 'BA' => '387', 'BB' => '1246', 'BD' => '880', 'BE' => '32', 'BF' => '226', 'BG' => '359', 'BH' => '973', 'BI' => '257', 'BJ' => '229', 'BL' => '590', 'BM' => '1441', 'BN' => '673', 'BO' => '591', 'BQ' => '599', 'BR' => '55', 'BS' => '1242', 'BT' => '975', 'BW' => '267', 'BY' => '375', 'BZ' => '501', 'CA' => '1', 'CC' => '61', 'CD' => '243', 'CF' => '236', 'CG' => '242', 'CH' => '41', 'CI' => '225', 'CK' => '682', 'CL' => '56', 'CM' => '237', 'CN' => '86', 'CO' => '57', 'CR' => '506', 'CU' => '53', 'CV' => '238', 'CW' => '599', 'CX' => '61', 'CY' => '357', 'CZ' => '420', 'DE' => '49', 'DJ' => '253', 'DK' => '45', 'DM' => '1767', 'DO' => '1809', 'DO' => '1829', 'DO' => '1849', 'DZ' => '213', 'EC' => '593', 'EE' => '372', 'EG' => '20', 'EH' => '212', 'ER' => '291', 'ES' => '34', 'ET' => '251', 'EU' => '388', 'FI' => '358', 'FJ' => '679', 'FK' => '500', 'FM' => '691', 'FO' => '298', 'FR' => '33', 'GA' => '241', 'GB' => '44', 'GD' => '1473', 'GE' => '995', 'GF' => '594', 'GG' => '44', 'GH' => '233', 'GI' => '350', 'GL' => '299', 'GM' => '220', 'GN' => '224', 'GP' => '590', 'GQ' => '240', 'GR' => '30', 'GT' => '502', 'GU' => '1671', 'GW' => '245', 'GY' => '592', 'HK' => '852', 'HN' => '504', 'HR' => '385', 'HT' => '509', 'HU' => '36', 'ID' => '62', 'IE' => '353', 'IL' => '972', 'IM' => '44', 'IN' => '91', 'IO' => '246', 'IQ' => '964', 'IR' => '98', 'IS' => '354', 'IT' => '39', 'JE' => '44', 'JM' => '1876', 'JO' => '962', 'JP' => '81', 'KE' => '254', 'KG' => '996', 'KH' => '855', 'KI' => '686', 'KM' => '269', 'KN' => '1869', 'KP' => '850', 'KR' => '82', 'KW' => '965', 'KY' => '1345', 'KZ' => '7', 'LA' => '856', 'LB' => '961', 'LC' => '1758', 'LI' => '423', 'LK' => '94', 'LR' => '231', 'LS' => '266', 'LT' => '370', 'LU' => '352', 'LV' => '371', 'LY' => '218', 'MA' => '212', 'MC' => '377', 'MD' => '373', 'ME' => '382', 'MF' => '590', 'MG' => '261', 'MH' => '692', 'MK' => '389', 'ML' => '223', 'MM' => '95', 'MN' => '976', 'MO' => '853', 'MP' => '1670', 'MQ' => '596', 'MR' => '222', 'MS' => '1664', 'MT' => '356', 'MU' => '230', 'MV' => '960', 'MW' => '265', 'MX' => '52', 'MY' => '60', 'MZ' => '258', 'NA' => '264', 'NC' => '687', 'NE' => '227', 'NF' => '672', 'NG' => '234', 'NI' => '505', 'NL' => '31', 'NO' => '47', 'NP' => '977', 'NR' => '674', 'NU' => '683', 'NZ' => '64', 'OM' => '968', 'PA' => '507', 'PE' => '51', 'PF' => '689', 'PG' => '675', 'PH' => '63', 'PK' => '92', 'PL' => '48', 'PM' => '508', 'PR' => '1787', 'PR' => '1939', 'PS' => '970', 'PT' => '351', 'PW' => '680', 'PY' => '595', 'QA' => '974', 'QN' => '374', 'QS' => '252', 'QY' => '90', 'RE' => '262', 'RO' => '40', 'RS' => '381', 'RU' => '7', 'RW' => '250', 'SA' => '966', 'SB' => '677', 'SC' => '248', 'SD' => '249', 'SE' => '46', 'SG' => '65', 'SH' => '290', 'SI' => '386', 'SJ' => '47', 'SK' => '421', 'SL' => '232', 'SM' => '378', 'SN' => '221', 'SO' => '252', 'SR' => '597', 'SS' => '211', 'ST' => '239', 'SV' => '503', 'SX' => '1721', 'SY' => '963', 'SZ' => '268', 'TA' => '290', 'TC' => '1649', 'TD' => '235', 'TG' => '228', 'TH' => '66', 'TJ' => '992', 'TK' => '690', 'TL' => '670', 'TM' => '993', 'TN' => '216', 'TO' => '676', 'TR' => '90', 'TT' => '1868', 'TV' => '688', 'TW' => '886', 'TZ' => '255', 'UA' => '380', 'UG' => '256', 'UK' => '44', 'US' => '1', 'UY' => '598', 'UZ' => '998', 'VA' => '379', 'VA' => '39', 'VC' => '1784', 'VE' => '58', 'VG' => '1284', 'VI' => '1340', 'VN' => '84', 'VU' => '678', 'WF' => '681', 'WS' => '685', 'XC' => '991', 'XD' => '888', 'XG' => '881', 'XL' => '883', 'XN' => '857', 'XN' => '858', 'XN' => '870', 'XP' => '878', 'XR' => '979', 'XS' => '808', 'XT' => '800', 'XV' => '882', 'YE' => '967', 'YT' => '262', 'ZA' => '27', 'ZM' => '260', 'ZW' => '263');

	return ($pais == '') ? $paises : (isset($paises[$pais]) ? $paises[$pais] : '');
}

//Obtiene toda la información sobre el plugin
function apg_sms_plugin($nombre) {
	$argumentos = (object) array('slug' => $nombre);
	$consulta = array('action' => 'plugin_information', 'timeout' => 15, 'request' => serialize($argumentos));
	$respuesta = get_transient('apg_sms_plugin');
	if (false === $respuesta) 
	{
		$respuesta = wp_remote_post('http://api.wordpress.org/plugins/info/1.0/', array('body' => $consulta));
		set_transient('apg_sms_plugin', $respuesta, 24 * HOUR_IN_SECONDS);
	}
	if (!is_wp_error($respuesta)) $plugin = get_object_vars(unserialize($respuesta['body']));
	else $plugin['rating'] = 100;
	
	return $plugin;
}

//Muestra el mensaje de actualización
function apg_sms_actualizacion() {
	global $apg_sms;
	
    echo '<div class="error fade" id="message"><h3>' . $apg_sms['plugin'] . '</h3><h4>' . sprintf(__("Please, update your %s. It's very important!", 'apg_sms'), '<a href="' . $apg_sms['ajustes'] . '" title="' . __('Settings', 'apg_sms') . '">' . __('settings', 'apg_sms') . '</a>') . '</h4></div>';
}

//Carga las hojas de estilo
function apg_sms_muestra_mensaje() {
	global $configuracion;

	wp_register_style('apg_sms_hoja_de_estilo', plugins_url('style.css', __FILE__)); //Carga la hoja de estilo
	wp_register_style('apg_sms_fuentes', plugins_url('fonts/stylesheet.css', __FILE__)); //Carga la hoja de estilo global
	wp_enqueue_style('apg_sms_fuentes'); //Carga la hoja de estilo global

	if (!isset($configuracion['mensaje_pedido']) || !isset($configuracion['mensaje_nota'])) add_action('admin_notices', 'apg_sms_actualizacion'); //Comprueba si hay que mostrar el mensaje de actualización
}
add_action('admin_init', 'apg_sms_muestra_mensaje');

//Eliminamos todo rastro del plugin al desinstalarlo
function apg_sms_desinstalar() {
  delete_option('apg_sms_settings');
  delete_transient('apg_sms_plugin');
}
register_uninstall_hook( __FILE__, 'apg_sms_desinstalar' );
?>

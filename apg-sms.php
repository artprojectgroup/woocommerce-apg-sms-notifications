<?php
/*
Plugin Name: WooCommerce - APG SMS Notifications
Version: 2.13.0.3
Plugin URI: https://wordpress.org/plugins/woocommerce-apg-sms-notifications/
Description: Add to WooCommerce SMS notifications to your clients for order status changes. Also you can receive an SMS message when the shop get a new order and select if you want to send international SMS. The plugin add the international dial code automatically to the client phone number.
Author URI: https://artprojectgroup.es/
Author: Art Project Group
Requires at least: 3.8
Tested up to: 4.9
WC requires at least: 2.1
WC tested up to: 3.2

Text Domain: woocommerce-apg-sms-notifications
Domain Path: /languages

@package WooCommerce - APG SMS Notifications
@category Core
@author Art Project Group
*/

//Igual no deberías poder abrirme
if ( !defined( 'ABSPATH' ) ) {
    exit();
}

//Definimos constantes
define( 'DIRECCION_apg_sms', plugin_basename( __FILE__ ) );

//Definimos las variables
$apg_sms = array( 	
	'plugin' 		=> 'WooCommerce - APG SMS Notifications', 
	'plugin_uri' 	=> 'woocommerce-apg-sms-notifications', 
	'donacion' 		=> 'https://artprojectgroup.es/tienda/donacion',
	'soporte' 		=> 'https://wcprojectgroup.es/tienda/ticket-de-soporte',
	'plugin_url' 	=> 'https://artprojectgroup.es/plugins-para-wordpress/plugins-para-woocommerce/woocommerce-apg-sms-notifications', 
	'ajustes' 		=> 'admin.php?page=apg_sms', 
	'puntuacion' 	=> 'https://wordpress.org/support/view/plugin-reviews/woocommerce-apg-sms-notifications' 
);

//Carga el idioma
load_plugin_textdomain( 'woocommerce-apg-sms-notifications', null, dirname( DIRECCION_apg_sms ) . '/languages' );

//Carga la configuración del plugin
$configuracion = get_option( 'apg_sms_settings' );

//Enlaces adicionales personalizados
function apg_sms_enlaces( $enlaces, $archivo ) {
	global $apg_sms;

	if ( $archivo == DIRECCION_apg_sms ) {
		$enlaces[] = '<a href="' . $apg_sms['donacion'] . '" target="_blank" title="' . __( 'Make a donation by ', 'woocommerce-apg-sms-notifications' ) . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[] = '<a href="'. $apg_sms['plugin_url'] . '" target="_blank" title="' . $apg_sms['plugin'] . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/artprojectgroup" title="' . __( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://twitter.com/artprojectgroup" title="' . __( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'Twitter" target="_blank"><span class="genericon genericon-twitter"></span></a> <a href="https://plus.google.com/+ArtProjectGroupES" title="' . __( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'Google+" target="_blank"><span class="genericon genericon-googleplus-alt"></span></a> <a href="http://es.linkedin.com/in/artprojectgroup" title="' . __( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[] = '<a href="https://profiles.wordpress.org/artprojectgroup/" title="' . __( 'More plugins on ', 'woocommerce-apg-sms-notifications' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . __( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . __( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[] = apg_sms_plugin( $apg_sms['plugin_uri'] );
	}

	return $enlaces;
}
add_filter( 'plugin_row_meta', 'apg_sms_enlaces', 10, 2 );

//Añade el botón de configuración
function apg_sms_enlace_de_ajustes( $enlaces ) { 
	global $apg_sms;

	$enlaces_de_ajustes = array( 
		'<a href="' . $apg_sms['ajustes'] . '" title="' . __( 'Settings of ', 'woocommerce-apg-sms-notifications' ) . $apg_sms['plugin'] .'">' . __( 'Settings', 'woocommerce-apg-sms-notifications' ) . '</a>', 
		'<a href="' . $apg_sms['soporte'] . '" title="' . __( 'Support of ', 'woocommerce-apg-sms-notifications' ) . $apg_sms['plugin'] .'">' . __( 'Support', 'woocommerce-apg-sms-notifications' ) . '</a>' 
	);
	foreach( $enlaces_de_ajustes as $enlace_de_ajustes )	{
		array_unshift( $enlaces, $enlace_de_ajustes );
	}

	return $enlaces; 
}
$plugin = DIRECCION_apg_sms; 
add_filter( "plugin_action_links_$plugin", 'apg_sms_enlace_de_ajustes' );

//¿Está activo WooCommerce?
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_network_only_plugin( 'woocommerce/woocommerce.php' ) ) {
	//Cargamos funciones necesarias
	include_once( 'includes/admin/funciones.php' );

	//Comprobamos si está instalado y activo WPML
	$wpml_activo = function_exists( 'icl_object_id' );
	
	//Actualiza las traducciones de los mensajes SMS
	function apg_registra_wpml( $configuracion ) {
		global $wpml_activo;
		
		//Registramos los textos en WPML
		if ( $wpml_activo && function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'apg_sms', 'mensaje_pedido', $configuracion['mensaje_pedido'] );
			icl_register_string( 'apg_sms', 'mensaje_recibido', $configuracion['mensaje_recibido'] );
			icl_register_string( 'apg_sms', 'mensaje_procesando', $configuracion['mensaje_procesando'] );
			icl_register_string( 'apg_sms', 'mensaje_completado', $configuracion['mensaje_completado'] );
			icl_register_string( 'apg_sms', 'mensaje_nota', $configuracion['mensaje_nota'] );
		} else if ( $wpml_activo ) {
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_pedido', $configuracion['mensaje_pedido'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_recibido', $configuracion['mensaje_recibido'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_procesando', $configuracion['mensaje_procesando'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_completado', $configuracion['mensaje_completado'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_nota', $configuracion['mensaje_nota'] );
		}
	}
	
	//Inicializamos las traducciones y los proveedores
	function apg_sms_inicializacion() {
		global $configuracion;

		apg_registra_wpml( $configuracion );
	}
	add_action( 'init', 'apg_sms_inicializacion' );

	//Pinta el formulario de configuración
	function apg_sms_tab() {
		include( 'includes/formulario.php' );
	}

	//Añade en el menú a WooCommerce
	function apg_sms_admin_menu() {
		add_submenu_page( 'woocommerce', __( 'APG SMS Notifications', 'woocommerce-apg-sms-notifications' ),  __( 'SMS Notifications', 'woocommerce-apg-sms-notifications' ) , 'manage_woocommerce', 'apg_sms', 'apg_sms_tab' );
	}
	add_action( 'admin_menu', 'apg_sms_admin_menu', 15 );

	//Carga los scripts y CSS de WooCommerce
	function apg_sms_screen_id( $woocommerce_screen_ids ) {
		$woocommerce_screen_ids[] = 'woocommerce_page_apg_sms';

		return $woocommerce_screen_ids;
	}
	add_filter( 'woocommerce_screen_ids', 'apg_sms_screen_id' );

	//Registra las opciones
	function apg_sms_registra_opciones() {
		global $configuracion;
	
		register_setting( 'apg_sms_settings_group', 'apg_sms_settings', 'apg_sms_update' );
		$configuracion = get_option( 'apg_sms_settings' );

		if ( ( class_exists( 'WC_SA' ) || class_exists( 'AppZab_Woo_Advance_Order_Status' ) || isset( $GLOBALS['advorder_lite_orderstatus'] ) ) && isset( $configuracion['estados_personalizados'] ) ) { //Comprueba la existencia de plugins de estado personalizado
			foreach ( $configuracion['estados_personalizados'] as $estado ) {
				add_action( "woocommerce_order_status_{$estado}", 'apg_sms_procesa_estados', 10 );
			}
		}
	}
	add_action( 'admin_init', 'apg_sms_registra_opciones' );
	
	function apg_sms_update( $configuracion ) {
		apg_registra_wpml( $configuracion );
		
		return $configuracion;
	}

	//Procesa el SMS
	function apg_sms_procesa_estados( $pedido, $notificacion = false ) {
		global $configuracion, $wpml_activo;
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );

		$numero_de_pedido	= $pedido;
		$pedido				= new WC_Order( $numero_de_pedido );
		$estado				= is_callable( array( $pedido, 'get_status' ) ) ? $pedido->get_status() : $pedido->status;

		//Comprobamos si se tiene que enviar el mensaje
		if ( isset( $configuracion['mensajes']) && $estado == 'on-hold' && ( !in_array( "todos", $configuracion['mensajes'] ) && !in_array( "mensaje_pedido", $configuracion['mensajes'] ) && !in_array( "mensaje_recibido", $configuracion['mensajes'] ) ) ) {
			return;
		} else if ( isset( $configuracion['mensajes']) && $estado == 'processing' && ( !in_array( "todos", $configuracion['mensajes'] ) && !in_array( "mensaje_pedido", $configuracion['mensajes'] ) && !in_array( "mensaje_procesando", $configuracion['mensajes'] ) ) ) {
			return;
		} else if ( isset( $configuracion['mensajes']) && $estado == 'completed' && ( !in_array( "todos", $configuracion['mensajes'] ) && !in_array( "mensaje_completado", $configuracion['mensajes'] ) ) ) {
			return;
		}
		//Permitir que otros plugins impidan que se envíe el SMS
		if ( !apply_filters( 'apg_sms_send_message', true, $pedido ) ) {
			return;
		}
		
		$nombres_de_estado	= array( 
			'on-hold'		=> 'Recibido', 
			'processing'	=> __( 'Processing', 'woocommerce-apg-sms-notifications' ), 
			'completed'		=> __( 'Completed', 'woocommerce-apg-sms-notifications' ) 
		);
		foreach ( $nombres_de_estado as $nombre_de_estado => $traduccion ) {
			if ( $estado == $nombre_de_estado ) {
				$estado = $traduccion;
			}
		}
	
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= get_post_meta( $numero_de_pedido, $configuracion['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, $configuracion['servicio'] );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, $configuracion['servicio'], false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $configuracion['envio'] ) && $configuracion['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
		//Teléfono propietario
		if ( strpos( $configuracion['telefono'], "|" ) ) {
			$administradores = explode( "|", $configuracion['telefono'] ); //Existe más de uno
		}
		if ( isset( $administradores ) ) {
			foreach( $administradores as $administrador ) {
				$telefono_propietario[]	= apg_sms_procesa_el_telefono( $pedido, $administrador, $configuracion['servicio'], true );
			}
		} else {
			$telefono_propietario	= apg_sms_procesa_el_telefono( $pedido, $configuracion['telefono'], $configuracion['servicio'], true );	
		}
		
		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_pedido		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_pedido', $configuracion['mensaje_pedido'] ) : $configuracion['mensaje_pedido'];
			$mensaje_recibido	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_recibido', $configuracion['mensaje_recibido'] ) : $configuracion['mensaje_recibido'];
			$mensaje_procesando	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_procesando', $configuracion['mensaje_procesando'] ) : $configuracion['mensaje_procesando'];
			$mensaje_completado	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_completado', $configuracion['mensaje_completado'] ) : $configuracion['mensaje_completado'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_pedido		= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_pedido'], 'apg_sms', 'mensaje_pedido' );
			$mensaje_recibido	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_recibido'], 'apg_sms', 'mensaje_recibido' );
			$mensaje_procesando	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_procesando'], 'apg_sms', 'mensaje_procesando' );
			$mensaje_completado	= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_completado'], 'apg_sms', 'mensaje_completado' );
		}
		
		//Envía el SMS
		if ( $estado == 'Recibido' ) {
			if ( isset( $configuracion['notificacion'] ) && $configuracion['notificacion'] == 1 ) {
				if ( !is_array( $telefono_propietario ) ) {
					apg_sms_envia_sms( $configuracion, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $configuracion['variables'] ) ); //Mensaje para el propietario
				} else {
					foreach( $telefono_propietario as $administrador ) {
						apg_sms_envia_sms( $configuracion, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $configuracion['variables'] ) ); //Mensaje para los propietarios
					}
				}
			}
			$mensaje = apg_sms_procesa_variables( $mensaje_recibido, $pedido, $configuracion['variables'] );
		} else if ( $estado == __( 'Processing', 'woocommerce-apg-sms-notifications' ) ) {
			if ( isset( $configuracion['notificacion'] ) && $configuracion['notificacion'] == 1 && $notificacion ) {
				if ( !is_array( $telefono_propietario ) ) {
					apg_sms_envia_sms( $configuracion, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $configuracion['variables'] ) ); //Mensaje para el propietario
				} else {
					foreach( $telefono_propietario as $administrador ) {
						apg_sms_envia_sms( $configuracion, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $configuracion['variables'] ) ); //Mensaje para los propietarios
					}
				}
			}
			$mensaje = apg_sms_procesa_variables( $mensaje_procesando, $pedido, $configuracion['variables'] );
		} else if ( $estado == __( 'Completed', 'woocommerce-apg-sms-notifications' ) ) {
			$mensaje = apg_sms_procesa_variables( $mensaje_completado, $pedido, $configuracion['variables'] );
		} else {
			$mensaje = apg_sms_procesa_variables( $configuracion[$estado], $pedido, $configuracion['variables'] );
		}

		if ( ( !$internacional || ( isset( $configuracion['internacional'] ) && $configuracion['internacional'] == 1 ) ) && !$notificacion ) {
			apg_sms_envia_sms( $configuracion, $telefono, $mensaje ); //Mensaje para el teléfono de facturación
		}
		if ( ( !$internacional_envio || ( isset( $configuracion['internacional'] ) && $configuracion['internacional'] == 1 ) ) && !$notificacion && $enviar_envio ) {
			apg_sms_envia_sms( $configuracion, $telefono_envio, $mensaje ); //Mensaje para el teléfono de envío
		}
	}
	add_action( 'woocommerce_order_status_pending_to_on-hold_notification', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como recibido
	add_action( 'woocommerce_order_status_processing', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como procesando
	add_action( 'woocommerce_order_status_completed', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como completo

	function apg_sms_notificacion( $pedido ) {
		apg_sms_procesa_estados( $pedido, true );
	}
	add_action( 'woocommerce_order_status_pending_to_processing_notification', 'apg_sms_notificacion', 10 ); //Funciona cuando el pedido es marcado directamente como procesando

	//Envía las notas de cliente por SMS
	function apg_sms_procesa_notas( $datos ) {
		global $configuracion, $wpml_activo;
		
		//Comprobamos si se tiene que enviar el mensaje
		if ( isset( $configuracion['mensajes']) && ( !in_array( "todos", $configuracion['mensajes'] ) && !in_array( "mensaje_nota", $configuracion['mensajes'] ) ) ) {
			return;
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );
	
		$numero_de_pedido		= $datos['order_id'];
		$pedido					= new WC_Order( $numero_de_pedido );
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;	
		$campo_envio			= get_post_meta( $numero_de_pedido, $configuracion['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, $configuracion['servicio'] );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, $configuracion['servicio'], false, true );
		$enviar_envio			= ( isset( $configuracion['envio'] ) && $telefono != $telefono_envio && $configuracion['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;

		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_nota', $configuracion['mensaje_nota'] ) : $configuracion['mensaje_nota'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_nota		= apply_filters( 'wpml_translate_single_string', $configuracion['mensaje_nota'], 'apg_sms', 'mensaje_nota' );
		}
		
		//Envía el SMS
		if ( !$internacional || ( isset( $configuracion['internacional'] ) && $configuracion['internacional'] == 1 ) ) {
			apg_sms_envia_sms( $configuracion, $telefono, apg_sms_procesa_variables( $mensaje_nota, $pedido, $configuracion['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de facturación
		}
		if ( ( !$internacional_envio || ( isset( $configuracion['internacional'] ) && $configuracion['internacional'] == 1 ) ) && $enviar_envio ) {
			apg_sms_envia_sms( $configuracion, $telefono_envio, apg_sms_procesa_variables( $mensaje_nota, $pedido, $configuracion['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de envío
		}
	}
	add_action( 'woocommerce_new_customer_note', 'apg_sms_procesa_notas', 10 );
} else {
	add_action( 'admin_notices', 'apg_sms_requiere_wc' );
}

//Muestra el mensaje de activación de WooCommerce y desactiva el plugin
function apg_sms_requiere_wc() {
	global $apg_sms;
		
	echo '<div class="error fade" id="message"><h3>' . $apg_sms['plugin'] . '</h3><h4>' . __( "This plugin require WooCommerce active to run!", 'woocommerce-apg-sms-notifications' ) . '</h4></div>';
	deactivate_plugins( DIRECCION_apg_sms );
}

//Obtiene toda la información sobre el plugin
function apg_sms_plugin( $nombre ) {
	global $apg_sms;
	
	$argumentos	= ( object ) array( 
		'slug'		=> $nombre 
	);
	$consulta	= array( 
		'action'	=> 'plugin_information', 
		'timeout'	=> 15, 
		'request'	=> serialize( $argumentos )
	);
	$respuesta	= get_transient( 'apg_sms_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_post( 'https://api.wordpress.org/plugins/info/1.0/', array( 
			'body'	=> $consulta
		) );
		set_transient( 'apg_sms_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}
	if ( !is_wp_error( $respuesta ) ) {
		$plugin = get_object_vars( unserialize( $respuesta['body'] ) );
	} else {
		$plugin['rating'] = 100;
	}

	$rating = array(
	   'rating'		=> $plugin['rating'],
	   'type'		=> 'percent',
	   'number'		=> $plugin['num_ratings'],
	);
	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . sprintf( __( 'Please, rate %s:', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] ) . '" href="' . $apg_sms['puntuacion'] . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

//Muestra el mensaje de actualización
function apg_sms_actualizacion() {
	global $apg_sms;
	
	echo '<div class="error fade" id="message"><h3>' . $apg_sms['plugin'] . '</h3><h4>' . sprintf( __( "Please, update your %s. It's very important!", 'woocommerce-apg-sms-notifications' ), '<a href="' . $apg_sms['ajustes'] . '" title="' . __( 'Settings', 'woocommerce-apg-sms-notifications' ) . '">' . __( 'settings', 'woocommerce-apg-sms-notifications' ) . '</a>' ) . '</h4></div>';
}

//Carga las hojas de estilo
function apg_sms_muestra_mensaje() {
	global $configuracion;

	wp_register_style( 'apg_sms_hoja_de_estilo', plugins_url( 'assets/css/style.css', __FILE__ ) ); //Carga la hoja de estilo
	wp_enqueue_style( 'apg_sms_hoja_de_estilo' ); //Carga la hoja de estilo

	/*if ( !isset( $configuracion['mensaje_pedido'] ) || !isset( $configuracion['mensaje_nota'] ) ) { //Comprueba si hay que mostrar el mensaje de actualización
		add_action( 'admin_notices', 'apg_sms_actualizacion' );
	}*/
}
add_action( 'admin_init', 'apg_sms_muestra_mensaje' );

//Eliminamos todo rastro del plugin al desinstalarlo
function apg_sms_desinstalar() {
	delete_option( 'apg_sms_settings' );
	delete_transient( 'apg_sms_plugin' );
}
register_uninstall_hook( __FILE__, 'apg_sms_desinstalar' );
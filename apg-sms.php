<?php
/*
Plugin Name: WC - APG SMS Notifications
Version: 2.17.1
Plugin URI: https://wordpress.org/plugins/woocommerce-apg-sms-notifications/
Description: Add to WooCommerce SMS notifications to your clients for order status changes. Also you can receive an SMS message when the shop get a new order and select if you want to send international SMS. The plugin add the international dial code automatically to the client phone number.
Author URI: https://artprojectgroup.es/
Author: Art Project Group
Requires at least: 3.8
Tested up to: 5.2
WC requires at least: 2.1
WC tested up to: 3.6

Text Domain: woocommerce-apg-sms-notifications
Domain Path: /languages

@package WC - APG SMS Notifications
@category Core
@author Art Project Group
*/

//Igual no deberías poder abrirme
defined( 'ABSPATH' ) || exit;

//Definimos constantes
define( 'DIRECCION_apg_sms', plugin_basename( __FILE__ ) );

//Funciones generales de APG
include_once( 'includes/admin/funciones-apg.php' );

//¿Está activo WooCommerce?
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) || is_network_only_plugin( 'woocommerce/woocommerce.php' ) ) {
	//Cargamos funciones necesarias
	include_once( 'includes/admin/funciones.php' );

	//Comprobamos si está instalado y activo WPML
	$wpml_activo = function_exists( 'icl_object_id' );
	
	//Actualiza las traducciones de los mensajes SMS
	function apg_registra_wpml( $apg_sms_settings ) {
		global $wpml_activo;
		
		//Registramos los textos en WPML
		if ( $wpml_activo && function_exists( 'icl_register_string' ) ) {
			icl_register_string( 'apg_sms', 'mensaje_pedido', $apg_sms_settings['mensaje_pedido'] );
			icl_register_string( 'apg_sms', 'mensaje_recibido', $apg_sms_settings['mensaje_recibido'] );
			icl_register_string( 'apg_sms', 'mensaje_procesando', $apg_sms_settings['mensaje_procesando'] );
			icl_register_string( 'apg_sms', 'mensaje_completado', $apg_sms_settings['mensaje_completado'] );
			icl_register_string( 'apg_sms', 'mensaje_nota', $apg_sms_settings['mensaje_nota'] );
		} else if ( $wpml_activo ) {
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_pedido', $apg_sms_settings['mensaje_pedido'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_recibido', $apg_sms_settings['mensaje_recibido'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_procesando', $apg_sms_settings['mensaje_procesando'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_completado', $apg_sms_settings['mensaje_completado'] );
			do_action( 'wpml_register_single_string', 'apg_sms', 'mensaje_nota', $apg_sms_settings['mensaje_nota'] );
		}
	}
	
	//Inicializamos las traducciones y los proveedores
	function apg_sms_inicializacion() {
		global $apg_sms_settings;

		apg_registra_wpml( $apg_sms_settings );
	}
	add_action( 'init', 'apg_sms_inicializacion' );

	//Pinta el formulario de configuración
	function apg_sms_tab() {
		include( 'includes/admin/funciones-formulario.php' );
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
		global $apg_sms_settings;
	
		register_setting( 'apg_sms_settings_group', 'apg_sms_settings', 'apg_sms_update' );
		$apg_sms_settings = get_option( 'apg_sms_settings' );

		if ( isset( $apg_sms_settings['estados_personalizados'] ) && !empty( $apg_sms_settings['estados_personalizados'] ) ) { //Comprueba la existencia de estados personalizados
			foreach ( $apg_sms_settings['estados_personalizados'] as $estado ) {
				add_action( "woocommerce_order_status_{$estado}", 'apg_sms_procesa_estados', 10 );
			}
		}
	}
	add_action( 'admin_init', 'apg_sms_registra_opciones' );
	
	function apg_sms_update( $apg_sms_settings ) {
		apg_registra_wpml( $apg_sms_settings );
		
		return $apg_sms_settings;
	}

	//Procesa el SMS
	function apg_sms_procesa_estados( $pedido, $notificacion = false ) {
		global $apg_sms_settings, $wpml_activo;
		
		$numero_de_pedido	= $pedido;
		$pedido				= new WC_Order( $numero_de_pedido );
		$estado				= is_callable( array( $pedido, 'get_status' ) ) ? $pedido->get_status() : $pedido->status;

		//Comprobamos si se tiene que enviar el mensaje o no
		if ( isset( $apg_sms_settings['mensajes'] ) ) {
			if ( $estado == 'on-hold' && !array_intersect( array( "todos", "mensaje_pedido", "mensaje_recibido" ), $apg_sms_settings['mensajes'] ) ) {
				return;
			} else if ( $estado == 'processing' && !array_intersect( array( "todos", "mensaje_pedido", "mensaje_procesando" ), $apg_sms_settings['mensajes'] ) ) {
				return;
			} else if ( $estado == 'completed' && !array_intersect( array( "todos", "mensaje_completado" ), $apg_sms_settings['mensajes'] ) ) {
				return;
			}
		} else {
			return;
		}
		//Permitir que otros plugins impidan que se envíe el SMS
		if ( !apply_filters( 'apg_sms_send_message', true, $pedido ) ) {
			return;
		}

		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= get_post_meta( $numero_de_pedido, $apg_sms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, $apg_sms_settings['servicio'] );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, $apg_sms_settings['servicio'], false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $apg_sms_settings['envio'] ) && $apg_sms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
		//Teléfono propietario
		if ( strpos( $apg_sms_settings['telefono'], "|" ) ) {
			$administradores = explode( "|", $apg_sms_settings['telefono'] ); //Existe más de uno
		}
		if ( isset( $administradores ) ) {
			foreach( $administradores as $administrador ) {
				$telefono_propietario[]	= apg_sms_procesa_el_telefono( $pedido, $administrador, $apg_sms_settings['servicio'], true );
			}
		} else {
			$telefono_propietario = apg_sms_procesa_el_telefono( $pedido, $apg_sms_settings['telefono'], $apg_sms_settings['servicio'], true );	
		}
		
		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_pedido		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_pedido', $apg_sms_settings['mensaje_pedido'] ) : $apg_sms_settings['mensaje_pedido'];
			$mensaje_recibido	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_recibido', $apg_sms_settings['mensaje_recibido'] ) : $apg_sms_settings['mensaje_recibido'];
			$mensaje_procesando	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_procesando', $apg_sms_settings['mensaje_procesando'] ) : $apg_sms_settings['mensaje_procesando'];
			$mensaje_completado	= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_completado', $apg_sms_settings['mensaje_completado'] ) : $apg_sms_settings['mensaje_completado'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_pedido		= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_pedido'], 'apg_sms', 'mensaje_pedido' );
			$mensaje_recibido	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_recibido'], 'apg_sms', 'mensaje_recibido' );
			$mensaje_procesando	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_procesando'], 'apg_sms', 'mensaje_procesando' );
			$mensaje_completado	= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_completado'], 'apg_sms', 'mensaje_completado' );
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );
		//Envía el SMS
		switch( $estado ) {
			case 'on-hold': //Pedido en espera
				if ( !!array_intersect( array( "todos", "mensaje_pedido" ), $apg_sms_settings['mensajes'] ) && isset( $apg_sms_settings['notificacion'] ) && $apg_sms_settings['notificacion'] == 1 && !$notificacion ) {
					if ( !is_array( $telefono_propietario ) ) {
						apg_sms_envia_sms( $apg_sms_settings, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $apg_sms_settings['variables'] ) ); //Mensaje para el propietario
					} else {
						foreach( $telefono_propietario as $administrador ) {
							apg_sms_envia_sms( $apg_sms_settings, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $apg_sms_settings['variables'] ) ); //Mensaje para los propietarios
						}
					}
				}
						
				if ( !!array_intersect( array( "todos", "mensaje_recibido" ), $apg_sms_settings['mensajes'] ) ) {
					//Limpia el temporizador para pedidos recibidos
					wp_clear_scheduled_hook( 'apg_sms_ejecuta_el_temporizador' );

					$mensaje = apg_sms_procesa_variables( $mensaje_recibido, $pedido, $apg_sms_settings['variables'] ); //Mensaje para el cliente

					//Temporizador para pedidos recibidos
					if ( isset( $apg_sms_settings['temporizador'] ) && $apg_sms_settings['temporizador'] > 0 ) {
						wp_schedule_single_event( time() + ( absint( $apg_sms_settings['temporizador'] ) * 60 * 60 ), 'apg_sms_ejecuta_el_temporizador' );
					}
				}
				break;
			case 'processing': //Pedido procesando
				if ( !!array_intersect( array( "todos", "mensaje_pedido" ), $apg_sms_settings['mensajes'] ) && isset( $apg_sms_settings['notificacion'] ) && $apg_sms_settings['notificacion'] == 1 && $notificacion ) {
					if ( !is_array( $telefono_propietario ) ) {
						apg_sms_envia_sms( $apg_sms_settings, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $apg_sms_settings['variables'] ) ); //Mensaje para el propietario
					} else {
						foreach( $telefono_propietario as $administrador ) {
							apg_sms_envia_sms( $apg_sms_settings, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $apg_sms_settings['variables'] ) ); //Mensaje para los propietarios
						}
					}
				}
				if ( !!array_intersect( array( "todos", "mensaje_procesando" ), $apg_sms_settings['mensajes'] ) ) {
					$mensaje = apg_sms_procesa_variables( $mensaje_procesando, $pedido, $apg_sms_settings['variables'] );
				}
				break;
			case 'completed': //Pedido completado
				if ( !!array_intersect( array( "todos", "mensaje_completado" ), $apg_sms_settings['mensajes'] ) ) {
					$mensaje = apg_sms_procesa_variables( $mensaje_completado, $pedido, $apg_sms_settings['variables'] );
				}
				break;
			default: //Pedido con estado personalizado
				$mensaje = apg_sms_procesa_variables( $apg_sms_settings[$estado], $pedido, $apg_sms_settings['variables'] );
		}

		if ( isset( $mensaje ) && ( !$internacional || ( isset( $apg_sms_settings['internacional'] ) && $apg_sms_settings['internacional'] == 1 ) ) && !$notificacion ) {
			if ( !is_array( $telefono ) ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono, $mensaje ); //Mensaje para el teléfono de facturación
			} else {
				foreach( $telefono as $cliente ) {
					apg_sms_envia_sms( $apg_sms_settings, $cliente, $mensaje ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono_envio, $mensaje ); //Mensaje para el teléfono de envío
			}
		}
	}
	add_action( 'woocommerce_order_status_pending_to_on-hold_notification', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como recibido
	add_action( 'woocommerce_order_status_failed_to_on-hold_notification', 'apg_sms_procesa_estados', 10 );
	add_action( 'woocommerce_order_status_processing', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como procesando
	add_action( 'woocommerce_order_status_completed', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido es marcado como completo

	function apg_sms_notificacion( $pedido ) {
		apg_sms_procesa_estados( $pedido, true );
	}
	add_action( 'woocommerce_order_status_pending_to_processing_notification', 'apg_sms_notificacion', 10 ); //Funciona cuando el pedido es marcado directamente como procesando
	
	//Temporizador
	function apg_sms_temporizador() {
		global $apg_sms_settings;
		
		$pedidos = wc_get_orders( array(
			'limit'			=> -1,
			'date_created'	=> '<' . ( time() - ( absint( $apg_sms_settings['temporizador'] ) * 60 * 60 ) - 1 ),
			'status'		=> 'on-hold',
		) );

		if ( $pedidos ) {
			foreach ( $pedidos as $pedido ) {
				apg_sms_procesa_estados( is_callable( array( $pedido, 'get_id' ) ) ? $pedido->get_id() : $pedido->id, false );
			}
		}
	}
	add_action( 'apg_sms_ejecuta_el_temporizador', 'apg_sms_temporizador' );

	//Envía las notas de cliente por SMS
	function apg_sms_procesa_notas( $datos ) {
		global $apg_sms_settings, $wpml_activo;
		
		//Comprobamos si se tiene que enviar el mensaje
		if ( isset( $apg_sms_settings['mensajes']) && !array_intersect( array( "todos", "mensaje_nota" ), $apg_sms_settings['mensajes'] ) ) {
			return;
		}
	
		//Pedido
		$numero_de_pedido		= $datos['order_id'];
		$pedido					= new WC_Order( $numero_de_pedido );
		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;	
		$campo_envio			= get_post_meta( $numero_de_pedido, $apg_sms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, $apg_sms_settings['servicio'] );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, $apg_sms_settings['servicio'], false, true );
		$enviar_envio			= ( isset( $apg_sms_settings['envio'] ) && $telefono != $telefono_envio && $apg_sms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( array( $pedido, 'get_billing_country' ) ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( array( $pedido, 'get_billing_phone' ) ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( array( $pedido, 'get_shipping_country' ) ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= get_post_meta( $numero_de_pedido, $apg_sms_settings['campo_envio'], false );
		$campo_envio			= ( isset( $campo_envio[0] ) ) ? $campo_envio[0] : '';
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, $apg_sms_settings['servicio'] );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, $apg_sms_settings['servicio'], false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $apg_sms_settings['envio'] ) && $apg_sms_settings['envio'] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;

		//WPML
		if ( function_exists( 'icl_register_string' ) || !$wpml_activo ) { //Versión anterior a la 3.2
			$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_nota', $apg_sms_settings['mensaje_nota'] ) : $apg_sms_settings['mensaje_nota'];
		} else if ( $wpml_activo ) { //Versión 3.2 o superior
			$mensaje_nota		= apply_filters( 'wpml_translate_single_string', $apg_sms_settings['mensaje_nota'], 'apg_sms', 'mensaje_nota' );
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );		
		//Envía el SMS
		if ( !$internacional || ( isset( $apg_sms_settings['internacional'] ) && $apg_sms_settings['internacional'] == 1 ) ) {
			if ( !is_array( $telefono ) ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono, apg_sms_procesa_variables( $mensaje_nota, $pedido, $apg_sms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de facturación
			} else {
				foreach( $telefono as $cliente ) {
					apg_sms_envia_sms( $apg_sms_settings, $cliente, apg_sms_procesa_variables( $mensaje_nota, $pedido, $apg_sms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono_envio, apg_sms_procesa_variables( $mensaje_nota, $pedido, $apg_sms_settings['variables'], wptexturize( $datos['customer_note'] ) ) ); //Mensaje para el teléfono de envío
			}
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

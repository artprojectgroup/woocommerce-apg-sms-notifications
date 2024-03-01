<?php
/*
Plugin Name: WC - APG SMS Notifications
Version: 2.27
Plugin URI: https://wordpress.org/plugins/woocommerce-apg-sms-notifications/
Description: Add to WooCommerce SMS notifications to your clients for order status changes. Also you can receive an SMS message when the shop get a new order and select if you want to send international SMS. The plugin add the international dial code automatically to the client phone number.
Author URI: https://artprojectgroup.es/
Author: Art Project Group
Requires at least: 5.0
Tested up to: 6.5
WC requires at least: 5.6
WC tested up to: 8.7

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
    //Añade compatibilidad con HPOS
    add_action( 'before_woocommerce_init', function() {
        if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
        }
    } );

    //Cargamos funciones necesarias
	include_once( 'includes/admin/funciones.php' );

	//Comprobamos si está instalado y activo WPML
	$wpml_activo = function_exists( 'icl_object_id' );
    
    //Mensajes
    $mensajes   = [
        'propietario'   => 'mensaje_pedido',
        'pending'       => 'mensaje_pendiente',
        'failed'        => 'mensaje_fallido',
        'on-hold'       => 'mensaje_recibido',
        'processing'    => 'mensaje_procesando',
        'completed'     => 'mensaje_completado',
        'refunded'      => 'mensaje_devuelto',
        'cancelled'     => 'mensaje_cancelado',
        'nota'          => 'mensaje_nota',
    ];

	//Actualiza las traducciones de los mensajes SMS
	function apg_registra_wpml( $apg_sms_settings ) {
		global $wpml_activo, $mensajes;
        
		//Registramos los mensajes en WPML
        foreach ( $mensajes as $mensaje ) {
            if ( $wpml_activo && function_exists( 'icl_register_string' ) ) { //Versión anterior a la 3.2
                icl_register_string( 'apg_sms', $mensaje, esc_textarea( $apg_sms_settings[ $mensaje ] ) );
            } else if ( $wpml_activo ) { //Versión 3.2 o superior
                do_action( 'wpml_register_single_string', 'apg_sms', $mensaje, esc_textarea( $apg_sms_settings[ $mensaje ] ) );
            }
        }
	}
	
	//Inicializamos las traducciones y los proveedores
	function apg_sms_inicializacion() {
		global $apg_sms_settings, $wpml_activo;

        if ( $wpml_activo ) {
		  apg_registra_wpml( $apg_sms_settings );
        }
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
	}
	add_action( 'admin_init', 'apg_sms_registra_opciones' );
	
	function apg_sms_update( $apg_sms_settings ) {
        apg_registra_wpml( $apg_sms_settings );
		
		return $apg_sms_settings;
	}

	//Procesa el SMS
	function apg_sms_procesa_estados( $numero_de_pedido, $temporizador = false ) {
		global $apg_sms_settings, $wpml_activo, $mensajes;
		
		$pedido   = wc_get_order( $numero_de_pedido );
		$estado   = is_callable( [ $pedido, 'get_status' ] ) ? $pedido->get_status() : $pedido->status;
        
        //Inicializa el mensaje para un estado personalizado
        if ( ! isset( $mensajes[ $estado ] ) ) {
            $mensajes[ $estado ]  = $estado;
        }

		//Comprobamos si se tiene que enviar el mensaje o no
		if ( isset( $apg_sms_settings[ 'mensajes' ] ) ) {
			if ( ( $estado == 'on-hold' || $estado == 'processing' ) && ! array_intersect( [ "todos", "mensaje_pedido", $mensajes[ $estado ] ], $apg_sms_settings[ 'mensajes' ] ) ) {
				return;
			} else if ( ! array_intersect( [ "todos", $mensajes[ $estado ] ], $apg_sms_settings[ 'mensajes' ] ) ) {
				return;
			}
		} else {
			return;
		}

        //Permitir que otros plugins impidan que se envíe el SMS
		if ( ! apply_filters( 'apg_sms_send_message', true, $pedido ) ) {
			return;
		}

		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( [ $pedido, 'get_billing_country' ] ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( [ $pedido, 'get_billing_phone' ] ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( [ $pedido, 'get_shipping_country' ] ) ? $pedido->get_shipping_country() : $pedido->shipping_country;
		$campo_envio			= esc_attr( $pedido->get_meta( $apg_sms_settings[ 'campo_envio' ], true ) );
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, esc_attr( $apg_sms_settings[ 'servicio' ] ) );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, esc_attr( $apg_sms_settings[ 'servicio' ] ), false, true );
		$enviar_envio			= ( ! empty( $telefono_envio ) && $telefono != $telefono_envio && isset( $apg_sms_settings[ 'envio' ] ) && $apg_sms_settings[ 'envio' ] == 1 ) ? true : false;
		$internacional			= ( isset( $billing_country ) && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( isset( $shipping_country ) && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;
        
		//Teléfono propietario
		if ( strpos( $apg_sms_settings[ 'telefono' ], "|" ) ) { //Existe más de uno
			$administradores = explode( "|", esc_attr( $apg_sms_settings[ 'telefono' ] ) );
			foreach ( $administradores as $administrador ) {
				$telefono_propietario[]	= apg_sms_procesa_el_telefono( $pedido, $administrador, esc_attr( $apg_sms_settings[ 'servicio' ] ), true );
			}
		} else {
			$telefono_propietario = apg_sms_procesa_el_telefono( $pedido, esc_attr( $apg_sms_settings[ 'telefono' ] ), esc_attr( $apg_sms_settings[ 'servicio' ] ), true );	
		}

        //Genera las variables con los textos personalizados
        foreach ( $mensajes as $mensaje ) {
            if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { //WPML versión anterior a la 3.2 o no instalado
                $$mensaje   = ( $wpml_activo ) ? icl_translate( 'apg_sms', $mensaje, esc_textarea( $apg_sms_settings[ $mensaje ] ) ) : esc_textarea( $apg_sms_settings[ $mensaje ] );
            } else if ( $wpml_activo ) { //WPML versión 3.2 o superior
                $$mensaje   = apply_filters( 'wpml_translate_single_string', esc_textarea( $apg_sms_settings[ $mensaje ] ), 'apg_sms', $mensaje );
            }
        }
        unset( $mensaje ); //Evita mensaje vacío con el temporizador
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );
        
		//Envía el SMS
        $variables  = esc_textarea( $apg_sms_settings[ 'variables' ] );
        
        //Mensaje SMS
        if ( $estado == 'on-hold' ) { //Pedido en espera
            //Mensaje para el/los propietarios
            if ( !! array_intersect( [ "todos", "mensaje_pedido" ], $apg_sms_settings[ 'mensajes' ] ) && isset( $apg_sms_settings[ 'notificacion' ] ) && $apg_sms_settings[ 'notificacion' ] == 1 && ! $temporizador ) { //Evita el envío en el temporizador
                if ( ! is_array( $telefono_propietario ) ) {
                    apg_sms_envia_sms( $apg_sms_settings, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Mensaje para el propietario
                } else {
                    foreach ( $telefono_propietario as $administrador ) {
                        apg_sms_envia_sms( $apg_sms_settings, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Mensaje para los propietarios
                    }
                }
            }
            //Mensaje para el cliente
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $apg_sms_settings[ 'mensajes' ] ) ) {
                //Limpia el temporizador para pedidos recibidos
                wp_clear_scheduled_hook( 'apg_sms_ejecuta_el_temporizador' );

                //Retardo para pedidos recibidos
                if ( isset( $apg_sms_settings[ 'retardo' ] ) && $apg_sms_settings[ 'retardo' ] > 0 && ( ! intval( $pedido->get_meta( 'apg_sms_retardo_enviado', true ) ) == 1 ) ) {
                    wp_schedule_single_event( time() + ( absint( $apg_sms_settings[ 'retardo' ] ) * 60 ), 'apg_sms_ejecuta_el_retraso', [ $numero_de_pedido ] );
                    $pedido->update_meta_data( 'apg_sms_retardo_enviado', -1 );
					$pedido->save();
                } else { //Envío normal
                    $mensaje = apg_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables ); //Mensaje para el cliente
                }

                //Temporizador para pedidos recibidos
                if ( isset( $apg_sms_settings[ 'temporizador' ] ) && $apg_sms_settings[ 'temporizador' ] > 0 ) {
                    wp_schedule_single_event( time() + ( absint( $apg_sms_settings[ 'temporizador' ] ) * 60 * 60 ), 'apg_sms_ejecuta_el_temporizador' );
                }
            }            
        } else if ( $estado == 'processing' ) { //Pedido procesando
            //Mensaje para el/los propietarios
            if ( !! array_intersect( [ "todos", "mensaje_pedido" ], $apg_sms_settings[ 'mensajes' ] ) && isset( $apg_sms_settings[ 'notificacion' ] ) && $apg_sms_settings[ 'notificacion' ] == 1 ) {
                if ( ! is_array( $telefono_propietario ) ) {
                    apg_sms_envia_sms( $apg_sms_settings, $telefono_propietario, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Mensaje para el propietario
                } else {
                    foreach ( $telefono_propietario as $administrador ) {
                        apg_sms_envia_sms( $apg_sms_settings, $administrador, apg_sms_procesa_variables( $mensaje_pedido, $pedido, $variables ), $estado, true ); //Mensaje para los propietarios
                    }
                }
            }
            //Mensaje para el cliente
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $apg_sms_settings[ 'mensajes' ] ) ) {
                $mensaje = apg_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables );
            }            
        } else if ( $estado != 'on-hold' && $estado != 'processing' ) { //El resto de estados
            if ( !! array_intersect( [ "todos", $mensajes[ $estado ] ], $apg_sms_settings[ 'mensajes' ] ) ) {
                $mensaje = apg_sms_procesa_variables( ${ $mensajes[ $estado ] }, $pedido, $variables );
            } else {
                $mensaje = apg_sms_procesa_variables( $apg_sms_settings[ $estado ], $pedido, $variables );            
            }
        }

        //Se envía el mensaje SMS si no se ha enviado aún
		if ( isset( $mensaje ) && ( ! $internacional || ( isset( $apg_sms_settings[ 'internacional' ] ) && $apg_sms_settings[ 'internacional' ] == 1 ) ) ) {
			if ( ! is_array( $telefono ) ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono, $mensaje, $estado ); //Mensaje para el teléfono de facturación
			} else {
				foreach ( $telefono as $cliente ) {
					apg_sms_envia_sms( $apg_sms_settings, $cliente, $mensaje, $estado ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono_envio, $mensaje, $estado ); //Mensaje para el teléfono de envío
			}
		}
	}
	add_action( 'woocommerce_order_status_changed', 'apg_sms_procesa_estados', 10 ); //Funciona cuando el pedido cambia de estado
	
    //Retraso
 	function apg_sms_retardo( $numero_de_pedido ) {
 		global $apg_sms_settings;
        
 		if ( $pedido = wc_get_order( intval( $numero_de_pedido ) ) ) {
 			$retraso_enviado    = $pedido->get_meta( 'apg_sms_retardo_enviado', true );
 			$estado             = is_callable( [ $pedido, 'get_status' ] ) ? $pedido->get_status() : $pedido->status;
 			if ( intval( $retraso_enviado ) == -1 ) { //Solo enviamos si no ha cambiado de estado
 				$pedido->update_meta_data( 'apg_sms_retardo_enviado', 1 );		 			
                if ( $estado == 'on-hold' ) {
                    apg_sms_procesa_estados( $numero_de_pedido );		 				
                    $retraso_enviado    = $pedido->get_meta( 'apg_sms_retardo_enviado', true );
                    if ( intval( $retraso_enviado ) == -1 ) {
                        $pedido->update_meta_data( 'apg_sms_retardo_enviado', 1 );
                        apg_sms_procesa_estados( $numero_de_pedido );
                    }
                }
				$pedido->save();
            }
        }
    }
 	add_action( 'apg_sms_ejecuta_el_retraso', 'apg_sms_retardo' );
    
	//Temporizador
	function apg_sms_temporizador() {
		global $apg_sms_settings;
		
		$pedidos = wc_get_orders( [
			'limit'			=> -1,
			'date_created'	=> '<' . ( time() - ( absint( $apg_sms_settings[ 'temporizador' ] ) * 60 * 60 ) - 1 ),
			'status'		=> 'on-hold',
		] );

		if ( $pedidos ) {
			foreach ( $pedidos as $pedido ) {
				apg_sms_procesa_estados( is_callable( [ $pedido, 'get_id' ] ) ? $pedido->get_id() : $pedido->id, true );
			}
		}
	}
	add_action( 'apg_sms_ejecuta_el_temporizador', 'apg_sms_temporizador' );

	//Envía las notas de cliente por SMS
	function apg_sms_procesa_notas( $datos ) {
		global $apg_sms_settings, $wpml_activo;
		
		//Comprobamos si se tiene que enviar el mensaje
		if ( isset( $apg_sms_settings[ 'mensajes' ] ) && ! array_intersect( [ "todos", "mensaje_nota" ], $apg_sms_settings[ 'mensajes' ] ) ) {
			return;
		}
	
		//Pedido
		$numero_de_pedido		= $datos[ 'order_id' ];
		$pedido					= wc_get_order( $numero_de_pedido );
		//Recoge datos del formulario de facturación
		$billing_country		= is_callable( [ $pedido, 'get_billing_country' ] ) ? $pedido->get_billing_country() : $pedido->billing_country;
		$billing_phone			= is_callable( [ $pedido, 'get_billing_phone' ] ) ? $pedido->get_billing_phone() : $pedido->billing_phone;
		$shipping_country		= is_callable( [ $pedido, 'get_shipping_country' ] ) ? $pedido->get_shipping_country() : $pedido->shipping_country;	
		$campo_envio			= $pedido->get_meta( esc_attr( $apg_sms_settings[ 'campo_envio' ] ), true );
		$telefono				= apg_sms_procesa_el_telefono( $pedido, $billing_phone, esc_attr( $apg_sms_settings[ 'servicio' ] ) );
		$telefono_envio			= apg_sms_procesa_el_telefono( $pedido, $campo_envio, esc_attr( $apg_sms_settings[ 'servicio' ] ), false, true );
		$enviar_envio			= ( $telefono != $telefono_envio && isset( $apg_sms_settings[ 'envio' ] ) && $apg_sms_settings[ 'envio' ] == 1 ) ? true : false;
		$internacional			= ( $billing_country && ( WC()->countries->get_base_country() != $billing_country ) ) ? true : false;
		$internacional_envio	= ( $shipping_country && ( WC()->countries->get_base_country() != $shipping_country ) ) ? true : false;

        //Genera la variable con el texto personalizado
		if ( function_exists( 'icl_register_string' ) || ! $wpml_activo ) { //WPML versión anterior a la 3.2 o no instalado
			$mensaje_nota		= ( $wpml_activo ) ? icl_translate( 'apg_sms', 'mensaje_nota', esc_textarea( $apg_sms_settings[ 'mensaje_nota' ] ) ) : esc_textarea( $apg_sms_settings[ 'mensaje_nota' ] );
		} else if ( $wpml_activo ) { //WPML versión 3.2 o superior
			$mensaje_nota		= apply_filters( 'wpml_translate_single_string', esc_textarea( $apg_sms_settings[ 'mensaje_nota' ] ), 'apg_sms', 'mensaje_nota' );
		}
		
		//Cargamos los proveedores SMS
		include_once( 'includes/admin/proveedores.php' );
        
		//Envía el SMS
		if ( ! $internacional || ( isset( $apg_sms_settings[ 'internacional' ] ) && $apg_sms_settings[ 'internacional' ] == 1 ) ) {
            $variables  = esc_textarea( $apg_sms_settings[ 'variables' ] );
			if ( ! is_array( $telefono ) ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono, apg_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Mensaje para el teléfono de facturación
			} else {
				foreach ( $telefono as $cliente ) {
					apg_sms_envia_sms( $apg_sms_settings, $cliente, apg_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Mensaje para los teléfonos recibidos
				}
			}
			if ( $enviar_envio ) {
				apg_sms_envia_sms( $apg_sms_settings, $telefono_envio, apg_sms_procesa_variables( $mensaje_nota, $pedido, $variables, wptexturize( $datos[ 'customer_note' ] ) ), 'mensaje_nota' ); //Mensaje para el teléfono de envío
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
		
	echo '<div class="notice notice-error is-dismissible" id="woocommerce-apg-sms-notifications"><h3>' . $apg_sms[ 'plugin' ] . '</h3><h4>' . __( "This plugin require WooCommerce active to run!", 'woocommerce-apg-sms-notifications' ) . '</h4></div>';
	deactivate_plugins( DIRECCION_apg_sms );
}

//Eliminamos todo rastro del plugin al desinstalarlo
function apg_sms_desinstalar() {
	delete_option( 'apg_sms_settings' );
	delete_transient( 'apg_sms_plugin' );
}
register_uninstall_hook( __FILE__, 'apg_sms_desinstalar' );

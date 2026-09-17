<?php
// Igual no deberias poder abrirme.
defined( 'ABSPATH' ) || exit;

// Definimos las variables.
$apg_sms = array(
	'plugin'     => 'WC - APG SMS Notifications',
	'plugin_uri' => 'woocommerce-apg-sms-notifications',
	'donacion'   => 'https://artprojectgroup.es/tienda/donacion',
	'soporte'    => 'https://artprojectgroup.es/tienda/soporte-tecnico',
	'plugin_url' => 'https://artprojectgroup.es/plugins-para-woocommerce/wc-apg-sms-notifications',
	'ajustes'    => 'admin.php?page=apg_sms',
	'puntuacion' => 'https://wordpress.org/support/view/plugin-reviews/woocommerce-apg-sms-notifications',
);

// Carga la configuracion del plugin.
$apg_sms_settings = get_option( 'apg_sms_settings' );

/**
 * Anade enlaces personalizados en la fila del plugin.
 *
 * @param string[] $enlaces Lista de enlaces existentes.
 * @param string   $archivo Archivo del plugin actual.
 * @return string[] Lista de enlaces actualizada.
 */
function apg_sms_enlaces( $enlaces, $archivo ) {
	global $apg_sms;

	if ( DIRECCION_apg_sms === $archivo ) {
		$enlaces[] = '<a href="' . esc_url( $apg_sms['donacion'] ) . '" target="_blank" title="' . esc_attr__( 'Make a donation by ', 'woocommerce-apg-sms-notifications' ) . 'APG"><span class="genericon genericon-cart"></span></a>';
		$enlaces[] = '<a href="' . esc_url( $apg_sms['plugin_url'] ) . '" target="_blank" title="' . esc_attr( $apg_sms['plugin'] ) . '"><strong class="artprojectgroup">APG</strong></a>';
		$enlaces[] = '<a href="https://www.facebook.com/artprojectgroup" title="' . esc_attr__( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a> <a href="https://x.com/artprojectgroup" title="' . esc_attr__( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'X" target="_blank"><span class="genericon genericon-x-alt"></span></a> <a href="https://es.linkedin.com/in/artprojectgroup" title="' . esc_attr__( 'Follow us on ', 'woocommerce-apg-sms-notifications' ) . 'LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>';
		$enlaces[] = '<a href="https://profiles.wordpress.org/artprojectgroup/" title="' . esc_attr__( 'More plugins on ', 'woocommerce-apg-sms-notifications' ) . 'WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>';
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . esc_attr__( 'Contact us by ', 'woocommerce-apg-sms-notifications' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . esc_attr__( 'Contact us by ', 'woocommerce-apg-sms-notifications' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
		$enlaces[] = apg_sms_plugin( $apg_sms['plugin_uri'] );
	}

	return $enlaces;
}
add_filter( 'plugin_row_meta', 'apg_sms_enlaces', 10, 2 );

/**
 * Anade enlaces de ajustes y soporte en la lista de acciones del plugin.
 *
 * @param string[] $enlaces Enlaces actuales.
 * @return string[] Enlaces actualizados.
 */
function apg_sms_enlace_de_ajustes( $enlaces ) {
	global $apg_sms;

	// translators: %s: Plugin name.
	$settings_title = sprintf( __( 'Settings of %s', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] );
	// translators: %s: Plugin name.
	$support_title = sprintf( __( 'Support of %s', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] );

	$enlaces_de_ajustes = array(
		'<a href="' . esc_url( $apg_sms['ajustes'] ) . '" title="' . esc_attr( $settings_title ) . '">' . esc_html__( 'Settings', 'woocommerce-apg-sms-notifications' ) . '</a>',
		'<a href="' . esc_url( $apg_sms['soporte'] ) . '" title="' . esc_attr( $support_title ) . '">' . esc_html__( 'Support', 'woocommerce-apg-sms-notifications' ) . '</a>',
	);

	foreach ( $enlaces_de_ajustes as $enlace_de_ajustes ) {
		array_unshift( $enlaces, $enlace_de_ajustes );
	}

	return $enlaces;
}
$plugin = DIRECCION_apg_sms;
add_filter( "plugin_action_links_$plugin", 'apg_sms_enlace_de_ajustes' );

/**
 * Obtiene informacion del plugin desde WordPress.org y genera el HTML de valoracion.
 *
 * Solo cachea la valoracion, y solo cuando la API responde correctamente: cachear
 * la respuesta HTTP entera dejaba un error guardado durante 24 horas.
 *
 * @param string $nombre Slug del plugin.
 * @return string HTML de la valoracion.
 */
function apg_sms_plugin( $nombre ) {
	global $apg_sms;

	// translators: %s: Plugin name.
	$titulo      = esc_attr( sprintf( __( 'Please, rate %s:', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] ) );
	$sin_datos   = '<a title="' . $titulo . '" href="' . esc_url( $apg_sms['puntuacion'] ) . '?rate=5#postform" class="estrellas">' . esc_html__( 'Unknown rating', 'woocommerce-apg-sms-notifications' ) . '</a>';

	$valoracion = get_transient( 'apg_sms_valoracion' );
	if ( false === $valoracion ) {
		$respuesta = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . rawurlencode( $nombre ) );

		if ( is_wp_error( $respuesta ) || 200 !== (int) wp_remote_retrieve_response_code( $respuesta ) ) {
			return $sin_datos; // Un fallo de la API no se cachea: se reintenta en la siguiente carga.
		}

		$plugin = json_decode( wp_remote_retrieve_body( $respuesta ) );
		if ( ! is_object( $plugin ) || ! isset( $plugin->rating, $plugin->num_ratings ) ) {
			return $sin_datos;
		}

		$valoracion = array(
			'rating' => $plugin->rating,
			'number' => $plugin->num_ratings,
		);
		set_transient( 'apg_sms_valoracion', $valoracion, 24 * HOUR_IN_SECONDS ); // Solo se guarda la valoracion, no la respuesta HTTP entera.
	}

	$rating = array(
		'rating' => $valoracion['rating'],
		'type'   => 'percent',
		'number' => $valoracion['number'],
	);

	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	return '<a title="' . $titulo . '" href="' . esc_url( $apg_sms['puntuacion'] ) . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

/**
 * Registra y carga la hoja de estilo del plugin en admin.
 *
 * @return void
 */
function apg_sms_estilo( $pantalla = '' ) {
	// La pantalla la da WordPress, en lugar de deducirla de la URL de la peticion.
	if ( 'woocommerce_page_apg_sms' === $pantalla || 'plugins.php' === $pantalla ) {
		wp_register_style( 'apg_sms_hoja_de_estilo', plugins_url( 'assets/css/style.css', DIRECCION_apg_sms ), array(), VERSION_apg_sms ); // Carga la hoja de estilo.
		wp_enqueue_style( 'apg_sms_hoja_de_estilo' ); // Carga la hoja de estilo.
	}
}
add_action( 'admin_enqueue_scripts', 'apg_sms_estilo' );

/**
 * Version del aviso de pasarelas.
 *
 * El descarte se guarda con esta version, no con la del plugin: al cambiarla, quien
 * ya habia descartado el aviso anterior vuelve a verlo porque hay novedades que le
 * afectan. Solo se sube cuando cambia la lista de pasarelas afectadas.
 *
 * @return string Version del aviso.
 */
function apg_sms_version_del_aviso() {
	return '3.3.0';
}

/**
 * Pasarelas que requieren revisar la configuracion, con el motivo.
 *
 * - migrada:      el proveedor cambio de nombre y de API; hacen falta credenciales nuevas.
 * - descatalogada: el proveedor dejo de ofrecer el servicio y se elimino del plugin.
 * - sin_servicio: el proveedor sigue documentando su API, pero no responde.
 *
 * @return array<string,array<string,string>> Mapa servicio => [ tipo, texto ].
 */
function apg_sms_proveedores_afectados() {
	return [
		'clockwork'        => [
			'tipo'  => 'migrada',
			'texto' => 'Clockwork &rarr; TextAnywhere',
		],
		'solutions_infini' => [
			'tipo'  => 'migrada',
			'texto' => 'Solutions Infini &rarr; Kaleyra',
		],
		'mobtexting'       => [
			'tipo'  => 'descatalogada',
			'texto' => 'MobTexting',
		],
		'twizo'            => [
			'tipo'  => 'sin_servicio',
			'texto' => 'Twizo / Silverstreet',
		],
	];
}

/**
 * Muestra un aviso en el escritorio a quien use una pasarela migrada o eliminada,
 * para que revise sus credenciales. Solo se muestra si el servicio configurado
 * esta afectado y no se ha descartado para la version actual.
 *
 * @return void
 */
function apg_sms_aviso_proveedores() {
	if ( ! current_user_can( 'manage_woocommerce' ) ) {
		return;
	}

	if ( get_option( 'apg_sms_aviso_proveedores' ) === apg_sms_version_del_aviso() ) { // Ya descartado el aviso vigente
		return;
	}

	$apg_sms_settings = get_option( 'apg_sms_settings' );
	$servicio         = isset( $apg_sms_settings['servicio'] ) ? $apg_sms_settings['servicio'] : '';
	$afectados        = apg_sms_proveedores_afectados();

	if ( ! isset( $afectados[ $servicio ] ) ) {
		return;
	}

	$url_ajustes   = admin_url( 'admin.php?page=apg_sms' );
	$url_descartar = wp_nonce_url( add_query_arg( 'apg_sms_descartar_aviso', '1' ), 'apg_sms_descartar_aviso' );

	if ( 'descatalogada' === $afectados[ $servicio ]['tipo'] ) {
		// translators: %s: URL of the plugin settings page.
		$mensaje = sprintf( __( '<strong>WC - APG SMS Notifications:</strong> the <strong>MobTexting</strong> gateway has been discontinued by the provider and removed in this version. Please <a href="%s">choose a different SMS gateway</a> to keep sending messages.', 'woocommerce-apg-sms-notifications' ), esc_url( $url_ajustes ) );
	} elseif ( 'sin_servicio' === $afectados[ $servicio ]['tipo'] ) {
		// translators: 1: gateway name (e.g. "Twizo / Silverstreet"), 2: URL of the plugin settings page.
		$mensaje = sprintf( __( '<strong>WC - APG SMS Notifications:</strong> the <strong>%1$s</strong> gateway is no longer reachable: the provider\'s API servers do not resolve any more, even though its documentation still lists them. Your SMS messages are not being delivered. Please <a href="%2$s">choose a different SMS gateway</a>.', 'woocommerce-apg-sms-notifications' ), $afectados[ $servicio ]['texto'], esc_url( $url_ajustes ) );
	} else {
		// translators: 1: gateway change (e.g. "Clockwork → TextAnywhere"), 2: URL of the plugin settings page.
		$mensaje = sprintf( __( '<strong>WC - APG SMS Notifications:</strong> your SMS gateway has been migrated to its successor (<strong>%1$s</strong>). Please <a href="%2$s">review your gateway settings</a> and enter the new provider credentials so your SMS messages keep working.', 'woocommerce-apg-sms-notifications' ), $afectados[ $servicio ]['texto'], esc_url( $url_ajustes ) );
	}

	echo '<div class="notice notice-warning"><p>' . wp_kses_post( $mensaje ) . '</p>';
	echo '<p><a href="' . esc_url( $url_descartar ) . '" class="button button-secondary">' . esc_html__( 'Dismiss this notice', 'woocommerce-apg-sms-notifications' ) . '</a></p></div>';
}
add_action( 'admin_notices', 'apg_sms_aviso_proveedores' );

/**
 * Procesa el descarte persistente del aviso de pasarelas.
 *
 * @return void
 */
function apg_sms_descarta_aviso_proveedores() {
	if ( isset( $_GET['apg_sms_descartar_aviso'] ) && current_user_can( 'manage_woocommerce' ) && check_admin_referer( 'apg_sms_descartar_aviso' ) ) {
		update_option( 'apg_sms_aviso_proveedores', apg_sms_version_del_aviso() );
		wp_safe_redirect( remove_query_arg( [ 'apg_sms_descartar_aviso', '_wpnonce' ] ) );
		exit;
	}
}
add_action( 'admin_init', 'apg_sms_descarta_aviso_proveedores' );

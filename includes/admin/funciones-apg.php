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
		$enlaces[] = '<a href="mailto:info@artprojectgroup.es" title="' . esc_attr__( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ) . 'e-mail"><span class="genericon genericon-mail"></span></a> <a href="skype:artprojectgroup" title="' . esc_attr__( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ) . 'Skype"><span class="genericon genericon-skype"></span></a>';
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
 * @param string $nombre Slug del plugin.
 * @return string HTML de la valoracion.
 */
function apg_sms_plugin( $nombre ) {
	global $apg_sms;

	$respuesta = get_transient( 'apg_sms_plugin' );
	if ( false === $respuesta ) {
		$respuesta = wp_remote_get( 'https://api.wordpress.org/plugins/info/1.2/?action=plugin_information&request[slug]=' . $nombre );
		set_transient( 'apg_sms_plugin', $respuesta, 24 * HOUR_IN_SECONDS );
	}

	if ( ! is_wp_error( $respuesta ) ) {
		$plugin = json_decode( wp_remote_retrieve_body( $respuesta ) );
	} else {
		// translators: %s: Plugin name.
		return '<a title="' . esc_attr( sprintf( __( 'Please, rate %s:', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] ) ) . '" href="' . esc_url( $apg_sms['puntuacion'] ) . '?rate=5#postform" class="estrellas">' . esc_html__( 'Unknown rating', 'woocommerce-apg-sms-notifications' ) . '</a>';
	}

	$rating = array(
		'rating' => $plugin->rating,
		'type'   => 'percent',
		'number' => $plugin->num_ratings,
	);

	ob_start();
	wp_star_rating( $rating );
	$estrellas = ob_get_contents();
	ob_end_clean();

	// translators: %s: Plugin name.
	return '<a title="' . esc_attr( sprintf( __( 'Please, rate %s:', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] ) ) . '" href="' . esc_url( $apg_sms['puntuacion'] ) . '?rate=5#postform" class="estrellas">' . $estrellas . '</a>';
}

/**
 * Registra y carga la hoja de estilo del plugin en admin.
 *
 * @return void
 */
function apg_sms_estilo() {
	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( false !== strpos( $request_uri, 'apg_sms' ) || false !== strpos( $request_uri, 'plugins.php' ) ) {
		wp_register_style( 'apg_sms_hoja_de_estilo', plugins_url( 'assets/css/style.css', DIRECCION_apg_sms ), array(), VERSION_apg_sms ); // Carga la hoja de estilo.
		wp_enqueue_style( 'apg_sms_hoja_de_estilo' ); // Carga la hoja de estilo.
	}
}
add_action( 'admin_enqueue_scripts', 'apg_sms_estilo' );

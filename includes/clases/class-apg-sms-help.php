<?php
/**
 * Ayuda contextual de la página de ajustes: la pestaña «Ayuda» nativa de WordPress, con un panel
 * por cada bloque de la pantalla y una entrada por cada ajuste. La versión completa vive en la web
 * y se enlaza desde el lateral.
 *
 * @package WC-APG-SMS-Notifications
 */

defined( 'ABSPATH' ) || exit;

/**
 * Añade los paneles de ayuda a la pantalla de ajustes.
 */
class APG_SMS_Help {

	/**
	 * Dirección de la documentación completa, en español.
	 */
	const DOCS_URL = 'https://artprojectgroup.es/documentacion/wc-apg-notificaciones-sms';

	/**
	 * Dirección de la documentación completa, en inglés.
	 */
	const DOCS_URL_EN = 'https://artprojectgroup.com/documentation/wc-apg-notificaciones-sms';

	/**
	 * Sustituye la ayuda de la pantalla de ajustes por los paneles del plugin.
	 *
	 * La pantalla no tiene pestañas: es una página única con varios bloques, así que se añaden
	 * todos los paneles de una vez, uno por bloque y en el orden en que aparecen. Se engancha en
	 * load-{hook de la página de ajustes}.
	 *
	 * @return void
	 */
	public static function add_tabs() {
		$screen = get_current_screen();
		if ( ! $screen ) {
			return;
		}

		// La pantalla cuelga del menú de WooCommerce y WooCommerce ya ha añadido sus propios
		// paneles y su lateral (en current_screen, antes que este load-): sólo queda la ayuda de
		// este plugin.
		$screen->remove_help_tabs();

		foreach ( self::get_panels() as $id => $panel ) {
			$screen->add_help_tab(
				array(
					'id'      => 'apg_sms_help_' . $id,
					'title'   => $panel['title'],
					'content' => self::render( $panel['items'] ),
				)
			);
		}

		$screen->set_help_sidebar(
			'<p><strong>' . esc_html__( 'More help', 'woocommerce-apg-sms-notifications' ) . '</strong></p>' .
			'<p><a href="' . esc_url( self::documentacion() ) . '" target="_blank">' . esc_html__( 'Full documentation', 'woocommerce-apg-sms-notifications' ) . '</a></p>'
		);
	}

	/**
	 * Dirección de la documentación en el idioma de quien lee la pantalla.
	 *
	 * @return string
	 */
	private static function documentacion() {
		return ( 0 === strpos( get_user_locale(), 'es' ) ) ? self::DOCS_URL : self::DOCS_URL_EN;
	}

	/**
	 * Pinta un panel: un párrafo de entrada y una lista de término => explicación.
	 *
	 * @param array $items Primer elemento: texto de entrada (cadena). Después, término => explicación.
	 * @return string
	 */
	private static function render( array $items ) {
		$html = '';
		$list = '';

		foreach ( $items as $term => $text ) {
			if ( is_int( $term ) ) {
				$html .= '<p>' . esc_html( $text ) . '</p>';
			} else {
				$list .= '<li><strong>' . esc_html( $term ) . '</strong>: ' . esc_html( $text ) . '</li>';
			}
		}

		return $html . ( '' !== $list ? '<ul>' . $list . '</ul>' : '' );
	}

	/**
	 * Paneles de la pantalla de ajustes, en el orden de sus bloques.
	 *
	 * @return array ID del panel => array( title, items ).
	 */
	private static function get_panels() {
		return array(
			'gateway'   => array(
				'title' => __( 'The gateway', 'woocommerce-apg-sms-notifications' ),
				'items' => array(
					__( 'The plugin does not send the messages itself: it hands them to the SMS gateway you hire, and that gateway charges you for them. Nothing goes out until this part is filled in.', 'woocommerce-apg-sms-notifications' ),
					__( 'SMS gateway', 'woocommerce-apg-sms-notifications' )       => __( 'which company sends your messages. Pick it and the screen asks for exactly what that one needs, which is usually a user and a password, or a key. No two gateways ask for the same thing.', 'woocommerce-apg-sms-notifications' ),
					__( 'Your mobile number', 'woocommerce-apg-sms-notifications' ) => __( 'the number where you want to be told about the shop, the one registered with your gateway. You can put several, separated by a vertical bar: 600000000|611111111.', 'woocommerce-apg-sms-notifications' ),
				),
			),

			'who'       => array(
				'title' => __( 'Who gets a message', 'woocommerce-apg-sms-notifications' ),
				'items' => array(
					__( 'Whether you are told about the shop, and which of your customer\'s numbers is used.', 'woocommerce-apg-sms-notifications' ),
					__( 'New order notification', 'woocommerce-apg-sms-notifications' )  => __( 'sends you an SMS the moment an order comes in, to the number above.', 'woocommerce-apg-sms-notifications' ),
					__( 'Send international SMS?', 'woocommerce-apg-sms-notifications' ) => __( 'adds the country prefix to the customer\'s number so the gateway accepts it. You need it as soon as you sell outside your own country, and some gateways need it always.', 'woocommerce-apg-sms-notifications' ),
					__( 'Send SMS to shipping mobile?', 'woocommerce-apg-sms-notifications' ) => __( 'writes to the number on the shipping address as well, and only when it is not the same as the billing one, so nobody gets the message twice. Useful for presents and for deliveries to a third party.', 'woocommerce-apg-sms-notifications' ),
					__( 'Shipping mobile field', 'woocommerce-apg-sms-notifications' )   => __( 'which field of the order holds that number. It only matters with the box above ticked, and the list shows the fields your shop actually has.', 'woocommerce-apg-sms-notifications' ),
				),
			),

			'messages'  => array(
				'title' => __( 'The messages', 'woocommerce-apg-sms-notifications' ),
				'items' => array(
					__( 'One message per order status, each one written by you. Under every box the screen lists the variables you can drop into the text, written between double percent signs, such as the order number, the customer name or the total.', 'woocommerce-apg-sms-notifications' ),
					__( 'Send only these messages', 'woocommerce-apg-sms-notifications' )   => __( 'which statuses send an SMS at all. Leave a status out and that change goes by in silence, which is the way to keep the bill down.', 'woocommerce-apg-sms-notifications' ),
					__( 'Custom Order Statuses & Actions', 'woocommerce-apg-sms-notifications' ) => __( 'brings in the statuses your shop has added on top of the WooCommerce ones, from a carrier plugin or your own workflow, so they can have their own message too.', 'woocommerce-apg-sms-notifications' ),
					__( 'Custom variables', 'woocommerce-apg-sms-notifications' )           => __( 'your own order fields, one per line and without the percent signs, so you can use them in the messages like any other variable.', 'woocommerce-apg-sms-notifications' ),
					__( 'order_product variable full details', 'woocommerce-apg-sms-notifications' ) => __( 'makes that variable write the whole order line by line instead of just the product names. Watch the length: an SMS is paid for in chunks of 160 characters.', 'woocommerce-apg-sms-notifications' ),
				),
			),

			'on_hold'   => array(
				'title' => __( 'Orders on hold', 'woocommerce-apg-sms-notifications' ),
				'items' => array(
					__( 'An order waiting for a bank transfer is the one case where sending the message straight away is of no use: the customer has not paid yet. These two settings are only about that status.', 'woocommerce-apg-sms-notifications' ),
					__( 'Order on-hold delay (minutes)', 'woocommerce-apg-sms-notifications' ) => __( 'waits those minutes before writing, and only writes if the order is still on hold. An order paid in the meantime never gets the reminder.', 'woocommerce-apg-sms-notifications' ),
					__( 'Order on-hold timer (hours)', 'woocommerce-apg-sms-notifications' )  => __( 'repeats the message every so many hours while the order stays on hold. Leave it empty and it is sent only once.', 'woocommerce-apg-sms-notifications' ),
				),
			),

			'debug'     => array(
				'title' => __( 'Troubleshooting', 'woocommerce-apg-sms-notifications' ),
				'items' => array(
					__( 'For when a message does not arrive. The answer almost always comes from the gateway, not from the plugin.', 'woocommerce-apg-sms-notifications' ),
					__( 'Send debug information?', 'woocommerce-apg-sms-notifications' ) => __( 'emails you what the gateway answered to each send, which is where you find out whether it was refused, whether the number was wrong or whether you have run out of credit.', 'woocommerce-apg-sms-notifications' ),
					__( 'email address', 'woocommerce-apg-sms-notifications' )           => __( 'where to send that information. Remember to switch it off once the problem is solved, or every order writes to you.', 'woocommerce-apg-sms-notifications' ),
				),
			),
		);
	}
}

<?php
/**
 * Plantilla: cuadro de informacion del plugin en la pagina de ajustes.
 *
 * Muestra enlaces de donacion, redes sociales, mas plugins, contacto
 * y documentacion/soporte dentro del panel de administracion.
 *
 * Variables esperadas en el scope del include:
 * - $apg_sms['donacion']   URL para donaciones.
 * - $apg_sms['plugin']     Nombre legible del plugin.
 * - $apg_sms['plugin_url'] URL de la documentacion/sitio del plugin.
 * - $apg_sms['soporte']    URL de la pagina de soporte.
 * - $apg_sms['puntuacion'] URL del listado en WordPress.org.
 * - $apg_sms['plugin_uri'] URL del plugin en WordPress.org.
 *
 * @package WC_APG_SMS_Notifications
 * @global  array<string,string> $apg_sms
 */

// Igual no deberias poder abrirme.
defined( 'ABSPATH' ) || exit;
?>
<div class="informacion">
	<!-- Fila: Donacion y autor -->
	<div class="fila">
		<div class="columna">
			<p>
				<?php esc_html_e( 'If you enjoyed and find helpful this plugin, please make a donation:', 'woocommerce-apg-sms-notifications' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $apg_sms['donacion'] ); ?>" target="_blank" title="<?php esc_attr_e( 'Make a donation by ', 'woocommerce-apg-sms-notifications' ); ?>APG"><span class="genericon genericon-cart"></span></a>
			</p>
		</div>
		<div class="columna">
			<p>Art Project Group:</p>
			<p>
				<a href="https://www.artprojectgroup.es" title="Art Project Group" target="_blank"><strong class="artprojectgroup">APG</strong></a>
			</p>
		</div>
	</div>

	<!-- Fila: Redes sociales y mas plugins -->
	<div class="fila">
		<div class="columna">
			<p>
				<?php esc_html_e( 'Follow us:', 'woocommerce-apg-sms-notifications' ); ?>
			</p>
			<p>
				<a href="https://www.facebook.com/artprojectgroup" title="<?php esc_attr_e( 'Follow us on ', 'woocommerce-apg-sms-notifications' ); ?>Facebook" target="_blank"><span class="genericon genericon-facebook-alt"></span></a>
				<a href="https://x.com/artprojectgroup" title="<?php esc_attr_e( 'Follow us on ', 'woocommerce-apg-sms-notifications' ); ?>X" target="_blank"><span class="genericon genericon-x-alt"></span></a>
				<a href="https://es.linkedin.com/in/artprojectgroup" title="<?php esc_attr_e( 'Follow us on ', 'woocommerce-apg-sms-notifications' ); ?>LinkedIn" target="_blank"><span class="genericon genericon-linkedin"></span></a>
			</p>
		</div>
		<div class="columna">
			<p>
				<?php esc_html_e( 'More plugins:', 'woocommerce-apg-sms-notifications' ); ?>
			</p>
			<p>
				<a href="https://profiles.wordpress.org/artprojectgroup/" title="<?php esc_attr_e( 'More plugins on ', 'woocommerce-apg-sms-notifications' ); ?>WordPress" target="_blank"><span class="genericon genericon-wordpress"></span></a>
			</p>
		</div>
	</div>

	<!-- Fila: Contacto y Documentacion/Soporte -->
	<div class="fila">
		<div class="columna">
			<p>
				<?php esc_html_e( 'Contact with us:', 'woocommerce-apg-sms-notifications' ); ?>
			</p>
			<p>
				<a href="mailto:info@artprojectgroup.es" title="<?php esc_attr_e( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ); ?>e-mail"><span class="genericon genericon-mail"></span></a>
				<a href="skype:artprojectgroup" title="<?php esc_attr_e( 'Contact with us by ', 'woocommerce-apg-sms-notifications' ); ?>Skype"><span class="genericon genericon-skype"></span></a>
			</p>
		</div>
		<div class="columna">
			<p>
				<?php esc_html_e( 'Documentation and Support:', 'woocommerce-apg-sms-notifications' ); ?>
			</p>
			<p>
				<a href="<?php echo esc_url( $apg_sms['plugin_url'] ); ?>" title="<?php echo esc_attr( $apg_sms['plugin'] ); ?>"><span class="genericon genericon-book"></span></a>
				<a href="<?php echo esc_url( $apg_sms['soporte'] ); ?>" title="<?php esc_attr_e( 'Support', 'woocommerce-apg-sms-notifications' ); ?>"><span class="genericon genericon-cog"></span></a>
			</p>
		</div>
	</div>

	<!-- Fila final: Valoracion -->
	<div class="fila final">
		<div class="columna">
			<p>
				<?php
				// translators: %s is the plugin name.
				echo esc_html( sprintf( __( 'Please, rate %s:', 'woocommerce-apg-sms-notifications' ), $apg_sms['plugin'] ) );
				?>
			</p>
			<?php echo wp_kses_post( apg_sms_plugin( $apg_sms['plugin_uri'] ) ); ?>
		</div>
		<div class="columna final"></div>
	</div>
</div>

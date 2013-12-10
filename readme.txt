=== WooCommerce - APG SMS Notifications ===
Contributors: artprojectgroup
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J3RA5W3U43JTE
Tags: woocommerce, commerce, e-commerce, commerce, shop, virtual shop, sms, sms notifications, solutions infini, twilio, clickatell, clockwork, bulksms, open end
Requires at least: 3.5
Tested up to: 3.7.1
Stable tag: 0.8.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Añade a tu tienda WooCommerce notificaciones SMS a tus clientes cuando cambie el estado del pedido.

== Description ==
**WooCommerce - APG SMS Notifications** añade a tu tienda WooCommerce la posibilidad de enviar notificaciones SMS al cliente cada vez que cambie el estado del pedido. También notifica al propietario, si así lo desea, cuando la tienda tenga un nuevo pedido.

= Características =
* Soporte de múltiples proveedores SMS:
 * Solutions Infini.
 * Twillio.
 * Clickatell.
 * Clockwork.
 * BulkSMS.
 * OPEN DND.
* Posibilidad de informar al propietario de la tienda sobre nuevos pedidos.
* Posibilidad de enviar, o no, SMS internacionales.
* Inserta de forma automática el código telefónico internacional, si es necesario, al número de teléfono del cliente.
* También notifica por SMS las notas a los clientes.
* Todos los mensajes son personalizables.
* Soporta gran cantidad de variables para personalizar nuestros mensajes: %id%, %order_key%, %billing_first_name%, %billing_last_name%, %billing_company%, %billing_address_1%, %billing_address_2%, %billing_city%, %billing_postcode%, %billing_country%, %billing_state%, %billing_email%, %billing_phone%, %shipping_first_name%, %shipping_last_name%, %shipping_company%, %shipping_address_1%, %shipping_address_2%, %shipping_city%, %shipping_postcode%, %shipping_country%, %shipping_state%, %shipping_method%, %shipping_method_title%, %payment_method%, %payment_method_title%, %order_subtotal%, %order_discount%, %cart_discount%, %order_tax%, %order_shipping%, %order_shipping_tax%, %order_total%, %status%, %shop_name% y %note%.
* Una vez configurado es totalmente automático.

= Origen =
**WooCommerce - APG SMS Notifications** ha sido programado a partir de la petición de [Chirag Vora](http://profiles.wordpress.org/chirag740) para añadir a WooCommerce la posibilidad de enviar notificaciones sobre el estado de los pedidos a través de mensajes SMS.

= Más información =
En nuestro sitio web oficial puede obtener más información sobre [**WooCommerce - APG SMS Notifications**](http://www.artprojectgroup.es/plugins-para-wordpress/woocommerce-apg-sms-notifications). 

= Comentarios =
No olvides dejarnos tu comentario en:

* [WooCommerce - APG SMS Notifications](http://www.artprojectgroup.es/plugins-para-wordpress/woocommerce-apg-sms-notifications) en Art Project Group.
* [Art Project Group](https://www.facebook.com/artprojectgroup) en Facebook.
* [@artprojectgroup](https://twitter.com/artprojectgroup) en Twitter.
* [+ArtProjectGroupES](https://twitter.com/artprojectgroup) en Google+.

= Más plugins =
Recuerda que puedes encontrar más plugin para WordPress en [Art Project Group](http://www.artprojectgroup.es/plugins-para-wordpress/) y en nuestro perfil en [WordPress](http://profiles.wordpress.org/artprojectgroup/).

== Installation ==
1. Puedes:
 * Subir la carpeta `woocommerce-apg-sms-notifications` al directorio `/wp-content/plugins/` vía FTP. 
 * Subir el archivo ZIP completo vía *Plugins -> Añadir nuevo -> Subir* en el Panel de Administración de tu instalación de WordPress.
 * Buscar **WooCommerce - APG SMS Notifications** en el buscador disponible en *Plugins -> Añadir nuevo* y pulsar el botón *Instalar ahora*.
2. Activar el plugin a través del menú *Plugins* en el Panel de Administración de WordPress.
3. Configurar el plugin en *WooCommerce -> Ajustes -> Envío* o a través del botón *Ajustes*.
4. Listo, ahora ya puedes disfrutar de él, y si te gusta y te resulta útil, hacer una [*donación*](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J3RA5W3U43JTE).


== Frequently asked questions ==
= ¿Cómo se configura? =
Para configurar el plugin sólo hay que añadir los datos proporcionados por cada proveedor SMS, y que varían en función de este. 

Además hay que añadir el número de teléfono móvil que esté vinculado con la cuenta. 

Se debe indicar si queremos, o no, recibir notificaciones SMS por cada nuevo pedido en la tienda y si queremos, o no, enviar SMS internacionales.

Por último hay que personalizar, si se desea, los mensajes que se enviarán por SMS.

= How to configure? =
[Chirag Vora](http://profiles.wordpress.org/chirag740) had made a translation in english on [Big Kahuna](http://bigkahuna.in/woocommerce-apg-sms-notifications).

== Screenshots ==
1. Captura de pantalla de WooCommerce - APG SMS Notifications.

== Changelog ==
= 0.8.8 =
* Mejora en la obtención del prefijo telefónico internacional.
= 0.8.7 =
* Mejora del código de envío mediante Clockwork.
* Mejora del código de envío mediante Clickatell.
* Mejora del código de envío mediante BulkSMS.
= 0.8.6 =
* Arreglo de error en la codificación del mensaje.
= 0.8.5 =
* Modificaciones menores del código.
= 0.8.4 =
* Arreglo de error en la fuente de iconos.
= 0.8.3 =
* Arreglo de error en el mensaje de OPEN DND.
= 0.8.2 =
* Arreglo en la codificación del mensaje.
* Añadidos botones de puntuación del plugin.
= 0.8.1 =
* Arreglo de error al enviar cambios de estado en los pedidos.
= 0.8 =
* Arreglo de error al enviar mensajes a Clockwork.
* Mejora del código de control y limpieza de teléfonos.
* Arreglo de la opción de notificación al propietario de la tienda.
= 0.7.1 =
* Control de la existencia de los parámetros de configuración.
* Modificación de la pantalla de configuración.
* Modificación de los enlaces del plugin.
* Actualización de la captura de pantalla.
* Añadidos nuevos enlaces.
= 0.7 =
* Añadido soporte para OPEN DND.
= 0.6 =
* Añadida la personalización de los mensajes. 
= 0.5 =
* Añadido soporte para BulkSMS.
= 0.4 =
* Pequeñas mejoras en el código y arreglo de erratas en las traducciones.
= 0.3.1 =
* Arreglo de pequeño error en el código.
= 0.3 =
* Añadido el envío de notas al cliente.
= 0.2 =
* Mejorado el código que comprueba el código internacional.
= 0.1 =
* Versión inicial.

== Upgrade Notice ==
= 0.8.8 =
* Mejora en la obtención del prefijo telefónico internacional.
= 0.8.7 =
* Mejora del código de envío mediante Clockwork.
* Mejora del código de envío mediante Clickatell.
* Mejora del código de envío mediante BulkSMS.
= 0.8.6 =
* Arreglo de error en la codificación del mensaje.
= 0.8.5 =
* Modificaciones menores del código.
= 0.8.4 =
* Arreglo de error en la fuente de iconos.
= 0.8.3 =
* Arreglo de error en el mensaje de OPEN DND.
= 0.8.2 =
* Arreglo en la codificación del mensaje.
* Añadidos botones de puntuación del plugin.
= 0.8.1 =
* Arreglo de error al enviar cambios de estado en los pedidos.
= 0.8 =
* Arreglo de error al enviar mensajes a Clockwork.
* Mejora del código de control y limpieza de teléfonos.
* Arreglo de la opción de notificación al propietario de la tienda.
= 0.7.1 =
* Control de la existencia de los parámetros de configuración.
* Modificación de la pantalla de configuración.
* Modificación de los enlaces del plugin.
* Actualización de la captura de pantalla.
* Añadidos nuevos enlaces.
= 0.7 =
* Añadido soporte para OPEN DND.
= 0.6 =
* Añadida la personalización de los mensajes. 
= 0.5 =
* Añadido soporte para BulkSMS.
= 0.4 =
* Pequeñas mejoras en el código y arreglo de erratas en las traducciones.
= 0.3.1 =
* Arreglo de pequeño error en el código.
= 0.3 =
* Añadido el envío de notas al cliente.
= 0.2 =
* Mejorado el código que comprueba el código internacional.

==Traducciones ==
* *English*: by **Art Project Group** (default language).
* *Español*: por **Art Project Group**.

== ¿Por qué está esta página en español? ==
Mientras WordPress no nos permita a los desarrolladores realizar esta página en más de un idioma, elegiremos siempre el español.

A pesar de que es una apuesta muy arriesgada y de que reduce mucho las posibilidades de propagación de nuestros plugins, creemos que la comunidad hispana de WordPress es lo suficientemente amplia como para abocarla al idioma inglés hasta el final de los tiempos.

Por ello regalamos a esa gran comunidad hispana nuestros plugins con interfaces, instrucciones, tutoriales, soporte y páginas web en *WordPress.org* en español.

Esperamos que os guste nuestra iniciativa.

== Donación ==
¿Te ha gustado y te ha resultado útil **WooCommerce - APG SMS Notifications** en tu sitio web? Te agradeceríamos una [pequeña donación](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=J3RA5W3U43JTE) que nos ayudará a seguir mejorando este plugin y a crear más plugins totalmente gratuitos para toda la comunidad WordPress.

== Gracias ==
* A [Chirag Vora](http://profiles.wordpress.org/chirag740) por habernos inspirado para crear **WooCommerce - APG SMS Notifications**.
* A todos los que lo usáis.
* A todos los que ayudáis a mejorarlo.
* A todos los que realizáis donaciones.
* A todos los que nos animáis con vuestros comentarios.

¡Muchas gracias a todos!

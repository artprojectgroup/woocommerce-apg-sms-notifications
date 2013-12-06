<?php $plugin = apg_sms_plugin(URI); ?>
<div class="donacion">
  <p>
    <?php _e('If you enjoyed and find helpful this plugin, please make a donation:', IDIOMA); ?>
  </p>
  <p><a href="<?php echo PAYPAL; ?>" target="_blank" title="<?php _e('Make a donation by ', IDIOMA); ?>PayPal"><span class="icon-paypal"></span></a></p>
  <div>
    <p>Art Project Group:</p>
    <p><a href="http://www.artprojectgroup.es" title="Art Project Group" target="_blank"><strong class="artprojectgroup">APG</strong></a></p>
  </div>
  <div>
    <p>
      <?php _e('Follow us:', IDIOMA); ?>
    </p>
    <p><a href="https://www.facebook.com/artprojectgroup" title="<?php _e('Follow us on ', IDIOMA); ?>Facebook" target="_blank"><span class="icon-facebook6"></span></a> <a href="https://twitter.com/artprojectgroup" title="<?php _e('Follow us on ', IDIOMA); ?>Twitter" target="_blank"><span class="icon-social19"></span></a> <a href="https://plus.google.com/+ArtProjectGroupES" title="<?php _e('Follow us on ', IDIOMA); ?>Google+" target="_blank"><span class="icon-google16"></span></a> <a href="http://es.linkedin.com/in/artprojectgroup" title="<?php _e('Follow us on ', IDIOMA); ?>LinkedIn" target="_blank"><span class="icon-logo"></span></a></p>
  </div>
  <div>
    <p>
      <?php _e('More plugins:', IDIOMA); ?>
    </p>
    <p><a href="http://profiles.wordpress.org/artprojectgroup/" title="<?php _e('More plugins on ', IDIOMA); ?>WordPress" target="_blank"><span class="icon-wordpress2"></span></a></p>
  </div>
  <div>
    <p>
      <?php _e('Contact with us:', IDIOMA); ?>
    </p>
    <p><a href="mailto:info@artprojectgroup.es" title="<?php _e('Contact with us by ', IDIOMA); ?>e-mail"><span class="icon-open21"></span></a> <a href="skype:artprojectgroup" title="<?php _e('Contact with us by ', IDIOMA); ?>Skype"><span class="icon-social6"></span></a></p>
  </div>
  <div>
    <p>
      <?php _e('Documentation and Support:', IDIOMA); ?>
    </p>
    <p><a href="<?php echo URL_PLUGIN; ?>" title="<?php echo PLUGIN; ?>"><span class="icon-work"></span></a></p>
  </div>
  <div>
    <p> <?php echo sprintf(__('Please, rate %s:', IDIOMA), PLUGIN); ?> </p>
    <div class="star-holder rate">
      <div style="width: <?php echo esc_attr(str_replace(',', '.', $plugin['rating'])); ?>px;" class="star-rating"></div>
      <div class="star-rate"> <a title="<?php _e('***** Fantastic!', IDIOMA); ?>" href="<?php echo PUNTUACION; ?>?rate=5#postform"><span></span></a> <a title="<?php _e('**** Great', IDIOMA); ?>" href="<?php echo PUNTUACION; ?>?rate=4#postform"><span></span></a> <a title="<?php _e('*** Good', IDIOMA); ?>" href="<?php echo PUNTUACION; ?>?rate=3#postform"><span></span></a> <a title="<?php _e('** Works', IDIOMA); ?>" href="<?php echo PUNTUACION; ?>?rate=2#postform"><span></span></a> <a title="<?php _e('* Poor', IDIOMA); ?>" href="<?php echo PUNTUACION; ?>?rate=1#postform"><span></span></a> </div>
    </div>
  </div>
</div>
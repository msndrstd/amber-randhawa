<?php
/**
 * The closing ask. One centred block, reused on every template.
 * See the `.lr-close` note in assets/css/system.css for why this is
 * the only centred block on the site.
 */
$heading = isset( $args['heading'] ) ? $args['heading'] : 'Come sit a spell.';
$body    = isset( $args['body'] ) ? $args['body'] : 'Slip your shoes off and pour your own glass of tea. Tell me what you are trying to do and I will tell you honestly whether I can help, and what it is going to take.';
?>
<section class="lr-sec lr-close lr-ground-oat">
  <div class="lr-wrap lr-cols lr-rise">
    <div class="lr-close-inner">
      <h2 class="lr-disp"><?php echo esc_html( $heading ); ?></h2>
      <span class="lr-mark" aria-hidden="true"></span>
      <p><?php echo esc_html( $body ); ?></p>
      <div class="lr-actions">
        <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="lr-btn lr-btn--go">Start a conversation</a>
        <a href="https://amberrandhawa.kw.com" class="lr-btn lr-btn--ghost" rel="noopener">See my listings</a>
      </div>
    </div>
  </div>
</section>

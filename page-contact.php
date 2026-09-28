<?php
/**
 * Template Name: Contact Page
 *
 * Land Records design system. All copy is Amber's, verbatim from the
 * previous version. The dark photo hero and the two eyebrow tags are
 * gone (rules 2 and 3). The form keeps its own field labels because
 * form labels are not eyebrow tags, they are how a form works.
 *
 * @package amber-randhawa
 */

get_header();

$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Contact' ],
];
?>

<main class="lr" id="main">

  <?php
  /* Photo band behind the H1, same contract as the community pages in
     page.php: assets/images/band-contact.jpg at roughly 4.5:1, with the
     linen wash from community.css section 8 keeping the type legible.
     Right-justified via object-position so Amber stays in frame when a
     narrow viewport crops the sides off. */
  $ar_band_dir = get_template_directory() . '/assets/images/band-contact.jpg';
  $ar_has_band = file_exists( $ar_band_dir );
  ?>
  <header class="lr-head<?php echo $ar_has_band ? ' lr-head--photo has-photo' : ''; ?>">

    <?php if ( $ar_has_band ) : ?>
      <div class="lr-head-media" aria-hidden="true">
        <?php $ar_band_sm = get_template_directory() . '/assets/images/band-contact-sm.jpg'; ?>
        <picture>
          <?php if ( file_exists( $ar_band_sm ) ) : ?>
            <source media="(max-width: 640px)"
                    srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/images/band-contact-sm.jpg?v=' . filemtime( $ar_band_sm ) ); ?>">
          <?php endif; ?>
          <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/band-contact.jpg?v=' . filemtime( $ar_band_dir ) ); ?>"
               alt="" decoding="async" fetchpriority="high">
        </picture>
      </div>
    <?php endif; ?>

    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp">Let's talk.</h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <p class="lr-lede">I answer my own phone. I return my own messages. Nobody types my emails for me. (I’m aware that being an actual human has somehow become a selling point, which says something about where we are as a society.)</p>
    </div>
  </header>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-cols">

      <div class="lr-c5 lr-rise">
        <p>Selling land your family has held for five generations? I know what that means, and I won’t treat it like an ordinary listing. Moving out from Atlanta and wondering what life is actually like out here? I can tell you things the listing sheet never will.</p>
        <p>Either way: no pressure, no script, and no little chat box popping up in the corner pretending to be me. Just a real conversation with a real person who knows this place and is good at her job.</p>

        <dl class="lr-facts">
          <div>
            <dt>Cell (call or text)</dt>
            <dd><a href="tel:<?php echo esc_attr( AR_PHONE_E164 ); ?>"><?php echo esc_html( AR_PHONE ); ?></a></dd>
          </div>
          <div>
            <dt>Office</dt>
            <dd><?php echo esc_html( AR_OFFICE ); ?><br><a href="tel:<?php echo esc_attr( AR_OFFICE_PHONE_E164 ); ?>"><?php echo esc_html( AR_OFFICE_PHONE ); ?></a></dd>
          </div>
          <div>
            <dt>Response time</dt>
            <dd>Within one business day, usually a lot faster.</dd>
          </div>
          <div>
            <dt>Listings</dt>
            <dd>Looking for what’s on the market right now? <a href="https://amberrandhawa.kw.com" target="_blank" rel="noopener">See my listings at KW.</a></dd>
          </div>
          <div>
            <dt>Where I work</dt>
            <dd>Metro Atlanta and northwest Georgia, including Cobb, Paulding, Haralson, Carroll and Polk counties.</dd>
          </div>
        </dl>
      </div>

      <div class="lr-rt6 lr-rise">
        <div class="lr-formcard">
          <?php
          if ( function_exists( 'wpcf7' ) ) :
              echo do_shortcode( '[contact-form-7 id="contact-form" title="Contact Form"]' );
          else : ?>
          <form class="lr-form" name="contact" method="POST" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="ar_contact_form">
            <?php wp_nonce_field( 'ar_contact_nonce', 'ar_nonce' ); ?>

            <div class="lr-form-row lr-form-row--half">
              <div class="lr-field">
                <label for="cf-name">Name</label>
                <input type="text" id="cf-name" name="name" autocomplete="name" required>
              </div>
              <div class="lr-field">
                <label for="cf-email">Email</label>
                <input type="email" id="cf-email" name="email" autocomplete="email" required>
              </div>
            </div>

            <div class="lr-form-row lr-form-row--half">
              <div class="lr-field">
                <label for="cf-phone">Phone <span class="lr-optional">(optional)</span></label>
                <input type="tel" id="cf-phone" name="phone" autocomplete="tel">
              </div>
              <div class="lr-field">
                <label for="cf-intent">I'm looking to</label>
                <select id="cf-intent" name="intent">
                  <option value="" disabled selected>Select one</option>
                  <option value="buy">Buy a home or land</option>
                  <option value="sell">Sell a home or land</option>
                  <option value="both">Buy and sell</option>
                  <option value="learn">Just learning about the area</option>
                </select>
              </div>
            </div>

            <div class="lr-form-row">
              <div class="lr-field">
                <label for="cf-message">Tell me a little about what you're after</label>
                <textarea id="cf-message" name="message" rows="5" placeholder="No wrong answers here."></textarea>
              </div>
            </div>

            <button type="submit" class="lr-btn lr-btn--go lr-form-submit">Send it</button>
          </form>
          <?php endif; ?>
        </div>
      </div>

    </div>
  </section>

</main>

<?php get_footer(); ?>

<?php
/**
 * Generic page template.
 *
 * Serves the FAQ, the Communities hub, every county and city page,
 * and the guide pages. All of them store their copy in the WordPress
 * content field, so the work here is to give that raw editor output
 * the Land Records design system: a light header, a breadcrumb trail,
 * prose held to the 62ch measure, and the shared closing ask.
 *
 * The old version opened on a forest ground. Nothing on this site is
 * a dark ground. See rule 2 in assets/css/system.css.
 */

get_header();

while ( have_posts() ) : the_post();

$lede  = get_post_meta( get_the_ID(), 'ar_lede', true );
$trail = function_exists( 'ar_crumb_trail' ) ? ar_crumb_trail() : [];

/* Header photo band. Community pages open on a photograph of the place
   with the H1 sitting on top of it. The file is band-<slug>.jpg at
   roughly 4.5:1, shot wide with dead space through the middle so the
   headline has somewhere to land. Until that file exists the band
   renders as a labelled placeholder naming the file to drop in --
   same contract as template-parts/slot.php, never a stock fallback. */
$slug     = get_post_field( 'post_name' );
$is_place = (bool) preg_match( '/-ga-real-estate$/', $slug ) || in_array( $slug, [ 'communities', 'faq' ], true );
$band     = 'band-' . $slug . '.jpg';
$has_band = $is_place && file_exists( get_template_directory() . '/assets/images/' . $band );
?>

<main class="lr" id="main">

  <header class="lr-head<?php echo $is_place ? ' lr-head--photo' : ''; ?><?php echo $has_band ? ' has-photo' : ''; ?>">

    <?php if ( $is_place ) : ?>
      <div class="lr-head-media" aria-hidden="true">
        <?php if ( $has_band ) : ?>
          <?php
          /* The 4.5:1 band is a strip. On a phone the header is roughly
             square -- the H1 wraps to three lines and drives the height --
             so cover shows only about a fifth of the strip's width and the
             subject falls outside it. A 1:1 crop of the same source is
             served below 640px. Optional: if band-<slug>-sm.jpg is absent
             the wide file is used at every width, as before. */
          $band_sm      = str_replace( '.jpg', '-sm.jpg', $band );
          $band_sm_path = get_template_directory() . '/assets/images/' . $band_sm;
          ?>
          <picture>
            <?php if ( file_exists( $band_sm_path ) ) : ?>
              <source media="(max-width: 640px)"
                      srcset="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $band_sm . '?v=' . filemtime( $band_sm_path ) ); ?>">
            <?php endif; ?>
            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/' . $band . '?v=' . filemtime( get_template_directory() . '/assets/images/' . $band ) ); ?>"
                 alt="" decoding="async" fetchpriority="high">
          </picture>
        <?php else : ?>
          <span class="lr-head-slot">
            <i>Header photo</i>
            <b><?php echo esc_html( $band ); ?></b>
            <em>4.5:1 &middot; wide, with room through the middle for the headline</em>
          </span>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp"><?php the_title(); ?></h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <?php if ( $lede ) : ?>
        <p class="lr-lede"><?php echo esc_html( $lede ); ?></p>
      <?php elseif ( has_excerpt() ) : ?>
        <p class="lr-lede"><?php echo esc_html( get_the_excerpt() ); ?></p>
      <?php endif; ?>
    </div>
  </header>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-doc">

      <div class="lr-doc-main lr-rise">
        <div class="lr-prose">
          <?php the_content(); ?>
        </div>
      </div>

      <aside class="lr-doc-aside lr-rise">
        <?php
        /* The aside exists so prose does not sit in the left two thirds
           of an empty container. On a community page it carries the
           place photo and the sibling links; everywhere else, just the
           photo slot. */
        $place    = trim( str_replace( [ '-county-ga-real-estate', '-ga-real-estate' ], '', $slug ) );
        $place    = ucwords( str_replace( '-', ' ', $place ) );
        ?>

        <div class="lr-aside-block">
          <?php get_template_part( 'template-parts/slot', null, [
              'file'  => 'place-' . $slug . '.jpg',
              'alt'   => $is_place ? 'A scene from ' . $place . ', Georgia' : get_the_title(),
              'label' => $is_place ? $place : 'Photo',
          ] ); ?>
        </div>

        <?php if ( $is_place ) :
          $trail_up = function_exists( 'ar_crumb_trail' ) ? ar_crumb_trail() : [];
          $parent   = ( count( $trail_up ) > 2 ) ? $trail_up[ count( $trail_up ) - 2 ] : null;
          $ar_near = function_exists( 'ar_nearby_places' ) ? ar_nearby_places( $slug ) : [];
          ?>
          <?php if ( $ar_near ) : ?>
          <div class="lr-aside-block">
            <span class="lr-aside-h">Nearby</span>
            <ul class="lr-aside-list">
              <?php foreach ( $ar_near as $n ) : ?>
                <li><a href="<?php echo esc_url( $n[0] ); ?>"><?php echo esc_html( $n[1] ); ?></a></li>
              <?php endforeach; ?>
            </ul>
          </div>
          <?php endif; ?>
        <?php endif; ?>

        <?php if ( function_exists( 'ar_render_page_posts' ) ) { ar_render_page_posts( $slug ); } ?>

        <div class="lr-aside-block">
          <span class="lr-aside-h">Worth reading</span>
          <ul class="lr-aside-list">
            <li><a href="<?php echo esc_url( home_url( '/commuting-to-atlanta/' ) ); ?>">How long is the drive to Atlanta?</a></li>
            <li><a href="<?php echo esc_url( home_url( '/property-taxes/' ) ); ?>">Property taxes, in plain English</a></li>
          </ul>
        </div>

      </aside>

    </div>
  </section>

  <?php get_template_part( 'template-parts/close' ); ?>

</main>

<?php endwhile; ?>
<?php get_footer(); ?>

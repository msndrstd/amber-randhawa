<?php
/**
 * Single post.
 *
 * Land Records design system. Light header, featured image at content
 * width, prose on the 62ch measure, previous/next, the shared close.
 * The old version opened on a forest ground with a linen headline.
 *
 * @package amber-randhawa
 */

get_header();

while ( have_posts() ) : the_post();

$cats     = get_the_category();
$cat_name = $cats ? $cats[0]->name : '';
$cat_link = $cats ? get_category_link( $cats[0]->term_id ) : '';
$feat_img = has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'full' ) : false;

// BlogPosting schema. Author references the site-wide Person node in functions.php.
$_ar_schema = [
    '@context'       => 'https://schema.org',
    '@type'          => 'BlogPosting',
    'headline'       => get_the_title(),
    'url'            => get_permalink(),
    'datePublished'  => get_the_date( 'c' ),
    'dateModified'   => get_the_modified_date( 'c' ),
    'author'         => [ '@id' => home_url( '/' ) . '#amber-randhawa' ],
    'articleSection' => $cat_name ? $cat_name : '',
    'mainEntityOfPage' => [ '@type' => 'WebPage', '@id' => get_permalink() ],
];
if ( $feat_img ) { $_ar_schema['image'] = $feat_img; }

$ar_writing = function_exists( 'ar_is_writing' ) && ar_is_writing();
$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    $ar_writing ? [ 'label' => 'Writing', 'url' => home_url( '/writing/' ) ] : [ 'label' => 'Blog', 'url' => home_url( '/blog/' ) ],
];
if ( $cat_name && ! $ar_writing ) { $trail[] = [ 'label' => $cat_name, 'url' => $cat_link ]; }
$trail[] = [ 'label' => get_the_title() ];
?>
<script type="application/ld+json"><?php echo wp_json_encode( $_ar_schema, JSON_UNESCAPED_SLASHES ); ?></script>

<main class="lr" id="main">

  <header class="lr-head">
    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp"><?php the_title(); ?></h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <p class="lr-lede">
        <?php echo esc_html( get_the_date( 'F j, Y' ) ); ?>
        <?php if ( $cat_name ) : ?> &middot; <?php echo esc_html( $cat_name ); ?><?php endif; ?>
      </p>
    </div>
  </header>

  <?php if ( has_post_thumbnail() ) : ?>
    <figure class="lr-band-photo">
      <?php the_post_thumbnail( 'ar-hero', [ 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ] ); ?>
    </figure>
  <?php endif; ?>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-doc">
      <div class="lr-doc-main lr-rise">
        <div class="lr-prose">
          <?php the_content(); ?>
        </div>

        <?php if ( has_tag() ) : ?>
          <p class="lr-note"><b>Filed under</b><?php echo get_the_tag_list( '', ', ' ); ?></p>
        <?php endif; ?>
      </div>

      <aside class="lr-doc-aside lr-rise">
        <div class="lr-aside-block">
          <span class="lr-aside-h">Written by</span>
          <ul class="lr-aside-list">
            <li><b>Amber Randhawa</b>Keller Williams Realty Cityside. Metro Atlanta and northwest Georgia.</li>
            <li><a href="<?php echo esc_url( home_url( '/about' ) ); ?>">More about me</a></li>
          </ul>
        </div>
        <div class="lr-aside-block">
          <span class="lr-aside-h">Worth reading</span>
          <ul class="lr-aside-list">
            <li><a href="<?php echo esc_url( home_url( '/commuting-to-atlanta' ) ); ?>">How long is the drive to Atlanta?</a></li>
            <li><a href="<?php echo esc_url( home_url( '/property-taxes' ) ); ?>">Property taxes, in plain English</a></li>
            <li><a href="<?php echo esc_url( home_url( '/communities' ) ); ?>">All the towns I work</a></li>
          </ul>
        </div>
      </aside>
    </div>
  </section>

  <?php
  /* Writing and Blog never cross-link: Amber's writing pages to her other
     writing, blog posts page to other blog posts. */
  if ( $ar_writing ) {
      $prev = get_previous_post( true );
      $next = get_next_post( true );
  } else {
      $ex   = function_exists( 'ar_writing_cat_id' ) && ar_writing_cat_id() ? [ ar_writing_cat_id() ] : [];
      $prev = get_previous_post( false, $ex );
      $next = get_next_post( false, $ex );
  }
  if ( $prev || $next ) : ?>
    <section class="lr-sec lr-sec--tight lr-ground-oat">
      <div class="lr-wrap">
        <div class="lr-cards lr-cards--2 lr-rise">
          <?php if ( $prev ) : ?>
            <div class="lr-card">
              <span class="lr-card-meta">Previous</span>
              <h2 class="lr-disp" style="font-size: var(--t-display-s); line-height: 1.25;">
                <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>"><?php echo esc_html( get_the_title( $prev ) ); ?></a>
              </h2>
            </div>
          <?php endif; ?>
          <?php if ( $next ) : ?>
            <div class="lr-card">
              <span class="lr-card-meta">Next</span>
              <h2 class="lr-disp" style="font-size: var(--t-display-s); line-height: 1.25;">
                <a href="<?php echo esc_url( get_permalink( $next ) ); ?>"><?php echo esc_html( get_the_title( $next ) ); ?></a>
              </h2>
            </div>
          <?php endif; ?>
        </div>
        <div class="lr-actions lr-actions--center">
          <a class="lr-btn lr-btn--ghost lr-btn--wide" href="<?php echo esc_url( home_url( $ar_writing ? '/writing/' : '/blog/' ) ); ?>"><?php echo $ar_writing ? 'All writing' : 'All posts'; ?></a>
        </div>
      </div>
    </section>
  <?php endif; ?>

  <?php get_template_part( 'template-parts/close' ); ?>

</main>

<?php endwhile; ?>
<?php get_footer(); ?>

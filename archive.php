<?php
/**
 * Blog archive and every taxonomy archive.
 *
 * Land Records design system. Light header, equal-weight card row,
 * the shared closing ask. The old version opened on a forest ground.
 */

get_header();

$is_blog = is_home() || is_post_type_archive( 'post' );
if ( is_category() || is_tag() || is_tax() ) {
    $head_title = single_term_title( '', false );
    $head_lede  = term_description() ? wp_strip_all_tags( term_description() ) : '';
} elseif ( is_search() ) {
    $head_title = 'Search: ' . get_search_query();
    $head_lede  = '';
} else {
    $head_title = 'The writing';
    $head_lede  = 'Local history, family stories, and the occasional electric fence.';
}

$trail = [ [ 'label' => 'Home', 'url' => home_url( '/' ) ] ];
if ( is_category() || is_tag() || is_tax() ) {
    $trail[] = [ 'label' => 'Writing', 'url' => home_url( '/blog' ) ];
}
$trail[] = [ 'label' => $head_title ];
?>

<main class="lr" id="main">

  <header class="lr-head">
    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp"><?php echo esc_html( $head_title ); ?></h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <?php if ( $head_lede ) : ?>
        <p class="lr-lede"><?php echo esc_html( $head_lede ); ?></p>
      <?php endif; ?>
    </div>
  </header>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap">

      <?php if ( have_posts() ) : ?>
        <div class="lr-cards lr-rise">
          <?php while ( have_posts() ) : the_post(); ?>
            <article class="lr-card">
              <?php if ( has_post_thumbnail() ) : ?>
                <a class="lr-card-thumb" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
                  <?php the_post_thumbnail( 'ar-card', [ 'loading' => 'lazy', 'decoding' => 'async' ] ); ?>
                </a>
              <?php endif; ?>
              <span class="lr-card-meta"><?php echo esc_html( get_the_date( 'F j, Y' ) ); ?></span>
              <h2 class="lr-disp" style="font-size: var(--t-display-s); line-height: 1.25;">
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </h2>
              <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 26 ) ); ?></p>
            </article>
          <?php endwhile; ?>
        </div>

        <?php
        $pag = paginate_links( [ 'type' => 'array', 'prev_text' => 'Newer', 'next_text' => 'Older' ] );
        if ( $pag ) : ?>
          <nav class="lr-actions lr-actions--center" aria-label="Pagination">
            <?php foreach ( $pag as $link ) {
                echo str_replace( 'page-numbers', 'lr-btn lr-btn--ghost page-numbers', $link );
            } ?>
          </nav>
        <?php endif; ?>

      <?php else : ?>
        <div class="lr-prose lr-rise">
          <p>Nothing here yet. The writing lives on the <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">main writing page</a>.</p>
        </div>
      <?php endif; ?>

    </div>
  </section>

  <?php get_template_part( 'template-parts/close' ); ?>

</main>

<?php get_footer(); ?>

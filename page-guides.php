<?php
/**
 * Template Name: Guides Hub
 *
 * The /guides/ layer from the GEO strategy. This is the citation
 * engine: questions with real answers and original data, as opposed
 * to the community pages, which are about places.
 *
 * @package amber-randhawa
 */

get_header();

/*
 * The hub finds its guides by looking up which page has each template
 * assigned, rather than by hardcoded slug. Slugs are Gogi's to choose in
 * WP admin, and a hub that 404s because a slug was typed differently is
 * a stupid way to lose a page.
 */
$guide_templates = [
    'page-guide-commute.php' => 'Six towns, one Thursday afternoon, one stopwatch.',
    'page-guide-taxes.php'   => 'What actually changed about Georgia property taxes, what a millage rate is, and how to figure out the real bill on a real house.',
];

$guides = [];
foreach ( $guide_templates as $tmpl => $blurb ) {
    // First: a page with the template explicitly assigned.
    $found = get_pages( [
        'meta_key'    => '_wp_page_template',
        'meta_value'  => $tmpl,
        'number'      => 1,
        'post_status' => 'publish',
    ] );
    $page = ! empty( $found ) ? $found[0] : null;

    // Failing that, a page whose slug this theme routes to the template.
    if ( ! $page && function_exists( 'ar_guide_template_map' ) ) {
        foreach ( ar_guide_template_map() as $slug => $file ) {
            if ( $file !== $tmpl ) { continue; }
            $by_slug = get_page_by_path( $slug );
            if ( $by_slug && 'publish' === $by_slug->post_status ) { $page = $by_slug; break; }
        }
    }

    if ( $page ) {
        $guides[] = [
            'title' => get_the_title( $page ),
            'url'   => get_permalink( $page ),
            'blurb' => $blurb,
        ];
    }
}

if ( function_exists( 'ar_extra_guides' ) ) {
    foreach ( ar_extra_guides() as $gslug => $g ) {
        $gp = get_page_by_path( $gslug );
        if ( $gp && 'publish' === $gp->post_status ) {
            $guides[] = [ 'title' => get_the_title( $gp ), 'url' => get_permalink( $gp ), 'blurb' => $g[1] ];
        }
    }
}

$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Guides' ],
];
?>

<main class="lr" id="main">

  <header class="lr-head">
    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp">The questions people actually ask me</h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <p class="lr-lede">Answered with numbers I measured myself, and with the method published so you can go check them.</p>
    </div>
  </header>

  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap">
      <?php if ( empty( $guides ) ) : ?>
        <div class="lr-prose lr-rise">
          <p><b>No guide pages found.</b> Each guide is a page template. In WP admin, create a page and set its Template to "Guide: Commuting to Atlanta" or "Guide: Property Taxes by County". If those options are not in the dropdown, the theme has not been pushed to this environment yet.</p>
        </div>
      <?php else : ?>
      <div class="lr-cards lr-cards--2 lr-rise">
        <?php foreach ( $guides as $g ) : ?>
          <article class="lr-card">
            <h2 class="lr-disp" style="font-size: var(--t-display-s); line-height: 1.25;">
              <a href="<?php echo esc_url( $g['url'] ); ?>"><?php echo esc_html( $g['title'] ); ?></a>
            </h2>
            <p><?php echo esc_html( $g['blurb'] ); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <?php get_template_part( 'template-parts/close' ); ?>

</main>

<?php get_footer(); ?>

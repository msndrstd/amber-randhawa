<?php
/**
 * Template Name: Guide: Commuting to Atlanta
 *
 * Tier 1 of the GEO build. The extraction pattern: a question-phrased
 * heading, a direct answer immediately under it, then the evidence in a
 * real <table>. Do not convert these to divs.
 *
 * COPY: from "Amber Randhawa - Full Website Content (v2) DUTCH" as edited
 * by Amber, pulled in 2026-09-28. Her edits removed: the Penalty column
 * and any mention of the API (she will not publish terms she would not
 * use herself), the Cobb table, the Paulding Transit section (only
 * senior/low-income on-demand service exists), and the "Where do you
 * actually work?" close.
 *
 * Drive figures: Google Maps Routes API, Thursday 28 August 2026, about
 * 4:30pm Eastern, inbound. AFTERNOON figures. Do not relabel as morning.
 * Route 476 is SRTA's Xpress service (verified at xpressga.com).
 *
 * @package amber-randhawa
 */

get_header();

$downtown = [
    [ 'Hiram',      27, '40 min', '59 min' ],
    [ 'Dallas',     34, '52 min', '73 min' ],
    [ 'Villa Rica', 33, '40 min', '57 min' ],
    [ 'Temple',     41, '42 min', '58 min' ],
    [ 'Bremen',     48, '53 min', '69 min' ],
    [ 'Rockmart',   47, '66 min', '85 min' ],
];
$airport = [
    [ 'Hiram',      35, '47 min', '56 min' ],
    [ 'Dallas',     42, '60 min', '71 min' ],
    [ 'Villa Rica', 44, '47 min', '55 min' ],
    [ 'Temple',     48, '49 min', '56 min' ],
    [ 'Bremen',     56, '60 min', '67 min' ],
    [ 'Rockmart',   66, '74 min', '83 min' ],
];

$faq = [
    [
        'How long is the drive to downtown Atlanta?',
        'It depends far more on interstate access than on distance. Villa Rica is 33 miles from downtown and about 57 minutes in afternoon traffic. Dallas is 34 miles, one mile closer, and 73 minutes. The difference is not mysterious: a Dallas or Hiram commuter spends much longer on surface streets than on an interstate.',
    ],
    [
        'Is there a bus or train from Paulding County to Atlanta?',
        'For all practical purposes, no, there is no public transportation you can use to regularly commute from Paulding County to Atlanta. The Xpress Route 476, run by SRTA’s Xpress service, does make three daily trips from the Hiram to Downtown and Midtown Atlanta. You’ll sit through many stops, and you’ll have very little flexibility in when you leave and arrive. If public transportation is important to you, I encourage you to research whether or not this would be a viable option for you.',
    ],
    [
        'Do most people in Paulding County commute to Atlanta?',
        'No. Almost a third of working Paulding residents commute to Cobb County, not Atlanta (source: metroatlanta.jobs). Cobb is the real destination for a big chunk of this county, and it is a much easier drive. Much of Hiram is about 30 minutes from the Marietta Square. So the honest answer to “where should I live” is a question back: where do you actually work? The right answer for a Midtown job and the right answer for a Marietta job are two different counties.',
    ],
];

$faq_schema = [
    '@context'   => 'https://schema.org',
    '@type'      => 'FAQPage',
    'mainEntity' => array_map( function ( $q ) {
        return [
            '@type'          => 'Question',
            'name'           => $q[0],
            'acceptedAnswer' => [ '@type' => 'Answer', 'text' => $q[1] ],
        ];
    }, $faq ),
];

$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'Guides', 'url' => home_url( '/guides/' ) ],
    [ 'label' => 'Commuting to Atlanta' ],
];
?>
<script type="application/ld+json"><?php echo wp_json_encode( $faq_schema, JSON_UNESCAPED_SLASHES ); ?></script>

<main class="lr" id="main">

  <?php $ar_band = get_template_directory() . '/assets/images/band-commuting-to-atlanta.jpg'; ?>
  <header class="lr-head<?php echo file_exists( $ar_band ) ? ' lr-head--photo has-photo' : ''; ?>">
    <?php get_template_part( 'template-parts/head-band', null, [ 'slug' => 'commuting-to-atlanta' ] ); ?>

    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp">Commuting to Atlanta from northwest Georgia</h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <p class="lr-lede">Six towns, measured the same afternoon with the same tool. Bremen is 17 miles farther from Atlanta than Dallas, but in a race, your Bremen coworker is going to win.</p>
    </div>
  </header>

  <!-- ══════ DOWNTOWN ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-doc">
      <div class="lr-doc-main lr-rise">
        <p>That is not a typo and it is not a sales line. Bremen, Temple and Villa Rica sit on I-20. Paulding County does not have direct access to an interstate at all, which is not a thing you think about until you are sitting on 278 west at ten past five.</p>

        <h2 class="lr-disp"><?php echo esc_html( $faq[0][0] ); ?></h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p class="lr-answer"><?php echo esc_html( $faq[0][1] ); ?></p>

        <div class="lr-tablescroll">
          <table class="lr-table">
            <caption>Measured drive times to downtown Atlanta. Thursday 28 August 2026, 4:30pm Eastern.</caption>
            <thead>
              <tr>
                <th scope="col">From</th>
                <th scope="col" class="num">Miles</th>
                <th scope="col" class="num">Clear roads</th>
                <th scope="col" class="num">Thu 4:30pm</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ( $downtown as $r ) : ?>
              <tr>
                <th scope="row"><?php echo esc_html( $r[0] ); ?></th>
                <td class="num"><?php echo esc_html( $r[1] ); ?></td>
                <td class="num"><?php echo esc_html( $r[2] ); ?></td>
                <td class="num"><?php echo esc_html( $r[3] ); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <p>What these numbers tell you more than anything is how important proximity to I-20 is. Villa Rica is one mile closer than Dallas and sixteen minutes faster. Temple is seven miles farther and fifteen minutes faster. This is a great reminder that &ldquo;how far is it&rdquo; and &ldquo;how long does it take&rdquo; are two very different questions.</p>
      </div>

      <aside class="lr-doc-aside lr-rise">
        <div class="lr-aside-block">
          <?php get_template_part( 'template-parts/slot', null, [
              'file' => 'guide-commute.jpg', 'label' => 'US 278 at rush hour', 'alt' => 'US 278 through Hiram, Georgia',
          ] ); ?>
        </div>
        <div class="lr-aside-block">
          <span class="lr-aside-h">At a glance</span>
          <ul class="lr-aside-list">
            <li><b>57 min</b>Villa Rica to downtown, the fastest of the six</li>
            <li><b>73 min</b>Dallas to downtown, one mile closer</li>
            <li><b>About 30 min</b>Hiram to the Marietta Square</li>
          </ul>
        </div>
        <?php if ( function_exists( 'ar_render_page_posts' ) ) { ar_render_page_posts( 'commuting-to-atlanta' ); } ?>
        <div class="lr-aside-block">
          <span class="lr-aside-h">Next</span>
          <ul class="lr-aside-list">
            <li><a href="<?php echo esc_url( home_url( '/property-taxes/' ) ); ?>">Property taxes, in plain English</a></li>
            <li><a href="<?php echo esc_url( home_url( '/communities/' ) ); ?>">All the towns I work</a></li>
          </ul>
        </div>
      </aside>
    </div>
  </section>

  <!-- ══════ AIRPORT ══════ -->
  <section class="lr-sec lr-ground-oat">
    <div class="lr-wrap lr-doc">
      <div class="lr-doc-main lr-rise">
        <h2 class="lr-disp">What about the airport?</h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <div class="lr-tablescroll">
          <table class="lr-table">
            <caption>To Hartsfield-Jackson, same measurement</caption>
            <thead>
              <tr>
                <th scope="col">From</th>
                <th scope="col" class="num">Miles</th>
                <th scope="col" class="num">Clear roads</th>
                <th scope="col" class="num">Thu 4:30pm</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ( $airport as $r ) : ?>
              <tr>
                <th scope="row"><?php echo esc_html( $r[0] ); ?></th>
                <td class="num"><?php echo esc_html( $r[1] ); ?></td>
                <td class="num"><?php echo esc_html( $r[2] ); ?></td>
                <td class="num"><?php echo esc_html( $r[3] ); ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <aside class="lr-doc-aside lr-rise">
        <div class="lr-aside-block">
          <?php get_template_part( 'template-parts/slot', null, [
              'file' => 'guide-airport.jpg', 'label' => 'Hartsfield-Jackson, or a plane overhead', 'alt' => '',
          ] ); ?>
        </div>
      </aside>
    </div>
  </section>

  <!-- ══════ COBB + TRANSIT — one section. Two short answers each got their
       own section and a full-height photo, which left most of each band
       empty. Merged 2026-09-28. ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-doc">
      <div class="lr-doc-main lr-rise">
        <h2 class="lr-disp"><?php echo esc_html( $faq[2][0] ); ?></h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p class="lr-answer"><?php echo esc_html( $faq[2][1] ); ?></p>

        <h2 class="lr-disp"><?php echo esc_html( $faq[1][0] ); ?></h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p class="lr-answer"><?php echo esc_html( $faq[1][1] ); ?></p>

        <p class="lr-note"><b>How I measured this</b>Every town was checked the same afternoon, Thursday 28 August 2026, at about 4:30pm Eastern, using Google Maps with live traffic. &ldquo;Clear roads&rdquo; is the same trip with no traffic at all. Each trip starts in the middle of town: 129 E Memorial Drive in Dallas, the Hiram Park-and-Ride at 79 Metromont Road, 501 Pacific Avenue in Bremen, 316 N Piedmont Avenue in Rockmart, 571 W Bankhead Highway in Villa Rica, 535 Carrollton Street in Temple. Downtown Atlanta is 191 Peachtree Street NE. These are afternoon trips into the city, which is the easier direction. Ask me about seven in the morning and I will tell you the truth.</p>
      </div>
      <aside class="lr-doc-aside lr-rise">
        <div class="lr-aside-block">
          <?php get_template_part( 'template-parts/slot', null, [
              'file' => 'guide-cobb.jpg', 'label' => 'Marietta Square', 'alt' => '',
          ] ); ?>
        </div>
      </aside>
    </div>
  </section>

  <?php get_template_part( 'template-parts/close' ); ?>

</main>

<?php get_footer(); ?>

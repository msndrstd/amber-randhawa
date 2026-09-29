<?php
/**
 * Homepage Template — LAND RECORDS v2
 * Amber Randhawa
 *
 * Two rules this template exists to enforce:
 *   1. ONE GRID. Every section shares the same container and the same
 *      left edge. Nothing is indented or staggered for effect.
 *   2. COLOUR IS THE STRUCTURE. The section grounds run
 *      linen, forest, oat, sage, linen, oat, forest, coral.
 *      All six Porch Light colours carry the page at full-bleed scale.
 *
 * Previous versions preserved as front-page.php.bak-*
 *
 * COPY RULE: no line on this page compares her to other agents. We say what
 * she does, never what they do not. Beyond that, every line is her own wording,
 * from the discovery
 * questionnaire, the brand voice guide, or the previous homepage.
 * Section 04 is the only new prose, written to her voice guide.
 * Do not paraphrase, tidy, or professionalise any of it.
 *
 * FACTS: drive times and medians come from reference_amber_verified_local_data
 * (Routes API, Thursday 2026-08-28, 4:30pm ET, inbound). Inbound at 4:30pm is
 * the LIGHT direction. Do not relabel as a morning commute without re-measuring.
 */
get_header();

$_dir = get_template_directory() . '/assets/images/';
$_uri = get_template_directory_uri() . '/assets/images/';

if ( ! function_exists( 'ar_photo_first' ) ) {
    function ar_photo_first( $files, $dir, $uri ) {
        foreach ( $files as $f ) {
            if ( file_exists( $dir . $f ) ) {
                return $uri . str_replace( '%2F', '/', rawurlencode( $f ) );
            }
        }
        return false;
    }
}

$p_hero  = ar_photo_first( [ 'home-hero-portrait.jpg', 'web/amber-hero.jpg' ], $_dir, $_uri );
$p_about = ar_photo_first( [ 'home-about-portrait.jpg', 'web/amber-about.jpg' ], $_dir, $_uri );

/* Hero reel. Muted, looping, no controls. The poster carries the first paint
   and phones get a still instead, so they never download the video. */
$_vdir   = get_template_directory() . '/assets/video/';
$_vuri   = get_template_directory_uri() . '/assets/video/';
$p_reel   = file_exists( $_vdir . 'hero-reel-fs.mp4' ) ? $_vuri . 'hero-reel-fs.mp4' : false;
$p_poster = ar_photo_first( [ 'hero-reel-fs-poster.jpg', 'home-hero-portrait.jpg' ], $_dir, $_uri );

$counties = [
    [ 'name'=>'Paulding County', 'slug'=>'paulding-county-ga-real-estate', 'towns'=>'Dallas &middot; Hiram',
      'know'=>'Where I grew up. Fastest growing of the four and it shows, though the west side towards Haralson County is just about as rural as it ever was.',
      'median'=>'$341,000', 'drive'=>'About an hour', 'from'=>'from Dallas' ],
    [ 'name'=>'Haralson County', 'slug'=>'haralson-county-ga-real-estate', 'towns'=>'Bremen &middot; Buchanan &middot; Tallapoosa',
      'know'=>'Bremen City Schools ranks sixth in Georgia on Niche’s 2026 Best School Districts list, which can affect how much you’ll pay for a house and how much you’ll be able to sell it for later.',
      'median'=>'$374,815', 'drive'=>'Around 70 minutes', 'from'=>'from Bremen' ],
    [ 'name'=>'Polk County', 'slug'=>'polk-county-ga-real-estate', 'towns'=>'Rockmart &middot; Cedartown',
      'know'=>'Overall the most rural of the counties listed. While folks who don’t mind a long drive may still commute into Atlanta or some of the more metro suburbs, Rockmart and Cedartown tend to be a bit more self-contained.',
      'median'=>'$273,638', 'drive'=>'Roughly 75 minutes', 'from'=>'from Rockmart' ],
    [ 'name'=>'Carroll County', 'slug'=>'carroll-county-ga-real-estate', 'towns'=>'Carrollton &middot; Villa Rica &middot; Temple',
      'know'=>'Villa Rica and Temple get away with still being Atlanta bedroom communities thanks to their location squarely on I-20. Carrollton is the largest city and is anchored by a state university and several large industries that help keep many of the people who live here working here as well.',
      'median'=>'$329,721', 'drive'=>'About 45 minutes', 'from'=>'from Villa Rica' ],
];

$drive = [
    [ 'Villa Rica', 57, 33, true  ],
    [ 'Temple',     58, 41, true  ],
    [ 'Hiram',      59, 27, false ],
    [ 'Bremen',     69, 48, true  ],
    [ 'Dallas',     73, 34, false ],
    [ 'Rockmart',   85, 47, false ],
];
$drive_max = 90;
?>


<main class="lr" id="main">

  <!-- ══════ 01 · HERO — full-bleed reel ══════ -->
  <section class="lr-hero-fs">
    <?php if ( $p_reel ) : ?>
      <video class="lr-hero-bg lr-hero-reel" autoplay muted loop playsinline preload="metadata"
             poster="<?php echo esc_url( $p_poster ); ?>" aria-hidden="true" tabindex="-1">
        <source src="<?php echo esc_url( $p_reel ); ?>" type="video/mp4">
      </video>
    <?php endif; ?>
    <img class="lr-hero-bg lr-hero-still" src="<?php echo esc_url( $p_poster ); ?>"
         alt="Northwest Georgia, from Amber Randhawa's reel" fetchpriority="high">
    <div class="lr-hero-scrim" aria-hidden="true"></div>

    <div class="lr-wrap lr-cols lr-hero-fs-inner">
      <div class="lr-c6 lr-hero-copy">
        <h1 class="lr-disp">I know this place. But not from a market report.</h1>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>I grew up on a remote 2.5 mile dirt road that connected one middle of nowhere to another. When people ask me where I’m from, my answer depends largely on where THEY are from. If you’re local enough to have heard of it, I’ll claim Draketown because that’s almost accurate. If you’re Southern cityfolk, I might say Dallas, or Rockmart, or Villa Rica, or Bremen. If you’re from Atlanta I’ll ask you if you’ve heard of Carrollton, and if you’re a Yankee I’ll just say Atlanta and be done with it! That’s the way it goes when you grow up so far out in the sticks the land doesn’t even have a name.</p>
        <p>There’s no crossroads in Polk, Paulding or Haralson County that I can’t tell you some sort of story about, and more times than not it will even be true. I can tell you which backroads will get you somewhere faster, and which roads are prone to flooding out in the spring storms. I know which little downtown areas tend to be speed traps, and which gas station parking lots have the best boiled peanuts. I can even tell you which bent trees mark the direction to a water source, because according to local legends, the Cherokee marked them that way before they were forced off this land nearly 200 years ago.</p>
        <div class="lr-actions">
          <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="lr-btn">Come sit a spell</a>
          <a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>" class="lr-btn lr-btn--ghost">Read the writing</a>
        </div>
        <span class="lr-tag">&ldquo;I can&rsquo;t wait to take you home.&rdquo;</span>
      </div>
    </div>
  </section>

  <!-- ══════ 02 · WHY NO SEARCH BAR — oat ══════ -->
  <section class="lr-sec lr-ground-oat lr-quote">
    <div class="lr-wrap lr-cols lr-rise">
      <div class="lr-c6">
        <blockquote class="lr-disp">&ldquo;Honey, why would you think I want to search around on your site if I haven&rsquo;t gotten to know you yet?&rdquo;</blockquote>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>That’s the same thing I said when we started building this website. That’s why there is no search bar shouting at you, no pop-up, nothing bugging you to chat from the corner. Get to know me first. The houses will still be there. Most of ’em anyway.</p>
      </div>
      <div class="lr-rt5 lr-quote-photo">
        <?php if ( $p_hero ) : ?>
          <img src="<?php echo esc_url( $p_hero ); ?>" alt="Amber Randhawa, caught mid-story" loading="lazy" width="900" height="1125">
        <?php else : ?>
          <div class="lr-slot"><span>Photo &middot; Amber
            <b>Caught mid-story rather than posed. Save as assets/images/home-hero-portrait.jpg</b></span></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- ══════ 03 · THE LEDGER — linen ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap">
      <div class="lr-cols lr-rise">
        <div class="lr-c6">
          <h2 class="lr-disp">There’s a lot of small towns out here. I can tell you which one you may actually want to live in.</h2>
          <span class="lr-mark" aria-hidden="true"></span>
          <p>There’s a lot of small towns in my coverage area, and I can help you decide which one you want to be a part of.</p>
          <p>These four counties are just some of the ones I cover, but when you ask me whether Bremen or Dallas makes more sense for you, I am comparing two places I actually know and have driven to and from many times.</p>
        </div>
      </div>

      <table class="lr-ledger lr-rise">
        <caption>Examples of four of the counties Amber Randhawa serves in northwest Georgia, with recent median sale price and measured drive time to downtown Atlanta.</caption>
        <thead>
          <tr>
            <th scope="col">County</th>
            <th scope="col">Towns</th>
            <th scope="col">What I know about it</th>
            <th scope="col" class="c-fig">Median</th>
            <th scope="col" class="c-fig">To downtown</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ( $counties as $c ) : ?>
          <tr>
            <th scope="row" class="c-name"><a href="<?php echo esc_url( home_url( '/' . $c['slug'] ) ); ?>"><?php echo esc_html( $c['name'] ); ?></a></th>
            <td class="c-towns"><?php echo wp_kses_post( $c['towns'] ); ?></td>
            <td class="c-know"><?php echo esc_html( $c['know'] ); ?></td>
            <td class="c-fig"><span class="lr-fig"><?php echo esc_html( $c['median'] ); ?></span><span class="lr-fig-sub">Median</span></td>
            <td class="c-fig"><span class="lr-fig"><?php echo esc_html( $c['drive'] ); ?></span><span class="lr-fig-sub"><?php echo esc_html( $c['from'] ); ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </section>

  <!-- ══════ 04 · THE DRIVE — oat. Writing left, table right. ══════ -->
  <section class="lr-sec lr-ground-oat">
    <div class="lr-wrap lr-cols">
      <div class="lr-c6 lr-rise">
        <h2 class="lr-disp">Bremen is 17 miles farther from Atlanta than Dallas, but in a race, your Bremen coworker is going to win.</h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>That is not a typo and it is not a sales line. Bremen, Temple and Villa Rica sit on I-20. Paulding County does not have direct access to an interstate at all, which is not a thing you think about until you are sitting on 278 west at ten past five, staring directly into the setting sun.</p>
        <p>There is a second number that matters just as much. Almost a third of Paulding County works in Cobb, not Atlanta. If that is you, the whole calculation flips. Much of Hiram is about 30 minutes from the Marietta Square, but from Bremen this trip could take well over an hour.</p>
        <p class="lr-note"><b>Measured, not estimated.</b>I drove these on a Thursday afternoon and wrote down what the clock said. Inbound at 4:30 is the easy direction. Ask me about seven in the morning and I will tell you the truth. It can get a bit hairy, but the westside commute is still one of the easiest Atlanta has to offer!</p>
      </div>

      <div class="lr-rt5 lr-rise">
        <table class="lr-bars">
          <caption>Drive time to downtown Atlanta, Thursday 4:30pm</caption>
          <thead>
            <tr>
              <th scope="col">Town</th>
              <th scope="col" class="b-track">&nbsp;</th>
              <th scope="col" class="b-val">Min</th>
              <th scope="col" class="b-miles">Miles</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $drive as $d ) : ?>
            <tr>
              <th scope="row"><?php echo esc_html( $d[0] ); ?></th>
              <td class="b-track"><span style="width: <?php echo esc_attr( round( $d[1] / $drive_max * 100, 1 ) ); ?>%"></span></td>
              <td class="b-val"><?php echo esc_html( $d[1] ); ?></td>
              <td class="b-miles"><?php echo esc_html( $d[2] ); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <!-- ══════ 04b · THE BAND — full-bleed photograph, no type ══════ -->
  <figure class="lr-band-photo">
    <img src="<?php echo esc_url( $_uri ); ?>band-pasture-1800.jpg"
         srcset="<?php echo esc_url( $_uri ); ?>band-pasture-1000.jpg 1000w, <?php echo esc_url( $_uri ); ?>band-pasture-1800.jpg 1800w"
         sizes="100vw"
         alt="A white board fence curving past a farm pond under a pecan tree, pine treeline beyond, northwest Georgia"
         loading="lazy" decoding="async" width="1800" height="750">
  </figure>

  <!-- ══════ 05 · WORKING WITH ME — linen ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap">
      <div class="lr-cols lr-rise">
        <div class="lr-c6">
          <h2 class="lr-disp">The transaction is complicated. I am not.</h2>
        </div>
      </div>
      <div class="lr-beats lr-rise">
        <div class="lr-beat">
          <span class="lr-beat-n" aria-hidden="true">i</span>
          <h3 class="lr-disp">I answer my own phone</h3>
          <p>Not a team. Not an assistant. Not a chat window with a friendly name that turns out to be three people in another time zone sharing one login. You call me, you get me. And I will tell you the truth about a house even when the truth talks you right out of buying it. You can’t return a house. I checked.</p>
        </div>
        <div class="lr-beat">
          <span class="lr-beat-n" aria-hidden="true">ii</span>
          <h3 class="lr-disp">I have been inside the comps</h3>
          <p>I price from houses I have actually stood in, not from what some robot or AI guessed about. A Zestimate has never smelled a basement. I have. I know what your street looks like at seven in the morning, and I usually know what’s being built two blocks over before anyone puts a sign in the dirt.</p>
        </div>
        <div class="lr-beat">
          <span class="lr-beat-n" aria-hidden="true">iii</span>
          <h3 class="lr-disp">I will make you laugh</h3>
          <p>Buying or selling a house is one of the most stressful things most people ever do, right up there with wedding planning and assembling Ikea furniture. I break the ice, I fill the awkward silences, and I will get a laugh out of you before we’re finished filling out the paperwork.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════ 06 · THE WRITING — oat ══════ -->
  <section class="lr-sec lr-ground-oat">
    <div class="lr-wrap">
      <div class="lr-cols lr-rise">
        <div class="lr-c6">
          <h2 class="lr-disp">Local history, family stories, and the occasional electric fence.</h2>
        </div>
      </div>

      <div class="lr-entries lr-rise">
        <?php
        /* One of Amber's own pieces first, then the two newest blog posts.
           Three cards keeps the row full, and the blog slots refresh every
           Monday, so the homepage always links to the newest post. */
        $wid  = function_exists( 'ar_writing_cat_id' ) ? ar_writing_cat_id() : 0;
        $ids  = [];
        if ( $wid ) {
            $ids = array_merge(
                get_posts( [ 'fields' => 'ids', 'numberposts' => 1, 'category' => $wid ] ),
                get_posts( [ 'fields' => 'ids', 'numberposts' => 2, 'category__not_in' => [ $wid ] ] )
            );
        }
        $q = $ids
            ? new WP_Query( [ 'post__in' => $ids, 'orderby' => 'post__in', 'posts_per_page' => 3, 'ignore_sticky_posts' => true ] )
            : new WP_Query( [ 'posts_per_page' => 3, 'ignore_sticky_posts' => true ] );
        if ( $q->have_posts() ) : while ( $q->have_posts() ) : $q->the_post();
        ?>
          <article class="lr-entry">
            <?php if ( has_post_thumbnail() ) : ?>
              <div class="lr-entry-thumb"><a href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true"><?php the_post_thumbnail( 'medium_large' ); ?></a></div>
            <?php endif; ?>
            <h3 class="lr-disp"><a href="<?php the_permalink(); ?>"><?php echo ar_display_title(); ?></a></h3>
            <p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
          </article>
        <?php endwhile; wp_reset_postdata(); endif; ?>
      </div>

      <div class="lr-actions lr-actions--center">
        <a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>" class="lr-btn lr-btn--ghost lr-btn--wide">All writing</a>
        <a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>" class="lr-btn lr-btn--ghost lr-btn--wide">The blog</a>
      </div>
    </div>
  </section>

  <!-- ══════ 07 · ABOUT — linen ══════ -->
  <section class="lr-sec lr-ground-linen lr-about">
    <div class="lr-wrap lr-cols">
      <div class="lr-c4 lr-rise">
        <?php if ( $p_about ) : ?>
          <img src="<?php echo esc_url( $p_about ); ?>" alt="Amber Randhawa climbing out of a photo booth, laughing" loading="lazy" width="800" height="1000">
        <?php else : ?>
          <div class="lr-slot"><span>Photo 06 &middot; About
            <b>Amber somewhere that is hers. A porch, a cemetery, a truck. Not an office.</b></span></div>
        <?php endif; ?>
      </div>
      <div class="lr-rt7 lr-rise">
        <h2 class="lr-disp">Twenty-five years in Corporate America. Plan B was mortuary school.</h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>I did background investigations, technical writing and anti-money laundering investigations for a Big Four firm, in roles so hard to explain that my own family could not tell you what I did. I was everyone&rsquo;s Chandler Bing. Then I got laid off, and instead of updating my resume I finally did what my husband had been telling me to do for two decades.</p>
        <p class="lr-pull">I am a writer, a genealogy obsessive, and a person who knows which cemeteries are worth walking. Real estate is how I fund the rest of it!</p>
        <div class="lr-actions">
          <a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="lr-btn lr-btn--ghost">Read the whole story</a>
        </div>
      </div>
    </div>
  </section>

  <!-- ══════ 08 · COME SIT A SPELL — oat. The closing ask. ══════ -->
  <section class="lr-sec lr-close lr-ground-oat">
    <div class="lr-wrap lr-cols lr-rise">
      <div class="lr-close-inner">
        <h2 class="lr-disp">Come sit a spell.</h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>Slip your shoes off and pour your own glass of tea. Tell me what you are trying to do and I will tell you honestly whether I can help, and what it is going to take.</p>
        <div class="lr-actions">
          <a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="lr-btn lr-btn--go">Start a conversation</a>
          <a href="https://amberrandhawa.kw.com" class="lr-btn lr-btn--ghost" rel="noopener">See my listings</a>
        </div>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>

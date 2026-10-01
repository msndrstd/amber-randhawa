<?php
/**
 * Template Name: About Page
 *
 * Land Records design system. Every word of copy on this page is
 * Amber's and is reproduced verbatim from the previous version.
 * Nothing was tidied, shortened or rephrased. See
 * feedback_amber_preserve_wording in project memory.
 *
 * What changed is the frame: the dark hero and the forest quote band
 * are gone (rule 2, nothing on this site is a dark ground), the five
 * eyebrow tags are gone (rule 3), and the Unsplash fallback that used
 * to fill the middle photo slot is gone with them. It pulled a generic
 * stock house from a third-party CDN onto a page whose whole argument
 * is that she is actually from here.
 *
 * @package amber-randhawa
 */

get_header();

$img_dir = get_template_directory() . '/assets/images/';
$img_uri = get_template_directory_uri() . '/assets/images/';

$find = function ( array $names ) use ( $img_dir, $img_uri ) {
    foreach ( $names as $f ) {
        if ( file_exists( $img_dir . $f ) ) { return $img_uri . $f; }
    }
    return false;
};

// Only ever look for files from an APPROVED shoot. Amber does not like
// the April 2026 photos, so they are deliberately not in these lists. When
// the new shoot lands, drop the files in under these names and both slots
// fill themselves. Until then the writing takes the full column, which is
// better than featuring a photo the client has rejected.
$local_img = $find( [ 'local.jpg', 'land.jpg', 'georgia.jpg' ] );
$band      = $find( [ 'band-about-1800.jpg' ] );
$band_sm   = $find( [ 'band-about-1000.jpg' ] );

$trail = [
    [ 'label' => 'Home', 'url' => home_url( '/' ) ],
    [ 'label' => 'About' ],
];
?>

<main class="lr" id="main">

  <header class="lr-head">
    <div class="lr-wrap">
      <?php get_template_part( 'template-parts/crumbs', null, [ 'trail' => $trail ] ); ?>
      <h1 class="lr-disp">So, where are you from?</h1>
      <span class="lr-mark" aria-hidden="true"></span>
      <p class="lr-lede">Realtor, writer, local historian, and lifelong resident of northwest Georgia.</p>
    </div>
  </header>

  <!-- ══════ WHO I AM — linen. Writing left, portrait right. ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-cols">
      <div class="lr-c6 lr-rise">
        <p>I usually hate this question, because I did not grow up in a city or a town or even a neighborhood. I grew up on a 2.5 mile dirt road that connected one middle of nowhere to another, halfway between two unincorporated communities, far enough out that the people inside those communities considered themselves city folk by comparison. I claim Draketown because it is closest. But really, I grew up on land without a name.</p>
        <p>I know this land the way you only can when you grow up on it. I know which berries are safe to eat, which snakes to leave alone, and how to read the sun to tell how long I have to get home before dark. I know the crimes, the church fires, the family feuds, and the ghost stories. And I will tell you every one of them if you sit still long enough.</p>
      </div>
      <div class="lr-rt5 lr-rise">
        <?php get_template_part( 'template-parts/slot', null, [
            'file' => 'about-work.jpg', 'label' => 'At work, or on a porch', 'alt' => '',
        ] ); ?>
      </div>
    </div>
  </section>

  <!-- ══════ THE WRITING — oat ══════ -->
  <section class="lr-sec lr-ground-oat">
    <div class="lr-wrap lr-cols">
      <div class="lr-c7 lr-rise">
        <p>As for the writing, this just about sums it up:</p>
        <blockquote class="lr-pull">
          <p>&ldquo;The self deprecating comments I add to a story are a load bearing structure. Please, I ask of everyone, laugh at my pain, mishaps and ridiculous situations - that makes them worth it. I often tell absurd stories because absurd things happen to me. You don&rsquo;t need to be creative if your life is already stranger than fiction.&rdquo;</p>
        </blockquote>
      </div>
    </div>
  </section>

  <?php if ( $band ) : ?>
  <figure class="lr-band-photo">
    <img src="<?php echo esc_url( $band ); ?>"
         <?php if ( $band_sm ) : ?>srcset="<?php echo esc_url( $band_sm ); ?> 1000w, <?php echo esc_url( $band ); ?> 1800w" sizes="100vw"<?php endif; ?>
         alt="An open hay pasture behind a dark board fence, pine and hardwood treeline along the far edge, northwest Georgia"
         loading="lazy" decoding="async" width="1800" height="750">
  </figure>
  <?php endif; ?>

  <!-- ══════ THE PIVOT — linen ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap lr-cols">
      <div class="lr-c7 lr-rise">
        <h2 class="lr-disp">Twenty-five years in Corporate America. Plan B was mortuary school.</h2>
        <span class="lr-mark" aria-hidden="true"></span>
        <p>(No, I&rsquo;m not joking about that second part. It&rsquo;s a recession-proof industry after all!)</p>
        <p>I spent two and a half decades in jobs so hard to explain that my own family could not tell you what I did for a living. Background investigations. Technical writing. Anti-money laundering investigations for a Big Four accounting firm. I was everyone&rsquo;s Chandler Bing. A transponster, if you will.</p>
        <p>Then the layoff made the decision for me. Instead of updating my resume, I finally did what my husband had been telling me to do for twenty years. My biggest goal in life now is to never have to log into Linked In again.</p>
      </div>
    </div>
  </section>

  <!-- ══════ WHAT IT ADDS UP TO — oat ══════ -->
  <section class="lr-sec lr-ground-oat">
    <div class="lr-wrap lr-cols">
      <div class="lr-c8 lr-rise">
        <p>Here is the thing about that strange resume: twenty-five years of research, contracts, investigations, and technical writing teaches a person to read the paperwork nobody else reads. Genealogy teaches a person that every piece of land has a paper trail and a story. And growing up out here teaches a person which roads flood in the spring storms, which little downtowns are speed traps, and which gas station parking lots have the best boiled peanuts.</p>
        <p class="lr-note"><b>Where I work</b>Metro Atlanta and northwest Georgia, including Cobb, Paulding, Haralson, Carroll and Polk counties.</p>
      </div>
    </div>
  </section>

  <!-- ══════ THE HUMAN STUFF — linen ══════ -->
  <section class="lr-sec lr-ground-linen">
    <div class="lr-wrap">
      <div class="lr-cols lr-rise">
        <div class="lr-c7">
          <h2 class="lr-disp">A few things you may or may not want to know&hellip;</h2>
        </div>
      </div>
      <div class="lr-cards lr-rise">
        <div class="lr-card">
          <span class="lr-card-meta">Current obsessions</span>
          <p>Genealogy, old cemeteries, Southern history, and finding out who USED to own your house.</p>
        </div>
        <div class="lr-card">
          <span class="lr-card-meta">The goal</span>
          <p>I want people to feel like they have known me forever. And I want them to laugh.</p>
        </div>
      </div>
    </div>
  </section>

  <?php get_template_part( 'template-parts/close', null, [
      'heading' => 'Let\'s talk.',
      'body'    => 'If that sounds like your kind of agent, come sit a spell.',
  ] ); ?>

</main>

<?php get_footer(); ?>

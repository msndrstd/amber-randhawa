<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <link rel="preconnect" href="https://use.typekit.net" crossorigin>
    <link rel="preconnect" href="https://p.typekit.net" crossorigin>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<script>
/* Reveal-on-scroll is opt-in. Without this flag every section renders
   visible, so a blocked or slow script can never blank the page. */
document.documentElement.className += ' lr-js';
setTimeout(function () {
  var n = document.querySelectorAll('.lr-rise:not(.is-in)');
  for (var i = 0; i < n.length; i++) { n[i].classList.add('is-in'); }
}, 2500);
</script>


<script>
/* Apply saved accessibility settings before first paint to avoid a flash
   of unstyled content. Full toggle logic lives in assets/js/a11y.js. */
(function () {
  try {
    var s = JSON.parse(window.localStorage.getItem('ar-a11y-settings') || '{}');
    var root = document.documentElement;
    var textSteps = ['a11y-text-1', 'a11y-text-2', 'a11y-text-3'];
    if (s.textStep > 0) root.classList.add(textSteps[s.textStep - 1]);
    ['readable', 'dyslexia', 'underline', 'contrast', 'motion', 'cursor'].forEach(function (key) {
      var map = {
        readable: 'a11y-readable',
        dyslexia: 'a11y-dyslexia',
        underline: 'a11y-underline-links',
        contrast: 'a11y-high-contrast',
        motion: 'a11y-reduce-motion',
        cursor: 'a11y-big-cursor'
      };
      if (s[key]) root.classList.add(map[key]);
    });
  } catch (e) {}
})();
</script>

<a class="a11y-skip-link" href="#main">Skip to main content</a>

<nav class="site-nav" role="navigation" aria-label="Primary navigation">
    <div class="container">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-logo" aria-label="<?php bloginfo( 'name' ); ?> — Home">
            <?php
            // Nav logo: dark version only — never pick cream/light/white
            $logo_src    = false;
            $img_dir     = get_template_directory() . '/assets/images/';
            $img_uri     = get_template_directory_uri() . '/assets/images/';
            $logo_files  = glob( $img_dir . '*.{svg,png,jpg,PNG,SVG,JPG}', GLOB_BRACE );
            if ( $logo_files ) {
                // 1st: explicit dark/green/forest keyword
                foreach ( $logo_files as $f ) {
                    $n = strtolower( basename( $f ) );
                    if ( strpos( $n, 'logo' ) !== false
                        && ( strpos( $n, 'dark' ) !== false || strpos( $n, 'green' ) !== false || strpos( $n, 'forest' ) !== false ) ) {
                        $logo_src = $img_uri . str_replace( ' ', '%20', basename( $f ) );
                        break;
                    }
                }
                // 2nd: any logo that is NOT a light variant
                if ( ! $logo_src ) {
                    foreach ( $logo_files as $f ) {
                        $n = strtolower( basename( $f ) );
                        if ( strpos( $n, 'logo' ) !== false
                            && strpos( $n, 'cream' ) === false
                            && strpos( $n, 'light' ) === false
                            && strpos( $n, 'white' ) === false ) {
                            $logo_src = $img_uri . str_replace( ' ', '%20', basename( $f ) );
                            break;
                        }
                    }
                }
                // Last resort: first logo found
                if ( ! $logo_src ) {
                    foreach ( $logo_files as $f ) {
                        if ( strpos( strtolower( basename( $f ) ), 'logo' ) !== false ) {
                            $logo_src = $img_uri . str_replace( ' ', '%20', basename( $f ) );
                            break;
                        }
                    }
                }
            }
            ?>
            <?php if ( $logo_src ) : ?>
                <img src="<?php echo esc_url( $logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>" height="72">
            <?php else : ?>
                <span class="nav-logo-text"><?php bloginfo( 'name' ); ?></span>
            <?php endif; ?>
        </a>

        <div class="nav-links" id="primary-nav">

            <?php /* Mobile panel only: explicit close. The hamburger is
                     still behind the panel and hard to find once the
                     overlay is up. */ ?>
            <button type="button" class="nav-panel-close" aria-label="Close menu">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M5 5l14 14M19 5L5 19"/></svg>
            </button>

            <?php /* Mobile panel only: the mark at the top of the overlay.
                     Cream variant, because the panel ground is forest. */ ?>
            <a class="nav-panel-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" tabindex="-1" aria-hidden="true">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/AR%20-%20Logo%20-%20Cream.svg' ); ?>" alt="" width="180" height="72">
            </a>

            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
            <a href="<?php echo esc_url( home_url( '/about' ) ); ?>">About</a>

            <div class="nav-item nav-has-dropdown">
                <button class="nav-dropdown-trigger" aria-expanded="false" aria-haspopup="true">
                    Communities <span class="nav-dropdown-arrow" aria-hidden="true">&#9660;</span>
                </button>
                <div class="nav-dropdown" role="menu">
                    <div class="nav-dropdown-inner">
                        <div class="nav-dropdown-col">
                            <span class="nav-dropdown-label">Cities</span>
                            <a href="<?php echo esc_url( home_url( '/dallas-ga-real-estate' ) ); ?>" role="menuitem">Dallas</a>
                            <a href="<?php echo esc_url( home_url( '/hiram-ga-real-estate' ) ); ?>" role="menuitem">Hiram</a>
                            <a href="<?php echo esc_url( home_url( '/bremen-ga-real-estate' ) ); ?>" role="menuitem">Bremen</a>
                            <a href="<?php echo esc_url( home_url( '/rockmart-ga-real-estate' ) ); ?>" role="menuitem">Rockmart</a>
                            <a href="<?php echo esc_url( home_url( '/villa-rica-ga-real-estate' ) ); ?>" role="menuitem">Villa Rica</a>
                        </div>
                        <div class="nav-dropdown-col">
                            <span class="nav-dropdown-label">Counties</span>
                            <a href="<?php echo esc_url( home_url( '/paulding-county-ga-real-estate' ) ); ?>" role="menuitem">Paulding County</a>
                            <a href="<?php echo esc_url( home_url( '/haralson-county-ga-real-estate' ) ); ?>" role="menuitem">Haralson County</a>
                            <a href="<?php echo esc_url( home_url( '/carroll-county-ga-real-estate' ) ); ?>" role="menuitem">Carroll County</a>
                            <a href="<?php echo esc_url( home_url( '/polk-county-ga-real-estate' ) ); ?>" role="menuitem">Polk County</a>
                            <div class="nav-dropdown-divider"></div>
                            <a href="<?php echo esc_url( home_url( '/communities' ) ); ?>" role="menuitem" class="nav-dropdown-all">View all communities &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php
            /* NOTE: this nav is hardcoded, it does not call wp_nav_menu().
               Editing Appearance > Menus in WP admin has no effect on the
               site. Links are added here. The Guides item only renders once
               a published page with the slug `guides` exists, so it cannot
               point at a 404. */
            $ar_guides_page = get_page_by_path( 'guides' );
            ?>
            <?php if ( $ar_guides_page && 'publish' === $ar_guides_page->post_status ) :
              /* Same dropdown pattern as Communities. Each child link only
                 renders if that page actually exists, so the submenu can
                 never contain a 404. */
              $ar_guide_links = [];
              foreach ( [ 'commuting-to-atlanta' => 'The drive to Atlanta',
                          'property-taxes'       => 'Property taxes by county' ] as $sl => $lbl ) {
                  $pg = get_page_by_path( $sl );
                  if ( $pg && 'publish' === $pg->post_status ) {
                      $ar_guide_links[] = [ 'url' => get_permalink( $pg ), 'label' => $lbl ];
                  }
              }
            ?>
              <?php if ( $ar_guide_links ) : ?>
              <div class="nav-item nav-has-dropdown">
                  <button class="nav-dropdown-trigger" aria-expanded="false" aria-haspopup="true">
                      Guides <span class="nav-dropdown-arrow" aria-hidden="true">&#9660;</span>
                  </button>
                  <div class="nav-dropdown nav-dropdown--single" role="menu">
                      <div class="nav-dropdown-inner">
                          <div class="nav-dropdown-col">
                              <?php foreach ( $ar_guide_links as $gl ) : ?>
                              <a href="<?php echo esc_url( $gl['url'] ); ?>" role="menuitem"><?php echo esc_html( $gl['label'] ); ?></a>
                              <?php endforeach; ?>
                              <div class="nav-dropdown-divider"></div>
                              <a href="<?php echo esc_url( get_permalink( $ar_guides_page ) ); ?>" role="menuitem" class="nav-dropdown-all">All guides &rarr;</a>
                          </div>
                      </div>
                  </div>
              </div>
              <?php else : ?>
              <a href="<?php echo esc_url( get_permalink( $ar_guides_page ) ); ?>">Guides</a>
              <?php endif; ?>
            <?php endif; ?>
            <a href="<?php echo esc_url( home_url( '/blog' ) ); ?>">Writing</a>
            <a href="<?php echo esc_url( home_url( '/faq' ) ); ?>">FAQ</a>
            <a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="nav-cta">Let's talk</a>

            <?php /* Mobile panel only. URLs match the sameAs list in the
                     JSON-LD in functions.php -- keep the two in step. */ ?>
            <div class="nav-panel-social">
                <a href="https://www.facebook.com/amberrandhawarealtor" target="_blank" rel="noopener noreferrer" aria-label="Amber Randhawa on Facebook">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="currentColor" aria-hidden="true" focusable="false"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06c0 5.02 3.66 9.18 8.44 9.94v-7.03H7.9v-2.91h2.54V9.85c0-2.52 1.49-3.91 3.77-3.91 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.78-1.63 1.57v1.89h2.78l-.44 2.91h-2.34V22c4.78-.76 8.44-4.92 8.44-9.94z"/></svg>
                </a>
                <a href="https://www.instagram.com/amberrandhawarealtor" target="_blank" rel="noopener noreferrer" aria-label="Amber Randhawa on Instagram">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.6" aria-hidden="true" focusable="false"><rect x="3" y="3" width="18" height="18" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r="1.1" fill="currentColor" stroke="none"/></svg>
                </a>
            </div>
        </div>

        <button class="nav-toggle" aria-expanded="false" aria-controls="primary-nav" aria-label="Toggle menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </div>
</nav>

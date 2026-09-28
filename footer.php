<footer class="site-footer" role="contentinfo">
    <div class="container">
        <div class="footer-inner">

            <div class="footer-logo">
                <?php
                // Footer logo: the main footer band is sage, which takes
                // near-black only. Scan for the dark variant, never cream.
                $footer_logo_src = false;
                $img_dir_ft  = get_template_directory() . '/assets/images/';
                $img_uri_ft  = get_template_directory_uri() . '/assets/images/';
                $logo_files_ft = glob( $img_dir_ft . '*.{svg,png,jpg,PNG,SVG,JPG}', GLOB_BRACE );
                if ( $logo_files_ft ) {
                    foreach ( $logo_files_ft as $f ) {
                        $n = strtolower( basename( $f ) );
                        if ( strpos( $n, 'logo' ) !== false && ( strpos( $n, 'near black' ) !== false || strpos( $n, 'near-black' ) !== false || strpos( $n, 'black' ) !== false || strpos( $n, 'dark' ) !== false ) ) {
                            $footer_logo_src = $img_uri_ft . str_replace( ' ', '%20', basename( $f ) );
                            break;
                        }
                    }
                    if ( ! $footer_logo_src ) {
                        foreach ( $logo_files_ft as $f ) {
                            if ( strpos( strtolower( basename( $f ) ), 'logo' ) !== false ) {
                                $footer_logo_src = $img_uri_ft . str_replace( ' ', '%20', basename( $f ) );
                                break;
                            }
                        }
                    }
                }
                ?>
                <?php if ( $footer_logo_src ) : ?>
                    <img src="<?php echo esc_url( $footer_logo_src ); ?>" alt="<?php bloginfo( 'name' ); ?>" height="60">
                <?php else : ?>
                    <span class="footer-logo-text"><?php bloginfo( 'name' ); ?></span>
                <?php endif; ?>
            </div>

            <nav aria-label="Footer navigation">
                <ul class="footer-links">
                    <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About</a></li>
                    <?php $ar_guides_page = get_page_by_path( 'guides' ); ?>
                    <?php if ( $ar_guides_page && 'publish' === $ar_guides_page->post_status ) : ?>
                    <li><a href="<?php echo esc_url( get_permalink( $ar_guides_page ) ); ?>">Guides</a></li>
                    <?php endif; ?>
                    <li><a href="<?php echo esc_url( home_url( '/writing/' ) ); ?>">Writing</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>">Blog</a></li>
                    <li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Contact</a></li>
                    <li><a href="<?php echo esc_url( 'https://amberrandhawa.kw.com' ); ?>" target="_blank" rel="noopener">Listings</a></li>
                </ul>
            </nav>

            <div class="footer-legal">
                <p>&copy; <?php echo date( 'Y' ); ?> Amber Randhawa &middot; Keller Williams Realty Cityside</p>
                <p>Northwest Georgia</p>
                <p class="footer-credit">Site created by <a href="https://msndrstdcreative.com" target="_blank" rel="noopener noreferrer">Msndrstd Creative</a></p>
            </div>

        </div>
    </div>

    <!-- KW Compliance Strip -->
    <div class="footer-compliance">
        <div class="container">
            <div class="compliance-inner">

                <?php
                // KW logo — scan for file with 'kw' or 'keller' in name
                $kw_logo_src   = false;
                $img_dir_kw    = get_template_directory() . '/assets/images/';
                $img_uri_kw    = get_template_directory_uri() . '/assets/images/';
                $all_imgs_kw   = glob( $img_dir_kw . '*.{svg,png,jpg,PNG,SVG,JPG}', GLOB_BRACE );
                if ( $all_imgs_kw ) {
                    foreach ( $all_imgs_kw as $f ) {
                        $n = strtolower( basename( $f ) );
                        if ( strpos( $n, 'kw' ) !== false || strpos( $n, 'keller' ) !== false ) {
                            $kw_logo_src = $img_uri_kw . str_replace( ' ', '%20', basename( $f ) );
                            break;
                        }
                    }
                }
                ?>

                <div class="compliance-kw">
                    <?php if ( $kw_logo_src ) : ?>
                        <img src="<?php echo esc_url( $kw_logo_src ); ?>" alt="Keller Williams Realty" class="kw-logo">
                    <?php else : ?>
                        <span class="kw-logo-text">Keller Williams Realty</span>
                    <?php endif; ?>
                </div>

                <div class="compliance-text">
                    <p>Each Keller Williams Realty office is independently owned and operated.</p>
                    <p>
                        Amber Randhawa &middot; Cell <a href="tel:<?php echo esc_attr( AR_PHONE_E164 ); ?>"><?php echo esc_html( AR_PHONE ); ?></a> &middot; <?php echo esc_html( AR_OFFICE ); ?> &middot; Office <a href="tel:<?php echo esc_attr( AR_OFFICE_PHONE_E164 ); ?>"><?php echo esc_html( AR_OFFICE_PHONE ); ?></a> &middot; License #453822
                        &nbsp;&nbsp;&bull;&nbsp;&nbsp;
                        Information deemed reliable but not guaranteed.
                    </p>
                </div>

                <div class="compliance-eho">
                    <!-- Equal Housing Opportunity — standard HUD symbol -->
                    <svg class="eho-logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 52" aria-label="Equal Housing Opportunity" role="img" width="32" height="36">
                        <title>Equal Housing Opportunity</title>
                        <!-- House outline -->
                        <path fill="none" stroke="currentColor" stroke-width="2.5" stroke-linejoin="round" d="M24 3L2 20h5v27h34V20h5z"/>
                        <!-- Equal sign -->
                        <rect fill="currentColor" x="12" y="28" width="24" height="3" rx="0.5"/>
                        <rect fill="currentColor" x="12" y="34" width="24" height="3" rx="0.5"/>
                    </svg>
                    <span>Equal Housing<br>Opportunity</span>
                </div>

            </div>
        </div>
    </div>

</footer>

<!-- ============================================================
     ACCESSIBILITY TOOLBAR — custom-built, no third-party overlay
     ============================================================ -->
<button type="button" id="a11y-toggle" class="a11y-toggle" aria-haspopup="dialog" aria-expanded="false" aria-controls="a11y-panel" aria-label="Accessibility options">
    <svg viewBox="0 0 24 24" fill="none" aria-hidden="true" focusable="false">
        <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="1.4"/>
        <circle cx="12" cy="7" r="1.6" fill="currentColor"/>
        <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M6.5 9.8c2.8 1 8.2 1 11 0M12 10.2v4.3M12 14.5l-2.6 4.4M12 14.5l2.6 4.4"/>
    </svg>
</button>

<div id="a11y-panel" class="a11y-panel" role="dialog" aria-modal="false" aria-label="Accessibility options">
    <div class="a11y-panel-header">
        <h2>Accessibility</h2>
        <button type="button" id="a11y-panel-close" class="a11y-panel-close" aria-label="Close accessibility panel">
            <svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false">
                <path stroke="currentColor" stroke-width="2" stroke-linecap="round" d="M5 5l14 14M19 5L5 19"/>
            </svg>
        </button>
    </div>

    <div class="a11y-group">
        <span class="a11y-group-label">Text size</span>
        <div class="a11y-btn-row">
            <button type="button" id="a11y-text-dec" class="a11y-step-btn" aria-label="Decrease text size">A&minus;</button>
            <button type="button" id="a11y-text-inc" class="a11y-step-btn" aria-label="Increase text size">A+</button>
        </div>
    </div>

    <div class="a11y-group">
        <span class="a11y-group-label">Reading</span>
        <button type="button" class="a11y-option" data-a11y-toggle="readable" aria-pressed="false">
            <span>Increase line spacing</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
        <button type="button" class="a11y-option" data-a11y-toggle="dyslexia" aria-pressed="false">
            <span>Dyslexia-friendly font</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
        <button type="button" class="a11y-option" data-a11y-toggle="underline" aria-pressed="false">
            <span>Underline links</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
    </div>

    <div class="a11y-group">
        <span class="a11y-group-label">Display</span>
        <button type="button" class="a11y-option" data-a11y-toggle="contrast" aria-pressed="false">
            <span>High contrast</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
        <button type="button" class="a11y-option" data-a11y-toggle="cursor" aria-pressed="false">
            <span>Large cursor</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
        <button type="button" class="a11y-option" data-a11y-toggle="motion" aria-pressed="false">
            <span>Reduce motion</span>
            <svg class="a11y-option-check" viewBox="0 0 24 24" aria-hidden="true"><path stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="none" d="M4 12l5 5L20 6"/></svg>
        </button>
    </div>

    <button type="button" id="a11y-reset" class="a11y-reset">Reset all</button>
</div>

<?php wp_footer(); ?>
</body>
</html>

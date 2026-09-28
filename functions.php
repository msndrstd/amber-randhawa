<?php
/**
 * Amber Randhawa — Theme Functions
 * Msndrstd Creative
 */

if ( ! defined( 'ABSPATH' ) ) exit;

require_once get_template_directory() . '/template-parts/nav-walker.php';

/* ============================================================
   THEME SETUP
   ============================================================ */
function ar_theme_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ] );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'wp-block-styles' );
    add_image_size( 'ar-hero',    1920, 1080, true );
    add_image_size( 'ar-card',    900,  600,  true );
    add_image_size( 'ar-portrait', 700, 900,  true );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'amber-randhawa' ),
        'footer'  => __( 'Footer Navigation', 'amber-randhawa' ),
    ] );
}
add_action( 'after_setup_theme', 'ar_theme_setup' );

/* ============================================================
   ENQUEUE SCRIPTS & STYLES
   ============================================================ */
function ar_enqueue_assets() {
    $v = wp_get_theme()->get( 'Version' );

    // Main stylesheet — version by file modified time so edits always bust cache
    $main_css_path = get_template_directory() . '/assets/css/main.css';
    wp_enqueue_style(
        'ar-main',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        file_exists( $main_css_path ) ? filemtime( $main_css_path ) : $v
    );

    // Adobe Fonts — Brandon Grotesque (kit: xgv6hox)
    wp_enqueue_style(
        'ar-adobe-fonts',
        'https://use.typekit.net/xgv6hox.css',
        [],
        null
    );

    // Lenis smooth scroll
    wp_enqueue_script(
        'lenis',
        'https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/dist/lenis.min.js',
        [],
        '1.0.42',
        true
    );

    // GSAP core
    wp_enqueue_script(
        'gsap',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js',
        [],
        '3.12.5',
        true
    );

    // GSAP ScrollTrigger
    wp_enqueue_script(
        'gsap-scrolltrigger',
        'https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js',
        [ 'gsap' ],
        '3.12.5',
        true
    );

    // Theme JS — version by file modified time so edits always bust cache
    $main_js_path = get_template_directory() . '/assets/js/main.js';
    wp_enqueue_script(
        'ar-main',
        get_template_directory_uri() . '/assets/js/main.js',
        [ 'gsap', 'gsap-scrolltrigger', 'lenis' ],
        file_exists( $main_js_path ) ? filemtime( $main_js_path ) : $v,
        true
    );

    // Pass WP data to JS
    wp_localize_script( 'ar-main', 'arData', [
        'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        'nonce'   => wp_create_nonce( 'ar_nonce' ),
        'homeUrl' => home_url(),
    ]);

    // Redesign — homepage (linen ground, forest as accent)
    $redesign_css_path = get_template_directory() . '/assets/css/redesign.css';
    if ( file_exists( $redesign_css_path ) ) {
        wp_enqueue_style(
            'ar-redesign',
            get_template_directory_uri() . '/assets/css/redesign.css',
            [ 'ar-main' ],
            filemtime( $redesign_css_path )
        );
    }

    // Land Records design system — SITE-WIDE.
    // Tokens, grid, the five type steps, buttons, prose, tables,
    // cards, the closing ask. Depends on ar-main for the palette
    // custom properties, so it must load after it.
    $system_css_path = get_template_directory() . '/assets/css/system.css';
    if ( file_exists( $system_css_path ) ) {
        wp_enqueue_style(
            'ar-system',
            get_template_directory_uri() . '/assets/css/system.css',
            [ 'ar-main' ],
            filemtime( $system_css_path )
        );
    }

    // Reveal observer — site-wide, since every template uses .lr-rise now.
    $lrjs = get_template_directory() . '/assets/js/landrecords.js';
    if ( file_exists( $lrjs ) ) {
        wp_enqueue_script(
            'ar-landrecords',
            get_template_directory_uri() . '/assets/js/landrecords.js',
            [],
            filemtime( $lrjs ),
            true
        );
    }

    // Land Records — front page only (hero, the eight homepage sections, homepage nav)
    if ( is_front_page() ) {
        $lr_css_path = get_template_directory() . '/assets/css/landrecords.css';
        if ( file_exists( $lr_css_path ) ) {
            wp_enqueue_style(
                'ar-landrecords',
                get_template_directory_uri() . '/assets/css/landrecords.css',
                [ 'ar-main', 'ar-system' ],
                filemtime( $lr_css_path )
            );
        }
    }

    // Community pages — stats tables, fact blocks, FAQ
    $community_css_path = get_template_directory() . '/assets/css/community.css';
    if ( file_exists( $community_css_path ) ) {
        wp_enqueue_style(
            'ar-community',
            get_template_directory_uri() . '/assets/css/community.css',
            [ 'ar-main' ],
            filemtime( $community_css_path )
        );
    }

    // Accessibility toolbar — custom-built, no third-party service
    $a11y_css_path = get_template_directory() . '/assets/css/a11y.css';
    wp_enqueue_style(
        'ar-a11y',
        get_template_directory_uri() . '/assets/css/a11y.css',
        [ 'ar-main' ],
        file_exists( $a11y_css_path ) ? filemtime( $a11y_css_path ) : $v
    );

    $a11y_js_path = get_template_directory() . '/assets/js/a11y.js';
    wp_enqueue_script(
        'ar-a11y',
        get_template_directory_uri() . '/assets/js/a11y.js',
        [],
        file_exists( $a11y_js_path ) ? filemtime( $a11y_js_path ) : $v,
        true
    );
}
add_action( 'wp_enqueue_scripts', 'ar_enqueue_assets' );

/* ============================================================
   GUIDE ROUTING BY SLUG

   The guides are page templates, which normally means someone has
   to open each page in WP admin and pick the template from a
   dropdown. That dropdown is only populated once the theme files
   are actually on the server, so creating the pages before a push
   silently produces three blank pages and no way to tell why.

   This removes the step. Create a page with one of the slugs below
   and it gets the right template automatically, whether or not
   anything is selected in the dropdown. An explicit template
   selection still wins, so nothing here overrides a deliberate
   choice.
   ============================================================ */
function ar_guide_template_map() {
    return [
        'guides'                            => 'page-guides.php',
        'commuting-to-atlanta'              => 'page-guide-commute.php',
        'commute'                           => 'page-guide-commute.php',
        'commuting-to-atlanta-from-northwest-georgia' => 'page-guide-commute.php',
        'property-taxes'                    => 'page-guide-taxes.php',
        'property-taxes-by-county'          => 'page-guide-taxes.php',
        'taxes'                             => 'page-guide-taxes.php',
    ];
}

function ar_route_guide_templates( $template ) {
    if ( ! is_page() ) {
        return $template;
    }

    $post = get_queried_object();
    if ( ! $post || empty( $post->post_name ) ) {
        return $template;
    }

    // A deliberate choice in the admin always wins.
    $chosen = get_page_template_slug( $post );
    if ( $chosen && 'default' !== $chosen ) {
        return $template;
    }

    $map = ar_guide_template_map();
    if ( ! isset( $map[ $post->post_name ] ) ) {
        return $template;
    }

    $file = get_template_directory() . '/' . $map[ $post->post_name ];
    return file_exists( $file ) ? $file : $template;
}
add_filter( 'template_include', 'ar_route_guide_templates', 99 );


/* ============================================================
   ONE H1 PER PAGE

   Every community page's content field opens with its own <h1>
   repeating the page title, which the template already renders in
   the page header. That shipped two H1s on nine pages. Strip the
   leading one when it duplicates the title, and leave any other
   heading alone.
   ============================================================ */
function ar_strip_duplicate_h1( $content ) {
    if ( ! is_singular() || ! in_the_loop() || ! is_main_query() ) {
        return $content;
    }
    $title = trim( wp_strip_all_tags( get_the_title() ) );
    if ( '' === $title ) { return $content; }

    return preg_replace_callback(
        '#^\s*<h1\b[^>]*>(.*?)</h1>#is',
        function ( $m ) use ( $title ) {
            $found = trim( wp_strip_all_tags( $m[1] ) );
            return ( strcasecmp( $found, $title ) === 0 ) ? '' : $m[0];
        },
        $content,
        1
    );
}
add_filter( 'the_content', 'ar_strip_duplicate_h1', 8 );


/* ============================================================
   BREADCRUMB TRAIL

   The community pages are flat top-level slugs, not children of
   /communities, so page ancestors give us nothing. The trail is
   built from the slug instead. Cities map to the county page they
   belong to, which is how an engine learns the hub-and-spoke
   relationship the flat URLs do not express.
   ============================================================ */
function ar_crumb_trail( $post = null ) {
    $post = get_post( $post );
    if ( ! $post ) { return []; }

    $home  = [ 'label' => 'Home', 'url' => home_url( '/' ) ];
    $slug  = $post->post_name;
    $title = get_the_title( $post );

    $counties = [
        'paulding' => 'Paulding County',
        'haralson' => 'Haralson County',
        'polk'     => 'Polk County',
        'carroll'  => 'Carroll County',
    ];
    $city_county = [
        'dallas'     => 'paulding',
        'hiram'      => 'paulding',
        'bremen'     => 'haralson',
        'rockmart'   => 'polk',
        'villa-rica' => 'carroll',
    ];

    $communities = [ 'label' => 'Communities', 'url' => home_url( '/communities/' ) ];

    // A county page
    foreach ( $counties as $key => $label ) {
        if ( $slug === $key . '-county-ga-real-estate' ) {
            return [ $home, $communities, [ 'label' => $title ] ];
        }
    }

    // A city page
    foreach ( $city_county as $city => $county ) {
        if ( $slug === $city . '-ga-real-estate' ) {
            return [
                $home,
                $communities,
                [ 'label' => $counties[ $county ], 'url' => home_url( '/' . $county . '-county-ga-real-estate' ) ],
                [ 'label' => $title ],
            ];
        }
    }

    // An extra guide page: Home > Guides > page
    if ( function_exists( 'ar_extra_guides' ) && isset( ar_extra_guides()[ $slug ] ) ) {
        return [ $home, [ 'label' => 'Guides', 'url' => home_url( '/guides/' ) ], [ 'label' => $title ] ];
    }

    // Anything else: honour real ancestors if there are any
    $trail = [ $home ];
    foreach ( array_reverse( (array) get_post_ancestors( $post ) ) as $anc ) {
        $trail[] = [ 'label' => get_the_title( $anc ), 'url' => get_permalink( $anc ) ];
    }
    $trail[] = [ 'label' => $title ];
    return $trail;
}


/* ============================================================
   CLEAN UP WP HEAD
   ============================================================ */
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );

/* ============================================================
   EXCERPT
   ============================================================ */
function ar_excerpt_length( $length ) {
    return 28;
}
add_filter( 'excerpt_length', 'ar_excerpt_length' );

function ar_excerpt_more( $more ) {
    return '';
}
add_filter( 'excerpt_more', 'ar_excerpt_more' );

/* ============================================================
   CUSTOM POST CATEGORIES (Blog pillars)
   ============================================================ */
// Categories are handled through default WP taxonomy.
// Recommended categories to create in WP admin:
// - Local History
// - Personal Memoir
// - Area Guides
// - Real Estate

/* ============================================================
   BODY CLASS
   ============================================================ */
function ar_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'is-singular';
        $ar_slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( $ar_slug ) {
            $classes[] = 'page-' . sanitize_html_class( $ar_slug );
        }
    }
    return $classes;
}
add_filter( 'body_class', 'ar_body_classes' );

/* ============================================================
   DISABLE BLOCK EDITOR FOR PAGES (use classic for full control)
   ============================================================ */
add_filter( 'use_block_editor_for_post_type', '__return_false' );

/* ============================================================
   SECURITY
   ============================================================ */
/**
 * Hide the WordPress core version from asset URLs, WITHOUT breaking cache busting.
 *
 * This used to strip `ver` from EVERY stylesheet and script URL. That defeated
 * the filemtime() versions this same file sets on the theme's own assets a
 * couple of hundred lines above, so once a browser or the Flywheel CDN cached
 * main.css there was no way to invalidate it. On 2026-08-31 the live site was
 * still serving a main.css from before the Porch Light palette lock: no
 * --color-oat and no --color-coral, so every oat section lost its background,
 * the section marks went invisible and the filled CTA rendered transparent.
 * The file on the server was correct the whole time. It was purely the cache.
 *
 * Now it strips the version only when it IS the WordPress core version, which
 * is the thing worth hiding. Theme assets keep their filemtime() version and
 * bust cache on every edit, as intended.
 */
function ar_remove_version_query( $src ) {
    if ( false === strpos( $src, 'ver=' ) ) {
        return $src;
    }

    global $wp_version;

    $query = (string) wp_parse_url( $src, PHP_URL_QUERY );
    $args  = array();
    wp_parse_str( $query, $args );

    if ( isset( $args['ver'] ) && $args['ver'] === $wp_version ) {
        $src = remove_query_arg( 'ver', $src );
    }

    return $src;
}
add_filter( 'style_loader_src',  'ar_remove_version_query', 9999 );
add_filter( 'script_loader_src', 'ar_remove_version_query', 9999 );

/* ============================================================
   GEO / SEO — SHARED HELPERS
   ============================================================ */

// Finds Amber's portrait photo in theme assets, same lookup order used on the About page.
function ar_get_portrait_url() {
    $img_dir = get_template_directory() . '/assets/images/';
    $img_uri = get_template_directory_uri() . '/assets/images/';
    foreach ( [
        'amber-portrait.jpg', 'amber-portrait.png', 'portrait.jpg', 'portrait.png',
        'amber-01.jpg', 'amber-01.png', 'Photo Apr 22 2026, 9 17 14 AM (1)-EDIT.jpg',
    ] as $f ) {
        if ( file_exists( $img_dir . $f ) ) {
            return $img_uri . str_replace( ' ', '%20', $f );
        }
    }
    return '';
}

/* ============================================================
   GEO / SEO — META DESCRIPTIONS
   (No SEO plugin installed; hand-written per key template.)
   ============================================================ */
function ar_get_meta_description() {
    $desc = '';

    /* Meta descriptions from "Amber Randhawa - Full Website Content (v2) DUTCH",
       as edited by Amber (pulled in 2026-09-28). Keyed by page slug so the
       copy lives in one place instead of in each page's excerpt field. */
    $by_slug = [
        'about'                          => "Meet Amber Randhawa: Realtor with Keller Williams Realty Cityside, writer, local historian and lifelong northwest Georgia local. License #453822.",
        'contact'                        => "Get in touch with Amber Randhawa about buying or selling in metro Atlanta and northwest Georgia. She answers her own phone.",
        'faq'                            => "Real answers on buying and selling in metro Atlanta and northwest Georgia from Realtor Amber Randhawa. Plus a few things people were too polite to ask.",
        'communities'                    => "Northwest Georgia and metro Atlanta real estate. Dallas, Hiram, Draketown, Bremen, Buchanan, Tallapoosa, Cedartown, Rockmart, Temple, Villa Rica and Carrollton.",
        'paulding-county-ga-real-estate' => "Amber Randhawa was born and raised in Paulding County and works it as a Keller Williams agent. Dallas, Hiram and all the surrounding backroads.",
        'haralson-county-ga-real-estate' => "Real estate in Haralson County, GA with Amber Randhawa. Bremen, Buchanan, Tallapoosa and the rural areas in between.",
        'carroll-county-ga-real-estate'  => "Carroll County GA real estate with Amber Randhawa. Carrollton, Villa Rica, Temple and the rural communities in between.",
        'polk-county-ga-real-estate'     => "Polk County GA real estate with Amber Randhawa. Cedartown, Rockmart, Aragon, rural land and Silver Comet Trail access.",
        'dallas-ga-real-estate'          => "Amber Randhawa was born and raised in Paulding County and works homes and land in Dallas, GA as a Keller Williams agent.",
        'hiram-ga-real-estate'           => "Looking at homes in Hiram, GA? Amber Randhawa is a Paulding County native who has watched this end of the county change in real time.",
        'bremen-ga-real-estate'          => "Bremen, GA real estate with a local. Amber Randhawa on Haralson County's largest city.",
        'rockmart-ga-real-estate'        => "Homes and rural land in Rockmart, GA. Amber Randhawa works Polk County.",
        'villa-rica-ga-real-estate'      => "Villa Rica, GA homes and real estate. Amber Randhawa on downtown, Mirror Lake, the rural edges, and the I-20 commute.",
        'guides'                         => "The questions people actually ask Amber Randhawa about living in northwest Georgia, answered with numbers and the method behind them.",
        'commuting-to-atlanta'           => "Measured drive times to downtown Atlanta, the airport, and Cobb County from six northwest Georgia towns, with the method published.",
        'property-taxes'                 => "What actually changed about Georgia property taxes, what a millage rate is, and how to figure out the real bill on a real house. Amber Randhawa explains.",
        'blog'                           => "Plain answers about buying, selling and living in Paulding County and northwest Georgia, from Realtor Amber Randhawa.",
        'writing'                        => "Local history, family stories, and the occasional electric fence. Writing from Amber Randhawa in northwest Georgia.",
    ];

    if ( is_front_page() ) {
        $desc = "Amber Randhawa is a Realtor and lifelong northwest Georgia local serving Paulding, Haralson, Carroll, Polk and Cobb counties. Real stories, real local knowledge.";
    } elseif ( is_home() && isset( $by_slug['blog'] ) ) {
        $desc = $by_slug['blog'];
    } elseif ( is_category( 'writing' ) ) {
        $desc = $by_slug['writing'];
    } elseif ( is_page() && function_exists( 'ar_extra_guide_meta' ) && isset( ar_extra_guide_meta()[ get_post_field( 'post_name', get_queried_object_id() ) ] ) ) {
        $desc = ar_extra_guide_meta()[ get_post_field( 'post_name', get_queried_object_id() ) ];
    } elseif ( is_page() && isset( $by_slug[ get_post_field( 'post_name', get_queried_object_id() ) ] ) ) {
        $desc = $by_slug[ get_post_field( 'post_name', get_queried_object_id() ) ];
    } elseif ( is_singular( 'post' ) && function_exists( 'ar_post_meta_map' ) && isset( ar_post_meta_map()[ get_post_field( 'post_name', get_queried_object_id() ) ] ) ) {
        $desc = ar_post_meta_map()[ get_post_field( 'post_name', get_queried_object_id() ) ];
    } elseif ( is_singular( 'post' ) ) {
        $excerpt = get_the_excerpt();
        $desc    = $excerpt ? wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '' ) : get_the_title();
    } elseif ( is_page() ) {
        $excerpt = get_the_excerpt();
        $desc    = $excerpt ? wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '' ) : '';
    }

    return $desc;
}
function ar_meta_description() {
    $desc = ar_get_meta_description();
    if ( $desc ) {
        echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'ar_meta_description', 1 );

/* ============================================================
   GEO / SEO — STRUCTURED DATA (JSON-LD)
   Person + RealEstateAgent site-wide; BlogPosting added per-post in single.php.
   Phone is Amber's direct line (confirmed by Gogi 2026-09-28). Address is the
   Keller Williams Realty Cityside office, which matches her KW profile.
   ============================================================ */
function ar_schema_json_ld() {
    $home      = home_url( '/' );
    $about_url = home_url( '/about/' );
    $portrait  = ar_get_portrait_url();
    $person_id = $home . '#amber-randhawa';

    $same_as = [
        'https://www.facebook.com/amberrandhawarealtor',
        'https://www.instagram.com/amberrandhawarealtor',
        'https://amberrandhawa.kw.com',
    ];

    $area_served = [];
    foreach ( [ 'Cobb County, GA', 'Paulding County, GA', 'Haralson County, GA', 'Carroll County, GA', 'Polk County, GA' ] as $place ) {
        $area_served[] = [ '@type' => 'AdministrativeArea', 'name' => $place ];
    }

    $graph = [
        [
            '@type'    => 'Person',
            '@id'      => $person_id,
            'name'     => 'Amber Randhawa',
            'jobTitle' => 'Real Estate Agent',
            'url'      => $about_url,
            'worksFor' => [
                '@type' => 'Organization',
                'name'  => 'Keller Williams Realty Cityside',
            ],
            'knowsAbout' => [ 'Northwest Georgia real estate', 'Paulding County local history', 'Genealogy' ],
            'sameAs'     => $same_as,
        ],
        [
            '@type'        => 'RealEstateAgent',
            '@id'          => $home . '#agent',
            'name'         => 'Amber Randhawa',
            'telephone'    => AR_PHONE_E164,
            'address'      => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => '3350 Atlanta Rd SE',
                'addressLocality' => 'Smyrna',
                'addressRegion'   => 'GA',
                'postalCode'      => '30080',
                'addressCountry'  => 'US',
            ],
            'url'          => $home,
            'image'        => $portrait,
            'description'  => 'Real estate agent serving metro Atlanta and northwest Georgia, including Cobb, Paulding, Haralson, Carroll, and Polk counties.',
            'areaServed'   => $area_served,
            'memberOf'     => [
                '@type'     => 'RealEstateAgent',
                'name'      => 'Keller Williams Realty Cityside',
                'telephone' => '+1-770-874-6200',
                'url'       => 'https://locations.kw.com/location/372',
            ],
            'employee'     => [ '@id' => $person_id ],
            'sameAs'       => $same_as,
            'hasCredential' => [
                '@type'             => 'EducationalOccupationalCredential',
                'credentialCategory' => 'license',
                'identifier'        => '453822',
                'recognizedBy'      => [
                    '@type' => 'Organization',
                    'name'  => 'Georgia Real Estate Commission',
                ],
            ],
        ],
    ];

    if ( $portrait ) {
        $graph[0]['image'] = $portrait;
    } else {
        unset( $graph[1]['image'] );
    }

    $schema = [
        '@context' => 'https://schema.org',
        '@graph'   => $graph,
    ];

    echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ar_schema_json_ld' );

/* ============================================================
   WRITING vs BLOG (2026-09-28)
   Amber's own writing lives in the "Writing" category and is served at
   /writing/. Everything else is the Blog at /blog/ (the posts page).
   The Blog excludes Writing, and Writing never appears in the Blog.
   ============================================================ */
function ar_writing_cat_id() {
    static $id = null;
    if ( null === $id ) {
        $t  = get_term_by( 'slug', 'writing', 'category' );
        $id = $t ? (int) $t->term_id : 0;
    }
    return $id;
}

add_action( 'init', function () {
    add_rewrite_rule( '^writing/?$', 'index.php?category_name=writing', 'top' );
    add_rewrite_rule( '^writing/page/([0-9]+)/?$', 'index.php?category_name=writing&paged=$matches[1]', 'top' );
    if ( get_option( 'ar_rewrite_ver' ) !== 'writing-1' ) {
        flush_rewrite_rules( false );
        update_option( 'ar_rewrite_ver', 'writing-1' );
    }
} );

add_filter( 'term_link', function ( $url, $term, $taxonomy ) {
    if ( 'category' === $taxonomy && 'writing' === $term->slug ) {
        return home_url( '/writing/' );
    }
    return $url;
}, 10, 3 );

add_action( 'pre_get_posts', function ( $q ) {
    if ( is_admin() || ! $q->is_main_query() ) { return; }
    if ( $q->is_home() && ar_writing_cat_id() ) {
        $q->set( 'category__not_in', [ ar_writing_cat_id() ] );
    }
} );

/** True when a post (default: current) is Amber's own writing. */
function ar_is_writing( $post = null ) {
    return ar_writing_cat_id() && has_category( ar_writing_cat_id(), $post );
}

/* ============================================================
   SEO / GEO PASS (2026-09-28)
   Titles, post meta descriptions, Open Graph, robots for thin
   archives, sitemap cleanup, one agent entity, related content,
   nearby places. Everything keyed by slug so it survives DB pushes.
   ============================================================ */

define( 'AR_PHONE', '770-634-3291' );
define( 'AR_PHONE_E164', '+1-770-634-3291' );
define( 'AR_OFFICE', 'Keller Williams Realty Cityside, 3350 Atlanta Rd SE, Smyrna, GA 30080' );
define( 'AR_OFFICE_PHONE', '770-874-6200' );
define( 'AR_OFFICE_PHONE_E164', '+1-770-874-6200' );

/** Search descriptions for the blog posts (written with each post). */
function ar_post_meta_map() {
    return [
        'living-in-dallas-ga' => 'Living in Dallas, GA (Paulding County, not Texas): the historic square, US 278 traffic, growth, trails, schools and real commute times, from a local.',
        'paulding-county-commute-to-atlanta' => 'Paulding County commute to Atlanta, measured: real drive times from Hiram and Dallas, GA, why no interstate matters, the Cobb County pull and Xpress 476.',
        'paulding-county-property-taxes' => 'Paulding County property taxes in plain English: the 40% assessment, millage, homestead, HB 581 (county in, schools out), SB 33 and the 2026 relief grant.',
        'buying-land-in-paulding-county-ga' => 'Buying land in Paulding County, GA? What to check first: septic soil reports, wells, zoning, surveys, easements, utilities, timber and conservation use.',
        'paulding-county-homestead-exemption' => 'The Paulding County homestead exemption: who qualifies, the April 1 deadline, how to file, what it saves, senior exemptions and the HB 581 floating cap.',
        'living-in-hiram-ga' => 'Living in Hiram GA: a Paulding County local on the downtown, shopping on 278, the Xpress bus and drive to Atlanta, and Silver Comet Trail access.',
        'silver-comet-trail-paulding-county' => 'Silver Comet Trail Paulding County guide: trailheads from Hiram to McPherson Church Road, the Pumpkinvine Creek Trestle, and the train behind the name.',
        'paulding-county-schools-school-zone-lookup' => 'Paulding County schools are assigned by address. How to look up any home\'s school zone, check state report cards, and how school choice works.',
        'moving-to-paulding-county-ga' => 'Moving to Paulding County GA? Your first-month checklist: water, power, trash, Georgia license and tags within 30 days, voting and homestead exemption.',
        'picketts-mill-battlefield-civil-war-paulding-county' => 'Pickett\'s Mill Battlefield and the Civil War in Paulding County: New Hope Church, Pickett\'s Mill and Dallas, Georgia in May 1864, and what to see today.',
        'unincorporated-paulding-county-vs-city-limits' => 'Unincorporated Paulding County vs Dallas or Hiram city limits: who handles zoning, police, water and taxes, and why your mailing address can fool you.',
        'septic-inspection-paulding-county-well-water' => 'Septic inspection in Paulding County, GA: how septic tanks work, where to find permit records, well water testing, and what to ask a seller before closing.',
        'new-construction-homes-paulding-county' => 'New construction homes in Paulding County: new build vs resale, builder contracts, pre-drywall inspections, warranties, HOAs and low appraisals, explained.',
        'paulding-county-hoa-homes-without-hoa' => 'Paulding County HOA or homes without an HOA? How to find recorded covenants, what Georgia\'s Property Owners\' Association Act means, and what to read first.',
        'selling-inherited-house-paulding-county' => 'Selling an inherited house in Paulding County: Georgia probate, executor authority, year\'s support, heirs property, and the house full of family stuff.',
        'draketown-ga-rural-land-western-paulding-county' => 'Buying in Draketown GA or rural land in western Paulding County? Wells, septic, internet, long driveways and county lines, from someone who grew up there.',
        'paulding-county-flood-zone-check-georgia' => 'Is that house in a Paulding County flood zone? How to check any Georgia address with FEMA and state maps, what zones mean and flood insurance basics.',
        'usda-loan-paulding-county' => 'Can you use a USDA loan in Paulding County? How the no-down-payment rural loan works, how to check an address on USDA\'s map and why eligibility changes.',
        'downtown-dallas-ga-history' => 'Downtown Dallas GA history, Paulding County seat (not Texas): the 1852 founding, its name, three courthouses, the 1864 battle and the square today.',
        'paulding-county-population-growth' => 'Paulding County population growth by the census numbers, 1990 to 2025, why it happened and what it means for buyers: traffic, schools and rural pockets.',
    ];
}

/** Title tags, keyed by page slug. Posts keep "Post title – Amber Randhawa". */
add_filter( 'pre_get_document_title', function ( $title ) {
    $map = [
        'about'                          => 'About Amber Randhawa, Realtor | Keller Williams Realty Cityside',
        'contact'                        => 'Contact Amber Randhawa | Northwest Georgia Realtor',
        'faq'                            => 'Real Estate FAQ, Northwest Georgia | Amber Randhawa',
        'communities'                    => 'Northwest Georgia Communities | Amber Randhawa, Realtor',
        'paulding-county-ga-real-estate' => 'Paulding County, GA Real Estate | Amber Randhawa',
        'haralson-county-ga-real-estate' => 'Haralson County, GA Real Estate | Amber Randhawa',
        'carroll-county-ga-real-estate'  => 'Carroll County, GA Real Estate | Amber Randhawa',
        'polk-county-ga-real-estate'     => 'Polk County, GA Real Estate | Amber Randhawa',
        'dallas-ga-real-estate'          => 'Dallas, GA Real Estate (Paulding County) | Amber Randhawa',
        'hiram-ga-real-estate'           => 'Hiram, GA Real Estate | Amber Randhawa',
        'bremen-ga-real-estate'          => 'Bremen, GA Real Estate | Amber Randhawa',
        'rockmart-ga-real-estate'        => 'Rockmart, GA Real Estate | Amber Randhawa',
        'villa-rica-ga-real-estate'      => 'Villa Rica, GA Real Estate | Amber Randhawa',
        'guides'                         => 'Northwest Georgia Buyer Guides | Amber Randhawa',
        'commuting-to-atlanta'           => 'Commuting to Atlanta from Northwest Georgia, Measured',
        'property-taxes'                 => 'Georgia Property Taxes in Plain English | Amber Randhawa',
    ];
    if ( is_front_page() ) { return 'Amber Randhawa, Realtor | Northwest Georgia & Metro Atlanta'; }
    if ( is_home() )       { return 'Blog: Paulding County & Northwest Georgia Real Estate | Amber Randhawa'; }
    if ( is_category( 'writing' ) ) { return 'Writing: Local History & Family Stories | Amber Randhawa'; }
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( isset( $map[ $slug ] ) ) { return $map[ $slug ]; }
    }
    return $title;
} );

/** noindex thin archives (tags, authors, dates, search) but keep following links. */
add_filter( 'wp_robots', function ( $robots ) {
    if ( is_tag() || is_author() || is_date() || is_search() ) {
        $robots['noindex'] = true;
        $robots['follow']  = true;
    }
    return $robots;
} );

/** Sitemap: no user list (exposes login names), no tag archives. */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
    return 'users' === $name ? false : $provider;
}, 10, 2 );
add_filter( 'wp_sitemaps_taxonomies', function ( $tax ) {
    unset( $tax['post_tag'] );
    return $tax;
} );

/** Best image for a URL preview: featured, then the page's header band, then her portrait. */
function ar_share_image() {
    if ( is_singular() && has_post_thumbnail() ) {
        return get_the_post_thumbnail_url( null, 'large' );
    }
    $dir = get_template_directory() . '/assets/images/';
    $uri = get_template_directory_uri() . '/assets/images/';
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        foreach ( [ 'band-' . $slug . '.jpg', 'place-' . $slug . '.jpg', 'guide-' . $slug . '.jpg' ] as $f ) {
            if ( file_exists( $dir . $f ) ) { return $uri . $f; }
        }
    }
    if ( is_front_page() && file_exists( $dir . 'hero-reel-fs-poster.jpg' ) ) {
        return $uri . 'hero-reel-fs-poster.jpg';
    }
    return function_exists( 'ar_get_portrait_url' ) ? ar_get_portrait_url() : '';
}

/** Canonical-style URL of the current view. */
function ar_current_url() {
    if ( is_front_page() ) { return home_url( '/' ); }
    if ( is_home() )       { return get_permalink( get_option( 'page_for_posts' ) ); }
    if ( is_singular() )   { return get_permalink(); }
    if ( is_category() || is_tag() ) { return get_term_link( get_queried_object() ); }
    return home_url( add_query_arg( [] ) );
}

/** Open Graph + Twitter card. */
add_action( 'wp_head', function () {
    $title = wp_get_document_title();
    $desc  = function_exists( 'ar_get_meta_description' ) ? ar_get_meta_description() : '';
    $img   = ar_share_image();
    $url   = ar_current_url();
    $type  = is_singular( 'post' ) ? 'article' : 'website';
    $tags  = [
        'og:site_name'   => 'Amber Randhawa',
        'og:locale'      => 'en_US',
        'og:type'        => $type,
        'og:title'       => $title,
        'og:description' => $desc,
        'og:url'         => is_wp_error( $url ) ? '' : $url,
        'og:image'       => $img,
    ];
    foreach ( $tags as $k => $v ) {
        if ( $v ) { echo '<meta property="' . esc_attr( $k ) . '" content="' . esc_attr( $v ) . '">' . "\n"; }
    }
    if ( 'article' === $type ) {
        echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '">' . "\n";
        echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . "\n";
    }
    echo '<meta name="twitter:card" content="' . ( $img ? 'summary_large_image' : 'summary' ) . '">' . "\n";
}, 2 );

/**
 * One agent entity. The county and city pages carry their own
 * RealEstateAgent JSON-LD in the post content from an older build;
 * the site-wide graph in ar_schema_json_ld() is now the single source.
 * The commute blog post's FAQ markup duplicated the commute guide's.
 */
add_filter( 'the_content', function ( $content ) {
    if ( is_page() && false !== strpos( $content, 'RealEstateAgent' ) ) {
        $content = preg_replace( '#<script type="application/ld\+json">(?:(?!</script>).)*"RealEstateAgent"(?:(?!</script>).)*</script>#s', '', $content );
    }
    if ( is_single( 'paulding-county-commute-to-atlanta' ) ) {
        $content = preg_replace( '#<script type="application/ld\+json">(?:(?!</script>).)*FAQPage(?:(?!</script>).)*</script>#s', '', $content );
    }
    return $content;
}, 20 );

/* ------------------------------------------------------------
   RELATED CONTENT
   Blog post => the hub page it supports, plus sibling posts.
   Hub pages => the posts that support them. Only published posts
   are ever linked, so scheduled posts appear as they go live.
   ------------------------------------------------------------ */
function ar_post_hubs() {
    $p = [ '/paulding-county-ga-real-estate/', 'Paulding County real estate' ];
    return [
        'living-in-dallas-ga'                                 => [ [ '/dallas-ga-real-estate/', 'Dallas, GA real estate' ], [ 'downtown-dallas-ga-history', 'paulding-county-commute-to-atlanta', 'unincorporated-paulding-county-vs-city-limits' ] ],
        'paulding-county-commute-to-atlanta'                  => [ [ '/commuting-to-atlanta/', 'The full commute guide, with every town measured' ], [ 'living-in-hiram-ga', 'living-in-dallas-ga', 'paulding-county-population-growth' ] ],
        'paulding-county-property-taxes'                      => [ [ '/property-taxes/', 'Georgia property taxes, in plain English' ], [ 'paulding-county-homestead-exemption', 'unincorporated-paulding-county-vs-city-limits', 'moving-to-paulding-county-ga' ] ],
        'buying-land-in-paulding-county-ga'                   => [ $p, [ 'draketown-ga-rural-land-western-paulding-county', 'septic-inspection-paulding-county-well-water', 'usda-loan-paulding-county' ] ],
        'paulding-county-homestead-exemption'                 => [ [ '/property-taxes/', 'Georgia property taxes, in plain English' ], [ 'paulding-county-property-taxes', 'moving-to-paulding-county-ga', 'unincorporated-paulding-county-vs-city-limits' ] ],
        'living-in-hiram-ga'                                  => [ [ '/hiram-ga-real-estate/', 'Hiram, GA real estate' ], [ 'paulding-county-commute-to-atlanta', 'silver-comet-trail-paulding-county', 'unincorporated-paulding-county-vs-city-limits' ] ],
        'silver-comet-trail-paulding-county'                  => [ $p, [ 'living-in-hiram-ga', 'downtown-dallas-ga-history', 'picketts-mill-battlefield-civil-war-paulding-county' ] ],
        'paulding-county-schools-school-zone-lookup'          => [ $p, [ 'moving-to-paulding-county-ga', 'paulding-county-population-growth', 'new-construction-homes-paulding-county' ] ],
        'moving-to-paulding-county-ga'                        => [ $p, [ 'paulding-county-homestead-exemption', 'paulding-county-schools-school-zone-lookup', 'paulding-county-commute-to-atlanta' ] ],
        'picketts-mill-battlefield-civil-war-paulding-county' => [ $p, [ 'downtown-dallas-ga-history', 'silver-comet-trail-paulding-county', 'living-in-dallas-ga' ] ],
        'unincorporated-paulding-county-vs-city-limits'       => [ $p, [ 'paulding-county-property-taxes', 'living-in-dallas-ga', 'living-in-hiram-ga' ] ],
        'septic-inspection-paulding-county-well-water'        => [ $p, [ 'buying-land-in-paulding-county-ga', 'draketown-ga-rural-land-western-paulding-county', 'paulding-county-flood-zone-check-georgia' ] ],
        'new-construction-homes-paulding-county'              => [ $p, [ 'paulding-county-hoa-homes-without-hoa', 'paulding-county-population-growth', 'paulding-county-flood-zone-check-georgia' ] ],
        'paulding-county-hoa-homes-without-hoa'               => [ $p, [ 'new-construction-homes-paulding-county', 'unincorporated-paulding-county-vs-city-limits', 'moving-to-paulding-county-ga' ] ],
        'selling-inherited-house-paulding-county'             => [ $p, [ 'paulding-county-property-taxes', 'paulding-county-homestead-exemption', 'septic-inspection-paulding-county-well-water' ] ],
        'draketown-ga-rural-land-western-paulding-county'     => [ $p, [ 'buying-land-in-paulding-county-ga', 'septic-inspection-paulding-county-well-water', 'usda-loan-paulding-county' ] ],
        'paulding-county-flood-zone-check-georgia'            => [ $p, [ 'septic-inspection-paulding-county-well-water', 'buying-land-in-paulding-county-ga', 'new-construction-homes-paulding-county' ] ],
        'usda-loan-paulding-county'                           => [ $p, [ 'buying-land-in-paulding-county-ga', 'draketown-ga-rural-land-western-paulding-county', 'new-construction-homes-paulding-county' ] ],
        'downtown-dallas-ga-history'                          => [ [ '/dallas-ga-real-estate/', 'Dallas, GA real estate' ], [ 'picketts-mill-battlefield-civil-war-paulding-county', 'living-in-dallas-ga', 'silver-comet-trail-paulding-county' ] ],
        'paulding-county-population-growth'                   => [ $p, [ 'new-construction-homes-paulding-county', 'paulding-county-schools-school-zone-lookup', 'paulding-county-commute-to-atlanta' ] ],
    ];
}

/** Posts that support each hub page, newest-first order not needed. */
function ar_page_posts() {
    return [
        'paulding-county-ga-real-estate' => [ 'moving-to-paulding-county-ga', 'buying-land-in-paulding-county-ga', 'paulding-county-property-taxes', 'paulding-county-schools-school-zone-lookup', 'unincorporated-paulding-county-vs-city-limits', 'paulding-county-population-growth', 'living-in-dallas-ga' ],
        'dallas-ga-real-estate'          => [ 'living-in-dallas-ga', 'downtown-dallas-ga-history', 'unincorporated-paulding-county-vs-city-limits', 'picketts-mill-battlefield-civil-war-paulding-county' ],
        'hiram-ga-real-estate'           => [ 'living-in-hiram-ga', 'paulding-county-commute-to-atlanta', 'silver-comet-trail-paulding-county' ],
        'polk-county-ga-real-estate'     => [ 'silver-comet-trail-paulding-county' ],
        'haralson-county-ga-real-estate' => [ 'draketown-ga-rural-land-western-paulding-county' ],
        'communities'                    => [ 'moving-to-paulding-county-ga', 'living-in-dallas-ga', 'living-in-hiram-ga', 'unincorporated-paulding-county-vs-city-limits' ],
        'faq'                            => [ 'moving-to-paulding-county-ga', 'paulding-county-homestead-exemption', 'buying-land-in-paulding-county-ga' ],
        'commuting-to-atlanta'           => [ 'paulding-county-commute-to-atlanta', 'living-in-hiram-ga', 'living-in-dallas-ga' ],
        'buying-land-northwest-georgia'  => [ 'buying-land-in-paulding-county-ga', 'draketown-ga-rural-land-western-paulding-county', 'septic-inspection-paulding-county-well-water', 'usda-loan-paulding-county', 'paulding-county-flood-zone-check-georgia' ],
        'living-near-silver-comet-trail' => [ 'silver-comet-trail-paulding-county', 'living-in-hiram-ga', 'downtown-dallas-ga-history' ],
        'moving-to-rockmart-ga'          => [ 'silver-comet-trail-paulding-county', 'buying-land-in-paulding-county-ga' ],
        'bremen-city-schools-vs-haralson-county-schools' => [ 'paulding-county-schools-school-zone-lookup' ],
        'villa-rica-property-taxes-carroll-county-vs-douglas-county' => [ 'paulding-county-property-taxes', 'paulding-county-homestead-exemption' ],
        'property-taxes'                 => [ 'paulding-county-property-taxes', 'paulding-county-homestead-exemption', 'unincorporated-paulding-county-vs-city-limits' ],
    ];
}

/** Published posts only, as [url, title]. */
function ar_published_links( $slugs, $limit = 5 ) {
    $out = [];
    foreach ( (array) $slugs as $s ) {
        $p = get_page_by_path( $s, OBJECT, 'post' );
        if ( $p && 'publish' === $p->post_status ) {
            $out[] = [ get_permalink( $p ), get_the_title( $p ) ];
        }
        if ( count( $out ) >= $limit ) { break; }
    }
    return $out;
}

/** Aside block of supporting posts for a hub page. Echoes nothing when there are none. */
function ar_render_page_posts( $slug, $heading = 'From the blog' ) {
    $map   = ar_page_posts();
    $links = isset( $map[ $slug ] ) ? ar_published_links( $map[ $slug ], 5 ) : [];
    if ( ! $links ) { return; }
    echo '<div class="lr-aside-block"><span class="lr-aside-h">' . esc_html( $heading ) . '</span><ul class="lr-aside-list">';
    foreach ( $links as $l ) {
        echo '<li><a href="' . esc_url( $l[0] ) . '">' . esc_html( $l[1] ) . '</a></li>';
    }
    echo '</ul></div>';
}

/** Nearby places: sibling cities in the same county plus the county page. */
function ar_nearby_places( $slug ) {
    $county_cities = [
        'paulding' => [ 'dallas', 'hiram' ],
        'haralson' => [ 'bremen' ],
        'polk'     => [ 'rockmart' ],
        'carroll'  => [ 'villa-rica' ],
    ];
    $want = [];
    foreach ( $county_cities as $county => $cities ) {
        $cslug = $county . '-county-ga-real-estate';
        $cslugs = array_map( function ( $c ) { return $c . '-ga-real-estate'; }, $cities );
        if ( $slug === $cslug ) {
            $want = array_merge( $cslugs, array_diff( array_map( function ( $k ) { return $k . '-county-ga-real-estate'; }, array_keys( $county_cities ) ), [ $cslug ] ) );
        } elseif ( in_array( $slug, $cslugs, true ) ) {
            $want = array_merge( array_diff( $cslugs, [ $slug ] ), [ $cslug ] );
        }
    }
    if ( ! $want ) {
        $want = array_merge( array_map( function ( $k ) { return $k . '-county-ga-real-estate'; }, array_keys( $county_cities ) ) );
    }
    $out = [];
    foreach ( $want as $w ) {
        $p = get_page_by_path( $w );
        if ( $p && 'publish' === $p->post_status ) {
            $out[] = [ get_permalink( $p ), str_replace( [ 'Real Estate in ', ', Georgia', ' Real Estate', ', Georgia Real Estate' ], '', get_the_title( $p ) ) ];
        }
    }
    return $out;
}

/* ============================================================
   EXTRA GUIDES (2026-09-28): content pages in the WP database,
   rendered by page.php, listed in the Guides hub and nav.
   ============================================================ */
function ar_extra_guides() {
    return [
        'bremen-city-schools-vs-haralson-county-schools' => [ 'Bremen vs Haralson schools', 'Two school districts, one city limits line, and a whole lot of houses with a Bremen mailing address sitting right next to it.' ],
        'villa-rica-property-taxes-carroll-county-vs-douglas-county' => [ 'Villa Rica taxes, by county', 'One city, two counties, two very different tax bills. Here\'s how to tell which side of the Villa Rica county line a house is on, and what it costs.' ],
        'moving-to-rockmart-ga' => [ 'Moving to Rockmart', 'A slate town, a creek through downtown, the Silver Comet out the back door, and an honest look at the drive to Atlanta.' ],
        'buying-land-northwest-georgia' => [ 'Buying land', 'Land doesn\'t care about county lines. The paperwork does. Here\'s who to call in Paulding, Haralson, Carroll and Polk before you buy.' ],
        'living-near-silver-comet-trail' => [ 'Living near the Silver Comet', 'Close to the Silver Comet can mean a walk, a drive, or a trail right past your fence. Here\'s how to tell which one you\'re buying.' ],
    ];
}
add_filter( 'pre_get_document_title', function ( $title ) {
    $map = [
        'bremen-city-schools-vs-haralson-county-schools' => 'Bremen City Schools vs Haralson County Schools',
        'villa-rica-property-taxes-carroll-county-vs-douglas-county' => 'Villa Rica Property Taxes: Carroll vs Douglas County',
        'moving-to-rockmart-ga' => 'Moving to Rockmart GA: Living Guide | Amber Randhawa',
        'buying-land-northwest-georgia' => 'Buying Land in Northwest Georgia | Amber Randhawa',
        'living-near-silver-comet-trail' => 'Homes Near the Silver Comet Trail | Amber Randhawa',
    ];
    if ( is_page() ) {
        $slug = get_post_field( 'post_name', get_queried_object_id() );
        if ( isset( $map[ $slug ] ) ) { return $map[ $slug ]; }
    }
    return $title;
}, 20 );
function ar_extra_guide_meta() {
    return [
        'bremen-city-schools-vs-haralson-county-schools' => 'Bremen City Schools vs Haralson County Schools: who\'s eligible, school lists, rankings, non-resident tuition, and how to confirm an address before you buy.',
        'villa-rica-property-taxes-carroll-county-vs-douglas-county' => 'Villa Rica property taxes in Carroll County vs Douglas County: how to tell which side a home is on, 2025 millage for each, and a worked example.',
        'moving-to-rockmart-ga' => 'Moving to Rockmart GA? Living in Rockmart, Georgia: slate history, the Silver Comet, Polk schools, utilities, the Atlanta drive and a first-month list.',
        'buying-land-northwest-georgia' => 'Buying land in northwest Georgia? Zoning, septic, wells, power, CUVA, easements and land loans, county by county for Paulding, Haralson, Carroll and Polk.',
        'living-near-silver-comet-trail' => 'Homes near the Silver Comet Trail in Georgia: trailheads by town and county, parking, what backing up to the trail is like, and how to check a parcel.',
    ];
}

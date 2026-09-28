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

    $communities = [ 'label' => 'Communities', 'url' => home_url( '/communities' ) ];

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
function ar_meta_description() {
    $desc = '';

    /* Meta descriptions from "Amber Randhawa - Full Website Content (v2) DUTCH",
       as edited by Amber (pulled in 2026-09-28). Keyed by page slug so the
       copy lives in one place instead of in each page's excerpt field. */
    $by_slug = [
        'about'                          => "Meet Amber Randhawa. Realtor with Keller Williams Realty Cityside, writer, local historian, and lifelong resident of rural northwest Georgia. License #453822.",
        'contact'                        => "Get in touch with Amber Randhawa about buying or selling in metro Atlanta and northwest Georgia. She answers her own phone.",
        'faq'                            => "Real questions, real answers on buying and selling in metro Atlanta and northwest Georgia, from Amber Randhawa at Keller Williams Realty Cityside. Plus a few things people were too polite to ask.",
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
        $desc = "Amber Randhawa is a real estate agent and lifelong Northwest Georgia local. She covers metro Atlanta and northwest Georgia, including Cobb, Paulding, Haralson, Carroll, and Polk counties. Real stories, real local knowledge, no template agent website.";
    } elseif ( is_home() && isset( $by_slug['blog'] ) ) {
        $desc = $by_slug['blog'];
    } elseif ( is_category( 'writing' ) ) {
        $desc = $by_slug['writing'];
    } elseif ( is_page() && isset( $by_slug[ get_post_field( 'post_name', get_queried_object_id() ) ] ) ) {
        $desc = $by_slug[ get_post_field( 'post_name', get_queried_object_id() ) ];
    } elseif ( is_singular( 'post' ) ) {
        $excerpt = get_the_excerpt();
        $desc    = $excerpt ? wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '' ) : get_the_title();
    } elseif ( is_page() ) {
        $excerpt = get_the_excerpt();
        $desc    = $excerpt ? wp_trim_words( wp_strip_all_tags( $excerpt ), 30, '' ) : '';
    }

    if ( $desc ) {
        echo '<meta name="description" content="' . esc_attr( $desc ) . '">' . "\n";
    }
}
add_action( 'wp_head', 'ar_meta_description', 1 );

/* ============================================================
   GEO / SEO — STRUCTURED DATA (JSON-LD)
   Person + RealEstateAgent site-wide; BlogPosting added per-post in single.php.
   NOTE: telephone/office address deliberately omitted — not yet confirmed
   as Amber's direct line vs. a shared KW market-center number. Add once confirmed.
   ============================================================ */
function ar_schema_json_ld() {
    $home      = home_url( '/' );
    $about_url = home_url( '/about' );
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
            'name'         => 'Amber Randhawa',
            'url'          => $home,
            'image'        => $portrait,
            'description'  => 'Real estate agent serving metro Atlanta and northwest Georgia, including Cobb, Paulding, Haralson, Carroll, and Polk counties.',
            'areaServed'   => $area_served,
            'memberOf'     => [
                '@type' => 'Organization',
                'name'  => 'Keller Williams Realty Cityside',
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

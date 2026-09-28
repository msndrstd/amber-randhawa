<?php
/**
 * Header photo band.
 *
 * $args['slug'] — looks for assets/images/band-<slug>.jpg, and serves
 * band-<slug>-sm.jpg (4:3) below 640px when that file exists. Renders
 * nothing at all if the wide file is missing, so a page without art
 * just keeps its plain header.
 *
 * The wash and the 4.5:1 framing live in community.css sections 8-12.
 *
 * @package amber-randhawa
 */
$ar_slug = isset( $args['slug'] ) ? $args['slug'] : '';
if ( ! $ar_slug ) { return; }

$ar_dir  = get_template_directory() . '/assets/images/';
$ar_uri  = get_template_directory_uri() . '/assets/images/';
$ar_wide = 'band-' . $ar_slug . '.jpg';
$ar_sm   = 'band-' . $ar_slug . '-sm.jpg';

if ( ! file_exists( $ar_dir . $ar_wide ) ) { return; }
?>
<div class="lr-head-media" aria-hidden="true">
  <picture>
    <?php if ( file_exists( $ar_dir . $ar_sm ) ) : ?>
      <source media="(max-width: 640px)"
              srcset="<?php echo esc_url( $ar_uri . $ar_sm . '?v=' . filemtime( $ar_dir . $ar_sm ) ); ?>">
    <?php endif; ?>
    <img src="<?php echo esc_url( $ar_uri . $ar_wide . '?v=' . filemtime( $ar_dir . $ar_wide ) ); ?>"
         alt="" decoding="async" fetchpriority="high">
  </picture>
</div>

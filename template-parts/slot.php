<?php
/**
 * Photo slot. Renders the image if a file with that name exists in
 * assets/images/, otherwise a labelled placeholder naming the file to
 * drop in. Never falls back to stock, per the imagery rule in
 * project memory.
 *
 * $args: file (string), alt (string), label (string), shape (wide|band|'')
 */
$file  = isset( $args['file'] ) ? $args['file'] : '';
$alt   = isset( $args['alt'] ) ? $args['alt'] : '';
$label = isset( $args['label'] ) ? $args['label'] : 'Photo';
$shape = isset( $args['shape'] ) ? $args['shape'] : '';

$dir = get_template_directory() . '/assets/images/';
$uri = get_template_directory_uri() . '/assets/images/';

if ( $file && file_exists( $dir . $file ) ) :
  /* Cache-bust on the file's own mtime. Photo swaps reuse the same
     filename, so without this the browser keeps serving the old shot. */
  $ver = filemtime( $dir . $file );
  ?>
  <img class="lr-aside-photo<?php echo $shape ? ' is-' . esc_attr( $shape ) : ''; ?>"
       src="<?php echo esc_url( $uri . $file . '?v=' . $ver ); ?>"
       alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" decoding="async">
<?php else : ?>
  <div class="lr-slot<?php echo $shape ? ' lr-slot--' . esc_attr( $shape ) : ''; ?>" role="img"
       aria-label="<?php echo esc_attr( $label ); ?> placeholder">
    <span><?php echo esc_html( $label ); ?><b><?php echo esc_html( $file ); ?></b></span>
  </div>
<?php endif; ?>

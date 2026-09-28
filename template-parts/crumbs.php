<?php
/**
 * Breadcrumbs. A real <nav> with a real ordered list, plus
 * BreadcrumbList JSON-LD. Answer engines read both, and the trail
 * is how a county page tells an engine it belongs to a hub.
 */
$trail = isset( $args['trail'] ) ? $args['trail'] : [];
if ( empty( $trail ) ) { return; }

$items = [];
$pos   = 1;
foreach ( $trail as $t ) {
    $entry = [ '@type' => 'ListItem', 'position' => $pos, 'name' => $t['label'] ];
    if ( ! empty( $t['url'] ) ) { $entry['item'] = $t['url']; }
    $items[] = $entry;
    $pos++;
}
?>
<nav class="lr-crumbs" aria-label="Breadcrumb">
  <ol>
    <?php foreach ( $trail as $i => $t ) : ?>
      <li>
        <?php if ( ! empty( $t['url'] ) && $i < count( $trail ) - 1 ) : ?>
          <a href="<?php echo esc_url( $t['url'] ); ?>"><?php echo esc_html( $t['label'] ); ?></a>
        <?php else : ?>
          <span aria-current="page"><?php echo esc_html( $t['label'] ); ?></span>
        <?php endif; ?>
      </li>
    <?php endforeach; ?>
  </ol>
</nav>
<script type="application/ld+json"><?php echo wp_json_encode(
  [ '@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => $items ],
  JSON_UNESCAPED_SLASHES
); ?></script>

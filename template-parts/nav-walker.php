<?php
/**
 * Custom Nav Walker — outputs clean anchor tags only, no extra divs
 */
class AR_Nav_Walker extends Walker_Nav_Menu {

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item   = $data_object;
        $attrs  = [];
        $attrs['href']   = ! empty( $item->url ) ? $item->url : '#';
        $attrs['target'] = ! empty( $item->target ) ? $item->target : '';
        $attrs['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

        if ( in_array( 'current-menu-item', $item->classes ) ) {
            $attrs['aria-current'] = 'page';
        }

        $attr_str = '';
        foreach ( $attrs as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $attr_str .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
            }
        }

        $output .= '<a' . $attr_str . '>' . esc_html( $item->title ) . '</a>';
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
    public function start_lvl( &$output, $depth = 0, $args = null ) {}
    public function end_lvl( &$output, $depth = 0, $args = null ) {}
}

/**
 * Fallback nav when no menu is assigned
 */
function ar_fallback_nav() {
    $links = [
        'About'   => '/about',
        'Writing' => '/blog',
        'Contact' => '/contact',
    ];
    foreach ( $links as $label => $path ) {
        echo '<a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a>';
    }
}


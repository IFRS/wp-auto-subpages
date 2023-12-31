<?php
/**
 * Plugin Name:       IFRS WP Auto Subpages
 * Plugin URI:        https://github.com/IFRS/wp-auto-subpages
 * Description:       Automatically add subpages to every page that has children.
 * Version:           0.0.1
 * Requires at least: 6.2
 * Requires PHP:      7.4
 * Author:            Ricardo Moro
 * Author URI:        https://ifrs.edu.br
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       ifrs-wp-auto-subpages
 * Domain Path:       /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

add_filter( 'the_content', function( $content ) {
  if (is_page()) {
    global $post;

    $html = '';
    $children = get_pages( array(
      'parent'      => $post->ID,
      'sort_column' => 'menu_order',
    ) );


    if ( $children ) {
      $html = '<span class="screen-reader-text">Sub-páginas:</span>';
      $html .= '<div class="wp-block-buttons is-layout-flex is-content-justification-left">';

      foreach ($children as $child) {
        $html .= '<div class="wp-block-button">';
        $html .= '<a class="wp-block-button__link wp-element-button" href="' . get_permalink($child) . '">';
        $html .= $child->post_title;
        $html .= '</a>';
        $html .= '</div>';
      }

      $html .= '</div>';
    }

    $content = $html . $content;
  }

  return $content;
} );

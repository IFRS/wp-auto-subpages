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
  if (is_page() && is_main_query() && in_the_loop()) {
    $post = get_post();

    $children = get_pages( array(
      'parent'      => $post->ID,
      'sort_column' => 'menu_order,post_title',
    ) );


    if ( $children ) {
      $html = '<span class="screen-reader-text">Sub-p&aacute;ginas:</span>';

      $botoes = '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left","orientation":"horizontal"}} --><div class="wp-block-buttons">';

      foreach ($children as $child) {
        $botoes .= '<!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="' . get_permalink($child) . '">' . $child->post_title . '</a></div><!-- /wp:button -->';
      }

      $botoes .= '</div><!-- /wp:buttons -->';

      $parsed_blocks = parse_blocks( $botoes );

      if ( $parsed_blocks ) {
        foreach ( $parsed_blocks as $block ) {
          $html .= render_block( $block );
        }
      }

      $content = $html . $content;
    }
  }

  return $content;
} );

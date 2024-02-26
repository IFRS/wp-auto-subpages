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
if ( ! defined( 'WPINC' ) || !defined('ABSPATH') ) {
	exit;
}

require_once 'includes/metaboxes.php';

function ifrs_auto_subpages_monta_blocos($tipo, $itens) {
  $markup = '';

  switch ($tipo) {
    case 'ul':
      $markup .= '<!-- wp:list --><ul>';

      foreach ($itens as $item) {
        $url = get_permalink($item);
        $markup .= <<<HTML
          <!-- wp:list-item -->
          <li><a data-type="link" data-id="{$url}" href="{$url}">{$item->post_title}</a></li>
          <!-- /wp:list-item -->
        HTML;
      }

      $markup .= '</ul><!-- /wp:list -->';
    break;
    case 'ol':
      $markup .= '<!-- wp:list {"ordered":true} --><ol>';

      foreach ($itens as $item) {
        $url = get_permalink($item);
        $markup .= <<<HTML
          <!-- wp:list-item -->
          <li><a data-type="link" data-id="{$url}" href="{$url}">{$item->post_title}</a></li>
          <!-- /wp:list-item -->
        HTML;
      }

      $markup .= '</ol><!-- /wp:list -->';
    break;
    default:
      $markup .= '<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left","orientation":"horizontal"}} --><div class="wp-block-buttons">';

      foreach ($itens as $item) {
        $url = get_permalink($item);
        $markup .= <<<HTML
          <!-- wp:button -->
          <div class="wp-block-button">
            <a class="wp-block-button__link wp-element-button" href="{$url}">{$item->post_title}</a>
          </div>
          <!-- /wp:button -->
        HTML;
      }

      $markup .= '</div><!-- /wp:buttons -->';

    break;
  }

  return $markup;
}

add_filter( 'the_content', function( $content ) {
  if (is_page() && is_main_query() && in_the_loop()) {
    $post = get_post();

    $children = get_pages( array(
      'parent'      => $post->ID,
      'sort_column' => 'menu_order,post_title',
    ) );

    if ( $children ) {
      $tipo_menu = get_post_meta( $post->ID, 'ifrs_subpages_menu_option', true );

      if ($tipo_menu === 'hide') return $content;

      $html = '<span class="screen-reader-text">Sub-p&aacute;ginas:</span>';

      $blocos = ifrs_auto_subpages_monta_blocos($tipo_menu, $children);

      $parsed_blocks = parse_blocks( $blocos );

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

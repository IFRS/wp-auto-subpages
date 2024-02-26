<?php
// CMB2 load
if ( file_exists( dirname( __FILE__ ) . '../vendor/cmb2/cmb2/init.php' ) ) {
	require_once dirname( __FILE__ ) . '../vendor/cmb2/cmb2/init.php';
}

function ifrs_show_if_has_children( $field ) {
	$children = get_pages( array('parent' => $field->object_id) );
  $screen = get_current_screen();
  $is_new_page = $screen->post_type === 'page' && $screen->action === 'add';

	if (!$children || $is_new_page) {
		return false;
	}
	return true;
}

add_action( 'cmb2_admin_init', function() {
	$subpages_menu_options = new_cmb2_box( array(
		'id'            => 'ifrs_subpages_menu_metabox',
		'title'         => __( 'Menu de Sub-páginas', 'ifrs' ),
		'object_types'  => array( 'page' ),
		'show_on_cb' => 'ifrs_show_if_has_children',
		// 'context'    => 'side',
		'priority'   => 'low',
		'closed'     => true,
	) );

	$subpages_menu_options->add_field( array(
		'name'       => __( 'Tipo de Menu', 'ifrs' ),
		'desc'       => __( 'Escolha o tipo de menu para exibir as sub-páginas dessa página', 'ifrs' ),
		'id'         => 'ifrs_subpages_menu_option',
		'type'       => 'select',
    'show_option_none' => __( 'Botões (Padrão)', 'ifrs' ),
    'options'    => array(
      'ul' => __( 'Lista Não Ordenada', 'ifrs' ),
      'ol' => __( 'Lista Ordenada', 'ifrs' ),
      'hide' => __( 'Esconder Menu', 'ifrs' ),
    ),
	) );
} );

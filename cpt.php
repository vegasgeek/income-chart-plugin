<?php
/**
 * Generate Income CPT.
 *
 * @package charts
 */

function cptui_register_my_cpts_income() {

	/**
	 * Post Type: Income.
	 */

	$labels = [
		'name'          => __( 'Income', 'vgs' ),
		'singular_name' => __( 'Income', 'vgs' ),
		'add_new'       => __( 'Add Income', 'vgs' ),
		'new_item'      => __( 'New Income', 'vgs' ),
		'view_item'     => __( 'View Income', 'vgs' ),
	];

	$args = [
		'label'                 => __( 'Income', 'vgs' ),
		'labels'                => $labels,
		'description'           => '',
		'public'                => false,
		'publicly_queryable'    => false,
		'show_ui'               => true,
		'show_in_rest'          => true,
		'rest_base'             => '',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
		'has_archive'           => false,
		'show_in_menu'          => true,
		'show_in_nav_menus'     => true,
		'delete_with_user'      => false,
		'exclude_from_search'   => false,
		'capability_type'       => 'post',
		'map_meta_cap'          => true,
		'hierarchical'          => false,
		'rewrite'               => [ 'slug' => 'income', 'with_front' => true ],
		'query_var'             => true,
		'supports'              => [ 'title', 'editor', 'thumbnail' ],
	];

	register_post_type( 'income', $args );
}

add_action( 'init', 'cptui_register_my_cpts_income' );

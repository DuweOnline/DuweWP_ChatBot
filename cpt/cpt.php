<?php

// Register Custom Post Types
function duwe_wp_chatbot()
{
	$labels = array(
		'name'                  => _x('DuweWP ChatBot', 'Post Type General Name', 'ChatBot'),
		'singular_name'         => _x('DuweWP ChatBot', 'Post Type Singular Name', 'ChatBot'),
		'menu_name'             => __('DuweWP ChatBot', 'ChatBot'),
		'name_admin_bar'        => __('DuweWP ChatBot', 'ChatBot'),
		'archives'              => __('DuweWP ChatBot Archives', 'ChatBot'),
		'attributes'            => __('DuweWP ChatBot Attributes', 'ChatBot'),
		'parent_item_colon'     => __('Parent DuweWP ChatBot:', 'ChatBot'),
		'all_items'             => __('All DuweWP ChatBot', 'ChatBot'),
		'add_new_item'          => __('New DuweWP ChatBot', 'ChatBot'),
		'add_new'               => __('Add New', 'ChatBot'),
		'new_item'              => __('New DuweWP ChatBot', 'ChatBot'),
		'edit_item'             => __('Edit DuweWP ChatBot', 'ChatBot'),
		'update_item'           => __('Update DuweWP ChatBot', 'ChatBot'),
		'view_item'             => __('View DuweWP ChatBot', 'ChatBot'),
		'view_items'            => __('View DuweWP ChatBot', 'ChatBot'),
		'search_items'          => __('Search DuweWP ChatBot', 'ChatBot'),
		'not_found'             => __('Not found', 'ChatBot'),
		'not_found_in_trash'    => __('Not found in Trash', 'ChatBot'),
		'featured_image'        => __('Featured Image', 'ChatBot'),
		'set_featured_image'    => __('Set featured image', 'ChatBot'),
		'remove_featured_image' => __('Remove featured image', 'ChatBot'),
		'use_featured_image'    => __('Use as featured image', 'ChatBot'),
		'insert_into_item'      => __('Insert into item', 'ChatBot'),
		'uploaded_to_this_item' => __('Uploaded to this DuweWP ChatBot', 'ChatBot'),
		'items_list'            => __('DuweWP ChatBot list', 'ChatBot'),
		'items_list_navigation' => __('DuweWP ChatBot list navigation', 'ChatBot'),
		'filter_items_list'     => __('Filter DuweWP ChatBot list', 'ChatBot'),
	);
	$args = array(
		'label'                 => __('DuweWP ChatBot', 'ChatBot'),
		'labels'                => $labels,
		'menu_icon'             => 'dashicons-format-chat',
		'supports'              => array( 'title' ),
		'hierarchical'          => true,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 80,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'post',
		'show_in_rest'          => true
	);
	register_post_type('duwe_wp_chatbot', $args);
}
add_action('init', 'duwe_wp_chatbot', 0);

add_filter( 'use_block_editor_for_post', 'disable_block_for_post_type', 10, 2 );
function disable_block_for_post_type( $use_block_editor, $post ) {
	if ( 'duwe_wp_chatbot' === $post->post_type ) {
		return false;
	};
	return $use_block_editor;
}


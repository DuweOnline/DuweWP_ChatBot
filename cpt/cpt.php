<?php

// Register Custom Post Types
function duwe_wp_chatbot()
{
	$labels = array(
		'name'                  => __('DuweWP ChatBot'),
		'singular_name'         => __('DuweWP ChatBot'),
		'menu_name'             => __('DuweWP ChatBot'),
		'name_admin_bar'        => __('DuweWP ChatBot'),
		'archives'              => __('DuweWP ChatBot Archives'),
		'attributes'            => __('DuweWP ChatBot Attributes'),
		'parent_item_colon'     => __('Parent DuweWP ChatBot:'),
		'all_items'             => __('All DuweWP ChatBot'),
		'add_new_item'          => __('New DuweWP ChatBot'),
		'add_new'               => __('Add New'),
		'new_item'              => __('New DuweWP ChatBot'),
		'edit_item'             => __('Edit DuweWP ChatBot'),
		'update_item'           => __('Update DuweWP ChatBot'),
		'view_item'             => __('View DuweWP ChatBot'),
		'view_items'            => __('View DuweWP ChatBot'),
		'search_items'          => __('Search DuweWP ChatBot'),
		'not_found'             => __('Not found'),
		'not_found_in_trash'    => __('Not found in Trash'),
		'featured_image'        => __('Featured Image'),
		'set_featured_image'    => __('Set featured image'),
		'remove_featured_image' => __('Remove featured image'),
		'use_featured_image'    => __('Use as featured image'),
		'insert_into_item'      => __('Insert into item'),
		'uploaded_to_this_item' => __('Uploaded to this DuweWP ChatBot'),
		'items_list'            => __('DuweWP ChatBot list'),
		'items_list_navigation' => __('DuweWP ChatBot list navigation'),
		'filter_items_list'     => __('Filter DuweWP ChatBot list'),
	);
	$args = array(
		'label'                 => __('DuweWP ChatBot'),
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


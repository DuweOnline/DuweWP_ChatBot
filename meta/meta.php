<?php
// Related
add_action('add_meta_boxes', 'related_cpt_add_meta_box');
function related_cpt_add_meta_box() {
	add_meta_box(
		'related_cpt_meta_box',
		'Link to other ChatBot screens',
		'related_cpt_render_meta_box',
		'duwe_wp_chatbot',
		'side',
		'default'
	);
}
// Text, URL and ID Combined
add_action('add_meta_boxes', 'custom_combined_metabox');
function custom_combined_metabox() {
	add_meta_box(
		'custom_combined_meta_box',
		'Custom Fields',
		'render_custom_combined_metabox',
		'duwe_wp_chatbot',
		'normal',
		'default'
	);
}

// Related
function related_cpt_render_meta_box($post) {
	$selected = get_post_meta($post->ID, '_related_cpt_entries', true) ?: [];
	$related_post_type = 'duwe_wp_chatbot';
	$posts = get_posts([
		'post_type' => $related_post_type,
		'numberposts' => -1,
		'post_status' => 'publish',
		'post__not_in' => [$post->ID],
	]);
	wp_nonce_field('related_cpt_nonce_action', 'related_cpt_nonce');
	echo '<p>Select ChatBot posts to convert to buttons on this screen:</p>';
	echo '<select id="duwe_chatbot_posts" name="related_cpt_entries[]" multiple style="width: 100%; height: 150px;">';
	foreach ($posts as $p) {
		$is_selected = in_array($p->ID, $selected) ? 'selected' : '';
		echo "<option value='".esc_html($p->ID)."' ".esc_html($is_selected).">".esc_html($p->post_title)."</option>";
	}
	echo '</select>';
}
add_action('save_post', 'related_cpt_save_meta_box');
function related_cpt_save_meta_box($post_id) {
	if (!isset($_POST['related_cpt_nonce']) || !wp_verify_nonce(sanitize_key($_POST['related_cpt_nonce']), 'related_cpt_nonce_action')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;
	if (isset($_POST['related_cpt_entries']) && is_array($_POST['related_cpt_entries'])) {
		$entries = array_map('intval', $_POST['related_cpt_entries']);
		update_post_meta($post_id, '_related_cpt_entries', $entries);
	} else {
		delete_post_meta($post_id, '_related_cpt_entries');
	}
}
// Text, URL and ID Combined
function render_custom_combined_metabox($post) {
	$content = get_post_meta($post->ID, '_custom_tinymce', true);
	$url = get_post_meta($post->ID, '_custom_url', true);
	$short = get_post_meta($post->ID, '_custom_short', true);

	wp_nonce_field('save_custom_combined_metabox', 'custom_combined_nonce');

	echo '<p><label><strong>ChatBot Screen Text (max 300 chars):</strong></label></p>';
	wp_editor(
		$content,
		'custom_tinymce',
		[
			'textarea_name' => 'custom_tinymce',
			'media_buttons' => false,
			'textarea_rows' => 5,
			'tinymce' => [
				'toolbar1' => 'undo redo',
				'toolbar2' => '',
				'menubar' => false,
				'statusbar' => false,
			],
			'quicktags' => false
		]
	);
	echo '<p>Enter the content for your ChatBot screen here.</p><hr><p><label for="custom_url"><strong>URL:</strong></label></p>';
	echo '<input type="url" name="custom_url" id="custom_url" placeholder="You can use https://your-url.com, tel:<number>, mailto:your-email@emailaddress.com or https://wa.me/<number> for WhatsApp numbers" value="' . esc_attr($url) . '" style="width:100%;" />';
	echo '<p>If you want the button for this screen to go directly to a URL or Email address, enter it here. Otherwise, leave this bank to go to the ChatBot screen.<br>(You can use https://your-url.com, tel:&lt;number&gt;, mailto:your-email@emailaddress.com or https://wa.me/&lt;number&gt; for WhatsApp numbers)</p><hr>';
	echo '<p><label for="custom_short"><strong>Alternate Button Text (max 100 chars):</strong></label><br>';
	echo '<input type="text" name="custom_short" id="custom_short" value="' . esc_attr($short) . '" maxlength="100" style="width:100%;" />';
	echo '<p>If you want the button for this screen to say something else apart from the title above, enter it here - keep it short.</p>';
}


add_action('save_post', 'save_combined_metabox');
function save_combined_metabox($post_id) {
	if (!isset($_POST['combined_metabox_nonce']) ||
		!wp_verify_nonce(sanitize_key($_POST['combined_metabox_nonce']), 'save_combined_metabox')) {
		return;
	}
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;
	if (isset($_POST['tinymce_limited_field'])) {
		$text = sanitize_textarea_field($_POST['tinymce_limited_field']);
		update_post_meta($post_id, '_custom_textarea', mb_substr($text, 0, 300));
	}
	if (isset($_POST['custom_url'])) {
		$url = esc_url_raw(trim(sanitize_key($_POST['custom_url'])));
		if (filter_var($url, FILTER_VALIDATE_URL)) {
			update_post_meta($post_id, '_custom_url', $url);
		} else {
			delete_post_meta($post_id, '_custom_url');
		}
	}
}

add_action('save_post', 'save_custom_combined_metabox');
function save_custom_combined_metabox($post_id) {
	if (!isset($_POST['custom_combined_nonce']) ||
		!wp_verify_nonce(sanitize_key($_POST['custom_combined_nonce']), 'save_custom_combined_metabox')) return;

	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if (!current_user_can('edit_post', $post_id)) return;

	// Save TinyMCE content
	if (isset($_POST['custom_tinymce'])) {
		$text = wp_strip_all_tags($_POST['custom_tinymce']);
		$text = mb_substr($text, 0, 300);
		update_post_meta($post_id, '_custom_tinymce', wp_kses_post($text));
	}

	// Save URL
	if (isset($_POST['custom_url'])) {
		$url = esc_url_raw(trim($_POST['custom_url']));
		update_post_meta($post_id, '_custom_url', $url);
	}

	// Save short text
	if (isset($_POST['custom_short'])) {
		$short = sanitize_text_field($_POST['custom_short']);
		$short = mb_substr($short, 0, 100);
		update_post_meta($post_id, '_custom_short', $short);
	}
}

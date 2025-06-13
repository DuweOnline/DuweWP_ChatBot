<?php


function duwewp_chatbot_options_add_admin_menu() {
	add_options_page(
		'DuweWP ChatBot Options',
		'DuweWP ChatBot Options',
		'manage_options',
		'duwewp_chatbot_options',
		'duwewp_chatbot_options_page'
	);
}

function duwewp_chatbot_options_settings_init() {
	register_setting('duwewp_chatbot_options_group', 'selected_cpt_entry', [
		'sanitize_callback' => 'duwewp_sanitize_post_id'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'show_chatbot_enabled', [
		'sanitize_callback' => 'duwewp_sanitize_checkbox'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'show_chatbot_homepage', [
		'sanitize_callback' => 'duwewp_sanitize_checkbox'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'chatbot_color', [
		'sanitize_callback' => 'duwewp_sanitize_hex_color_value'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'chatbot_button_color', [
		'sanitize_callback' => 'duwewp_sanitize_hex_color_value'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'chatbot_icon', [
		'sanitize_callback' => 'duwewp_sanitize_post_id'
	]);
	
	register_setting('duwewp_chatbot_options_group', 'chatbot_avatar', [
		'sanitize_callback' => 'duwewp_sanitize_post_id'
	]);
	function duwewp_sanitize_checkbox($value) {
		return $value === '1' ? '1' : '0';
	}
	function duwewp_sanitize_post_id($value) {
		return absint($value); // ensures integer and non-negative
	}
	// Sanitize hex color (allows values like #ffffff)
	function duwewp_sanitize_hex_color_value($value) {
		$value = trim($value);
		if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
			return $value;
		}
		return ''; // return empty if not a valid hex color
	}

	add_settings_section(
		'initial_section',
		'',
		null,
		'duwewp_chatbot_options'
	);
	add_settings_field(
		'selected_cpt_entry_field',
		'Instructions',
		'duwewp_initial_section_render',
		'duwewp_chatbot_options',
		'initial_section'
	);
	add_settings_field(
		'settings_divider_1',
		'',
		'render_settings_divider',
		'duwewp_chatbot_options',
		'initial_section'
	);
	add_settings_section(
		'duwewp_chatbot_options_section',
		'Select Initial DuweWP ChatBot Screen',
		null,
		'duwewp_chatbot_options'
	);
	add_settings_field(
		'selected_cpt_entry_field',
		'Choose an entry',
		'duwewp_chatbot_options_field_render',
		'duwewp_chatbot_options',
		'duwewp_chatbot_options_section'
	);
	add_settings_field(
		'settings_divider_1',
		'',
		'render_settings_divider',
		'duwewp_chatbot_options',
		'duwewp_chatbot_options_section'
	);
	add_settings_section(
		'show_bot_options',
		'DuweWP ChatBot Options',
		null,
		'duwewp_chatbot_options'
	);
	add_settings_field(
		'chatbot_color_field',
		'Chat Icon and ChatBot Border Colour',
		'chatbot_color_field_render',
		'duwewp_chatbot_options',
		'show_bot_options'
	);
	add_settings_field(
		'chatbot_button_color',
		'ChatBot Button Color',
		'chatbot_button_color_render',
		'duwewp_chatbot_options',
		'show_bot_options'
	);
	add_settings_field(
		'chatbot_icon_field',
		'Upload ChatBot Icon',
		'icon_image_field_render',
		'duwewp_chatbot_options',
		'show_bot_options',
		['name' => 'chatbot_icon']
	);
	add_settings_field(
		'chatbot_avatar_field',
		'Upload ChatBot Avatar',
		'avatar_image_field_render',
		'duwewp_chatbot_options',
		'show_bot_options',
		['name' => 'chatbot_avatar']
	);
	add_settings_field(
		'settings_divider_1',
		'',
		'render_settings_divider',
		'duwewp_chatbot_options',
		'show_bot_options'
	);
	add_settings_section(
		'show_bot_section',
		'DuweWP ChatBot Visibility Options',
		null,
		'duwewp_chatbot_options'
	);
	add_settings_field(
		'show_chatbot_homepage',
		'Expand DuweWP ChatBot on homepage',
		'show_chatbot_homepage_render',
		'duwewp_chatbot_options',
		'show_bot_section'
	);
	add_settings_field(
		'show_chatbot_checkbox',
		'Show DuweWP ChatBot',
		'show_chatbot_checkbox_render',
		'duwewp_chatbot_options',
		'show_bot_section'
	);
}

function duwewp_initial_section_render() {
	echo 'Hiiiiii';
}

function duwewp_chatbot_options_field_render() {
	$selected = get_option('selected_cpt_entry');
	$cpt_slug = 'duwe_wp_chatbot';

	$entries = get_posts([
		'post_type' => $cpt_slug,
		'numberposts' => -1,
		'post_status' => 'publish',
	]);

	echo '<label><select name="selected_cpt_entry">';
	echo '<option value="">-- Select --</option>';
	foreach ($entries as $entry) {
		$is_selected = selected($selected, $entry->ID, false);
		echo "<option value='".esc_html($entry->ID)."' ".esc_html($is_selected).">".esc_html($entry->post_title)."</option>";
	}
	echo '</select></label><p>This is the first DuweWP ChatBot screen users will see</p>';
}

function show_chatbot_homepage_render() {
	$checked = get_option('show_chatbot_homepage');
	echo '<label class="switch"><input type="checkbox" class="duwe-toggle" name="show_chatbot_homepage" value="1"' . checked(1, $checked, false) . '> <span class="slider  round"></span></label><p>Activating this will expand the DuweWP ChatBot on the homepage. Leaving it off will keep it closed on the homepage and leave it as click to open.</p>';
}
function show_chatbot_checkbox_render() {
	$checked = get_option('show_chatbot_enabled');
	echo '<label class="switch"><input type="checkbox" class="duwe-toggle" name="show_chatbot_enabled" value="1"' . checked(1, $checked, false) . '><span class="slider  round"></span></label><p>Turning this off this will completely disable the DuweWP ChatBot, but keep all the settings intact.</p>';
}

function chatbot_color_field_render() {
	$color = get_option('chatbot_color', '#ffffff');
	echo '<input type="text" name="chatbot_color" value="' . esc_attr($color) . '" class="color-field" /> <p>If no colour is selected, a default will be used</p>';
}
function chatbot_button_color_render() {
	$buttoncolor = get_option('chatbot_button_color', '#ffffff');
	echo '<input type="text" name="chatbot_button_color" value="' . esc_attr($buttoncolor) . '" class="color-field" /> <p>If no colour is selected, a default will be used</p>';
}

function icon_image_field_render($args) {
	$name = $args['name'];
	$image_id = get_option($name);
	$image_html = $image_id ? wp_get_attachment_image($image_id, 'thumbnail') : '';

	echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($image_id) . '" class="image-id-field" />';
	echo '<div class="image-preview" style="margin-bottom: 10px;">';
	if ($image_html) {
		echo wp_kses_post($image_html);
	}
	echo '</div>';
	echo '<button type="button" class="button upload-image-button" data-target="' . esc_attr($name) . '">';
	echo $image_html ? 'Replace Image' : 'Upload Image';
	echo '</button> ';
	
	echo '<button type="button" class="button remove-image-button" data-target="' . esc_attr($name) . '"';
	if (!$image_html) echo ' style="display:none;"';
	echo '>Remove Image</button>';
	echo '<p>Please upload a square transparent background icon.<p>If no image is uploaded, a default icon will be used.</p>';
}

function avatar_image_field_render($args) {
	$name = $args['name'];
	$image_id = get_option($name);
	$image_html = $image_id ? wp_get_attachment_image($image_id, 'thumbnail') : '';

	echo '<input type="hidden" name="' . esc_attr($name) . '" value="' . esc_attr($image_id) . '" class="image-id-field" />';
	echo '<div class="image-preview" style="margin-bottom: 10px;">';
	if ($image_html) {
		echo wp_kses_post($image_html);
	}
	echo '</div>';
	echo '<button type="button" class="button upload-image-button" data-target="' . esc_attr($name) . '">';
	echo $image_html ? 'Replace Image' : 'Upload Image';
	echo '</button> ';
	
	echo '<button type="button" class="button remove-image-button" data-target="' . esc_attr($name) . '"';
	if (!$image_html) echo ' style="display:none;"';
	echo '>Remove Image</button>';
	echo '<p>Please try to upload a square image, or edit your image to be a square.</><p>If no image is uploaded, a default or your gravatar will be used.</p>';
}


function render_settings_divider() {
	echo '<hr style="margin: 1rem 0 0 0; border-top: 1px solid #ccc;" />';
}

function duwewp_chatbot_options_page() {
	?>
	<div class="wrap">
		<h1>DuweWP ChatBot Options</h1>
		<form action="options.php" method="post">
			<?php
				settings_fields('duwewp_chatbot_options_group');
				do_settings_sections('duwewp_chatbot_options');
				submit_button();
			?>
		</form>
	</div>
	<?php
}
add_action('admin_menu', 'duwewp_chatbot_options_add_admin_menu');
add_action('admin_init', 'duwewp_chatbot_options_settings_init');
<?php
// SHOW CONTENT
add_action('wp_footer', 'select_cpt_entry_maybe_display_content');

function select_cpt_entry_maybe_display_content() {
	if (!get_option('show_chatbot_enabled')) {
		return;
	}
	$selected_post_id = get_option('selected_cpt_entry');
	$chatbot_color = get_option('chatbot_color');
	$chatbot_bg = get_option('chatbot_color') . '0a';
	$button_color = get_option('chatbot_button_color');
	$chatbot_icon = get_option('chatbot_icon');
	$chatbot_avatar = get_option('chatbot_avatar');
	$chatbot_start = get_option('selected_cpt_entry');
	$post = get_post($chatbot_start);
	if ($post) {
		$chatbot_start = $post->post_name;
		$chatbot_start = preg_replace("/[^a-zA-Z]+/", "", $chatbot_start);
	}
	if ($chatbot_icon) {
		$chatbot_icon = wp_get_attachment_image($chatbot_icon, 'chatbot_icon', false, [
			'class' => 'openchat',
			'alt' => get_post_meta($chatbot_icon, '_wp_attachment_image_alt', true)
		]);
	}
	if ($chatbot_avatar) {
		$chatbot_avatar = wp_get_attachment_image($chatbot_avatar, 'chatbot_avatar', false, [
			'class' => 'avatar',
			'alt' => get_post_meta($chatbot_icon, '_wp_attachment_image_alt', true)
		]);
	} else {
		$admin_users = get_users(['role' => 'administrator']);
		$admin_user = $admin_users[0];
		if ($admin_user) {
			$chatbot_avatar = get_avatar($admin_user->ID, 76, '', $admin_user->display_name, [
				'class' => 'admin-gravatar',
				'style' => 'border-radius:50%;'
			]);
		}
	}
	if (!$selected_post_id) {
		return;
	}

	$post = get_post($selected_post_id);
	if (!$post) {
		return;
	}
	if (get_option('show_chatbot_homepage')) {
		if ( is_front_page() && is_home() ) {
			echo '<script>setTimeout(() => { chatbot.classList.add("active"); StateMachine.render(); }, 2500)</script>';
		}
	}
	echo '
	<style>
	#openchat {
		background: '.esc_html($chatbot_color).';
	}
	#chatbot {
		border-color: '.esc_html($chatbot_color).';
	}
	#chatbotInner {
		background:  '.esc_html($chatbot_bg).';
	}
	.chatbot_button {
		background: '.esc_html($button_color).';
		border-color: '.esc_html($button_color).';
	}
	.chatbot_button:hover {
		filter: contrast(150%);
	}
	</style>
	<div id="chatbot" role="modal">
		<div id="chatbotInner">
			<div id="message">
				<div id="avatar">'.wp_kses_post($chatbot_avatar).'</div>
				<div id="messagetext" class=""></div>
			</div>
			<div id="chatbot_buttons"></div>
		</div>
	</div>
	<div id="openchat" title="Open chat">'.wp_kses_post($chatbot_icon).'</div>
	<script defer>
	var openchat = document.getElementById("openchat");
	var chatbot = document.getElementById("chatbot");
	openchat.addEventListener("click", function() {
		chatbot.classList.toggle("active");
		StateMachine.render();
	})
	var StateMachine = {
		currentState: "",
		states: {},
		interact: function() {},
		render: function() {}
	}
	StateMachine.interact = function(option) {
		var currentState = this.states[this.currentState]
		var selectedOption = currentState.options[option]
		if (selectedOption.href) { window.open(selectedOption.href); return; }
		this.currentState = selectedOption.next;
		this.render();
	}
	StateMachine.render = function() {
		var message = document.getElementById("messagetext")
		var chatbot_buttons = document.getElementById("chatbot_buttons")
		var currentState = this.states[this.currentState];
		message.innerHTML = "<div class=\'typing\'><span></span><span></span><span></span></div>";
		chatbot_buttons.innerHTML = "";
		setTimeout(() => {
			message.innerHTML = currentState.message;
			chatbot_buttons.innerHTML = "";
			currentState.options.forEach((option, i) => {
				chatbot_buttons.innerHTML += \'<a href="javascript:StateMachine.interact(\'+i+\');" class="chatbot_button">\'+option.title+\'</a>\';
			});
		}, 500);
		return;
	}
	StateMachine.currentState = "'.esc_html($chatbot_start).'";
	';
	
	$args = array(
		'post_type'      => 'duwe_wp_chatbot',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
	echo ' StateMachine.states = {';
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_slug = get_post_field( 'post_name', get_the_ID() );
			$post_slug = preg_replace("/[^a-zA-Z]+/", "", $post_slug);
			$textarea = rtrim(wpautop(get_post_meta(get_the_ID(), '_custom_tinymce', true)));
			$textarea = preg_replace( '/(\r\n)+|\r+|\n+|\t+/', ' ', $textarea );
			$buttons = get_post_meta(get_the_ID(), '_related_cpt_entries', true);
			echo esc_html($post_slug) .': {';
			echo 'message: "'.wp_kses_post($textarea).'",';
			echo 'options: [';
				if (!empty($buttons) && is_array($buttons)) {				
					foreach ($buttons as $button) {
						$post = get_post($button);
						$post_slug = get_post_field( 'post_name', $post->ID );
						$post_slug = preg_replace("/[^a-zA-Z]+/", "", $post_slug);
						$post_url = get_post_field( '_custom_url', $post->ID );
						$post_button = get_post_field( '_custom_short', $post->ID );
						if ($post && $post->post_status === 'publish') {
							if($post_url != '') {
								if($post_button != '') {
									echo '{ title: "'.esc_html($post_button).'", href: "'.esc_url_raw($post_url).'" },';
								} else {
									echo '{ title: "'.esc_html(get_the_title($post)).'", href: "'.esc_url_raw($post_url).'" },';
								}
							} else {
								if($post_button != '') {
									echo '{ title: "'.esc_html($post_button).'", next: "'.esc_html($post_slug).'" },';
								} else {
									echo '{ title: "'.esc_html(get_the_title($post)).'", next: "'.esc_html($post_slug).'" },';
								}
							}
						}
					}
				}
			echo ']';
			echo '},';
		}	
	}
	
	// Reset post data
	wp_reset_postdata();
	echo '}</script>';
}
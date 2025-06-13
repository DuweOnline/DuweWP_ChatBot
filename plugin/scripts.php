<?php
//  COLOUR PICKER AND IMAGE UPLOADER / SELECT2 
add_action( 'admin_enqueue_scripts', function(){
	wp_enqueue_media();
	wp_enqueue_style('wp-color-picker');
	wp_enqueue_style('wp-toggle-check', plugin_dir_url(__DIR__) . '/toggle.css', false, '1.0.0');
	wp_enqueue_script('select-cpt-admin', plugin_dir_url(__DIR__) . '/admin.js', ['jquery', 'wp-color-picker'], '1.0.0', true);
	wp_enqueue_style( 'select2', plugin_dir_url(__DIR__) . '/select2.min.css', false, '1.0.0');
	wp_enqueue_script( 'select2', plugin_dir_url(__DIR__) . '/select2.min.js', ['jquery'], '1.0.0', true );

} );
add_action( 'admin_footer-post.php', function() {
	?><script>
	jQuery( function($){
		$( '#duwe_chatbot_posts' ).select2();
	} );
	</script><?php
} );

function duwe_scripts() {
	wp_enqueue_style('duwe-chatstyles', plugin_dir_url(__DIR__) . 'duwe-chatbot.css', false, '1.0.0');
};
add_action( 'wp_enqueue_scripts', 'duwe_scripts' );

add_image_size('chatbot_avatar', 100, 100, true);
add_image_size('chatbot_icon', 40, 40, true);
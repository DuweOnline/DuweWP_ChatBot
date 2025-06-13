<?php

/*
Plugin Name:  DuweWP ChatBot
Plugin URI:   https://duwe.co.uk
Description:  Adds a configurable "guided tour" ChatBot to a Wordpress site
Version:      1.0.0
Author:       Duwe Online
Author URI:   https://duwe.co.uk
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Options Page
include_once __DIR__ . '/options/options.php';

// CPT
include_once __DIR__ . '/cpt/cpt.php';

// META
include_once __DIR__ . '/meta/meta.php';

// SCRIPTS/STYLES
include_once __DIR__ . '/plugin/scripts.php';

// CONTENT
include_once __DIR__ . '/plugin/display.php';


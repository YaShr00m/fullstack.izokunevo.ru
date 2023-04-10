<?php
/**
 * Understrap Child Theme functions and definitions
 *
 * @package UnderstrapChild
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Removes the parent themes stylesheet and scripts from inc/enqueue.php
 */
function understrap_remove_scripts() {
	wp_dequeue_style( 'understrap-styles' );
	wp_deregister_style( 'understrap-styles' );

	wp_dequeue_script( 'understrap-scripts' );
	wp_deregister_script( 'understrap-scripts' );
}
add_action( 'wp_enqueue_scripts', 'understrap_remove_scripts', 20 );



/**
 * Enqueue our stylesheet and javascript file
 */
function theme_enqueue_styles() {

	// Get the theme data.
	$the_theme = wp_get_theme();

	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	// Grab asset urls.
	$theme_styles  = "/css/child-theme{$suffix}.css";
	$theme_scripts = "/js/child-theme{$suffix}.js";

	wp_enqueue_style( 'child-understrap-styles', get_stylesheet_directory_uri() . $theme_styles, array(), $the_theme->get( 'Version' ) );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'child-understrap-scripts', get_stylesheet_directory_uri() . $theme_scripts, array(), $the_theme->get( 'Version' ), true );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	if (!is_admin()) wp_enqueue_style( 'child-understrap-style', get_stylesheet_directory_uri() .'/style.css', array(), $the_theme->get( 'Version' ) );
	if ( !is_admin() && is_singular( 'real_estate' ) )   wp_enqueue_style( 'child-understrap-style-estate', get_stylesheet_directory_uri() .'/css/estate.css', array(), $the_theme->get( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );



/**
 * Load the child theme's text domain
 */
function add_child_theme_textdomain() {
	load_child_theme_textdomain( 'understrap-child', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'add_child_theme_textdomain' );



/**
 * Overrides the theme_mod to default to Bootstrap 5
 *
 * This function uses the `theme_mod_{$name}` hook and
 * can be duplicated to override other theme settings.
 *
 * @return string
 */
function understrap_default_bootstrap_version() {
	return 'bootstrap5';
}
add_filter( 'theme_mod_understrap_bootstrap_version', 'understrap_default_bootstrap_version', 20 );



/**
 * Loads javascript for showing customizer warning dialog.
 */
function understrap_child_customize_controls_js() {
	wp_enqueue_script(
		'understrap_child_customizer',
		get_stylesheet_directory_uri() . '/js/customizer-controls.js',
		array( 'customize-preview' ),
		'20130508',
		true
	);
}
add_action( 'customize_controls_enqueue_scripts', 'understrap_child_customize_controls_js' );

/**
 * Most of my code after this line
 */

add_image_size( 'post-item-thumb', 400, 260, true);
add_image_size( 'post-item-image', 734, 400, true);
add_image_size( 'post-item-gallery-thumb', 175, 105, true);

// custom post types
include ('inc/cpt/index.php');

// custom taxonomy
include ('inc/ct/index.php');

// metabox fields
include( 'inc/metabox/index.php' );

// ajax
include( 'inc/ajax/index.php' );

// подсчет кол-ва объектов в городе

function get_city_objects_num ($city_id) {
$query = $query = new WP_Query(
	array(
		'post_type'  => 'real_estate',
		'meta_query' => array(
			array(
				'key' => '_city',
				'value' => $city_id,
				'compare' => '='
			)
		)
	)
);
return $query->found_posts;
}

// Установка первого изображения галереи как featured
add_action('acf/submit_form', 'my_acf_form_submit', 10, 2);
function my_acf_form_submit($form, $post_id) {
	foreach ( get_field( 'photos', $post_id ) as $photo ) :
		update_post_meta( $post_id, '_thumbnail_id', $photo ['id'] );
		continue;
	endforeach;
}
// создание пользователя на основе email для правильного добавления объявления
// с возможностью редактирования в будущем
function my_user_add ($email, $password = false) {
	if (is_user_logged_in()) echo 'LOGGED_IN';
	// time () добавлена чтобы при разных доменах эл. почты и при одинаковой части до @ не совпадал
	$username = substr($email, 0, strrpos($email, '@')).'_'.time ();
	$password = wp_generate_password();

	$user = get_user_by( 'email', $email );
	if( ! $user ) {
		// Create the new user
		$user_id = wp_create_user( $username, $password, $email );
		if( is_wp_error( $user_id ) ) {
			// examine the error message
			$WP_Error = new WP_Error();
			echo 'SOME_ERROR';
			exit;
		}
		// Get current user object
		$user = get_user_by( 'id', $user_id );
	}
	else {
		echo 'USER_EXISTS';
	}
	$login = wp_signon(array('user_login' => $username, 'user_password' => $password), true );
	if ($login->data->ID) echo 'LOGIN_SUCCESS';
	return $user;

}

// make $_FILES cleaner
function reArrayFiles(&$file_post) {

	$file_ary = array();
	$file_count = count($file_post['name']);
	$file_keys = array_keys($file_post);

	for ($i=0; $i<$file_count; $i++) {
		foreach ($file_keys as $key) {
			$file_ary[$i][$key] = $file_post[$key][$i];
		}
	}

	return $file_ary;
}
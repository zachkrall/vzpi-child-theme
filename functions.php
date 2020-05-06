<?php
/**
 * Blocksy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Blocksy
 */

// if ( version_compare( PHP_VERSION, '5.7.0', '<' ) ) {
// 	require get_template_directory() . '/inc/php-fallback.php';
// 	return;
// }
//
// require get_template_directory() . '/inc/init.php';


// add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
//
// function my_theme_enqueue_styles() {
//     $parent_style = 'blocksy';
//     wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
//     wp_enqueue_style( 'blocksy-child',
//         get_stylesheet_directory_uri() . '/style.css',
//         array( $parent_style ),
//         wp_get_theme()->get('Version')
//     );
// }

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {

    $parent_style = 'blocksy-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}

?>

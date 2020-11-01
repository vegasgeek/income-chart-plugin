<?php
/**
 * Plugin Name: Income Chart
 * Plugin URI: https://vegasgeek.com
 * Description: Messing around with chartsjs to display a chart of monthly incomes.
 * Author: John Hawkins
 * Version: 1.0
 * Author URI: https://vegasgeek.com
 * Text Domain: vgs
 *
 * @package charts
 */

require 'cpt.php';
require 'shortcode.php';

/**
 * Enqueue charts script.
 *
 * @return void
 */
function vgs_enqueue_charts_script() {
	wp_enqueue_script( 'chartsjs', 'https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js', array(), '2.9.3', false );
}
add_action( 'wp_enqueue_scripts', 'vgs_enqueue_charts_script' );

/**
 * Redirect ACF to save JSON to our plugin folder.
 *
 * @param string $path Path.
 */
function vgs_acf_json_save_point( $path ) {
	// update path.
	$path = plugin_dir_path( __FILE__ ) . '/acf-json';

	// return.
	return $path;

}
add_filter( 'acf/settings/save_json', 'vgs_acf_json_save_point' );

/**
 * Tell ACF to load JSON files from our plugin folder
 *
 * @param string $paths Paths.
 */
function vgs_acf_json_load_point( $paths ) {
	// remove original path (optional).
	unset( $paths[0] );

	// append path.
	$paths[] = plugin_dir_path( __FILE__ ) . '/acf-json';

	return $paths;
}
add_filter( 'acf/settings/load_json', 'vgs_acf_json_load_point' );

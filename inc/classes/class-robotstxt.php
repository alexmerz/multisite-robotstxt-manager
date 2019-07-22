<?php
/**
 * Manager Class
 *
 * @package    WordPress
 * @subpackage Plugin
 * @author     Chris W. <chrisw@null.net>
 * @license    GNU GPLv3
 * @link       /LICENSE
 */

namespace MsRobotstxtManager;

if ( false === defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display Robots.txt File If Called
 */
final class Robotstxt {
	/**
	 * Check File Being Called
	 */
	public function __construct() {
		if ( false !== strpos( filter_input( INPUT_SERVER, 'REQUEST_URI', FILTER_SANITIZE_URL ), 'robots.txt' ) ) {
			$this->robotstxt();
		}
	}


	/**
	 * Display Robots.txt File
	 */
	private function robotstxt() {
		/*
		 * Retrieves an option value based on an option name.
		 * https://developer.wordpress.org/reference/functions/get_option/
		 */
		$website_option = get_option( MS_ROBOTSTXT_MANAGER_PLUGIN_NAME );
		$robotstxt_file = '';

		// Ignore If Disabled.
		if ( true !== empty( $website_option['disable'] ) ) {
			return;
		}

		// Robots.txt Set.
		if ( true !== empty( $website_option['robotstxt'] ) && true === empty( $robotstxt_file ) ) {
			$robotstxt_file = $website_option['robotstxt'];
		}

		// Display Robots.txt File.
		if ( true !== empty( $robotstxt_file ) ) {
			header( 'Status: 200 OK', true, 200 );
			header( 'Content-type: text/plain; charset=' . get_bloginfo( 'charset' ) );

			/**
			 * Fires when displaying the robots.txt file.
			 *
			 * @since 2.1.0
			 */
			do_action( 'do_robotstxt' );

			/*
			 * Escaping for HTML blocks.
			 * https://developer.wordpress.org/reference/functions/esc_html/
			 */
			echo esc_html( $robotstxt_file );
			exit;
		}
	}
}//end class
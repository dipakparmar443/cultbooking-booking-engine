<?php
/**
 * Plugin Name:     CultBooking Hotel Booking Engine & Channel Manager
 * Contributors:    cultbooking
 * Plugin URI:      https://wordpress.org/plugins/cultbooking-booking-engine/
 * Description:     Top-performing Booking Engine & Channel Manager. Booking button for all property types. Direct bookings from your website.
 * Author:          CultBooking
 * Author URI:      https://www.cultbooking.com/
 * Donate link:     https://www.cultbooking.com/
 * Text Domain:     cultbooking-booking-engine
 * Domain Path:     /languages
 * Version:         1.1
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * @package         CultBooking_Hotel_Booking_Engine_Channel_Manager
 * @ to-do : cultbooking-booking-engine
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'CHHECM_URL', plugin_dir_url( __FILE__ ) );

define( 'CHHECM_BASENAME', plugin_basename( __FILE__ ) );

require_once 'includes/class-' . basename( __FILE__ );

/**
 * Plugin textdomain.
 */
function chbecm_textdomain() {
	load_plugin_textdomain( 'cultbooking-booking-engine', false, basename( dirname( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'chbecm_textdomain' );

/**
 * Plugin activation.
 */
function chbecm_activation() {
	// If check pro plugin activated or not.
}
register_activation_hook( __FILE__, 'chbecm_activation' );

/**
 * Plugin deactivation.
 */
function chbecm_deactivation() {
	// Deactivation code here.	
}
register_deactivation_hook( __FILE__, 'chbecm_deactivation' );

/**
 * Initialization class.
 */
function chbecm_init() {
	global $cultbooking_booking_engine;
	$cultbooking_booking_engine = new CultBooking_Hotel_Booking_Engine_Channel_Manager();
}
add_action( 'plugins_loaded', 'chbecm_init' );
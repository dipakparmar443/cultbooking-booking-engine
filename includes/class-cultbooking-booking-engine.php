<?php
/**
 * Class for Custom Text Field support.
 *
 * @package WordPress
 */

// If check class exists.
if ( ! class_exists( 'CultBooking_Hotel_Booking_Engine_Channel_Manager' ) ) {

	/**
	 * Declare class.
	 */
	class CultBooking_Hotel_Booking_Engine_Channel_Manager {

		/**
		 * Calling construct.
		 */
		public $plugin_name;//public variable that will store the plugin name

		public function __construct() {
			
			$this->plugin_name = CHHECM_BASENAME; //the plugin name stored in $plugin_name

			add_filter( "plugin_action_links_$this->plugin_name", array( $this, 'chbecm_settings_link' ) );
			add_shortcode('CultBooking', array( $this, 'chbecm_iframe_plugin_add_shortcode_fun'));

			add_action('admin_enqueue_scripts', array( $this, 'chbecm_enqueue_admin' ) );

			add_action('wp_enqueue_scripts', array($this,'chbecm_enqueue_front') );

			//check if the user is who they claim to be
			if( current_user_can( 'administrator' ) ){
				 add_action('admin_menu', array( $this, 'chbecm_plugin_menu_setup')); 
			} 
			
		}

		public function chbecm_enqueue_front(){
			wp_enqueue_script( 'chbecm-iframe-resize-js', CHHECM_URL . '/assets/js/iframeResizer.min.js', array( 'jquery' ) );
			wp_enqueue_script ('chbecm-iframe-js', CHHECM_URL . '/assets/js/iframe.js' );
		}

		public function chbecm_enqueue_admin(){

			wp_register_style('chbecm-style', CHHECM_URL . '/assets/css/chbecm-style.css');
			wp_enqueue_style('chbecm-style');

			wp_register_style('chbecm-material-icons', '//fonts.googleapis.com/icon?family=Material+Icons');
			wp_enqueue_style('chbecm-material-icons');

			wp_enqueue_style( 'wp-color-picker');
			
			wp_enqueue_script( 'chbecm-wp-color-picker', CHHECM_URL . '/assets/js/chbecm-init.js', array( 'wp-color-picker' ), false, true  );
		}

		public function chbecm_iframe_plugin_add_shortcode_fun(){

			$chbecm_hotel_id = get_option('chbecm_hotel_id');

			$html = '';

			if(isset($chbecm_hotel_id)) {

				$html .= '<style>iframe.chbecm-booking-engine{width: 100% !important;}</style><iframe class="chbecm-booking-engine" width="100%" style="width:100% !important;" frameborder="0" src="https://neo.cultbooking.com/CPC/?agentcode=58078&hotelcode=' . $chbecm_hotel_id . '" autosize="true" ></iframe>' ;
			}else {
				$html .= '<p>' . esc_html('Please insert your hotel ID in CultBooking Booking Engine Plugin Options setting.') . '</p>';
			}
			return $html;
		}

		//add custom settings link
		public function chbecm_settings_link( $links ) {
			$settings_link = '<a href="admin.php?page=cultbooking-booking-engine">' . esc_html( 'Settings' ) . '</a>';
			array_push( $links, $settings_link ); //inject 
			return $links;
		}

		public function chbecm_plugin_menu_setup( ) {
			/**
		     * Check the current section is what we want
		     **/
			add_menu_page( 'Booking Engine', 'Booking Engine', 'manage_options', 'cultbooking-booking-engine', array( $this, 'chbecm_admin_index'), CHHECM_URL. '/assets/images/ms-icon-150x150.png' );
		}	
		
		/**
		 * Add Label settings
		 */
		//admin form
		public function chbecm_admin_index() {
			
			if( isset( $_POST["chbecm_hotel_id"] ) && ! empty( $_POST["chbecm_hotel_id"] ) ) { 				
				update_option('chbecm_hotel_id', sanitize_text_field( $_POST["chbecm_hotel_id"] ) );
				?>
				<div id="chbecm-error-chbecm_updated" class="notice notice-success settings-error is-dismissible"> 
					<p><strong><?php echo __( 'Settings saved.', 'cultbooking-booking-engine'); ?></strong></p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>
				</div>
				<?php				
			}elseif( isset( $_POST["chbecm_hotel_id"] ) && empty( $_POST["chbecm_hotel_id"] )){
				update_option('chbecm_hotel_id', sanitize_text_field( $_POST["chbecm_hotel_id"] ) );
				?>
				<div id="chbecm-error-chbecm_updated" class="notice notice-error settings-error is-dismissible"> 
					<p><strong><?php echo __( 'Please enter valid Hotel ID.', 'cultbooking-booking-engine'); ?></strong></p>
					<button type="button" class="notice-dismiss"><span class="screen-reader-text"></span></button>
				</div>
				<?php
			}
			
			?>
          
			<!-- html admin form -->
			<div class="wrap chbecm_main_wrap">
			    <h1><?php echo __( 'CultBooking Booking Engine Plugin Options', 'cultbooking-booking-engine' ); ?></h1>

			    <form method="post" action="">
			        <table class="form-table">
			            <tbody>
			                <tr>
			                    <th><?php echo __( 'Hotel ID', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <fieldset>
			                            <input class="regular-text" type="text" name="chbecm_hotel_id" pattern="^[0-9]*" value="<?php echo get_option('chbecm_hotel_id'); ?>" />
			                        </fieldset>
			                    </td>
			                </tr>
			              	<tr>
			                    <th scope="row"><?php echo __( 'Get Hotel ID', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">account_circle</span>
			                            <span><a href='<?php echo esc_url_raw( "https://admin.cultbooking.com/register" ); ?>' target="_blank"> <?php echo __( 'Need help?', 'cultbooking-booking-engine' ); ?></a>
			                            <?php echo __( 'Don\'t have a Hotel Id?', 'cultbooking-booking-engine' ); ?>
			                            <a href='<?php echo esc_url_raw( "https://admin.cultbooking.com/register" ); ?>' target="_blank"><?php echo __( 'Register here', 'cultbooking-booking-engine' ); ?></a></span>
			                        </p>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php echo __( 'Booking Engine', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">content_copy</span>
			                            <span><?php echo __( 'Copy this shortcode <code>[CultBooking]</code> and paste it anywhere in the page content, the booking engine will show up.', 'chbecm' ); ?></span>
			                        </p>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php echo __( 'Manage Bookings', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">calendar_today</span> 
			                            <span><?php echo __( 'To manage all the bookings received, please visit ', 'cultbooking-booking-engine' ); ?> 
			                            <a href='<?php echo esc_url_raw( "https://admin.cultbooking.com/reservations" ); ?>' target="_blank"><?php echo __( 'CultBooking Reservations Dashboard →', 'cultbooking-booking-engine' ); ?></a></span>
			                        </p>
			                    </td>
			                </tr>
			                <tr>
			                    <th scope="row"><?php echo __( 'Manage Price', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">calendar_view_day</span> 
			                            <span><?php echo __( 'To manage room price and calendar availability, please visit', 'cultbooking-booking-engine' ); ?>
			                            <a href='<?php echo esc_url_raw( "https://admin.cultbooking.com/calendar" ); ?>' target="_blank"><?php echo __( 'CultBooking Calendar →', 'cultbooking-booking-engine' ); ?></a></span>
			                        </p>
			                    </td>
			                </tr>
			                <tr>            
			                    <th scope="row"><?php echo __( 'Extranet', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">settings_system_daydream</span> 
			                            <span><?php echo __( 'To manage users, promotions, etc., please visit', 'cultbooking-booking-engine' ); ?>
			                            <a href='<?php echo esc_url_raw( "https://admin.cultbooking.com/calendar" ); ?>' target="_blank"><?php echo __( 'CultBooking Extranet →', 'cultbooking-booking-engine' ); ?> </a></span>
			                        </p>
			                    </td>
			                </tr>
			                
			                <tr>
			                    <th scope="row"><?php echo __( 'For More Information', 'cultbooking-booking-engine' ); ?></th>
			                    <td>
			                        <p>
			                            <span class="material-icons">web</span>
			                            <span><a href='<?php echo esc_url_raw( "https://www.cultbooking.com/en/" ); ?>' target="_blank"> <?php echo __( 'Visit CultBooking Official Website', 'cultbooking-booking-engine' ); ?></a></span>
			                            
			                        </p>
			                    </td>
			                </tr>			                
			            </tbody>
			        </table>
			        <?php 
			        if( current_user_can( 'administrator' ) ){
			            submit_button( 'Save Settings', 'primary' ); 
			        } 
			        ?>
			    </form>
			</div>
			<?php
		}
		
	}
}

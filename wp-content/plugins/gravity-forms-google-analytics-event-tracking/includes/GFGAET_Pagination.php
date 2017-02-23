<?php
class GFGAET_Pagination {
	/**
	 * Holds the class instance.
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private static $instance = null;
	
	/**
	 * Retrieve a class instance.
	 *
	 * @since 2.0.0
	 */
	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	} //end get_instance
	
	/**
	 * Class constructor.
	 *
	 * @since 2.0.0
	 */
	private function __construct() {

	}
	
	/**
	 * Send pagination events.
	 *
	 * @since 2.0.0
	 *
	 * @param array $form                The form arguments
	 * @param int   @source_page_number  The original page number
	 * @param int   $current_page_number The new page number
	 */
	public function paginate( $form, $source_page_number, $current_page_number ) {
		require_once( 'vendor/ga-mp/src/Racecore/GATracking/Autoloader.php');
		Racecore\GATracking\Autoloader::register( dirname(__FILE__) . '/vendor/ga-mp/src/' );
		
		$ua_code = GFGAET::get_ua_code();
		if ( false !== $ua_code ) {
			$event = new \Racecore\GATracking\Tracking\Event();

			/**
			 * Filter: gform_pagination_event_category
			 *
			 * Filter the event category dynamically
			 *
			 * @since 2.0.0
			 *
			 * @param string $category              Event Category
			 * @param array  $form                  Gravity Form form array
			 * @param int    $source_page_number    Source page number
			 * @param int    $current_page_number   Current Page Number
			 */
			$event_category = apply_filters( 'gform_pagination_event_category', 'form', $form, $source_page_number, $current_page_number );

			/**
			 * Filter: gform_pagination_event_action
			 *
			 * Filter the event action dynamically
			 *
			 * @since 2.0.0
			 *
			 * @param string $action                Event Action
			 * @param array  $form                  Gravity Form form array
			 * @param int    $source_page_number    Source page number
			 * @param int    $current_page_number   Current Page Number
			 */
			$event_action = apply_filters( 'gform_pagination_event_action', 'pagination', $form, $source_page_number, $current_page_number );

			/**
			 * Filter: gform_pagination_event_label
			 *
			 * Filter the event label dynamically
			 *
			 * @since 2.0.0
			 *
			 * @param string $label                 Event Label
			 * @param array  $form                  Gravity Form form array
			 * @param int    $source_page_number    Source page number
			 * @param int    $current_page_number   Current Page Number
			 */
			$event_label = sprintf( '%s::%d::%d', esc_html( $form['title'] ), absint( $source_page_number ), absint( $current_page_number ) );
			$event_label = apply_filters( 'gform_pagination_event_label', $event_label, $form, $source_page_number, $current_page_number );
			
			// Set the event meta
			$event->setEventCategory( $event_category );
			$event->setEventAction( $event_action );
			$event->setEventLabel( $event_label );
			
			if ( GFGAET::is_ga_only() ) {
				?>
				<script>
				if ( typeof window.parent.ga == 'undefined' ) {
					if ( typeof window.parent.__gaTracker != 'undefined' ) {
						window.parent.ga = window.parent.__gaTracker;
					}
				}
				if ( typeof window.parent.ga != 'undefined' ) {

					// Try to get original UA code from third-party plugins or tag manager
					var default_ua_code = null;
					window.parent.ga(function(tracker) {
						default_ua_code = tracker.get('trackingId');
					});
					
					// If UA code matches, use that tracker
					if ( default_ua_code == '<?php echo esc_js( $ua_code ); ?>' ) {
						window.parent.ga( 'send', 'event', '<?php echo esc_js( $event_category ); ?>', '<?php echo esc_js( $event_action ); ?>', '<?php echo esc_js( $event_label ); ?>' );
					} else {
						// UA code doesn't match, use another tracker
						window.parent.ga( 'create', '<?php echo esc_js( $ua_code ); ?>', 'auto', 'GTGAET_Tracker' );
						window.parent.ga( 'GTGAET_Tracker.send', 'event', '<?php echo esc_js( $event_category );?>', '<?php echo esc_js( $event_action ); ?>', '<?php echo esc_js( $event_label ); ?>' );
					}
				}
				</script>
				<?php
				return;
			} else if ( GFGAET::is_gtm_only() ) {
				?>
				<script>
				if ( typeof( window.parent.dataLayer ) != 'undefined' ) {
			    	window.parent.dataLayer.push({'event': 'GFTrackEvent',
						'GFTrackCategory':'<?php echo esc_js( $event_category ); ?>',
						'GFTrackAction':'<?php echo esc_js( $event_action ); ?>',
						'GFTrackLabel':'<?php echo esc_js( $event_label ); ?>'
						});
				}
				</script>
				<?php
				return;
			}
			
			// Submit the event
			$tracking = new \Racecore\GATracking\GATracking( $ua_code );
			try {
				$tracking->sendTracking( $event );
			} catch (Exception $e) {
				error_log( $e->getMessage() . ' in ' . get_class( $e ) );
			}
		}
		
	}
}
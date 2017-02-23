<?php
/**
 * Gravity forms event tracking
 *
 * @package   Gravity_Forms_Event_Tracking
 * @author    Nathan Marks <nmarks@nvisionsolutions.ca>
 * @license   GPL-2.0+
 * @link      http://www.nvisionsolutions.ca
 * @copyright 2014 Nathan Marks
 */

GFForms::include_feed_addon_framework();

class GFGAET_Submission_Feeds extends GFFeedAddOn {

	protected $_version = "2.0.0";
	protected $_min_gravityforms_version = "1.8.20";
	protected $_slug = "gravity-forms-event-tracking";
	protected $_path = "gravity-forms-google-analytics-event-tracking/gravity-forms-event-tracking.php";
	protected $_full_path = __FILE__;
	protected $_title = "Gravity Forms Google Analytics Event Tracking";
	protected $_short_title = "Submission Tracking";

	// Members plugin integration
	protected $_capabilities = array( 'gravityforms_event_tracking', 'gravityforms_event_tracking_uninstall' );

	// Permissions
	protected $_capabilities_settings_page = 'gravityforms_event_tracking';
	protected $_capabilities_form_settings = 'gravityforms_event_tracking';
	protected $_capabilities_uninstall = 'gravityforms_event_tracking_uninstall';

	public $ua_id = false;

	private static $_instance = null;

	public static function get_instance() {
		if ( self::$_instance == null ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Overriding init function to change the load_plugin_textdomain call.
	 * See comment above for explanation.
	 */
	public function init() {
		parent::init();
	}

	/**
	 * Public facing init
	 * 
	 * @since 2.0.0
	 */
	public function init_frontend() {

		parent::init_frontend();
		
		require_once( 'vendor/ga-mp/src/Racecore/GATracking/Autoloader.php');
		Racecore\GATracking\Autoloader::register( dirname(__FILE__) . '/vendor/ga-mp/src/' );
		
		// IPN hook for paypal standard!
		if ( class_exists( 'GFPayPal' ) ) {
			add_action( 'gform_paypal_post_ipn', array( $this, 'paypal_track_form_post_ipn' ), 10, 2 );
		}

	}

	/**
	 * Process the feed!
	 * @param  array $feed  feed data and settings
	 * @param  array $entry gf entry object
	 * @param  array $form  gf form data
	 */
	public function process_feed( $feed, $entry, $form ) {

		$paypal_feeds = $this->get_feeds_by_slug( 'gravityformspaypal', $form['id'] );
		$has_paypal_feed = false;
		
		foreach ( $paypal_feeds as $paypal_feed ){
			if ( $paypal_feed['is_active'] && $this->is_feed_condition_met( $paypal_feed, $form, $entry ) ){
				$has_paypal_feed = true;
				break;
			}
		}

		$ga_event_data = $this->get_event_data( $feed, $entry, $form );

		if ( $has_paypal_feed ) {
			gform_update_meta( $entry['id'], 'ga_event_data', maybe_serialize( $ga_event_data ) );
		}
		else {
			$this->track_form_after_submission( $entry, $form, $ga_event_data );
		}
	}

	/**
	 * Load UA Settings
	 * 
	 * @since 1.4.0
	 * @return bool Returns true if UA ID is loaded, false otherwise
	 */
	private function load_ua_settings() {

		$this->ua_id = $ua_id = GFGAET::get_ua_code();
		
		if ( false !== $this->ua_id ) {
			return true;
		}
		return false;
	}
	
	/**
	 * Load UA Settings
	 * 
	 * @since 1.4.0
	 * @return bool Returns true if UA ID is loaded, false otherwise
	 */
	 private function get_ga_id() {
        $this->load_ua_settings();
        if ( $this->ua_id == false ) {
            return '';
        } else {
            return $this->ua_id;   
        }
     }

	/**
	 * Get data required for processing
	 * @param  array $feed  feed
	 * @param  array $entry GF Entry object
	 * @param  array $form  GF Form object
	 */
	private function get_event_data( $feed, $entry, $form ) {
		global $post;

		// Paypal will need this cookie for the IPN
		$ga_cookie = isset( $_COOKIE['_ga'] ) ? $_COOKIE['_ga'] : '';

		// Location
		$document_location = 'http' . ( isset( $_SERVER['HTTPS'] ) ? 's' : '' ) . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SERVER['REQUEST_URI'];

		// Title
		$document_title = isset( $post ) && get_the_title( $post ) ? get_the_title( $post ) : 'no title';

		// Store everything we need for later
		$ga_event_data = array(
			'feed_id' => $feed['id'],
			'entry_id' => $entry['id'],
			'ga_cookie' => $ga_cookie,
			'document_location' => $document_location,
			'document_title' => $document_title,
			'gaEventUA' => $this->get_event_var( 'gaEventUA', $feed, $entry, $form ),
			'gaEventCategory' => $this->get_event_var( 'gaEventCategory', $feed, $entry, $form ),
			'gaEventAction' => $this->get_event_var( 'gaEventAction', $feed, $entry, $form ),
			'gaEventLabel' => $this->get_event_var( 'gaEventLabel', $feed, $entry, $form ),
			'gaEventValue' => $this->get_event_var( 'gaEventValue', $feed, $entry, $form ),
		);

		return $ga_event_data;
	}

	/**
	 * Get our event vars
	 */
	private function get_event_var( $var, $feed, $entry, $form ) {

		if ( isset( $feed['meta'][ $var ] ) && ! empty( $feed['meta'][ $var ] ) ) {
			return $feed['meta'][ $var ];
		}
		else {
			switch ( $var ) {
				case 'gaEventCategory':
					return 'Forms';

				case 'gaEventAction':
					return 'Submission';

				case 'gaEventLabel':
					return 'Form: {form_title} ID: {form_id}';

				case 'gaEventValue':
					return false;

				default:
					return false;
			}
		}

	}

	/**
	 * Handle the form after submission before sending to the event push
	 * 
	 * @since 1.4.0
	 * @param array $entry Gravity Forms entry object
	 * @param array $form Gravity Forms form object
	 */
	private function track_form_after_submission( $entry, $form, $ga_event_data ) {

		// Try to get payment amount
		// This needs to go here in case something changes with the amount
		if ( ! $ga_event_data['gaEventValue'] ) {
			$ga_event_data['gaEventValue'] = $this->get_event_value( $entry, $form );
		}

		$event_vars = array( 'gaEventUA', 'gaEventCategory', 'gaEventAction', 'gaEventLabel', 'gaEventValue' );

		foreach ( $event_vars as $var ) {
			if ( $ga_event_data[ $var ] ) {
				$ga_event_data[ $var ] = GFCommon::replace_variables( $ga_event_data[ $var ], $form, $entry, false, false, true, 'text' );
			}
		}
		

		// Push the event to google
		$this->push_event( $entry, $form, $ga_event_data );
	}

	/**
	 * Handle the IPN response for pushing the event
	 * 
	 * @since 1.4.0
	 * @param array $post_object global post array from the IPN
	 * @param array $entry Gravity Forms entry object
	 */
	public function paypal_track_form_post_ipn( $post_object, $entry ) {
		// Check if the payment was completed before continuing
		if ( strtolower( $entry['payment_status'] ) != 'paid' ) {
			return;
		}

		$form = GFFormsModel::get_form_meta( $entry['form_id'] );

		$ga_event_data = maybe_unserialize( gform_get_meta( $entry['id'], 'ga_event_data' ) );

		// Override this coming from paypal IPN
		$_COOKIE['_ga'] = $ga_event_data['ga_cookie'];

		// Push the event to google
		$this->push_event( $entry, $form, $ga_event_data );
	}

	/**
	 * Push the Google Analytics Event!
	 * 
	 * @since 1.4.0
	 * @param array $event Gravity Forms event object
	 * @param array $form Gravity Forms form object
	 */
	private function push_event( $entry, $form, $ga_event_data ) {

		//Get all analytics codes to send
		$google_analytics_codes = $this->get_ua_codes( $ga_event_data[ 'gaEventUA' ], $this->get_ga_id() );

		/**
		* Filter: gform_ua_ids
		*
		* Filter all outgoing UA IDs to send events to
		*
		* @since 1.6.5
		*
		* @param array  $google_analytics_codes UA codes
		* @param object $form Gravity Form form object
		* @param object $entry Gravity Form Entry Object
		*/
		$google_analytics_codes = apply_filters( 'gform_ua_ids', $google_analytics_codes, $form, $entry );

        if ( !is_array( $google_analytics_codes ) || empty( $google_analytics_codes ) ) return; 

		$event = new \Racecore\GATracking\Tracking\Event();

		// Set some defaults
		$event->setDocumentPath( str_replace( home_url(), '', $entry[ 'source_url' ] ) );
		$event->setDocumentLocation( $ga_event_data['document_location'] );
		$event->setDocumentTitle( $ga_event_data['document_title'] );
		
		// Set our event object variables
		/**
		 * Filter: gform_event_category
		 *
		 * Filter the event category dynamically
		 *
		 * @since 1.6.5
		 *
		 * @param string $category Event Category
		 * @param object $form     Gravity Form form object
		 * @param object $entry    Gravity Form Entry Object
		 */
		$event_category = apply_filters( 'gform_event_category', $ga_event_data['gaEventCategory'], $form, $entry );
		$event->setEventCategory( $event_category );

		/**
		 * Filter: gform_event_action
		 *
		 * Filter the event action dynamically
		 *
		 * @since 1.6.5
		 *
		 * @param string $action Event Action
		 * @param object $form   Gravity Form form object
		 * @param object $entry  Gravity Form Entry Object
		 */
		$event_action = apply_filters( 'gform_event_action', $ga_event_data['gaEventAction'], $form, $entry );
		$event->setEventAction( $event_action );

		/**
		 * Filter: gform_event_label
		 *
		 * Filter the event label dynamically
		 *
		 * @since 1.6.5
		 *
		 * @param string $label Event Label
		 * @param object $form  Gravity Form form object
		 * @param object $entry Gravity Form Entry Object
		 */
		$event_label = apply_filters( 'gform_event_label', $ga_event_data['gaEventLabel'], $form, $entry );
		$event->setEventLabel( $event_label );

		/**
		 * Filter: gform_event_value
		 *
		 * Filter the event value dynamically
		 *
		 * @since 1.6.5
		 *
		 * @param object $form Gravity Form form object
		 * @param object $entry Gravity Form Entry Object
		 */
		$event_value = apply_filters( 'gform_event_value', $ga_event_data['gaEventValue'], $form, $entry );
		if ( $event_value ) {
			// Event value must be a valid float!
			$event_value = GFCommon::to_number( $event_value );
			$event->setEventValue( $event_value );
		}
		
		$feed_id = absint( $ga_event_data[ 'feed_id' ] );
		$entry_id = $entry['id'];
		
		if ( GFGAET::is_ga_only() ) {
			$count = 1;
			?>
			<script>
			<?php
			foreach( $google_analytics_codes as $ua_code ) {
				?>
				var feed_submission = sessionStorage.getItem('feed_<?php echo absint( $feed_id ); ?>_entry_<?php echo absint( $entry[ 'id' ] ); ?>');
				if ( null == feed_submission ) {
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
							window.parent.ga( 'create', '<?php echo esc_js( $ua_code ); ?>', 'auto', 'GTGAET_Tracker<?php echo absint( $count ); ?>' );
							window.parent.ga( 'GTGAET_Tracker<?php echo absint( $count ); ?>.send', 'event', '<?php echo esc_js( $event_category );?>', '<?php echo esc_js( $event_action ); ?>', '<?php echo esc_js( $event_label ); ?>' );
						}
						
						sessionStorage.setItem('feed_<?php echo absint( $feed_id ); ?>_entry_<?php echo absint( $entry[ 'id' ] ); ?>', true );
					}
				}
				
				<?php
				$count += 1;
			}	
			?>
			</script>
			<?php
			return;
		} else if ( GFGAET::is_gtm_only() ) {
			?>
			<script>
			var form_submission = sessionStorage.getItem('feed_<?php echo absint( $feed_id ); ?>_entry_<?php echo absint( $entry[ 'id' ] ); ?>');
			if ( null == form_submission ) {
				if ( typeof( window.parent.dataLayer ) != 'undefined' ) {
			    	window.parent.dataLayer.push({'event': 'GFTrackEvent',
						'GFTrackCategory':'<?php echo esc_js( $event_category ); ?>',
						'GFTrackAction':'<?php echo esc_js( $event_action ); ?>',
						'GFTrackLabel':'<?php echo esc_js( $event_label ); ?>',
						'GFEntryData':<?php echo json_encode( $entry ); ?>
						});
					sessionStorage.setItem("feed_<?php echo absint( $feed_id ); ?>_entry_<?php echo absint( $entry[ 'id' ] ); ?>", "true");
				}
			}
			</script>
			<?php
			return;
		}

		//Push out the event to each UA code
		foreach( $google_analytics_codes as $ua_code ) {
			
    		$tracking = new \Racecore\GATracking\GATracking( $ua_code );
    		try {
    		    $tracking->sendTracking( $event );
    		} catch (Exception $e) {
    		    error_log( $e->getMessage() . ' in ' . get_class( $e ) );
    		}
        }// Init tracking object
	}

	/**
	 * Get the event value for payment entries
	 * 
	 * @since 1.4.0
	 * @param array $event Gravity Forms event object
	 * @return string/boolean Event value or false if not a payment form
	 */
	private function get_event_value( $entry ) {
		$value = rgar( $entry, 'payment_amount' );

		if ( ! empty( $value ) && intval( $value ) ) {
			return intval( $value );
		}

		return false;
	}

	//---------- Form Settings Pages --------------------------

	/**
	 * Form settings page title
	 * 
	 * @since 1.5.0
	 * @return string Form Settings Title
	 */
	public function feed_settings_title() {
		return __( 'Submission Tracking Settings', 'gravity-forms-google-analytics-event-tracking' );
	}
	
	public function maybe_save_feed_settings( $feed_id, $form_id ) {
		if ( ! rgpost( 'gform-settings-save' ) ) {
			return $feed_id;
		}

		check_admin_referer( $this->_slug . '_save_settings', '_' . $this->_slug . '_save_settings_nonce' );

		if ( ! $this->current_user_can_any( $this->_capabilities_form_settings ) ) {
			GFCommon::add_error_message( esc_html__( "You don't have sufficient permissions to update the form settings.", 'gravityforms' ) );
			return $feed_id;
		}

		// store a copy of the previous settings for cases where action would only happen if value has changed
		$feed = $this->get_feed( $feed_id );
		$this->set_previous_settings( $feed['meta'] );

		$settings = $this->get_posted_settings();
		$sections = $this->get_feed_settings_fields();
		$settings = $this->trim_conditional_logic_vales( $settings, $form_id );

		$is_valid = $this->validate_settings( $sections, $settings );
		$result   = false;
		
		//Check for a valid UA code
		$feed_ua_code = isset( $settings[ 'gaEventUA' ] ) ? $settings[ 'gaEventUA' ] : '';
		$ua_codes = $this->get_ua_codes( $feed_ua_code, $this->get_ga_id() );
		if ( empty( $ua_codes ) ) {
			GFCommon::add_error_message( __( 'You must set a UA code for event tracking to work.', 'gravity-forms-google-analytics-event-tracking' ) );
			return $feed_id;
		} 
		
		
		if ( $is_valid ) {
			$settings = $this->filter_settings( $sections, $settings );
			$feed_id = $this->save_feed_settings( $feed_id, $form_id, $settings );
			if ( $feed_id ) {
				GFCommon::add_message( $this->get_save_success_message( $sections ) );
			} else {
				GFCommon::add_error_message( $this->get_save_error_message( $sections ) );
			}
		} else {
			GFCommon::add_error_message( $this->get_save_error_message( $sections ) );
		}

		return $feed_id;
	}
	
	/**
	 * Return Google Analytics GA Codes
	 * 
	 * @since 1.7.0
	 * @return array Array of GA codes
	 */
	private function get_ua_codes( $feed_ua, $settings_ua ) {
		$google_analytics_codes = array();
        if ( !empty( $feed_ua ) ) {
            $ga_ua = explode( ',', $feed_ua );
            if ( is_array( $ga_ua ) ) {
                foreach( $ga_ua as &$value ) {
                    $value = trim( $value );   
                } 
            }
            $google_analytics_codes = $ga_ua;
        }
        if( $settings_ua ) {
            $google_analytics_codes[] = $settings_ua;
        }
        $google_analytics_codes = array_unique( $google_analytics_codes );
        return $google_analytics_codes;
	}

	/**
	 * Form settings fields
	 * 
	 * @since 1.5.0
	 * @return array Array of form settings
	 */
	public function feed_settings_fields() {
    	$ga_id_placeholder = $this->get_ga_id();
		return array(
			array(
				"title"  => __( 'Feed Settings', 'gravity-forms-google-analytics-event-tracking' ),
				"fields" => array(
					array(
						'label'    => __( 'Feed Name', 'gravity-forms-google-analytics-event-tracking' ),
						'type'     => 'text',
						'name'     => 'feedName',
						'class'    => 'medium',
						'required' => true,
						'tooltip'  => '<h6>' . __( 'Feed Name', 'gravity-forms-google-analytics-event-tracking' ) . '</h6>' . __( 'Enter a feed name to uniquely identify this setup.', 'gravity-forms-google-analytics-event-tracking' )
					)
				),
			),
			array(
				"title"  => __( 'Submission Tracking Settings', 'gravity-forms-google-analytics-event-tracking' ),
				"fields" => array(
					array(
						"label"   => "",
						"type"    => "instruction_field",
						"name"    => "instructions"
					),
					array(
						"label"   => __( 'Event UA Code', 'gravity-forms-google-analytics-event-tracking' ),
						"type"    => "text",
						"name"    => "gaEventUA",
						"class"   => "medium",
						"tooltip" => sprintf( '<h6>%s</h6>%s', __( 'Google Analytics UA Code', 'gravity-forms-google-analytics-event-tracking' ), __( 'Leave empty to use global GA Code. You can enter multiple UA codes as long as they are comma separated.', 'gravity-forms-google-analytics-event-tracking' ) ),
						"placeholder" => $ga_id_placeholder,
					),
					array(
						"label"   => __( 'Event Category', 'gravity-forms-google-analytics-event-tracking' ),
						"type"    => "text",
						"name"    => "gaEventCategory",
						"class"   => "medium merge-tag-support mt-position-right",
						"tooltip" => sprintf( '<h6>%s</h6>%s', __( 'Event Category', 'gravity-forms-google-analytics-event-tracking' ), __( 'Enter your Google Analytics event category', 'gravity-forms-google-analytics-event-tracking' ) ),
					),
					array(
						"label"   => __( 'Event Action', 'gravity-forms-google-analytics-event-tracking' ),
						"type"    => "text",
						"name"    => "gaEventAction",
						"class"   => "medium merge-tag-support mt-position-right",
						"tooltip" => sprintf( '<h6>%s</h6>%s', __( 'Event Action', 'gravity-forms-google-analytics-event-tracking' ), __( 'Enter your Google Analytics event action', 'gravity-forms-google-analytics-event-tracking' ) ),
					),
					array(
						"label"   => __( 'Event Label', 'gravity-forms-google-analytics-event-tracking' ),
						"type"    => "text",
						"name"    => "gaEventLabel",
						"class"   => "medium merge-tag-support mt-position-right",
						"tooltip" => sprintf( '<h6>%s</h6>%s', __( 'Event Label', 'gravity-forms-google-analytics-event-tracking' ), __( 'Enter your Google Analytics event label', 'gravity-forms-google-analytics-event-tracking' ) ),
					),
					array(
						"label"   => __( 'Event Value', 'gravity-forms-google-analytics-event-tracking' ),
						"type"    => "text",
						"name"    => "gaEventValue",
						"class"   => "medium merge-tag-support mt-position-right",
						"tooltip" => sprintf( '<h6>%s</h6>%s', __( 'Event Value', 'gravity-forms-google-analytics-event-tracking' ), __( 'Enter your Google Analytics event value. Leave blank to omit pushing a value to Google Analytics. Or to use the purchase value of a payment based form. <strong>Note:</strong> This must be a number (int/float).', 'gravity-forms-google-analytics-event-tracking' ) ),
					),
				)
			),
			array(
				"title"  => __( 'Other Settings', 'gravity-forms-google-analytics-event-tracking' ),
				"fields" => array(
					array(
						'name'    => 'conditionalLogic',
						'label'   => __( 'Conditional Logic', 'gravity-forms-google-analytics-event-tracking' ),
						'type'    => 'feed_condition',
						'tooltip' => '<h6>' . __( 'Conditional Logic', 'gravity-forms-google-analytics-event-tracking' ) . '</h6>' . __( 'When conditions are enabled, events will only be sent to google when the conditions are met. When disabled, all form submissions will trigger an event.', 'gravity-forms-google-analytics-event-tracking' )
					)
				)
			),
		);
	}

	/**
	 * Instruction field
	 * 
	 * @since 1.5.0
	 */
	public function single_setting_row_instruction_field(){
		echo '
			<tr>
				<th colspan="2">
					<p>' . __( "If you leave these blank, the following defaults will be used when the event is tracked", 'gravity-forms-google-analytics-event-tracking' ) . ':</p>
					<p>
						<strong>' . __( "Event Category", 'gravity-forms-google-analytics-event-tracking' ) . ':</strong> Forms<br>
						<strong>' . __( "Event Action", 'gravity-forms-google-analytics-event-tracking' ) . ':</strong> Submission<br>
						<strong>' . __( "Event Label", 'gravity-forms-google-analytics-event-tracking' ) . ':</strong> Form: {form_title} ID: {form_id}<br>
						<strong>' . __( "Event Value", 'gravity-forms-google-analytics-event-tracking' ) . ':</strong> Payment Amount (on payment forms only, otherwise nothing is sent by default)
					</p>
				</td>
			</tr>';
	}

	/**
	 * Return the feed list columns
	 * @return array columns
	 */
	public function feed_list_columns() {
		return array(
			'feedName'        => __( 'Name', 'gravity-forms-google-analytics-event-tracking' ),
			'gaEventCategory' => __( 'Category', 'gravity-forms-google-analytics-event-tracking' ),
			'gaEventAction'   => __( 'Action', 'gravity-forms-google-analytics-event-tracking' ),
			'gaEventLabel'    => __( 'Label', 'gravity-forms-google-analytics-event-tracking' ),
			'gaEventValue'    => __( 'Value', 'gravity-forms-google-analytics-event-tracking' ),
		);
	}

}
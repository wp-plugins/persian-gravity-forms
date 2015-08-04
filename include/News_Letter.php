<?php
/**
* Post Permalink Merge Tag
* http://GravityForms.ir
*/
class GFIR_NewsLetter {
	
    function __construct() {
		add_filter('gform_notification_events', array( $this, 'Add_Manual_Notification_Event') );
		add_filter('gform_before_resend_notifications', array( $this, 'Manual_Notification_Event_Conditional_Logic'), 10, 2);	
	}
	
	public function Add_Manual_Notification_Event( $events ) {
		$events['newsletter'] = __( 'خبرنامه' );
		return $events;
	}
	
	public function Manual_Notification_Event_Conditional_Logic($form, $leads) {
		
		if ( empty( $leads ) || empty( $form ) ) {
			_e( 'There was an error while resending the notifications.', 'gravityforms' );
			die();
		};

		$notifications = json_decode( rgpost( 'notifications' ) );
		if ( ! is_array( $notifications ) ) {
			die( __( 'No notifications have been selected. Please select a notification to be sent.', 'gravityforms' ) );
		}

		if ( ! rgempty( 'sendTo', $_POST ) && ! GFCommon::is_valid_email_list( rgpost( 'sendTo' ) ) ) {
			die( __( 'The <strong>Send To</strong> email address provided is not valid.', 'gravityforms' ) );
		}

		foreach ( $leads as $lead_id ) {
			$lead = RGFormsModel::get_lead( $lead_id );
			foreach ( $notifications as $notification_id ) {
				
				$notification = $form['notifications'][ $notification_id ];
				if ( ! $notification ) {
					continue;
				}
				
				//overriding To email if one was specified
				if ( rgpost( 'sendTo' ) ) {
					$notification['to']     = rgpost( 'sendTo' );
					$notification['toType'] = 'email';
				}
				
				if ( $notification['event'] == 'newsletter') {
					GFCommon::send_notifications( $notification, $form, $lead, true, $notification['event'] );
				}
				else {
					GFCommon::send_notification( $notification, $form, $lead );
				}
			}
		}

		die();	
		return;
	
	}
	
}
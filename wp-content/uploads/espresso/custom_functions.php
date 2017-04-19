<?php
//Change coupon code alert message
function event_espresso_coupon_payment_page( $event_id = FALSE, $event_cost = 0.00, $mer = TRUE, $use_coupon_code = 'N' ) {
	
		do_action('action_hook_espresso_log', __FILE__, __FUNCTION__, '');		
 
		global $espresso_premium,$org_options;		
		if ( ! $espresso_premium ) {
			return FALSE;
		}

		$event_cost = (float)$event_cost;
//		echo '<h4>$event_id : ' . $event_id . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//		echo '<h4>$event_cost : ' . $event_cost . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
		
		$coupon_code = isset( $_POST['event_espresso_coupon_code'] ) ? wp_strip_all_tags( $_POST['event_espresso_coupon_code'] ) : FALSE;
		if ( $coupon_code === FALSE ) {
			$coupon_code = isset( $_SESSION['espresso_session']['event_espresso_coupon_code'] ) ? wp_strip_all_tags( $_SESSION['espresso_session']['event_espresso_coupon_code'] ) : FALSE;
		}
//		echo '<h4>$coupon_code : ' . $coupon_code . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';			
		
		if ( ! $use_coupon_code ) {
			$use_coupon_code = isset( $_POST['use_coupon'][$event_id] ) ? $_POST['use_coupon'][$event_id] : 'N';			
		}
//		echo '<h4>$use_coupon_code : ' . $use_coupon_code . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
		
		if ( in_array($use_coupon_code, array('Y',"G","A")) && $event_cost > 0 ) {
			if ( $coupon_code ){

				global $wpdb;
				$percentage = FALSE;
				$discount_type_price = '';
				$msg = '';
				$error = '';
				$event_id = absint( $event_id );
				$coupon_id = FALSE;
						
				
				if ( isset( $_SESSION['espresso_session']['events_in_session'][ $event_id ] ) && isset( $_SESSION['espresso_session']['events_in_session'][ $event_id ]['coupon']['code'] )) {
					// check if coupon has already been added to session
					if ( $_SESSION['espresso_session']['events_in_session'][ $event_id ]['coupon']['code'] == $coupon_code ) {
						// grab values from session
						$coupon = $_SESSION['espresso_session']['events_in_session'][ $event_id ]['coupon'];
						//printr( $coupon, '$coupon  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
	                	$valid = TRUE;
	                	$coupon_id = $coupon['id'];
						$coupon_code = $coupon['code'];
	                	$coupon_amount = (float)$coupon['coupon_code_price'];
	                	$coupon_code_description = $coupon['coupon_code_description'];
	                	$use_percentage = $coupon['use_percentage'];

					}
					
				} else {
					
					$SQL = "SELECT d.* FROM " . EVENTS_DISCOUNT_CODES_TABLE . " d ";
					$SQL .= " LEFT JOIN " . EVENTS_DISCOUNT_REL_TABLE . " r ON r.discount_id  = d.id ";
					$SQL .= "WHERE d.coupon_code = %s ";
					if($use_coupon_code != 'A'){//if $use_coupon_code is 'A', then we use ALL coupon codes, regardless of whether htey 'apply_to_all', or have a relation to this event
						$SQL .= " AND ";
						$SQL .= $event_id ? " (r.event_id = '" . $event_id . "' OR " : '';
						$SQL .= " d.apply_to_all = 1";
						$SQL .= $event_id ? " ) ": '';
					}
					$prepared_SQL = $wpdb->prepare( $SQL, $coupon_code );
					if ( $coupon = $wpdb->get_row( $prepared_SQL )) {	
					
						$valid = TRUE;
						$coupon_id = $coupon->id;
						$coupon_code = $coupon->coupon_code;
						$coupon_amount = (float)$coupon->coupon_code_price;
						$coupon_code_description = $coupon->coupon_code_description;
						$use_percentage = $coupon->use_percentage;
					}
					
				}
				
//				echo '<h4>$coupon_id : ' . $coupon_id . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
		 
				if ( $coupon_id ) {				
							
					$discount_type_price = $use_percentage == 'Y' ? number_format( $coupon_amount, 1, '.', '' ) . '%' : $org_options['currency_symbol'] . number_format( $coupon_amount, 2, '.', '' );
					$discount = 0;

//				echo '<h4>$coupon_code : ' . $coupon_code . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//				echo '<h4>$coupon_amount : ' . $coupon_amount . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//				echo '<h4>$use_percentage : ' . $use_percentage . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
					
					if ( $use_percentage == 'Y' ) {
						$percentage = TRUE;
						$pdisc = (float)$coupon_amount / 100;
//						echo '<h4>$pdisc : ' . $pdisc . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
						$discount = (float)$event_cost * (float)$pdisc;
						$event_cost = $event_cost - (float)$discount;
					} else {
						$event_cost = $event_cost - $coupon_amount;
						$discount = $coupon_amount;
					}
										
					$event_cost = (float)$event_cost > 0.00 ? (float)$event_cost : 0.00;
//					echo '<h4>$discount : ' . $discount . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//					echo '<h4>$event_cost : ' . $event_cost . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';
//					echo '<h4>$mer : ' . $mer . '  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h4>';

					do_action('action_hook_espresso_log', __FILE__, __FUNCTION__, 'line '. __LINE__ .' : $event_cost=' . $event_cost );
					
					if ( $mer ) {
					
						$coupon_details = array();					
						$coupon_details['id'] = $coupon_id;
						$coupon_details['code'] = $coupon_code;
						$coupon_details['coupon_code_price'] = $coupon_amount;
						$coupon_details['coupon_code_description'] = $coupon_code_description;
						$coupon_details['use_percentage'] = $use_percentage;
						$coupon_details['discount'] = $discount;
//						printr( $coupon_details, '$coupon_details  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
						$_SESSION['espresso_session']['events_in_session'][ $event_id ]['coupon'] = $coupon_details;
						
						$msg = '<p id="event_espresso_valid_coupon" style="margin:0;">';
						$msg .= '<strong>' . __('Promotional code ', 'event_espresso') . $coupon_code . '</strong> ( ' . $discount_type_price . __(' discount', 'event_espresso') . ' )<br/>';
	          		    $msg .= __('has been successfully applied to your purchase.', 'event_espresso') . '</p>';
						
					} else {
					
						$msg = '<p id="event_espresso_valid_coupon" style="margin:0;">';
						$msg .= '<strong>' . __('Promotional code ', 'event_espresso') . $coupon_code . '</strong> ( ' . $discount_type_price . __(' discount', 'event_espresso') . ' )<br/>';
	          		    $msg .= __('has been successfully applied to your purchase.', 'event_espresso');
	          		    $msg .= '</p>';
						
					}							

	            } else {
				
					$valid = FALSE;
					if ( $mer ) {
					
						$error = '<p id="event_espresso_invalid_coupon" style="margin:0;color:red;">' . __('Sorry, promotional code ', 'event_espresso') . '<strong>' . $coupon_code . '</strong>' . __(' is invalid, expired, or can not be used for the event(s) you are applying it to.', 'event_espresso') . '</p>';
						
					} else {
					
						$msg = '<p id="event_espresso_invalid_coupon" style="margin:0;color:red;">';
						$msg .= __('Sorry, promotional code ', 'event_espresso') . '<strong>' . $coupon_code . '</strong>';
						$msg .= __(' is either invalid, expired, or can not be used for the event(s) you are applying it to.', 'event_espresso');
	          		    $msg .= '</p>';
						
					}
					
	            }
//				printr( $_SESSION, '$_SESSION  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
				return array( 'event_cost'=>$event_cost, 'valid'=>$valid, 'percentage'=>$percentage, 'discount'=>$discount_type_price, 'msg' => $msg, 'error' => $error, 'code' => $coupon_code );

			}
        }

		return FALSE;		
 
   }

//Change the purchase limit text to "call the box office"
	function event_espresso_group_price_dropdown($event_id, $label = 1, $multi_reg = 0, $value = '') {
	
		global $wpdb, $org_options;
		do_action('action_hook_espresso_log', __FILE__, __FUNCTION__, '');
		/*
		 * find out pricing type.
		 * - If multiple price options, for each one
		 * -- Create a row in a table with a name
		 * -- qty dropdown
		 *
		 */

		//Will make the name an array and put the time id as a key so we
		//know which event this belongs to
		$multi_name_adjust = $multi_reg == 1 ? "[$event_id]" : '';
		
		$SQL = "SELECT ept.id, ept.event_cost, ept.surcharge, ept.surcharge_type, ept.price_type, edt.allow_multiple, edt.additional_limit ";
		$SQL .= "FROM " . EVENTS_PRICES_TABLE . " ept ";
		$SQL .= "JOIN " . EVENTS_DETAIL_TABLE . "  edt ON ept.event_id =  edt.id ";
		$SQL .= "WHERE event_id=%d ORDER BY ept.id ASC";
		// filter SQL statement
		$SQL = apply_filters( 'filter_hook_espresso_group_price_dropdown_sql', $SQL );
		// get results
		$results = $wpdb->get_results( $wpdb->prepare( $SQL, $event_id ));

		if ($wpdb->num_rows > 0) {

			$attendee_limit = 1;
			//echo $label==1?'<label for="event_cost">' . __('Choose an Option: ','event_espresso') . '</label>':'';
			//echo '<input type="radio" name="price_option' . $multi_name_adjust . '" id="price_option-' . $event_id . '">';
			?>
<table class="price_list">
	<?php
			$available_spaces = get_number_of_attendees_reg_limit($event_id, 'number_available_spaces');
			foreach ($results as $result) {

				//Setting this field for use on the registration form
				$_SESSION['espresso_session']['events_in_session'][$event_id]['price_id'][$result->id]['price_type'] = stripslashes_deep($result->price_type);
				// Addition for Early Registration discount
				if ($early_price_data = early_discount_amount($event_id, $result->event_cost)) {
					$result->event_cost = $early_price_data['event_price'];
					$message = __(' Early Pricing', 'event_espresso');
				}


				$surcharge = '';

				if ($result->surcharge > 0 && $result->event_cost > 0.00) {
					$surcharge = " + {$org_options['currency_symbol']}{$result->surcharge} " . $org_options['surcharge_text'];
					if ($result->surcharge_type == 'pct') {
						$surcharge = " + {$result->surcharge}% " . $org_options['surcharge_text'];
					}
				}

				?>
	<tr>
		<td class="price_type"><?php echo $result->price_type; ?></td>
		<td class="price"><?php
							if (!isset($message))
								$message = '';
							echo $org_options['currency_symbol'] . number_format($result->event_cost, 2) . $message . ' ' . $surcharge;
							?></td>
		<td class="selection">
			<?php		
				$attendee_limit = 1;
				$att_qty = empty($_SESSION['espresso_session']['events_in_session'][$event_id]['price_id'][$result->id]['attendee_quantity']) ? '' : $_SESSION['espresso_session']['events_in_session'][$event_id]['price_id'][$result->id]['attendee_quantity'];
				
				if ($result->allow_multiple == 'Y') {			
					$attendee_limit = $result->additional_limit;
					if ($available_spaces != 'Unlimited') {
						$attendee_limit = ($attendee_limit <= $available_spaces) ? $attendee_limit : $available_spaces;
					}
				}
					
				event_espresso_multi_qty_dd( $event_id, $result->id,  $attendee_limit, $att_qty );
				
			?>
		</td>
	</tr>
	<?php
			}
			?>
	<tr>
		<td colspan="3" class="reg-allowed-limit">
			<?php printf(__("To purchase more than %d tickets for this show, please call the box office.", 'event_espresso'), $attendee_limit); ?>
		</td>
	</tr>
</table>

<input type="hidden" id="max_attendees-<?php echo $event_id; ?>" class="max_attendees" value= "<?php echo $attendee_limit; ?>" />
<?php
		} else if ($wpdb->num_rows == 0) {
			echo '<span class="free_event">' . __('Free Event', 'event_espresso') . '</span>';
			echo '<input type="hidden" name="payment' . $multi_name_adjust . '" id="payment-' . $event_id . '" value="' . __('free event', 'event_espresso') . '">';
		}
		
	}
?>
<?php
// Change the way final price displays in the shopping cart (change $final_price to $orig_price)
//This function is called from the shopping cart
function event_espresso_add_attendees_to_db_multi() {
	//echo '<h3>'. __CLASS__ . '->' . __FUNCTION__ . ' <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span></h3>';
	do_action('action_hook_espresso_log', __FILE__, __FUNCTION__, '');		
	
	global $wpdb, $org_options;
	
	if ( espresso_verify_recaptcha() ) {

		$primary_registration_id = NULL;
		$multi_reg = true;

		$events_in_session = $_SESSION['espresso_session']['events_in_session'];
		if (event_espresso_invoke_cart_error($events_in_session)) {
			return false;
		}				

		$count_of_events = count($events_in_session);
		$current_session_id = $_SESSION['espresso_session']['id'];
		$biz_name = $count_of_events . ' ' . $org_options['organization'] . __(' events', 'event_espresso');
		$event_cost = $_SESSION['espresso_session']['grand_total'];
		$event_cost = apply_filters('filter_hook_espresso_cart_grand_total', $event_cost);

		// If there are events in the session, add them one by one to the attendee table
		if ($count_of_events > 0) {
			foreach ($events_in_session as $event_id => $event) {

				$event_meta = event_espresso_get_event_meta($event_id);
				$session_vars['data'] = $event;

				if ( is_array( $event['event_attendees'] )) {
				
					$counter = 1;
					//foreach price type in event attendees
					foreach ( $event['event_attendees'] as $price_id => $event_attendees ) { 
					
						$session_vars['data'] = $event;

						foreach ( $event_attendees as $attendee) {

							$attendee['price_id'] = $price_id;
							//this has all the attendee information, name, questions....
							$session_vars['event_attendees'] = $attendee; 
							$session_vars['data']['price_type'] = stripslashes_deep($event['price_id'][$price_id]['price_type']);
							if ( isset($event_meta['additional_attendee_reg_info']) && $event_meta['additional_attendee_reg_info'] == 1 ) {

								$num_people = (int)$event['price_id'][$price_id]['attendee_quantity'];
								$session_vars['data']['num_people'] = empty($num_people) || $num_people == 0 ? 1 : $num_people;

							}

							// ADD ATTENDEE TO DB
							$return_data = event_espresso_add_attendees_to_db( $event_id, $session_vars, TRUE );
							
							$tmp_registration_id = $return_data['registration_id'];
							$notifications = $return_data['notifications'];

							if ($primary_registration_id === NULL) {
								$primary_registration_id = $tmp_registration_id;
							}

							$SQL = "SELECT * FROM " . EVENTS_MULTI_EVENT_REGISTRATION_ID_GROUP_TABLE . "  ";
							$SQL .= "WHERE primary_registration_id = %s AND registration_id = %s";
							$check = $wpdb->get_row( $wpdb->prepare( $SQL, $primary_registration_id, $tmp_registration_id ));
							
							if ( $check === NULL) {
								$tmp_data = array( 'primary_registration_id' => $primary_registration_id, 'registration_id' => $tmp_registration_id );
								$wpdb->insert( EVENTS_MULTI_EVENT_REGISTRATION_ID_GROUP_TABLE, $tmp_data, array( '%s', '%s' ));
							}
						$counter++;

						}
					}
				}
			}
			

			$SQL = "SELECT a.*, ed.id AS event_id, ed.event_name, dc.coupon_code_price, dc.use_percentage ";
			$SQL .= "FROM " . EVENTS_ATTENDEE_TABLE . " a JOIN " . EVENTS_DETAIL_TABLE . " ed ON a.event_id=ed.id ";
			$SQL .= "LEFT JOIN " . EVENTS_DISCOUNT_CODES_TABLE . " dc ON a.coupon_code=dc.coupon_code ";
			$SQL .= "WHERE attendee_session=%s ORDER BY a.id ASC";
			
			$attendees			= $wpdb->get_results( $wpdb->prepare( $SQL, $current_session_id ));				
			$quantity			= 0;
			$sub_total			= 0;
			$discounted_total	= 0;
			$discount_amount	= 0;
			$is_coupon_pct		= ! empty( $attendees[0]->use_percentage ) && $attendees[0]->use_percentage == 'Y' ? TRUE : FALSE;
			
			//printr( $attendees, '$attendees  <br /><span style="font-size:10px;font-weight:normal;">' . __FILE__ . '<br />line no: ' . __LINE__ . '</span>', 'auto' );
			foreach ($attendees as $attendee) {
			
				if ( $attendee->is_primary ) {
					$primary_attendee_id	= $attendee_id = $attendee->id;
					$coupon_code			= $attendee->coupon_code;
					$event_id				= $attendee->event_id;
					$fname					= $attendee->fname;
					$lname					= $attendee->lname;
					$address				= $attendee->address;
					$city					= $attendee->city;
					$state					= $attendee->state;
					$country 			= $attendee->country_id;
					$zip					= $attendee->zip;
					$attendee_email			= $attendee->email;
					$registration_id		= $attendee->registration_id;
				}
				$quantity			+= (int)$attendee->quantity;
				$sub_total			+= (int)$attendee->quantity * $attendee->orig_price;
				$discounted_total	+= (int)$attendee->quantity * $attendee->final_price;
			}
			$discount_amount	= $sub_total - $discounted_total;
			$total_cost			= $discounted_total;		
			$total_cost			= $total_cost < 0 ? 0.00 : (float)$total_cost;
			
			if ( function_exists( 'espresso_update_attendee_coupon_info' ) && $primary_attendee_id && ! empty( $attendee->coupon_code )) {
				espresso_update_attendee_coupon_info( $primary_attendee_id, $attendee->coupon_code  );
			} 	
				
			if ( function_exists( 'espresso_update_groupon' ) && $primary_attendee_id && ! empty( $coupon_code )) {
				espresso_update_groupon( $primary_attendee_id, $coupon_code  );
			} 

			espresso_update_primary_attendee_total_cost( $primary_attendee_id, $total_cost, __FILE__ );

			if ( ! empty( $notifications['coupons'] ) || ! empty( $notifications['groupons'] )) {
				echo '<div id="event_espresso_notifications" class="clearfix event-data-display no-hide">';
				echo $notifications['coupons'];
				// add space between $coupon_notifications and  $groupon_notifications ( if any $groupon_notifications exist )
				echo ! empty( $notifications['coupons'] ) && ! empty( $notifications['groupons'] ) ? '<br/>' : '';
				echo $notifications['groupons'];
				echo '</div>';	
			}						
			
			//Post the gateway page with the payment options
			if ( $total_cost > 0 ) {
?>
<div class="espresso_payment_overview event-display-boxes ui-widget" >
<h3 class="section-heading ui-widget-header ui-corner-top">
	<?php _e('Payment Overview', 'event_espresso'); ?>
</h3>
<div class="event-data-display ui-widget-content ui-corner-bottom" >

	<!--<div class="event-messages ui-state-highlight"> <span class="ui-icon ui-icon-alert"></span>
		<p class="instruct">
			<?//php _e('Please review your order before entering billing information.', 'event_espresso'); ?>
		</p>
	</div>-->
	<p><?php echo $org_options['email_before_payment'] == 'Y' ? __('A confirmation email has been sent with additional details of your registration.', 'event_espresso') : ''; ?></p>
	<table>
		<?php foreach ($attendees as $attendee) { ?>
		<tr>
			<td width="70%">
				<?php echo '<strong>'.stripslashes_deep($attendee->event_name ) . '</strong>'?>&nbsp;-&nbsp;<?php echo stripslashes_deep( $attendee->price_option ) ?> <?php echo $attendee->final_price < $attendee->orig_price ? '<br />&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:.8em;">' . $org_options['currency_symbol'] . number_format($attendee->orig_price - $attendee->final_price, 2) . __(' discount per registration','event_espresso') . '</span>' : ''; ?><br/>
				&nbsp;&nbsp;&nbsp;&nbsp;<?php echo stripslashes_deep( event_date_display($attendee->start_date) ) . " at " . stripslashes_deep( event_date_display($attendee->event_time, get_option('time_format')) ) ?>
			</td>
			<td width="10%"><?php echo $org_options['currency_symbol'] . number_format($attendee->orig_price, 2); ?></td>
			<td width="10%"><?php echo 'x ' . (int)$attendee->quantity ?></td>
			<td width="10%" style="text-align:right;"><?php echo $org_options['currency_symbol'] . number_format( $attendee->orig_price * (int)$attendee->quantity, 2) ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3"><?php _e('Sub-Total:','event_espresso'); ?></td>
			<td colspan="" style="text-align:right"><?php echo $org_options['currency_symbol'] . number_format($sub_total, 2); ?></td>
		</tr>
		<?php
				if (!empty($discount_amount)) {
						?>
		<tr>
			<td colspan="3"><?php _e('Adjustment (Donation and/or Discount):','event_espresso'); ?></td>
			<td colspan="" style="text-align:right"><?php echo '-' . $org_options['currency_symbol'] . number_format( $discount_amount, 2 ); ?></td>
		</tr>
		<?php } ?>
		<tr>
			<td colspan="3"><strong class="event_espresso_name">
				<?php _e('Total Amount due: ', 'event_espresso'); ?>
				</strong></td>
			<td colspan="" style="text-align:right"><?php echo $org_options['currency_symbol'] ?><?php echo number_format($total_cost,2); ?></td>
		</tr>
	</table>
	<p class="event_espresso_refresh_total">
		<a href="?page_id=<?php echo $org_options['event_page_id']; ?>&regevent_action=show_shopping_cart">
		<?php _e('Edit Cart', 'event_espresso'); ?>
		</a>
		<?php _e(' or ', 'event_espresso'); ?>
		<a href="?page_id=<?php echo $org_options['event_page_id']; ?>&regevent_action=load_checkout_page">
		<?php _e('Edit Registrant Information', 'event_espresso'); ?>
		</a> 
	</p>
</div>
</div>
<br/><br/>
<?php
				//Show payment options
				if (file_exists(EVENT_ESPRESSO_GATEWAY_DIR . "gateway_display.php")) {
					require_once(EVENT_ESPRESSO_GATEWAY_DIR . "gateway_display.php");
				} else {
					require_once(EVENT_ESPRESSO_PLUGINFULLPATH . "gateways/gateway_display.php");
				}
				//Check to see if the site owner wants to send an confirmation eamil before payment is recieved.
				if ($org_options['email_before_payment'] == 'Y') {
					event_espresso_email_confirmations(array('session_id' => $_SESSION['espresso_session']['id'], 'send_admin_email' => 'true', 'send_attendee_email' => 'true', 'multi_reg' => true));
				}
				
			} elseif ( $total_cost == 0.00 ) {
				?>
<p>
<?php _e('Thank you! Your registration is confirmed for', 'event_espresso'); ?>
<strong><?php echo stripslashes_deep( $biz_name ) ?></strong></p>
<p>
<?php _e('A confirmation email has been sent with additional details of your registration.', 'event_espresso'); ?>
</p>
<?php
				event_espresso_email_confirmations(array('session_id' => $_SESSION['espresso_session']['id'], 'send_admin_email' => 'true', 'send_attendee_email' => 'true', 'multi_reg' => true));

				event_espresso_clear_session();
			}
		}
		
	}		
	
}

// Change anchor text from 'Register' to 'Buy tickets'
function event_espresso_cart_link($atts) {

	do_action('action_hook_espresso_log', __FILE__, __FUNCTION__, '');
	global $wpdb, $org_options, $this_event_id;

	$events_in_session = isset( $_SESSION['espresso_session']['events_in_session'] ) ? $_SESSION['espresso_session']['events_in_session'] : event_espresso_clear_session( TRUE );
	
	extract(
		shortcode_atts(
			array(
				'event_id' => $this_event_id,
				'anchor' => __('Reserve seats', 'event_espresso'),
				'event_name' => ' ',
				'separator' => NULL,
				'view_cart' => FALSE,
				'event_page_id' => $org_options['event_page_id'], //instead of sending it in as a var, grab the id here.
				'direct_to_cart' => 1,
				'moving_to_cart' => "Redirecting to cart..."
			), 
			$atts
		)
	);
	
	if ( empty( $event_id )) {
		$error = "<div class='event_espresso_error'><p><em>".__('Attention', 'event_espresso')."</em>";
		$error .= __('An error occured, a valid event id is required for this shortcode to function properly.', 'event_espresso');
		$error .= "</p></div>";
		return $error;
	}
	

	$registration_cart_class = '';

	// if event is already in session, return the view cart link  		array_key_exists($event_id, $events_in_session)
	if ( $view_cart || is_array( $events_in_session ) && isset( $events_in_session[ $event_id ] )) {
		$registration_cart_url = add_query_arg('regevent_action', 'show_shopping_cart', get_permalink($org_options['event_page_id']));
		//$registration_cart_url = get_option('siteurl') . '/?page_id=' . $event_page_id . '&regevent_action=show_shopping_cart';
		$anchor = __("View Cart", 'event_espresso');
		
	} else {

		$event_ids = explode( '-', $event_id );
		
		if ( is_array( $event_ids )) {

			$SQL = "SELECT id, is_active, event_status, registration_start, registration_startT, registration_end, registration_endT ";
			$SQL .= "FROM " . EVENTS_DETAIL_TABLE . " e ";
			$SQL .= "WHERE id IN ( " . str_replace( 'cart_link_', '', implode( ', ', $event_ids )) . " )";
			$events = $wpdb->get_results( $SQL, OBJECT_K );
			$event_ids = array_flip( $event_ids );
			foreach ( $events as $event ) {
				
				$reg_start = strtotime( $event->registration_start . " " . $event->registration_startT );
				$reg_end = strtotime( $event->registration_end . " " . $event->registration_endT );
				
				if ( $event->is_active != "Y" || time() < $reg_start ||  ( time() > $reg_end && $event->event_status != 'O' ) || ! in_array( $event->event_status, array( 'A', 'O', 'S' )) ) {
					unset( $event_ids[ $event->id ] );
					unset( $event_ids[ 'cart_link_' . $event->id ] );
					if ( is_array( $events_in_session ) && isset( $events_in_session[ $event->id ] )) {
						unset( $events_in_session[ $event->id ] );
					}
				}
			}
			$event_ids = array_flip( $event_ids );
		}
		$event_id =implode( '-', $event_ids );
		
		if ( empty( $event_id )) {
			$error = "<div class='event_espresso_error'><p><em>" . __('Attention', 'event_espresso') . "</em><br />";
			$error .= __('We\'re sorry. Either an error occurred or the event(s) you were attempting to register for may no longer be open for registration.', 'event_espresso');
			$error .= "</p></div>";
			return empty( $events_in_session ) ? $error : '';
		}
		//show them the add to cart link
		$registration_cart_url = isset($externalURL) && $externalURL != '' ? $externalURL : add_query_arg('event_id', $event_id, get_permalink($org_options['event_page_id']));
		$registration_cart_class = 'ee_add_item_to_cart';
		
	}
            
	ob_start();
            
	if ($view_cart && $direct_to_cart == 1) {
		echo "<span id='moving_to_cart'>{$moving_to_cart}</span>";
		echo "<script language='javascript'>window.location='" . $registration_cart_url . "';</script>";
	} else {
		echo $separator . ' <a class="ee_view_cart ' . $registration_cart_class . '" id="cart_link_' . $event_id . '" href="' . $registration_cart_url . '" title="' . stripslashes_deep($event_name) . '" moving_to_cart="' . urlencode($moving_to_cart) . '" direct_to_cart="' . $direct_to_cart . '" >' . $anchor . '</a>';
	}

	$buffer = ob_get_contents();
	ob_end_clean();
	return $buffer;
	
}

/*
Function Name: Maximum Date Display
Author: Seth Shoultes
Contact: seth@smartwebutah.com
Website: http://shoultes.net
Description: This function is used in the Events Table Display template file to show events for a maximum number of days in the future
Usage Example: 
Requirements: Events Table Display template file
Notes: 
*/
function display_event_espresso_date_max($max_days="null"){
	global $wpdb;
	//$org_options = get_option('events_organization_settings');
	//$event_page_id =$org_options['event_page_id'];
	if ($max_days != "null"){
		if ($_REQUEST['show_date_max'] == '1'){
			foreach ($_REQUEST as $k=>$v) $$k=$v;
		}
		$max_days = $max_days;
		$sql  = "SELECT * FROM " . EVENTS_DETAIL_TABLE . " WHERE ADDDATE('".date ( 'Y-m-d' )."', INTERVAL ".$max_days." DAY) >= start_date AND start_date >= '".date ( 'Y-m-d' )."' AND is_active = 'Y' ORDER BY date(start_date)";
		event_espresso_get_event_details($sql);//This function is called from the event_list.php file which should be located in your templates directory.

	}				
}

/*
Function Name: Event Status
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This function is used to display the status of an event.
Usage Example: Can be used to display custom status messages in your events.
Requirements: 
Notes: 
*/
if (!function_exists('espresso_event_status')) {
	function espresso_event_status($event_id){
		$event_status = event_espresso_get_is_active($event_id);
		
		//These messages can be uesd to display the status of the an event.
		switch ($event_status['status']){
			case 'EXPIRED':
				$event_status_text = __('This event is expired.','event_espresso');
			break;
			
			case 'ACTIVE':
				$event_status_text = __('This event is active.','event_espresso');
			break;
			
			case 'NOT_ACTIVE':
				$event_status_text = __('This event is not active.','event_espresso');
			break;
			
			case 'ONGOING':
				$event_status_text = __('This is an ongoing event.','event_espresso');
			break;
			
			case 'SECONDARY':
				$event_status_text = __('This is a secondary/waiting list event.','event_espresso');
			break;
			
		}
		return $event_status_text;
	}
}

/*
Function Name: Custom Event List Builder
Author: Seth Shoultes
Contact: seth@eventespresso.com
Website: http://eventespresso.com
Description: This function creates lists of events using custom templates.
Usage Example: Create a page or widget template to show events.
Requirements: Template files must be stored in your wp-content/uploads/espresso/templates directory
Notes: 
*/
if (!function_exists('espresso_list_builder')) {
	function espresso_list_builder($sql, $template_file, $before, $after){
		
		global $wpdb, $org_options;
		//echo 'This page is located in ' . get_option( 'upload_path' );
		$event_page_id = $org_options['event_page_id'];
		$currency_symbol = $org_options['currency_symbol'];
		$events = $wpdb->get_results($sql);
		$category_id = $wpdb->last_result[0]->id;
		$category_name = $wpdb->last_result[0]->category_name;
		$category_desc = html_entity_decode( wpautop($wpdb->last_result[0]->category_desc) );
		$display_desc = $wpdb->last_result[0]->display_desc;
		
		if ($display_desc == 'Y'){
			echo '<p id="events_category_name-'. $category_id . '" class="events_category_name">' . stripslashes_deep($category_name) . '</p>';
			echo wpautop($category_desc);				
		}
		
		foreach ($events as $event){
			$event_id = $event->id;
			$event_name = $event->event_name;
			$event_identifier = $event->event_identifier;
			$active = $event->is_active;
			$registration_start = $event->registration_start;
			$registration_end = $event->registration_end;
			$start_date = $event->start_date;
			$end_date = $event->end_date;
			$reg_limit = $event->reg_limit;
			$event_address = $event->address;
			$event_address2 = $event->address2;
			$event_city = $event->city;
			$event_state = $event->state;
			$event_zip = $event->zip;
			$event_country = $event->country;
			$member_only = $event->member_only;
			$externalURL = $event->externalURL;
			$recurrence_id = $event->recurrence_id;
			
			$allow_overflow = $event->allow_overflow;
			$overflow_event_id = $event->overflow_event_id;
			
			//Address formatting
			$location = ($event_address != '' ? $event_address :'') . ($event_address2 != '' ? '<br />' . $event_address2 :'') . ($event_city != '' ? '<br />' . $event_city :'') . ($event_state != '' ? ', ' . $event_state :'') . ($event_zip != '' ? '<br />' . $event_zip :'') . ($event_country != '' ? '<br />' . $event_country :'');
			
			//Google map link creation
			$google_map_link = espresso_google_map_link(array( 'address'=>$event_address, 'city'=>$event_city, 'state'=>$event_state, 'zip'=>$event_zip, 'country'=>$event_country, 'text'=> 'Map and Directions', 'type'=> 'text') );
			
			//These variables can be used with other the espresso_countdown, espresso_countup, and espresso_duration functions and/or any javascript based functions.
			$start_timestamp = espresso_event_time($event_id, 'start_timestamp', get_option('time_format'));
			$end_timestamp = espresso_event_time($event_id, 'end_timestamp', get_option('time_format'));
			
			//This can be used in place of the registration link if you are usign the external URL feature
			$registration_url = $externalURL != '' ? $externalURL : get_option('siteurl') . '/?page_id='.$event_page_id.'&regevent_action=register&event_id='. $event_id;
		
			if (!is_user_logged_in() && get_option('events_members_active') == 'true' && $member_only == 'Y') {
				//Display a message if the user is not logged in.
				 //_e('Member Only Event. Please ','event_espresso') . event_espresso_user_login_link() . '.';
			}else{
	//Serve up the event list
	//As of version 3.0.17 the event lsit details have been moved to event_list_display.php
				echo $before = $before == ''? '' : $before;
				include('templates/'. $template_file);
				echo $after = $after == ''? '' : $after;
			} 
		}
	//Check to see how many database queries were performed
	//echo '<p>Database Queries: ' . get_num_queries() .'</p>';
	}
}
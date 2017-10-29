<?php

/**
 * BSF_Envato_Activate setup
 *
 * @since 1.0
 */
class BSF_Envato_Activate {

	/**
	 * Instance
	 * @var BSF_Envato_Activate
	 */
	private static $instance;

	/**
	 * Reference to the License manager class.
	 * @var BSF_License_Manager
	 */
	private $license_manager;

	/**
	 * Stores temporary response messsages from the API validations.
	 * @var array()
	 */
	private $message_box;

	/**
	 *  Initiator.
	 */
	public static function instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new BSF_Envato_Activate();
		}

		return self::$instance;
	}

	public function __construct() {
		$this->license_manager = new BSF_License_Manager();

		$action = isset( $_GET['license_action'] ) ? esc_attr( $_GET['license_action'] ) : '';

		if ( $action == 'activate_license' ) {
			$this->process_envato_activation();
		}

		add_filter( 'update_footer', array( $this, 'alternate_method_link'), 20 );
	}

	public function envato_register( $args ) {

		// Check if alternate method is to be used
		$method = isset( $_GET['activation_method'] ) ? esc_attr( $_GET['activation_method'] ) : 'oauth';

		$html 		  = '';
		$product_id   = isset( $args['product_id'] ) ? $args['product_id'] : '';
		$is_active 	  = $this->license_manager->bsf_is_active_license( $product_id );
		$product_name = $this->license_manager->bsf_get_product_info( $product_id, 'name' );
		$purchase_url = $this->license_manager->bsf_get_product_info( $product_id, 'purchase_url' );

		$bundled 	  = BSF_Update_Manager::bsf_is_product_bundled( $product_id );

		if ( ! empty( $bundled ) ) {
			$parent_id       	= $bundled[0];
			$is_active 	  	 	= $this->license_manager->bsf_is_active_license( $parent_id );
			$parent_name     	= brainstrom_product_name( $parent_id );
			$registration_page 	= bsf_registration_page_url( '', $parent_id );

			$html .= '<div class="bundled-product-license-registration">';
			$html .= '<span>';

			if ( $is_active ) {

				$html .= '<h3>License Active!</h3>';

				$html  .= '<p>' . sprintf( 
					'Your license is activated, you will receive updates for <i>%s</i> when they are available.',
					$product_name
				). '</p>';
			} else {

				$html .= '<h3>Updates Unavailable!</h3>';
				$html  .=  '<p>' . sprintf( 
							'This plugin is came bundled with the <i>%1$s</i>. For receiving updates, you need to activate license of <i>%2$s</i> <a href="%3$s">here</a>.', 
							$parent_name, 
							$parent_name, 
							$registration_page 
						). '</p>';
			}

			$html .= '</span>';
			$html .= '</div>';

			return $html;
		}

		if ( $method == 'license-key' ) {
			$html .= bsf_license_activation_form( $args );

			return $html;
		}

		// Licence activation button.
		$form_action                  = ( isset( $args['form_action'] ) && ! is_null( $args['form_action'] ) ) ? $args['form_action'] : '';
		$form_class                   = ( isset( $args['form_class'] ) && ! is_null( $args['form_class'] ) ) ? $args['form_class'] : "bsf-license-form-{$product_id}";
		$submit_button_class          = ( isset( $args['submit_button_class'] ) && ! is_null( $args['submit_button_class'] ) ) ? $args['submit_button_class'] : '';
		$license_form_heading_class   = ( isset( $args['bsf_license_form_heading_class'] ) && ! is_null( $args['bsf_license_form_heading_class'] ) ) ? $args['bsf_license_form_heading_class'] : '';
		$license_active_class         = ( isset( $args['bsf_license_active_class'] ) && ! is_null( $args['bsf_license_active_class'] ) ) ? $args['bsf_license_active_class'] : '';
		$license_not_activate_message = ( isset( $args['bsf_license_not_activate_message'] ) && ! is_null( $args['bsf_license_not_activate_message'] ) ) ? $args['bsf_license_not_activate_message'] : '';

		$size                   = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
		$button_text_activate   = ( isset( $args['button_text_activate'] ) && ! is_null( $args['button_text_activate'] ) ) ? $args['button_text_activate'] : 'Activate License';
		$button_text_deactivate = ( isset( $args['button_text_deactivate'] ) && ! is_null( $args['button_text_deactivate'] ) ) ? $args['button_text_deactivate'] : 'Deactivate License';
		$placeholder            = ( isset( $args['placeholder'] ) && ! is_null( $args['placeholder'] ) ) ? $args['placeholder'] : 'Enter your license key..';

		if ( $is_active != true ) {
			$form_action = get_api_site() . 'envato-validation-callback/?wp-envato-validate';
		} else {
			$form_action = bsf_registration_page_url( '', $product_id );
		}

		$html .= '<div class="envato-license-registration">';

		$html .= '<form method="post" class="' . $form_class . '" action="' . $form_action . '">';

		if ( $this->getMessage( 'message' ) !== '' ) {
			$html .= '<span class="bsf-license-message license-'. $this->getMessage( 'status' ) .'">';
			$html .= $this->getMessage( 'message' );
			$html .= '</span>';
		}

		if ( $is_active ) {

			$envato_active_oauth_title = apply_filters( "envato_active_oauth_title_{$product_id}", 'Updates & Support Registration - <span class="active">Active!</span>' );
			$envato_active_oauth_subtitle = sprintf( 
						'Your license is active.',
						$product_name
					);

			$envato_active_oauth_subtitle = apply_filters( "envato_active_oauth_subtitle_{$product_id}", $envato_active_oauth_subtitle );

			$html .= '<div class="bsf-wrap-title">';
			$html .= 		'<h3 class="envato-oauth-heading">' . $envato_active_oauth_title . '</h2>';
			$html .= 		'<p class="envato-oauth-subheading">' . $envato_active_oauth_subtitle . '</p>';
			$html .=  '</div>';

			$html .= '<input type="hidden" readonly class="' . $license_active_class . ' ' . $size . '-text" id="bsf_license_manager[license_key]" name="bsf_license_manager[license_key]" value="License Validated"/>';
			$html .= '<input type="hidden" class="' . $size . '-text" id="bsf_license_manager[product_id]" name="bsf_license_manager[product_id]" value="' . esc_attr( stripslashes( $product_id ) ) . '"/>';

			$html .= '<input type="submit" class="button ' . $submit_button_class . '" name="bsf_deactivate_license" value="' . esc_attr__( $button_text_deactivate, 'bsf' ) . '"/>';

		} else {

			$envato_not_active_oauth_title = apply_filters( "envato_not_active_oauth_title_{$product_id}", __( 'Updates & Support Registration - <span class="not-active">Not Active!</span>', 'bsf' ) );
			$envato_not_active_oauth_subtitle = apply_filters( "envato_not_active_oauth_subtitle_{$product_id}",  __( 'Click on the button below and activate your license using Envato API to unlock premium benefits.', 'bsf' ) );

			$html .= '<div class="bsf-wrap-title">';
			$html .= 		'<h3 class="envato-oauth-heading">' . $envato_not_active_oauth_title . '</h2>';
			$html .= 		'<p class="envato-oauth-subheading">' . $envato_not_active_oauth_subtitle . '</p>';
			$html .=  '</div>';

			$html .= '<input type="hidden" readonly class="' . $license_active_class . ' ' . $size . '-text" id="bsf_license_manager[license_key]" name="url" value="' . get_site_url() . '"/>';
			$html .= '<input type="hidden" readonly class="' . $license_active_class . ' ' . $size . '-text" id="bsf_license_manager[license_key]" name="redirect" value="' . $this->get_redirect_url() . '"/>';
			$html .= '<input type="hidden" readonly class="' . $license_active_class . ' ' . $size . '-text" id="bsf_license_manager[license_key]" name="product_id" value="' . $product_id . '"/>';
			$html .= '<input type="button" class="button bsf-envato-form-activation ' . $submit_button_class . '" name="bsf_activate_license" value="' . esc_attr__( $button_text_activate, 'bsf' ) . '"/>';
			$html .= "<p>If you don't have a license, you can <a target='_blank' href='$purchase_url'>get it here »</a></p>";
		}

		$html .= '</form>';

		$html .= '</div> <!-- envato-license-registration -->';

		if ( isset( $_GET['debug'] ) ) {
			$html .= get_bsf_systeminfo();
		}

		return $html;
	}

	public function envato_activation_url( $form_data ) {
		$product_id = isset( $form_data['product_id'] ) ? esc_attr( $form_data['product_id'] ) : '';

		$form_data['token'] = sha1( $this->create_token( $product_id ) );
		$url 				= get_api_site() . 'envato-validation-callback/?wp-envato-validate';

		$envato_activation_url =  add_query_arg( 
			$form_data, 
			$url 
		);


		return $envato_activation_url;
	}

	protected function get_redirect_url() {

		if ( is_ssl() ) {
			$current_url = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		} else {
			$current_url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}

		$current_url = esc_url( remove_query_arg( array( 'license_action', 'token', 'product_id', 'purchase_key', 'success', 'status', 'message' ), $current_url ) );

		return $current_url;
	}

	protected function create_token( $product_id ) {
		$token = $product_id . '|' . current_time( 'timestamp' ) . '|' . bsf_generate_rand_token();
		update_site_option( "bsf_envato_token_$product_id", $token );

		return $token;
	}

	protected function validateToken( $token, $product_id ) {

		$stored_token = get_site_option( "bsf_envato_token_$product_id", '' );

		if ( $token == sha1( $stored_token ) ) {
			$token_atts = explode( '|', $stored_token );

			$stored_id = $token_atts[0];

			if ( $stored_id != $product_id ) {
				// Token is invalid
				return false;
			}

			$timestamp  = (int) $token_atts[1];
			$validUltil = $timestamp + 900;

			if ( current_time( 'timestamp' ) > $validUltil ) {
				// Timestamp has expired.
				return false;
			}

			// If above conditions did not meet, the token is valid.
			return true;
		}

		return false;
	}

	protected function process_envato_activation() {
		$token      = isset( $_GET['token'] ) ? esc_attr( $_GET['token'] ) : '';
		$product_id = isset( $_GET['product_id'] ) ? esc_attr( $_GET['product_id'] ) : '';

		if ( $this->validateToken( $token, $product_id ) ) {
			$args                 = array();
			$args['purchase_key'] = isset( $_GET['purchase_key'] ) ? esc_attr( $_GET['purchase_key'] ) : '';
			$args['status']       = isset( $_GET['status'] ) ? esc_attr( $_GET['status'] ) : '';
			$this->license_manager->bsf_update_product_info( $product_id, $args );

			$this->setMessage(
				array(
					'status'  => 'success',
					'message' => 'License successfully activated!'
				)
			);

		} else {

			$this->setMessage(
				array(
					'status'  => 'error',
					'message' => 'The token is invalid or is expired, please try again.'
				)
			);

		}
	}

	protected function setMessage( $message = array() ) {
		$this->message_box = $message;
	}

	protected function getMessage( $key ) {
		$message = $this->message_box;

		return isset( $message[ $key ] ) ? $message[ $key ] : '';
	}

	public function alternate_method_link( $text ) {
		
		$text = sprintf( 
			'<a href="%s">Activate license using purchase key</a>', 
			add_query_arg( 'activation_method', 'license-key' ) 
		);

		return $text;
	}

}

function bsf_envato_register( $args ) {
	$BSF_Envato_Activate = BSF_Envato_Activate::instance();

	return $BSF_Envato_Activate->envato_register( $args );
}
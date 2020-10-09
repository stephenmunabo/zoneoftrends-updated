<?php
/**
 * Curl requests
 *
 * @package  Aliexpress_Dropshipping_For_Woocommerce
 * @version  1.0.0
 * @link     https://cedcommerce.com
 * @since    1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Class_Ced_Shopee_Send_Http_Request
 *
 * @since 1.0.0
 */
class Class_Ced_Aliexpress_Send_Http_Request {

			/**
			 * Shopee Shop Id.
			 *
			 * @since    1.0.0
			 * @var      int    $shop_id   Shopee Shop Id.
			 */
			public $shop_id;
		/**
		 * The endpoint variable.
		 *
		 * @since    1.0.0
		 * @var      string    $end_point_url    The endpoint variable.
		 */
		public $end_point_url;
		/**
		 * The partner id variable.
		 *
		 * @since    1.0.0
		 * @var      string    $partner_id   The partner id variable.
		 */
		public $partner_id;

		/**
		 * The secret key variable
		 *
		 * @since    1.0.0
		 * @var      string    $secret_key    The secret key variable.
		 */
		public $secret_key;

	/**
	 * Class_Ced_Shopee_Send_Http_Request construct
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/lib/wTiConfig.php';
		$this->config_instance = new Ced_Ali_Config();
		$this->endpointUrl     = $this->config_instance->endpointUrl;

	}

	/**
	 * Function for sending curl request
	 *
	 * @since 1.0.0
	 * @param string $action Action to be performed.
	 * @param array  $parameters Parameters required on shopee.
	 * @param int    $store Shopee Shop Id.
	 */
	public function send_http_request( $action = '', $post_data = array() ) {
		$header = array(
			'Content-Type:application/x-www-form-urlencoded;charset=utf-8',
			'Accept:application/json',
		);
		if ( $action == 'https://oauth.aliexpress.com/token' ) {
			$url = 'https://oauth.aliexpress.com/token';
		} else {
			$url = $this->endpointUrl . $action;
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $header );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_POST, true );
		if ( ! empty( $post_data ) ) {
			curl_setopt( $ch, CURLOPT_POSTFIELDS, substr( $post_data, 0, -1 ) );
		}
		$output         = curl_exec( $ch );
		$httpStatusCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
		curl_close( $ch );
		if ( ! is_array( $output ) ) {
			$output = json_decode( $output, true );
		}
		return $output;
	}

	/**
	 * Function for prepare_header
	 *
	 * @since 1.0.0
	 * @param string $api_url The url for performing several actions.
	 * @param array  $parmaeters Parameters required by shopee.
	 */
	public function prepare_header( $api_url = '', $parmaeters = array() ) {
		$authorisation = $api_url . '|' . json_encode( $parmaeters );
		$authorisation = rawurlencode( hash_hmac( 'sha256', $authorisation, $this->secret_key, false ) );

		$header = array(
			'Content-Type: application/json',
			'Authorization: ' . $authorisation,
		);
		return $header;
	}

	/**
	 * Function for parse_response
	 *
	 * @since 1.0.0
	 * @param string $response Response from shopee.
	 */
	public function parse_response( $response ) {
		if ( ! empty( $response ) ) {
			return json_decode( $response, true );
		}
	}


}

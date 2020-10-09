<?php
if ( ! class_exists( 'Ced_Ali_Config' ) ) {
	class Ced_Ali_Config {
		public $endpointUrl;

		/**
		 * Constructor
		 */
		public function __construct() {

			$this->endpointUrl = 'http://gw.api.alibaba.com/openapi/param2/1/portals.open/api.';
		}

	}
}

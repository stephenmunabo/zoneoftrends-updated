<?php
/**
 * TOP API: aliexpress.trade.buy.placeorder request
 *
 * @author auto create
 * @since 1.0, 2019.10.15
 */
class AliexpressTradeBuyPlaceorderRequest {

	/**
	 * 下单具体参数
	 **/
	private $paramPlaceOrderRequest4OpenApiDTO;

	private $apiParas = array();

	public function setParamPlaceOrderRequest4OpenApiDTO( $paramPlaceOrderRequest4OpenApiDTO ) {
		$this->paramPlaceOrderRequest4OpenApiDTO                     = $paramPlaceOrderRequest4OpenApiDTO;
		$this->apiParas['param_place_order_request4_open_api_d_t_o'] = $paramPlaceOrderRequest4OpenApiDTO;
	}

	public function getParamPlaceOrderRequest4OpenApiDTO() {
		return $this->paramPlaceOrderRequest4OpenApiDTO;
	}

	public function getApiMethodName() {
		return 'aliexpress.trade.buy.placeorder';
	}

	public function getApiParas() {
		 return $this->apiParas;
	}

	public function check() {
	}

	public function putOtherTextParam( $key, $value ) {
		$this->apiParas[ $key ] = $value;
		$this->$key             = $value;
	}
}

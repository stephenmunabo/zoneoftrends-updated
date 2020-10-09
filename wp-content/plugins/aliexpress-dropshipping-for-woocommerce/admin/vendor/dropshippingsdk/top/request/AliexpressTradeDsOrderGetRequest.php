<?php
/**
 * TOP API: aliexpress.trade.ds.order.get request
 *
 * @author auto create
 * @since 1.0, 2019.12.26
 */
class AliexpressTradeDsOrderGetRequest {

	/**
	 * 订单查询条件
	 **/
	private $singleOrderQuery;

	private $apiParas = array();

	public function setSingleOrderQuery( $singleOrderQuery ) {
		$this->singleOrderQuery               = $singleOrderQuery;
		$this->apiParas['single_order_query'] = $singleOrderQuery;
	}

	public function getSingleOrderQuery() {
		 return $this->singleOrderQuery;
	}

	public function getApiMethodName() {
		return 'aliexpress.trade.ds.order.get';
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

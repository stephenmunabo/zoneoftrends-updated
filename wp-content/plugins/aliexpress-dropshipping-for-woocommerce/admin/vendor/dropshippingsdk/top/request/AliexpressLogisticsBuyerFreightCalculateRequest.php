<?php
/**
 * TOP API: aliexpress.logistics.buyer.freight.calculate request
 *
 * @author auto create
 * @since 1.0, 2019.05.15
 */
class AliexpressLogisticsBuyerFreightCalculateRequest {

	/**
	 * 运费计算请求参数
	 **/
	private $paramAeopFreightCalculateForBuyerDTO;

	private $apiParas = array();

	public function setParamAeopFreightCalculateForBuyerDTO( $paramAeopFreightCalculateForBuyerDTO ) {
		$this->paramAeopFreightCalculateForBuyerDTO                     = $paramAeopFreightCalculateForBuyerDTO;
		$this->apiParas['param_aeop_freight_calculate_for_buyer_d_t_o'] = $paramAeopFreightCalculateForBuyerDTO;
	}

	public function getParamAeopFreightCalculateForBuyerDTO() {
		 return $this->paramAeopFreightCalculateForBuyerDTO;
	}

	public function getApiMethodName() {
		return 'aliexpress.logistics.buyer.freight.calculate';
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

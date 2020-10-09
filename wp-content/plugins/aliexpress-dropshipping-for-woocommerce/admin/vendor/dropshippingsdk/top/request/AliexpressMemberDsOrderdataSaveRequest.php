<?php
/**
 * TOP API: aliexpress.member.ds.orderdata.save request
 *
 * @author auto create
 * @since 1.0, 2020.01.09
 */
class AliexpressMemberDsOrderdataSaveRequest {

	/**
	 * DS订单和用户数据
	 **/
	private $dserCollectData;

	private $apiParas = array();

	public function setDserCollectData( $dserCollectData ) {
		$this->dserCollectData               = $dserCollectData;
		$this->apiParas['dser_collect_data'] = $dserCollectData;
	}

	public function getDserCollectData() {
		return $this->dserCollectData;
	}

	public function getApiMethodName() {
		return 'aliexpress.member.ds.orderdata.save';
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

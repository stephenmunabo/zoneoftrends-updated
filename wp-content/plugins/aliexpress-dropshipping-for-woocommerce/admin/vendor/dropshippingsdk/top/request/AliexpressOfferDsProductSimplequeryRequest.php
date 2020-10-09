<?php
/**
 * TOP API: aliexpress.offer.ds.product.simplequery request
 *
 * @author auto create
 * @since 1.0, 2019.05.28
 */
class AliexpressOfferDsProductSimplequeryRequest {

	/**
	 * 国家
	 **/
	private $localCountry;

	/**
	 * 语言
	 **/
	private $localLanguage;

	/**
	 * 商品ID
	 **/
	private $productId;

	private $apiParas = array();

	public function setLocalCountry( $localCountry ) {
		$this->localCountry              = $localCountry;
		$this->apiParas['local_country'] = $localCountry;
	}

	public function getLocalCountry() {
		 return $this->localCountry;
	}

	public function setLocalLanguage( $localLanguage ) {
		$this->localLanguage              = $localLanguage;
		$this->apiParas['local_language'] = $localLanguage;
	}

	public function getLocalLanguage() {
		return $this->localLanguage;
	}

	public function setProductId( $productId ) {
		$this->productId              = $productId;
		$this->apiParas['product_id'] = $productId;
	}

	public function getProductId() {
		return $this->productId;
	}

	public function getApiMethodName() {
		return 'aliexpress.offer.ds.product.simplequery';
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

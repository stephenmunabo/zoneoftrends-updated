<?php
require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/aliexapi-master/vendor/autoload.php';

use AliexApi\Configuration\GenericConfiguration;
use AliexApi\AliexIO;
use AliexApi\Operations\ListProducts;
abstract class CedWadAli {

	public function __construct( $aliApiKey, $aliTrackingId = '', $aliDigitalSignature = '' ) {
		$this->apiKey              = '79509'; // $aliApiKey;
		$this->aliTrackingId       = 'cedcommerce';// $aliTrackingId;
		$this->aliDigitalSignature = $aliDigitalSignature;
	}

	protected function aliconfig( $conf ) {
		$conf
			->setApiKey( $this->apiKey )
			->setTrackingKey( $this->aliTrackingId )
			->setDigitalSign( $this->aliDigitalSignature );
			return $conf;
	}

	protected function index() {
		$this->searchItems();
	}

	protected function searchItems() {
		$lppfields = array(
			// 'categoryId' => '1501',
			'keywords' => 'baby shoes',
		);
		$array     = $this->listPromotionProduct( $lppfields );
		// dd($array);
	}

	protected function prepareAll() {
		$conf = new GenericConfiguration();
		$this->aliconfig( $conf );
		$this->aliexIO      = new AliexIO( $conf );
		$this->listproducts = new ListProducts();

	}

	protected function listPromotionProduct() {
		$this->listproducts->setFields( 'productId,productTitle,productUrl,imageUrl,allImageUrls,originalPrice,salePrice,discount,30daysCommission,volume' );
		if ( $this->_name ) {
			$this->listproducts->setnames( $this->_name );
		}
		if ( $this->_keyword ) {
			$this->listproducts->setKeywords( $this->_keyword );
		}
		if ( $this->_catId ) {
			$this->listproducts->setCategoryId( $this->_catId );
		}
		if ( $this->_pricefrom ) {
			$this->listproducts->setOriginalPriceFrom( $this->_pricefrom );
		}
		if ( $this->_priceto ) {
			$this->listproducts->setOriginalPriceTo( $this->_priceto );
		}
		// if($this->_creditscorefrom) $this->listproducts->setStartCreditScore($this->_creditscorefrom);
		// if($this->_creditscoreto) $this->listproducts->setEndCreditScore($this->_creditscoreto);
		if ( $this->_pageNumber ) {
			$this->listproducts->setPageNo( $this->_pageNumber );
		}
		$this->listproducts->setHighQualityItems( 'true' );
		$formattedResponse = $this->aliexIO->runOperation( $this->listproducts );
		$array             = json_decode( $formattedResponse, true );
		return $array;
	}
}

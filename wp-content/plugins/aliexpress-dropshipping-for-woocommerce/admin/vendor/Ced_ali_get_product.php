<?php
require_once CED_ALIEXPRESS_DIRPATH . 'admin/vendor/ced_ali_abstract.php';
if ( ! class_exists( 'CedWadBunch' ) ) {

	/**
	 * class to handle filter and get products from aliexpress
	 *
	 * Using free libaray available on git https://github.com/clchangnet/aliexapi
	 * Credits to https://github.com/clchangnet
	 **/
	class CedWadBunch extends CedWadAli {
		/**
		 * This function is to set filetr id.
		 *
		 * @setfilterId
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setfilterId( $filterId ) {
			$this->filterId = $filterId;
			return $this;
		}

		/**
		 * This function is to set name.
		 *
		 * @name
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setname( $name ) {
			$this->_name = $name;
			return $this;
		}

		/**
		 * This function is to set keyword.
		 *
		 * @setkeyword
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setkeyword( $keyword ) {
			$this->_keyword = $keyword;
			return $this;
		}
		/**
		 * This function is to set price from.
		 *
		 * @setpriceform
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setpriceform( $priceFrom ) {
			$this->_pricefrom = $priceFrom;
			return $this;
		}

		/**
		 * This function is to set price to.
		 *
		 * @setpriceto
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setpriceto( $priceto ) {
			$this->_priceto = $priceto;
			return $this;
		}

		/**
		 * This function is to set credit score from.
		 *
		 * @setpriceto
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setcreditscoreform( $searchMinmarkup ) {
			$this->_searchMinmarkup = $searchMinmarkup;
			return $this;
		}

		/**
		 * This function is to set credit score to.
		 *
		 * @setpriceto
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setcreditscoreto( $searchMaxmarkup ) {
			$this->_searchMaxmarkup = $searchMaxmarkup;
			return $this;
		}

		/**
		 * This function is to set credit score to.
		 *
		 * @setpriceto
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setpagenumber( $pageNumber ) {
			$this->_pageNumber = $pageNumber;
			return $this;
		}
		/**
		 * This function is to set category id.
		 *
		 * @setcategory
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function setcategory( $catId ) {
			$this->_catId = $catId;
			return $this;
		}
		/**
		 * This function is to get products.
		 *
		 * @getProducts
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function getProducts() {
			$this->result = $this->listPromotionProduct();
			return $this;
		}
		/**
		 * This function is to prepare for getting products.
		 *
		 * @prepareAll
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function prepareAll() {
			parent::prepareAll();
			return $this;
		}
		/**
		 * This function is to create bunch.
		 *
		 * @createBunch
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function createBunch() {
			$products = isset( $this->result['result']['products'] ) ? $this->result['result']['products'] : false;
			if ( $products && is_array( $products ) && ! empty( $products ) ) {
				if ( CedWadInsertBunch( $products, $this->filterId ) ) {
					echo prapareAjaxResponse();
					die;
				}
			}
		}

		/**
		 * This function is to create bunch.
		 *
		 * @createBunch
		 * @author CedCommerce <plugins@cedcommerce.com>
		 * @link  https://www.cedcommerce.com/
		 */
		public function returnProducts() {
			$products = isset( $this->result['result']['products'] ) ? $this->result['result']['products'] : false;
			if ( $products && is_array( $products ) && ! empty( $products ) ) {
				return $products;
			}
		}

	}
}

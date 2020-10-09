<?php
class Xgo {


	public static function ProductDetailsFromUrl( $productId, $merchant_id = false) {
		try {
			$type_variant = false;
			$html         = '';
			$url          = 'https://www.aliexpress.com/item/' . $productId . '.html';

			$product_data      = array();
			$product_content_1 = $product_data;
			$modules           = $product_content_1;

			$http_response_code = self::get_http_response_code($url);
			if ( '200' != $http_response_code) {
				return 4;
			} else {
				$html = file($url);
			}
			if ( false == $html ) {
				return false;
			}
			
			$links = array();
			foreach ($html as $key=> $a) {
				if ( strpos( $a, 'actionModule' ) !== false) {
					$modules = trim(str_replace('data: ', '', $a));
					$modules = rtrim($modules, ',');
					$modules = json_decode($modules, true);
				}
			}
			$product_content_1['discount'] = $modules['priceModule']['discount'];
			$product_content_1['price']    = $modules['priceModule']['formatedPrice'];
			if ( '' != isset($modules['priceModule']['formatedActivityPrice']) && $modules['priceModule']['formatedActivityPrice'] ) {
				$product_content_1['offer_price_cr'] = $modules['priceModule']['formatedActivityPrice'];
			} else {
				$product_content_1['offer_price_cr'] = $modules['priceModule']['formatedPrice'];
			}
			$product_content_1['main_image']       = $modules['pageModule']['imagePath'];
			$product_content_1['productDetailUrl'] = $modules['pageModule']['itemDetailUrl'];
			$product_content_1['description_url']  = $modules['descriptionModule']['descriptionUrl'];
			$product_content_1['rating']           = $modules['titleModule']['feedbackRating']['averageStar'];
			$product_content_1['name']             = str_replace("'", '', $modules['titleModule']['subject']);
			if (isset($modules['skuModule']) && !empty($modules['skuModule'])) {
				$variants = self::prepareVariants($modules['skuModule']);
				if (is_array($variants) && !empty($variants)) {
					$type_variant = true;
				} else {
					$product_content_1['inventory'] = $variants;
				}
			} else {
				$product_content_1['inventory'] = 5; //custom_value
			}
			$images         = self::prepareImages($modules['imageModule']);
			$page_data      = self::preparePageAttributes($modules['pageModule']);
			$specifications = self::prepareSpecs($modules['specsModule']);
			
			$product_content_1['specifications']    = $specifications;
			$product_content_1['short_description'] = $page_data['short_description'];
			$product_content_1['keyword']           = $page_data['keywords'];
			$product_content_1['tagline']           = $page_data['tagline'];

			$product_data['data'] = $product_content_1;
			if ($type_variant) {
				$product_data['data']['variants'] = $variants;
			}
			$product_data['data']['all_images'] = $images;
			return $product_data;
		} catch (Exception $e) {
			return false;
		}
	}
	
	/*Parent Function Name: ProductDetailsFromUrl*/
	public static function prepareVariants( $skuModule) {
		$skuPriceList    = self::prepareSPLProperty($skuModule['skuPriceList']);
		$skuPropertyList = isset($skuModule['productSKUPropertyList'])?self::prepareSPRLProperty($skuModule['productSKUPropertyList']):array();
		if (!empty($skuPropertyList)) {
			$prepareVariantsContent = self::prepareVariantsData($skuPriceList, $skuPropertyList, 'variants');
		} else {
			$prepareVariantsContent = self::prepareVariantsData($skuPriceList, $skuPropertyList, 'simple');
		}
		return $prepareVariantsContent;
	}
	
	/*Parent Function Name: ProductDetailsFromUrl*/
	public static function prepareImages( $imageModule) {
		$modify_images = array();
		if (is_array($imageModule) && !empty($imageModule)) {
			foreach ($imageModule['imagePathList'] as $key=> $image) {
				// $modify_images[$key] = $image.replace(/&/g, 'cedommm'); //enable if the url will get break
				$modify_images[$key] = $image;
			}
		}
		return $modify_images;
	}
	
	/*Parent Function Name: ProductDetailsFromUrl*/
	public static function preparePageAttributes( $pageModule) {
		$prepared_page = array();
		if (is_array($pageModule) && !empty($pageModule)) {
			$short_desc                         = $pageModule['description'];
			$prepared_page['short_description'] = $short_desc;
			$prepared_page['keywords']          = $pageModule['keywords'];
			$prepared_page['tagline']           = $pageModule['ogDescription'];
		}
		return $prepared_page;
	}
	
	/*Parent Function Name: ProductDetailsFromUrl*/
	public static function prepareSpecs( $specsModule) {
		$specifications = array();
		if (is_array($specsModule) && !empty($specsModule)) {
			foreach ($specsModule['props'] as $key=> $val) {
				$specs_tags               = array();
				$specs_tags['attr_name']  = $val['attrName'];
				$specs_tags['attr_value'] = $val['attrValue'];
				$specifications[$key]     = $specs_tags;
			}
		}
		return $specifications;
	}
	
	/* Parent Function Name: prepareSPLProperty */
	public static function prepareSPLProperty( $skuPriceList) {
		$variant_details_1 = array();
		if (is_array($skuPriceList) && !empty($skuPriceList)) {
			foreach ($skuPriceList as $key => $value_data) {
				$child_variant_details_1                 = array();
				$child_variant_details_1['skuAttr']      = $value_data['skuAttr'];
				$child_variant_details_1['skuId']        = $value_data['skuId'];
				$child_variant_details_1['skuPropIds']   = $value_data['skuPropIds'];
				$child_variant_details_1['avalaibility'] = $value_data['skuVal']['availQuantity'];
				$child_variant_details_1['inventory']    = $value_data['skuVal']['inventory'];
				if ( '' !== isset($value_data['skuVal']['skuAmount']) && $value_data['skuVal']['skuAmount'] ) {
					$child_variant_details_1['price_cr'] = $value_data['skuVal']['skuAmount']['currency'];
					$child_variant_details_1['price']    = $value_data['skuVal']['skuAmount']['value'];
				} else {
					$child_variant_details_1['price'] = $value_data['skuVal']['skuCalPrice'];
				}

				if ( '' !== isset($value_data['skuVal']['skuActivityAmount']) && $value_data['skuVal']['skuActivityAmount'] ) {
					$child_variant_details_1['offer_price_cr'] = $value_data['skuVal']['skuActivityAmount']['currency'];
					$child_variant_details_1['offer_price']    = $value_data['skuVal']['skuActivityAmount']['value'];
				} else {
					$child_variant_details_1['offer_price'] = $value_data['skuVal']['actSkuCalPrice'];
				}
				$variant_details_1[$key] = $child_variant_details_1;
			}
		}
		return $variant_details_1;
	}
	
	/* Parent Function Name: prepareSPLProperty */
	public static function prepareSPRLProperty( $skuPropertyList) {
		$variant_details_2 = array();
		if (is_array($skuPropertyList) && !empty($skuPropertyList)) {
			foreach ($skuPropertyList as $key=> $value) {
				$skuPropertyData                    = array();
				$skuPropertyData['isOrder']         = $value['order'];
				$skuPropertyData['skuPropertyId']   = $value['skuPropertyId'];
				$skuPropertyData['skuPropertyName'] = $value['skuPropertyName'];
				$skuParentPropertyValues            = array();
				foreach ($value['skuPropertyValues'] as $key1=> $value1) {
					$skuPropertyValues                 = array();
					$skuPropertyValues['identifier_1'] = $value1['propertyValueId'];
					if (isset($value1['propertyValueDefinitionName'])) {
						$skuPropertyValues['identifier_2'] = $value1['propertyValueDefinitionName'];
					} else {
						$skuPropertyValues['identifier_2'] = $value1['propertyValueDisplayName'];
					}
					
					if ( '' != isset($value1['propertyValueDisplayName']) && $value1['propertyValueDisplayName']) {
						$skuPropertyValues['name'] = $value1['propertyValueDisplayName'];
					} else {
						$skuPropertyValues['name'] = $value1['propertyValueName'];
					}
					
					$skuPropertyValues['image']     = isset($value1['skuPropertyImagePath'])?$value1['skuPropertyImagePath']:null;
					$skuPropertyValues['sum_image'] = isset($value1['skuPropertyImageSummPath'])?$value1['skuPropertyImageSummPath']:null;
					if (isset($value1['propertyValueId'])) {
						$identifier = $value1['propertyValueId'];
					} else {
						$identifier = $value1['propertyValueId'] + '#' + $value1['propertyValueDefinitionName'];
					}
					$skuParentPropertyValues[$identifier] = $skuPropertyValues;
				}
				$skuPropertyData['values']                  = $skuParentPropertyValues;
				$variant_details_2[$value['skuPropertyId']] = $skuPropertyData;
			}
		}
		return $variant_details_2;
	}
	
	/* Parent Function Name: prepareSPLProperty */
	public static function prepareVariantsData( $skuPriceList, $skuPropertyList, $type) {
		$variants             = array();
		$initial_level_return = $variants;
		if ( 'variants' == $type ) {
			foreach ($skuPriceList as $key=> $value) {
				$first_level_return  = array();
				$skuAttr             = $value['skuAttr'];
				$skuAttr             = explode(';', $skuAttr);
				$second_level_return = array();
				foreach ($skuAttr as $key1=> $value1) {
					$skuPropListData       = array();
					$child_skuPropListData = $skuPropListData;
					$value1_1              = explode(':', $value1);
					
					$custom_skuPropId   = (int) filter_var($value1_1[0], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					$custom_propValueId = $value1_1[1];
					
					if (strpos($custom_propValueId, '#') !== false) {
						$temp               = explode('#', $custom_propValueId);
						$custom_propValueId = (int) filter_var($temp[0], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					} else {
						$custom_propValueId = (int) filter_var($custom_propValueId, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION );
					}
					
					$skuPropListData                       = $skuPropertyList[$custom_skuPropId];
					$child_skuPropListData['l_data']       = $skuPropListData['values'][$custom_propValueId];
					$child_skuPropListData['propertyName'] = $skuPropListData['skuPropertyName'];
					$second_level_return[$key1]            = $child_skuPropListData;
					unset($custom_propValueId);
					unset($child_skuPropListData);
					unset($skuPropListData);
				}
				$first_level_return['data']        = $value;
				$first_level_return['linked_data'] = $second_level_return;
				$initial_level_return[$key]        = $first_level_return;
			}

			foreach ($initial_level_return as $init_key=> $init_variant) {
				
				$variant_detail                     = array();
				$variant_detail['sku_id']           = $init_variant['data']['skuId'];
				$variant_detail['sku_prop_ids']     = $init_variant['data']['skuPropIds'];
				$variant_detail['sku_attr']         = $init_variant['data']['skuAttr'];
				$variant_detail['calculated_price'] = $init_variant['data']['price'];
				$variant_detail['sale_price']       = isset($init_variant['data']['offerPrice'])?$init_variant['data']['offerPrice']:$init_variant['data']['offer_price'];
				$variant_detail['inventory']        = $init_variant['data']['inventory'];
				$variant_detail['avalaibility']     = $init_variant['data']['avalaibility'];
				$variant_detail['linked_data']      = $init_variant['linked_data'];
				$variants[$init_key]                = $variant_detail;
			}
			return $variants;
		} elseif ( 'simple' == $type ) {
			$init_variant = $skuPriceList[0];
			return $init_variant['inventory'];
		}
		return true;
	}
	
	public static function get_http_response_code( $url) {
		$headers = get_headers($url);
		return substr($headers[0], 9, 3);
	}
}


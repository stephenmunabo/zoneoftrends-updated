<?php

/**
 * 运费计算请求参数
 *
 * @author auto create
 */
class AeopFreightCalculateForBuyerDto {


	/**
	 * 城市编码
	 **/
	public $city_code;

	/**
	 * 国家编码
	 **/
	public $country_code;

	/**
	 * 商品价格
	 **/
	public $price;

	/**
	 * 商品价格币种
	 **/
	public $price_currency;

	/**
	 * 商品ID
	 **/
	public $product_id;

	/**
	 * 商品数量
	 **/
	public $product_num;

	/**
	 * 省份编码
	 **/
	public $province_code;

	/**
	 * 发货国家
	 **/
	public $send_goods_country_code;
}


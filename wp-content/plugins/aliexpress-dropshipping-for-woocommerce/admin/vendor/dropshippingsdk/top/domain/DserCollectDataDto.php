<?php

/**
 * DS订单和用户数据
 *
 * @author auto create
 */
class DserCollectDataDto {


	/**
	 * AE订单id
	 **/
	public $ae_orderid;

	/**
	 * AE订单id
	 **/
	public $ae_product_id;

	/**
	 * AE商品SKU信息,SKU键值对：  "200000182:193;200007763:201336100"
	 **/
	public $ae_sku_info;

	/**
	 * 订单站外销售金额,保留2位小数
	 **/
	public $order_amount;

	/**
	 * 站外支付时间,GMT时间，格式YYYYMMDD:HHMMSS
	 **/
	public $paytime;

	/**
	 * SKU站外销售金额,保留2位小数
	 **/
	public $product_amount;

	/**
	 * 商品站外url
	 **/
	public $product_url;
}


<?php

/**
 * Sku属性对象list，允许1-3个sku属性对象，按sku属性顺序排放。sku属性从类目属性接口getAttributesResultByCateId获取。该项值输入sku属性，不能输入普通类目属性。注意，sku属性是有顺序的，必须按照顺序存放。
 *
 * @author auto create
 */
class AeopSkuProperty {


	/**
	 * 自定义名
	 **/
	public $property_value_definition_name;

	/**
	 * 自定义id
	 **/
	public $property_value_id_long;

	/**
	 * skuImage
	 **/
	public $sku_image;

	/**
	 * skuPropertyId
	 **/
	public $sku_property_id;

	/**
	 * 属性名
	 **/
	public $sku_property_name;

	/**
	 * 属性值
	 **/
	public $sku_property_value;
}


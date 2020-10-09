<?php
/**
 * Cron to import ptoduct.
 *
 * @class    Ced_Ali_Product_Chrome_Import
 * @version  1.0.0
 * @author   CedCommerce
 */
class Ced_Ali_Product_Chrome_Import {

	public function __construct() {
		$file_path                               = realpath(__FILE__);
		$post_data                               = filter_input_array( INPUT_POST, FILTER_SANITIZE_STRING );
		$description_url                         = isset($post_data['data']['description_url']) ? parse_url(urldecode($post_data['data']['description_url'])) : '';
		$product_id                              = parse_str($description_url['query'], $query_params);
		$product_id                              = $query_params['productId'];
		$product_url                             = urldecode($post_data['data']['productDetailUrl']);
		$chrome_data[$product_id]['product_id']  = $product_id;
		$chrome_data[$product_id]['product_url'] = $product_url;
		$filename                                = substr($file_path, 0, strpos($file_path, 'plugins')); 
		$filename                                = $filename . 'ced-ali-chrome-data.json';
		if (file_exists($filename)) {
			$file_data = file_get_contents($filename);
			$file_data = json_decode($file_data, true);
			if (isset($file_data[$product_id])) {
				echo 300;
				die;
			}
			$product_url                           = urldecode($post_data['data']['productDetailUrl']);
			$file_data[$product_id]['product_id']  = $product_id;
			$file_data[$product_id]['product_url'] = $product_url;
			$file_data                             = json_encode($file_data);
			$file_put                              = file_put_contents($filename, $file_data);
		} else {
			$file_put = file_put_contents($filename, json_encode($chrome_data));
		}
		if (isset($file_put) && $file_put) {
			echo 200;
			die;
			die;
		}
		echo 400;
		die;
		die;
	}
}
$marketplace_cron_obj =	new Ced_Ali_Product_Chrome_Import();


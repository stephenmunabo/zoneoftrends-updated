(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	 var ajaxNonce = Ced_Aliexpress_action_handler.ajax_nonce;
	jQuery( document ).on(
		'click',
		'.ced-setting-add-markup-row',
		function(){
			var count = jQuery(".ced_setting_price_markup_row").length;
			var html  ='<tr class="ced_setting_price_markup_row">\
					<th>\
						<label class="basic_heading">\
						Price Range						</label>\
					</th>\
					<td>\
						<input placeholder="min price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup]['+count+'][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="">\
					</td>\
					<td>\
						<input placeholder="max price" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup]['+count+'][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="">\
					</td>\
					<td>\
												<select name="ced_ali_global_setting[ced_ali_global_price_markup]['+count+'][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">\
							<option value="">--Select Markup--</option>\
							<option value="fixed_price_markup">Fixed Price Markup</option>\
							<option value="percantage_increase">Percantage Increase</option>\
						</select>\
					</td>\
					<td>\
						<input placeholder="Enter markup to be applied" type="text" name="ced_ali_global_setting[ced_ali_global_price_markup]['+count+'][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="">\
					</td>\
					<td>\
						<div class="ced-wTi-add-markup-row-wrapper">\
							<button type="button" class="ced-setting-add-markup-row">+</button>\
							<button type="button" class="ced-setting-remove-markup-row">-</button>\
						</div>\
					</td>\
					</tr>';
			jQuery(html).insertAfter(".ced_aliexpress_price_markup_wrap tr:last");
		}
	);
	$( document ).on(
		'click',
		'.ced-setting-remove-markup-row',
		function(){
			var count = jQuery(".ced_setting_price_markup_row").length;
			if (count > 1) {
				jQuery(this).closest('tr').remove();
			}
		}
	);
	




	jQuery( document ).on(
		'click',
		'.ced-ali-add-markup-row',
		function(){
			var count = jQuery(".ced_setting_price_markup_row").length;
			var html  ='<tr class="ced_setting_price_markup_row">\
					<th>\
						<label class="basic_heading">\
						Price Range						</label>\
					</th>\
					<td>\
						<input placeholder="min price" type="text" name="ced_ali_filter_url_data['+count+'][ced_ali_min_price_markup]" class="ced_ali_min_price_markup" id="ced_ali_min_price_markup" value="">\
					</td>\
					<td>\
						<input placeholder="max price" type="text" name="ced_ali_filter_url_data['+count+'][ced_ali_max_price_markup]" class="ced_ali_max_price_markup" id="ced_ali_max_price_markup" value="">\
					</td>\
					<td>\
												<select name="ced_ali_filter_url_data['+count+'][ced_aliexpress_price_markup]" class="ced_aliexpress_price_markup" id="ced_aliexpress_price_markup">\
							<option value="">--Select Markup--</option>\
							<option value="fixed_price_markup">Fixed Price Markup</option>\
							<option value="percantage_increase">Percantage Increase</option>\
						</select>\
					</td>\
					<td>\
						<input placeholder="Enter markup to be applied" type="text" name="ced_ali_filter_url_data['+count+'][ced_ali_markup_aplied]" class="ced_ali_markup_aplied" id="ced_ali_markup_aplied" value="">\
					</td>\
					<td>\
						<div class="ced-wTi-add-markup-row-wrapper">\
						<button type="button" class="ced-ali-add-markup-row">+</button>\
							<button type="button" class="ced-ali-remove-markup-row">-</button>\
							</div>\
					</td>\
					</tr>';
			jQuery(html).insertAfter(".ced_aliexpress_price_markup_wrap tr:last");
		}
	);
	$( document ).on(
		'click',
		'.ced-ali-remove-markup-row',
		function(){
			var count = jQuery(".ced_setting_price_markup_row").length;
			if (count > 1) {
				jQuery(this).closest('tr').remove();
			}
		}
	);

	jQuery( document ).on('click','.ced_auto_import_btn',function(e) {

		var filterId         = $( this ).attr( 'ced-filter-id' );
		var numberOfProducts = $('.ced_ali_auto_import_'+filterId).val();
	
		$(".ced_aliexpress_loader").show();
		$.ajax(
				{
					url : Ced_Aliexpress_action_handler.ajax_url,
					type : 'post',
					data : {
						ajax_nonce:ajaxNonce,
						action : 'get_aliexpress_auto_import',
						filterId:filterId,
						numberOfProducts:numberOfProducts,
					},
					success : function(response) {
						scroll_at_top();
						$(".ced_aliexpress_loader").hide();
						var response     = jQuery.parseJSON( response );
						var response_msg = response.message;
						var message_html = '';
						if (response.status == 200) {
							var notice = "";
							notice    += "<div class='notice notice-success'><p>" + response_msg + "</p></div>";
							$( ".success-admin-notices" ).append( notice );
						} 

					}
				}
			);
	});

	jQuery( document ).on('click','#ced_ali_instant_sync_inventory',function(e) {

		var ali_product_id = $( this ).data( 'id' );
		var post_id        = $( this ).data( 'post_id' );
	
		$( '.ced_spinner_' + post_id ).css( 'visibility' , 'visible' );
		$.ajax(
				{
					url : Ced_Aliexpress_action_handler.ajax_url,
					type : 'post',
					data : {
						ajax_nonce:ajaxNonce,
						action : 'ced_update_inventory_using_action',
						ali_product_id:ali_product_id,
						post_id:post_id,
					},
					success : function(response) {
						$( '.ced_spinner_' + post_id ).css( 'visibility' , 'none' );
						location.reload();
					}
				}
			);
	});

	jQuery( document ).on('click','.ced_ali_product_import',function(e) {

		var filterId       = $( this ).attr( 'data-filter-id' );
		var productId      = $( this ).attr( 'data-product-id' );
		var parentcategory = $( this ).attr( 'data-parent-category' );
		var subCategory    = $( this ).attr( 'data-sub-category' );
		var productUrl     = $( this ).attr( 'data-product-url' );
		$(".ced_aliexpress_loader").show();
				$.ajax(
					{
						url : Ced_Aliexpress_action_handler.ajax_url,
						type : 'post',
						data : {
							action : 'get_aliexpress_products',
							filterId:filterId,
							productId:productId,
							parentcategory:parentcategory,
							subCategory:subCategory,
							productUrl:productUrl,

						},
						success : function(response) {

							scroll_at_top();
							$(".ced_aliexpress_loader").hide();
							var response     = jQuery.parseJSON( response );
							var response_msg = response.message;
							var message_html = '';
							if (response.status == 200) {
								var notice = "";
								notice    += "<div class='notice notice-success'><p>" + response_msg + "</p></div>";
								$( ".success-admin-notices" ).append( notice );
							} else {
								var notice = "";
								notice    += "<div class='notice notice-error'><p>" + response_msg + "</p></div>";
								$( ".success-admin-notices" ).append( notice );
							}

						}
					}
				);
	});

	 /*---------------------------------Bulk Actions in Manage Products-------------------------------------------------*/

	$( document ).on(
		'click',
		'.ced_ali_bulk_operation',
		function(e){
			$(".ced_aliexpress_loader").show();
				e.preventDefault();
				var operation 	   = $( ".bulk-action-selector" ).val();
				var filterId       = $( ".aliexpress_product_id" ).attr( 'data-filter-id' );
				var productId      = $( ".aliexpress_product_id" ).attr( 'data-product-id' );
				var parentcategory = $( ".aliexpress_product_id" ).attr( 'data-parent-category' );
				var subCategory    = $( ".aliexpress_product_id" ).attr( 'data-sub-category' );
				var productUrl     = $( ".aliexpress_product_id" ).attr( 'data-product-url' );

			if (operation <= 0 ) {
				  var notice = "";
				  notice    += "<div class='notice notice-error'><p>Please Select Operation To Be Performed</p></div>";
				  $( ".success-admin-notices" ).append( notice );
			} else {
				var aliexpress_products_ids = new Array();
				$( '.aliexpress_product_id:checked' ).each(
					function(){
						aliexpress_products_ids.push( $( this ).val() );
					}
				);
				performBulkAction( aliexpress_products_ids,operation,filterId,parentcategory,subCategory,productUrl );
			}

		}
	);

	function performBulkAction( aliexpress_products_ids,operation,filterId,parentcategory,subCategory,productUrl )
		{
		if (aliexpress_products_ids == "") {
			var notice = "";
			notice    += "<div class='notice notice-error'><p>No Products Selected</p></div>";
			$( ".success-admin-notices" ).append( notice );
		}
		var aliexpress_products_id = aliexpress_products_ids[0];
		$.ajax(
			{
				url : Ced_Aliexpress_action_handler.ajax_url,
				data : {
					action : 'ced_aliexpress_process_bulk_action',
					operation_performed : operation,
					productId : aliexpress_products_id,
					filterId:filterId,
					parentcategory:parentcategory,
					subCategory:subCategory,
					productUrl:productUrl,
				},
				type : 'POST',
				success: function(response)
				{
					var response = jQuery.parseJSON( response );
					if (response.status == 200) {
						var Response_message = jQuery.trim( response.message );
						var notice           = "";
						notice              += "<div class='notice notice-success'><p>" + response.message + "</p></div>";
						$( ".success-admin-notices" ).append( notice );

						var remainig_products_ids = aliexpress_products_ids.splice( 1 );
						if (remainig_products_ids == "") {
							scroll_at_top();
							$( '.ced_aliexpress_loader' ).hide();

							return;
						} else {
							performBulkAction( remainig_products_ids,operation );
						}

					} else if (response.status == 400) {
						var notice = "";
						notice    += "<div class='notice notice-error'><p>" + response.message + "</p></div>";
						$( ".success-admin-notices" ).append( notice );
						var remainig_products_ids = aliexpress_products_ids.splice( 1 );
						if (remainig_products_ids == "") {
							scroll_at_top();
							$( '.ced_aliexpress_loader' ).hide();
							return;
						} else {
							performBulkAction( remainig_products_ids,operation );
						}

					}
				}
			}
		);
	}
	function scroll_at_top() {
		$( "html, body" ).animate(
			{
				scrollTop: 0
			},
			600
		);
	}
	jQuery( document ).on('click','.ced_ali_place_order',function(e) {

		var orderId = $( this ).attr( 'ced-ali-order-id' );
		$.ajax(
				{
					url : Ced_Aliexpress_action_handler.ajax_url,
					type : 'post',
					data : {
						ajax_nonce:ajaxNonce,
						action : 'ced_ali_place_order',
						orderId:orderId,
					},
					success : function(response) {
						var response     = jQuery.parseJSON( response );
						var response_msg = response.message;
						alert(response_msg);
						if (response.status==200) {

							location.reload();
						}


					}
				}
			);
	});

})( jQuery );

jQuery(document).ready(function($){
	jQuery('input[name=ced_ali_radio_shipping]').change(function(){

		var data_estimated_deliv = jQuery( this ).attr('data-attr-time');
		var data_carrier_cost    = jQuery( this ).attr('data-attr-price');
		var data_carrier_name    = jQuery( this ).attr('data-attr-name');

		jQuery(".ced_ali_place_order").attr("data-attr-time", data_estimated_deliv);
		jQuery(".ced_ali_place_order").attr("data-attr-price", data_carrier_cost);
		jQuery(".ced_ali_place_order").attr("data-attr-name", data_carrier_name);

	});

});

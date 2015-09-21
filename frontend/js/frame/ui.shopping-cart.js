(function($){
	
	$.jShoppingCart = {};
	$.jShoppingCart.url				= "/shopping-cart";
	$.jShoppingCart.statusElement	= "#shopping-cart-status";
	
	function putInCart(id, quantity, silent, fn){
		if (!quantity) quantity = 1;
		if (!silent) silent = false;
		$.post($.jShoppingCart.url, {ajax : 1, action : "put", product : id, quantity : quantity}, function(res){
			if (res.result){
				if (res.html) $($.jShoppingCart.statusElement).html(res.html);
			}
			if (res.msg && !silent) showMessage(res.msg);
			if (res.notify) showNotify(res.notify, 'msg', 1000);
			if (typeof fn == 'function') fn.call(this, res);
		}, 'json');
	}
	
	function updateItem(id, quantity, fn){
		$.post($.jShoppingCart.url, {ajax : 1, action : "update", item : id, quantity : quantity}, function(res){
			if (res.result){
				if (res.html) $($.jShoppingCart.statusElement).html(res.html);
			}
			if (typeof fn == 'function') fn.call(this, res);
		}, 'json');
	}
	
	function updateShipping(id, fn){
		$.post($.jShoppingCart.url, {ajax : 1, action : "shipping", id : id}, function(res){
			if (typeof fn == 'function') fn.call(this, res);
		}, 'json');
	}
	
	$.fn.jShoppingCartButton = function(o){
		
		var options = {
		};
		$.extend(options, o || {});
		
		return this.each(function(){
			
			var $a = $(this);
			
			$a.unbind('click').click(function(){
				var arr = $a.attr('href').split('#'), id = null, quantity = 1;
				if (arr.length > 1){
					id = arr[1];
				} else {
					var $form = $a.parents('form:first');
					if (!$form.size()){
						$form = $a.parents('.js-item:first');
					}
					quantity = $form.find('input[name="quantity"]').val();
					id = $form.find('input[name="product_id"]').val();
				}
				
				putInCart(id, quantity);
				
				return false;
			});
			
		});
		
	}
	
	$.fn.jShoppingCart = function(o){

		var options = {
		};
		$.extend(options, o || {});
		
		return this.each(function(){
			
			var $cart = $(this), $status = $(options.statusElement), $rows = $cart.find('.js-rows'),
				$shippings = $cart.find('.js-shippings'), $total = $cart.find('.js-total'), $grand = $cart.find('.js-grand'),
				$count = $cart.find('.js-count');
				
			function refreshCart(res){
				if (res.count) $count.text(res.count);
				if (res.total) $total.text(res.total);
				if (res.grand) $grand.text(res.grand);
			}
			
			function updateCartItem(el, quantity){
				var $li = $(el), $input = $li.find('input[name^="quantity"]'), id = $li.attr('value');
				updateItem(id, quantity, function(res){
					if (res.result){
						if (res.amount) $li.find('.js-amount').text(res.amount);
						if (res.quantity) $input.val(res.quantity); else {
							$li.remove();
							var i = 0;
							$rows.find('span.js-number').each(function(){
								i++;
								$(this).text('' + i);
							});
						}
						refreshCart(res);
					} else {
						if (res.msg) showMessage(res.msg);
					}
				});
			}
			
			$rows.find('a[href$="#increase"], a[href$="#decrease"], a[href$="#delete"]').click(function(){
				var $li = $(this).parents('.js-row:first'), arr = $(this).attr('href').split('#'), 
					quantity = parseInt($li.find('input[name^="quantity"]').val());
				if (arr[1] == 'increase') quantity++;
				if (arr[1] == 'decrease') quantity--;
				if (arr[1] == 'delete') quantity = 0;
				
				updateCartItem($li, quantity);
				return false;
			});
			
			$rows.find('input[name^="quantity"]').change(function(){
				updateCartItem($(this).parents('.js-row:first'), parseInt($(this).val()));
			});
			
			$shippings.find('input[name="shipping"]').change(function(){
				updateShipping($shippings.find('input[name="shipping"]:checked').val(), function(res){
					if (res.result) refreshCart(res);
				});
			});
			
		});

	}

})(jQuery);

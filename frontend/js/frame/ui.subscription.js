(function($){
	
	$.fn.jSubscription = function(){
		
		return this.each(function(){
			var $div = $(this), $a = $div.find('a'), $form = $div.find('form'), w = $div.width();
			
			$a.click(function(){
				$a.hide();
				$form.show();
				$form.find('input:first').focus();
				
				$div.width(400);
				$div.css('right', '0px');
				
				return false;
			});
			
			$form.jForm({
				focus : false
			});
			
			$form.submit(function(){
				if ($form.find(':input.error').size()) return false;
				$.post($form.attr('action'), $form.getFields({ajax : 1}), function(res){
					if (res.result){
						$form.hide();
						$a.show();
						$div.width(w);
					}
					if (res.msg) showError(res.msg);
				}, 'json');
				return false;
			});
		});
		
	}

})(jQuery);

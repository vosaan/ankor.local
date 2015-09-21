(function($){
	
	$.fn.jFilterList = function(o){
		var options = {
			'wrapper'	: null,
			'orderby'	: 'Id desc',
			'posUrl'	: null,
			'txtDelete'	: 'Delete?',
			'onInit'	: function() {},
			'onMove'	: function() {}
		};
		$.extend(options, o || {});
		
		return this.each(function(){
			var $list = $(this), $wrap = null, orderby = options.orderby;
			
			if (typeof options.wrapper == 'object' || options.wrapper.substr(0, 1) == '#')
				$wrap = $(options.wrapper);
			else
				$wrap = $list.parents(options.wrapper+':first');
			
			if (!$wrap || $wrap.size() == 0) $wrap = $('body');
			
			$list.find('a.orderby').click(function(){
				var arr = $(this).attr('href').split('#'), value = arr[1], form = false;
				value += '-'+($(this).is('.orderby-asc') ? 'desc' : 'asc')
				
				$wrap.find('form.filter-form:first').each(function(){
					$(this).append('<input type="hidden" name="sort" value="'+value+'" />');
					$(this).submit();
					form = true;
				});
				if (!form){
					$(this).attr('href', '?sort='+value);
					return true;
				}
				return false;
			});
			
			var arr = orderby.split(' ');
			$list.find('a.orderby[href$=#'+arr[0]+']').each(function(){
				$(this).addClass('orderby-'+(arr[1] && arr[1] == 'desc' ? 'desc' : 'asc'));
			});
			
			$wrap.find('form.filter-form select').change(function(){
				$(this).parents('form:first').submit();
			});
			
			$list.find('a[href$="#delete"]').click(function(){
				if (!confirm(options.txtDelete)) return false;
				var $this = $(this), $li = $this.parents('li:first');
				if (!$li.size()) $li = $this.parents('tr:first');
				$.post($(this).attr('href'), {ajax : 1}, function(res){
					if (res.result) $li.remove();
					if (res.msg) alert(res.msg);
				}, 'json');
				return false;
			});
	
			if (options.posUrl && $.fn.orderPosition){
				$list.find('ul.ui-sortable').orderPosition(options.posUrl, {onStop : options.onMove});
			}
	
			$wrap.find('form.filter-form').each(function(){
				options.onInit.call(this);
			});
		
		});
	}	
	
})(jQuery)

(function($){
	
	$.fn.jSmallTable = function(o){
		
		var options = {
			sortable	: true,
			hideOnDelete : false,
			checkboxImg : null
		};
		$.extend(options, o || {});
		
		return this.each(function(){
			var $table = $(this);

			$table.find('> tbody').sortable();

			var initRows = function(){
				$table.find(':input:last').unbind('keydown').keydown(function(e){
					if (e.keyCode == 9 && !e.shiftKey){
						var $tr = $(this).parents('tr:first'), req = true;
						$tr.find(':input.required').each(function(){
							if ($(this).val() == ''){
								req = false;
								return false;
							}
						});
						if ($tr.next('tr').size() == 0 && req){
							var $n = $tr.clone();
							$n.find('input').val('');
							$n.addClass('new-row').appendTo($table.find('> tbody'));
							$table.find('> tbody tr:last input:first').focus();
							initRows();
						}
					}
				});

				$table.find('input[name*="Delete"]').unbind('change').change(function(){
					if ($table.find('> tbody tr').size() > 1) {
						$(this).parents('tr:first').each(function(){
							if (options.hideOnDelete && !$(this).is('.new-row')) $(this).hide(); else $(this).remove();
						});
						initRows();
					}
				});
				
				if (options.checkboxImg){
					$table.find('input[name*="Delete"]').each(function(){
						var $p = $(this).parent();
						if (!$p.is('.icon-wrap')){
							$(this).wrap('<a class="icon-wrap" href="#delete-row" />');
							$p = $(this).parent();
							$('<img src="'+options.checkboxImg+'" alt="" />').appendTo($p);
							$p.find('input').hide();
						}
					});
					
					$table.find('a[href$="#delete-row"]').unbind('click').click(function(){
						$(this).parent().find('input').attr('checked', true).change();
						return false;
					});
				}
			}

			initRows();

			var fixHelper = function(e, ui) {
				ui.children().each(function() {
					$(this).width($(this).width());
				});
				return ui;
			};		

			if (options.sortable) $table.find('> tbody').sortable({
				helper : fixHelper
			});

		})
		
	}
	
})(jQuery);
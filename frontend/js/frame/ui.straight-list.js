(function($){

	$.fn.jStraightList = function(o){
		var options = {
			rows	: 4,
			limit	: 20,
			goTop	: true,
			lightbox : false
		};
		$.extend(options, o || {});
		
		return this.each(function(){
			var $wrap = $(this), $list = $wrap.find('div.js-items'), $pages = $wrap.find('div.js-pages'),
				limit = options.limit, rows = options.rows;
			
			function initImages(){
				if (!options.lightbox) return;
				if ($.ui.rlightbox){
					if ($.ui.rlightbox.global.sets.a && $.ui.rlightbox.global.sets.a.length > 0){
						$.ui.rlightbox.global.sets = {};
					}
					$list.find('a.lightbox').rlightbox({loop : true, preventSameUrl : true});
				} else if ($.fn.prettyPhoto) {
					$list.find('a[rel^="prettyPhoto"]').prettyPhoto();
				}
			}

			function initPages(){
				$pages.find('a').unbind('click').click(function(){
					go(this);
					return false;
				});
			}

			function drawPages(){
				var size = parseInt($list.find('#list-size').text()), $ul = $pages.find('ul'), 
					current = $($ul.find('> li')).index($ul.find('> li.active')) + 1,
					pages = Math.ceil(size / limit);

				if ($pages.find('li').size() != pages){
					$ul.html('');
					for (var i = 0; i < pages; i++){
						var p = i + 1;
						$('<li value="'+p+'"><a href="?page='+p+'#'+p+'">'+p+'</a></li>').appendTo($ul);
					}

					$ul.find('> li.active').removeClass('active');
					$ul.find('> li:eq('+(current - 1)+')').addClass('active');
					if (pages == 1) $pages.hide(); else $pages.show();

					initPages();
				}
				if (current > pages){
					while (current > pages) current--;
					$ul.find('> li.active').removeClass('active');
					$ul.find('> li:eq('+(current - 1)+')').addClass('active');
				}
			}

			function initWidth(force){
				force = force || false;
				var cols = Math.floor($list.width() / $list.find('.item:first').width());
				if (limit == cols * rows && !force) return;
				limit = cols * rows;
				drawPages();
				$pages.find('li.active a').each(function(){
					go(this, null, true);
				});
			}

			// public function
			var go = function(el, extra, noMove){
				extra = extra || {};
				noMove = noMove || false;
				var data = {};
				$.extend(data, extra, {"ajax" : 1, "limit" : limit});
				var url = '', $el = null;
				if (typeof el == 'object'){
					$el = $(el);
					url = $el.is('form') ? $el.attr('action') : $el.attr('href');
				} else {
					url = el;
				}
				$.get(url, data, function(html){
					$list.html(html);
					if ($el){
						if ($el.is('form')){
							$pages.find('li.active').removeClass('active');
							$pages.find('li:first').addClass('active');
						} else {
							$el.parents('ul:first').find('li.active').removeClass('active');
							$el.parents('li:first').addClass('active');
						}
					}
					initImages();
					drawPages();
					if (!noMove && options.goTop) $('html, body').animate({'scrollTop' : $wrap.offset().top+'px'});
				}, 'html');
			}
			
			$wrap.data('jStraightList', {
				"go" : go
			});
			
			initPages();	
			initImages();
			initWidth(true);

			var windowResizing = false;

			$(window).resize(function(){
				if (windowResizing) return;
				windowResizing = true;
				setTimeout(function(){
					windowResizing = false;
					initWidth();
				}, 500);
			});

		});
	}


})(jQuery);

(function($) {

	$(document).ready(function() {
		$(window).bind('load resize', function() {
			var $h = $('#header');
			if ($h.width() < 1551 && $h.width() > 1360) {
				$h.addClass('short-content');
				$('div.callback-box').addClass('short-callback');
				$('.wrap').css('margin', '0 2%');
			} 
			else {
				$h.removeClass('short-content');
				$('div.callback-box').removeClass('short-callback');
				$('.wrap').css('margin', '0 6%');
			}

			$('.intro .row').css({height: 'auto'});
			$('.intro .row').css({height: $('.intro').height() + 'px'});


		});

		$(window).bind('load', function() {
			var $h = $('#header');
			$h.find('.sub-cover td a').each(function() {
				var str = $(this).text();
				var str2 = str.replace(/\s/g, '&nbsp;');
				$(this).html(str2);
			});
			var a = ['.nav03', '.nav04', '.nav06'];
			for (var i = 0; i < a.length; i++) {
				var $c = $h.find(a[i] + ' .sub-cover'), z = $c.width();
				$c.parent('.sub').css({'width': z + 40 + 'px', 'marginLeft': -(z + 40) / 2 + 'px'});
			}
			$h.find('.submenu .sub').css('top', '18px').hide();
			$('#visible').css('top', '125px').show();
		});

		//$('.nav03 .submenu').not('#stop').hover(function(){$(this).children('.sub').show();},function(){$(this).children('.sub').hide();});
		$('.push .minim').click(function() {
			$('.full').animate({top: '0px'}, 400);
			$('.full').find('.nav').delay(400).animate({opacity: 1}, 400);
			$('.mini').delay(200).fadeOut(400);
			$('.mini .sub').css('opacity', 0);
			$('.push .maxim, .push .minim').toggle();
		});
		$('.push .maxim').click(function() {
			$('.full').delay(400).animate({top: '-294px'}, 400);
			$('.full').find('.nav').animate({opacity: 0}, 400);
			$('.mini').delay(400).fadeIn(400);
			$('.mini .sub').delay(400).animate({opacity: 1}, 800);
			$('.push .maxim, .push .minim').toggle();
		});
		$('.faq .title a').click(function() {
			$(this).parent('.title').next('.answer').toggle();
			$(this).toggleClass('open');
		});
		//$('.switch li').click(function(){$('.switch li').removeClass('current');$(this).addClass('current');var a=$(this).attr('id'); $('.switch-case .case').hide(); $('.switch-case .'+a).show();});

		$('.switch li').click(function() {
			$(this).closest('ul').find('li').each(function(){
				$(this).removeClass('current');
			});
			
			$(this).addClass('current');
			var a = $(this).attr('id');
			$(this).closest('.switch').next('.switch-case').find('.case').each(function(){
				$(this).hide();
			})
			$('.switch-case .' + a).show();
		});


		$('.colorize a').click(function() {
			$('.colorize li').removeClass('current');
			$(this).parent('li').addClass('current');
		});

		$("#fileupload").customInputFile({
			filename: "#filename"
		});
		$('#clear').bind('click', function() {
			$("#fileupload").trigger("clear");
			$(this).hide();
		});

		$('form :input.focus:first').focus();

		if ($.fn.jShoppingCart)
			$('#shopping-cart').jShoppingCart();
		$('#shopping-cart-status').click(function() {
			if ($(this).find('a').size())
				redirect($(this).find('a:first').attr('href'));
			return false;
		});

		$('#poll').pollBox();

		if ($.fn.prettyPhoto) {
			$("a[rel^='prettyPhoto']").prettyPhoto();
		}

		if ($.fn.slides)
			$('.slides').slides({
				preload: false,
				effect: 'fade',
				crossfade: true,
				slideSpeed: 350,
				fadeSpeed: 500,
				generateNextPrev: false,
				generatePagination: false
			});

		$('#subscription').jSubscription();

	});

})(jQuery);
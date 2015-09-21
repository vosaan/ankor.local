function scrollTo(el) {
	var top = 0;
	if (typeof el == 'object') {
		top = jQuery(el).offset().top;
	} else {
		top = el;
	}
	$('html, body').animate({scrollTop: top + 'px'}, 'slow');
}

(function($) {

	var rules = {
		"Address": /^.{4,}$/,
		"Date": /^\d{2}\.\d{2}\.\d{4}$/,
		"Email": /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
		"Empty": /^\s*$/,
		"Int": /^\d+$/,
		"Login": /^[a-z0-9\|\-_]{3,30}$/i,
		"Name": /^[^\d]{2,}$/i,
		"Password": /^\S{6,}$/i,
		"Phone"		: /^[\d- \(\)]{7,}$/i,
		"Selected": function(value) {
			if (this.tagName == 'INPUT' && $(this).attr('type') == 'checkbox')
				return $(this).is(':checked');
			if (this.tagName == 'SELECT')
				return $(this).val() != '0' && $(this).val() != '';
			return false;
		},
		"Slug": /^[\w\d\-_\#\?\.\@\&=;,]+$/i,
		"Text": /\S{4,}/,
		"Title": /\S{2,}/,
		"Website": /^[\w\-_\d]{1,}[\w\-_\d\.]*\.[\w]{2,5}$/,
		"Year": function(value) {
			return parseInt(value) > 1900 && parseInt(value) < 2100
		}
	};

	var convertion = {
		"Slug": function(value) {
			return value.toLowerCase().replace(/\s/g, '-').replace(/[^\w\d\-_\#\?\.\@\&=;,]/gi, '').replace(/\-$/g, '').replace(/^\-/g, '');
		}
	};



	$.fn.jForm = function(o) {
		var options = {
			focus: true,
			testUrl: null,
			scrollOnError : false,
			fly: false
		};

		$.extend(options, o || {});

		return this.each(function() {
			if (this.tagName != 'FORM')
				return;

			var $form = $(this), $val = [], $conv = [], $conf = []

			function goTop(error) {
				if (!error)
					error = false;
				$el = error ? $form.find('.error') : $form;
				scrollTo($el.offset().top - 20);
			}

			function testUnique(el) {
				var $this = $(el), $span = $this.parents('.js-row:first').find('.js-test');
				$span.hide();
				if (options.fly)
					$span.addClass('flying');
				if ($this.is('.error') || !options.testUrl)
					return;
				$.get(options.testUrl, {"ajax": 1, "field": $this.attr('name'), "value": $this.val()}, function(res) {
					$span.removeClass('test-avail test-error test-busy');
					if (res.avail) {
						$span.addClass('test-avail');
						if ($this.attr('reason') == 'busy')
							$this.attr('reason', '');
					} else {
						$span.addClass('test-busy');
						$this.addClass('error').attr('reason', 'test');
					}
					if (res.text)
						$span.text(res.text);
					$span.show();
				}, "json");
			}

			function validationTest() {
				if ($(this).is('[reason="confirm"]'))
					return;
				var unique = false, ok = false, arr = $(this).data('validationRules') || [];
				for (var i = 0; i < arr.length; i++) {
					if (arr[i] == 'unique') {
						unique = true;
						continue;
					}
					if (rules[arr[i]]) {
						var rule = rules[arr[i]];
						if (rule instanceof RegExp) {
							ok = ok | rule.test($(this).val());
						} else {
							//if (typeof rule == 'function'){ // does not work in chrome
							ok = ok | rule.call(this, $(this).val());
						}
					} else if (arr[i].substr(0, 8) == 'ReferTo=') {
						ok = $(this).parents('form:first').find(':input[name="' + arr[i].substr(8) + '"]').val() == $(this).val();
					}
				}
				var $span = $(this).parents('.js-row:first').find('.js-error');
				if (ok) {
					$(this).removeClass('error').attr('reason', '');
					$span.removeClass('error');
					if (!options.fly)
						$span.hide();
				} else {
					$(this).addClass('error').attr('reason', 'error');
					$span.addClass('error');
					if (!options.fly)
						$span.show();
				}
				if (unique)
					testUnique(this);
			}

			function convertionDo() {
				var arr = $(this).data('convertionRule'), $el = $(arr[1]);
				if (!convertion[arr[0]])
					return;
				var value = convertion[arr[0]].call(this, $(this).val());
				$el.each(function() {
					if (this.tagName == 'INPUT' || this.tagName == 'TEXTAREA')
						$(this).val(value);
					else
						$(this).text(value);
				});
			}

			function confirmDo() {
				var field = $(this).data('confirmField');
				if (!field || $(this).is('[reason="error"]'))
					return;
				var $el = $('#' + field);
				var $span = $(this).parents('.js-row:first').find('.js-confirm');
				if ($el.val() == $(this).val()) {
					$(this).removeClass('error').attr('reason', '');
					$span.removeClass('error');
					if (!options.fly)
						$span.hide();
				} else {
					$(this).addClass('error').attr('reason', 'confirm');
					$span = $span.addClass('error');
					if (!options.fly)
						$span.show();
				}
			}

			$form.find(':input').each(function() {
				if (!$(this).attr('rel'))
					return;
				var arr = $(this).attr('rel').split(';');
				for (var i = 0; i < arr.length; i++) {
					if (/^validate\(.+\)$/.test(arr[i])) {
						var str = arr[i].replace('validate(', '').replace(')', '');
						$(this).data('validationRules', str.split('|'));
						$val.push(this);
					} else if (/^convert\(.+\)$/.test(arr[i])) {
						var str = arr[i].replace('convert(', '').replace(')', ''), arr = str.split(',');
						if (arr.length != 2)
							continue;
						var $el = arr[1].substr(0, 1) == '#' ? $(arr[1]) : $form.find(arr[1]);
						$(this).data('convertionRule', [arr[0], $el]);
						$conv.push(this);
					} else if (/^confirm\(.+\)$/.test(arr[i])) {
						var str = arr[i].replace('confirm(', '').replace(')', '');
						$(this).data('confirmField', str);
						$conf.push(this);
					}
				}
			});

			$.each($val, function(i, el) {
				$(el).bind('change', validationTest);
				if (options.fly) {
					var $row = $(el).parents('.js-row:first');
					$(el).hover(function() {
						var $span = $row.find('.js-' + $(this).attr('reason'));
						if (!$span.is('.flying'))
							$span.addClass('flying');
						if ($(this).hasClass('error'))
							$row.addClass('hover'); //$span.show();
					}, function() {
						var $span = $row.find($(this).attr('reason') == 'error' ? '.js-error' : '.js-test');
						if ($(this).hasClass('error'))
							$row.removeClass('hover'); //$span.removeClass('flying').hide();
					});
				}
			});

			$.each($conv, function(i, el) {
				$(el).bind('change', convertionDo);
			});

			$.each($conf, function(i, el) {
				$(el).bind('change', confirmDo);
			});

			//$form.find(':input[rel^="validate("]').change(function(){
			//});

			if ($form.find(':submit').size() > 1 && $form.find(':submit.default').size() == 1) {
				$form.find('input').keypress(function(e) {
					if (e.which && e.which == 13) {
						$form.find(':submit.default').click();
						return false;
					}
					return true;
				});
			}

			var preventScroll = function(bool) {
				options.scrollOnError = !bool;
			}

			$form.data('jForm', {preventScroll: preventScroll});

			$form.submit(function(o) {
				$.each($val, function(i, el) {
					$(el).change();
				});
				$form.removeClass('js-failed');
				if ($form.find(':input.error').size() > 0) {
					if (options.focus && options.scrollOnError)
						goTop(true);
					$form.addClass('js-failed');
					return false;
				}
				return true;
			});

		});
	}

	$.fn.jAjaxForm = function(o) {

		var options = {
			onClose: function() {
			},
			onMessageClose: function() {
			},
			onResult: function() {
			},
			popup: false,
		};
		$.extend(options, o || {});

		return this.each(function() {

			var $form = $(this);

			function processMsg(msg, type) {
				if (options.popup) {
					if (type == 'message')
						showMessage(msg);
					else
						showError(msg);
					return null;
				}
				if (typeof msg == 'object')
					msg = msg.join("<br />");
				return $('<blockquote class="' + type + '" />').html(msg).appendTo($form.find('div.response'));
			}

			function processResult(res) {
				options.onResult.call($form.get(0), res);
				if (res.result) {
					if (res.msg) {
						if (options.popup) {
							showMessage(res.msg);
						} else {
							$form.find('fieldset').slideUp('fast');
							processMsg(res.msg, 'message');
						}
					}
					if (res.html) {
						$(res.html).appendTo($form.find('div.response'));
					}
					if (res.url) {
						$.timer(res.timeout || 5000, function(timer) {
							timer.stop();
							redirect(res.url);
						});
					}
				} else if (res.msg) {
					var $b = processMsg(res.msg, 'error');
					if ($b) {
						$.timer(5000, function(timer) {
							timer.stop();
							$b.slideUp(function() {
								$b.remove();
								options.onMessageClose.call($form.get(0), false);
							})
						});
					}
				}
				if (res.callback) {
					if (res.callback == 'close') {
						$.timer(res.timeout || 5000, function(timer) {
							timer.stop();
							options.onClose.call($form.get(0), res);
							$form.find('fieldset').hide();
						});
						
						
						
					}
				}
			}

			if ($form.attr('enctype') && $form.attr('enctype').toLowerCase() == 'multipart/form-data') {
				// uploading files through iframe
				var target = $form.attr('target') || ($form.attr('id') + '-frame');
				var $frame = $('#' + target);
				if (!$frame.size()) {
					$frame = $('<iframe frameborder="0" width="0" height="0" style="display: none" />')
							.attr('src', $form.attr('action')).attr('name', target);
					$frame.prependTo($('body:first'));
					$form.attr('target', target);
				}

				$frame.load(function() {
					var html = $frame.contents().find('body:first').html();
					if (html.substr(0, 1) == '{') {
						var res = eval("(" + html + ")");
						processResult(res);
					}
				});

				if (!$form.is('.multipart-form-data'))
					$form.addClass('multipart-form-data');
			}


			$form.jForm($.extend(options, o || {scrollable: false, })).submit(function() {
				if ($form.is('.js-failed'))
					return false;
				if ($form.is('.multipart-form-data'))
					return true;

				$.post($form.attr('action'), $form.getFields({"ajax": 1, "submit": 1}), function(res) {
					processResult(res);
				}, "json");
				return false;
			});

		});
	}

	$.fn.jDialog = function(o) {

		var action = null,
				options = {
			fixed: true,
			hideScroll: false,
			calcLeft: true,
			onInit: function() {
			},
			onShow: function() {
			}
		};
		if (typeof o == 'object')
			$.extend(options, o || {});
		else
			action = o;

		function show(el) {
			var options = $(el).data('jDialogOptions') || options;
			var $box = $(el).show(), $body = $box.find('> .dialog-body'), $mask = $box.find('> .dialog-mask'),
					left = parseInt(($(window).width() - $body.outerWidth(true)) / 2),
					top = parseInt(($(window).height() - $body.outerHeight(true)) / 2);
			$box.css('position', 'absolute').css('left', 0).css('top', 0).css('width', '100%').css('height', $(document).height());
			if (!options.fixed)
				top += $(window).scrollTop();
			$body.css('top', top);
			if (options.fixed)
				$body.css('position', 'fixed');
			if (options.calcLeft)
				$body.css('left', left);

			if (options.hideScroll)
				$('body').css('overflow', 'hidden');
			options.onShow.call(el);
		}

		function hide(el) {
			var options = $(el).data('jDialogOptions');
			$(el).hide();
			if (options.hideScroll)
				$('body').css('overflow', 'auto');
		}

		function destroy(el) {
			var options = $(el).data('jDialogOptions');
			$(el).remove();
			if (options.hideScroll)
				$('body').css('overflow', 'auto');
		}

		return this.each(function() {

			switch (action) {
				case 'show':
					show(this);
					return true;

				case 'hide':
					hide(this);
					return true;

				case 'destroy':
					destroy(this);
					return true;
			}
			$(this).data('jDialogOptions', options);
			options.onInit.call(this);
		});
	}
})(jQuery)

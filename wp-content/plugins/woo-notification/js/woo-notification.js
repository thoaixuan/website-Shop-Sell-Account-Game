'use strict';
jQuery(document).ready(function () {
	if (jQuery('#message-purchased').length > 0) {
		var notify = woo_notification;

		if (_woocommerce_notification_params.billing == 0 && _woocommerce_notification_params.detect == 0) {
			notify.detect_address();
		}
	}

});
jQuery(window).load(function () {
	var notify = woo_notification;
	notify.ajax_url = _woocommerce_notification_params.ajax_url;
	notify.products = _woocommerce_notification_params.products;
	notify.messages = _woocommerce_notification_params.messages;
	notify.image = _woocommerce_notification_params.image;
	notify.redirect_target = _woocommerce_notification_params.redirect_target;
	notify.time = _woocommerce_notification_params.time;
	notify.display_effect = _woocommerce_notification_params.display_effect;
	notify.hidden_effect = _woocommerce_notification_params.hidden_effect;
	notify.messages = _woocommerce_notification_params.messages;
	notify.names = _woocommerce_notification_params.names;
	notify.detect = _woocommerce_notification_params.detect;
	notify.billing = _woocommerce_notification_params.billing;
	notify.message_custom = _woocommerce_notification_params.message_custom;
	notify.message_number_min = _woocommerce_notification_params.message_number_min;
	notify.message_number_max = _woocommerce_notification_params.message_number_max;
	notify.time_close = _woocommerce_notification_params.time_close;
	notify.show_close = _woocommerce_notification_params.show_close;
	if (_woocommerce_notification_params.billing == 0 && _woocommerce_notification_params.detect == 0) {
		notify.cities = [notify.getCookie('wn_city')];
		notify.country = [notify.getCookie('wn_country')];
		var check_ip = notify.getCookie('wn_ip')
		if (check_ip && check_ip != 'undefined') {
			notify.init();
		}
	} else {
		notify.cities = _woocommerce_notification_params.cities;
		notify.country = _woocommerce_notification_params.country;
		notify.init();
	}

})


var woo_notification = {
	billing           : 0,
	in_the_same_cate  : 0,
	loop              : 0,
	init_delay        : 5,
	total             : 30,
	display_time      : 10,
	next_time         : 60,
	count             : 0,
	intel             : 0,
	wn_popup          : 0,
	id                : 0,
	messages          : '',
	products          : '',
	ajax_url          : '',
	display_effect    : '',
	hidden_effect     : '',
	time              : '',
	names             : '',
	cities            : '',
	country           : '',
	message_custom    : '',
	message_number_min: '',
	message_number_max: '',
	detect            : 0,
	time_close        : 0,
	show_close        : 0,

	shortcodes    : ['{first_name}', '{city}', '{state}', '{country}', '{product}', '{product_with_link}', '{time_ago}', '{custom}'],
	init          : function () {
		if (this.ajax_url) {
			this.ajax_get_data();
		} else {
			setTimeout(function () {
				woo_notification.get_product();
			}, this.init_delay * 1000);
		}
		jQuery('#message-purchased').on('mouseenter', function () {
			window.clearInterval(woo_notification.wn_popup);
		}).on('mouseleave', function () {
			woo_notification.message_show()
		});
	},
	detect_address: function () {
		var ip_address = this.getCookie('wn_ip');
		if (!ip_address) {

			jQuery.getJSON('https://extreme-ip-lookup.com/json/', function (data) {
				if (data.query) {
					woo_notification.setCookie('wn_ip', data.query, 86400);
				}
				if (data.city) {
					woo_notification.setCookie('wn_city', data.city, 86400);
				}
				if (data.country) {
					woo_notification.setCookie('wn_country', data.country, 86400);
				}
			});
		}

	},
	ajax_get_data : function () {
		if (this.ajax_url) {
			var str_data;
			if (this.id) {
				str_data = '&id=' + this.id;
			} else {
				str_data = '';
			}
			jQuery.ajax({
				type   : 'POST',
				data   : 'action=woonotification_get_product' + str_data,
				url    : this.ajax_url,
				success: function (data) {
					var products = jQuery.parseJSON(data);

					if (products && products != 'undefined' && products.length > 0) {
						woo_notification.products = products;
						woo_notification.message_show();
						setTimeout(function () {
							woo_notification.get_product();
						}, woo_notification.init_delay * 1000);
					}
				},
				error  : function (html) {
				}
			})
		}
	},
	message_show  : function () {
		var count = this.count++;
		if (this.total <= count) {
			return;
		}
		window.clearInterval(this.intel);
		var message_id = jQuery('#message-purchased');
		if (message_id.hasClass(this.hidden_effect)) {
			jQuery(message_id).removeClass(this.hidden_effect);
		}
		jQuery(message_id).addClass(this.display_effect).css('display', 'flex');

		this.wn_popup = setTimeout(function () {
			woo_notification.message_hide();
		}, this.display_time * 1000);
	},

	message_hide   : function () {


		var message_id = jQuery('#message-purchased');

		if (message_id.hasClass(this.display_effect)) {
			jQuery(message_id).removeClass(this.display_effect);
		}
		jQuery('#message-purchased').addClass(this.hidden_effect);
		jQuery('#message-purchased').fadeOut(1000);
		window.clearInterval(this.wn_popup);
		if (this.getCookie('woo_notification_close')) {
			return;
		}
		if (this.loop) {
			this.intel = setInterval(function () {
				woo_notification.get_product();
			}, this.next_time * 1000);
		}
	},
	get_time_string: function () {
		var time_cal = this.random(0, this.time * 3600);
		/*Check day*/
		var check_time = parseFloat(time_cal / 86400);
		if (check_time > 1) {
			check_time = parseInt(check_time);
			if (check_time == 1) {
				return check_time + ' ' + _woocommerce_notification_params.str_day
			} else {
				return check_time + ' ' + _woocommerce_notification_params.str_days
			}
		}
		check_time = parseFloat(time_cal / 3600);
		if (check_time > 1) {
			check_time = parseInt(check_time);
			if (check_time == 1) {
				return check_time + ' ' + _woocommerce_notification_params.str_hour
			} else {
				return check_time + ' ' + _woocommerce_notification_params.str_hours
			}
		}
		check_time = parseFloat(time_cal / 60);
		if (check_time > 1) {
			check_time = parseInt(check_time);
			if (check_time == 1) {
				return check_time + ' ' + _woocommerce_notification_params.str_min
			} else {
				return check_time + ' ' + _woocommerce_notification_params.str_mins
			}
		} else if (check_time < 10) {
			return _woocommerce_notification_params.str_few_sec
		} else {
			check_time = parseInt(check_time);
			return check_time + ' ' + _woocommerce_notification_params.str_secs
		}
	},
	get_product    : function () {

		var products = this.products;
		var messages = this.messages;
		var image_redirect = this.image;
		var redirect_target = this.redirect_target;
		var data_first_name, data_state, data_country, data_city, time_str, index;
		if (products == 'undefined' || !products || !messages) {
			return;
		}
		if (products.length > 0 && messages.length > 0) {
			/*Get message*/
			index = woo_notification.random(0, messages.length - 1);
			var string = messages[index];

			/*Get product*/
			index = woo_notification.random(0, products.length - 1);
			var product = products[index];

			/*Get name*/
			if (parseInt(this.billing) > 0 && parseInt(this.in_the_same_cate) < 1) {

				data_first_name = product.first_name;
				data_city = product.city;
				data_state = product.state;
				data_country = product.country;
				time_str = product.time;

			} else {

				if (this.names && this.names != 'undefined') {
					index = woo_notification.random(0, this.names.length - 1);
					data_first_name = this.names[index];
				} else {
					data_first_name = '';
				}
				if (this.cities && this.cities != 'undefined') {
					index = woo_notification.random(0, this.cities.length - 1);
					data_city = this.cities[index];
				} else {
					data_city = '';
				}


				data_state = '';
				data_country = this.country;

				time_str = this.get_time_string();
			}

			var data_product = '<span class="wn-popup-product-title">' + product.title + '</span>';
			var data_product_link = '<a ';
			if (redirect_target) {
				data_product_link += 'target="_blank"';
			}
			data_product_link += ' href="' + product.url + '">' + product.title + '</a>';
			var data_time = '<small>' + _woocommerce_notification_params.str_about + ' ' + time_str + ' ' + _woocommerce_notification_params.str_ago + ' </small>';
			var data_custom = this.message_custom;
			var image_html = '';
			if (product.thumb) {
				if (image_redirect) {
					image_html = '<a ';
					if (redirect_target) {
						image_html += 'target="_blank"';
					}
					image_html += ' href="' + product.url + '"><img src="' + product.thumb + '"></a>'
				} else {
					image_html = '<img src="' + product.thumb + '">';
				}
			}
			/*Replace custom message*/
			data_custom = data_custom.replace('{number}', this.random(this.message_number_min, this.message_number_max));
			/*Replace message*/
			var replaceArray = this.shortcodes;
			var replaceArrayValue = [data_first_name, data_city, data_state, data_country, data_product, data_product_link, data_time, data_custom];
			var finalAns = string;
			for (var i = replaceArray.length - 1; i >= 0; i--) {
				finalAns = finalAns.replace(replaceArray[i], replaceArrayValue[i]);
			}
			var close_html = '';
			if (parseInt(this.show_close) > 0) {
				close_html = '<div id="notify-close"></div>'
			}
			var html = image_html + '<p>' + finalAns + '</p>' + close_html;
			jQuery('#message-purchased').html(html);
			this.close_notify();
			woo_notification.message_show();
		}

	},
	close_notify   : function () {
		jQuery('#notify-close').unbind();
		jQuery('#notify-close').bind('click', function () {
			woo_notification.message_hide();
			if (parseInt(woo_notification.time_close) > 0) {
				jQuery('#message-purchased').unbind();
				woo_notification.setCookie('woo_notification_close', 1, 3600 * parseInt(woo_notification.time_close));
			}
		});
	},
	random         : function (min, max) {
		min = parseInt(min);
		max = parseInt(max);
		var rand_number = Math.random() * (max - min);
		return Math.round(rand_number) + min;
	},
	setCookie      : function (cname, cvalue, expire) {
		var d = new Date();
		d.setTime(d.getTime() + (expire * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	},

	getCookie: function (cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for (var i = 0; i < ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}
}

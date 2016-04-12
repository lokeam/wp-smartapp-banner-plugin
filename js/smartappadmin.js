jQuery(document).ready(function($) {
	$.fn.serializeObject = function()
	{
	    var o = {};
	    var a = this.serializeArray();
	    $.each(a, function() {
	        if (o[this.name] !== undefined) {
	            if (!o[this.name].push) {
	                o[this.name] = [o[this.name]];
	            }
	            o[this.name].push(this.value || '');
	        } else {
	            o[this.name] = this.value || '';
	        }
	    });
	    return o;
	};

	var smartAppBanner = {
		init: function() {
			var self = this;
			self._checkIcon();
			self.toggleField();
		},
		removeField: function() {
			var self = this;
				$('#mobileAppIcon').addClass('SmartAppBanner-hide');
		},
		toggleField: function() {
			var self = this;
			$('#usetownsquaresettings').change(function(){
				$('#mobileAppIcon').toggleClass('SmartAppBanner-hide');
			});
		},
		_checkIcon: function() {
			var self = this;
			$(document).ready(function(){
				if ( $('#uTSSettings').hasClass('usetss') ) {
					console.log('has class');
					self.removeField();
				}
			});
		}
	}

	$(function() {
		smartAppBanner.init();
	});

	window.smartAppBanner = smartAppBanner;
});
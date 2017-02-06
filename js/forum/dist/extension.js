'use strict';

System.register('flarum/auth/ssowat/main', ['flarum/extend', 'flarum/app', 'flarum/components/LogInButtons', 'flarum/components/LogInButton', 'flarum/components/Button'], function (_export, _context) {
	"use strict";

	var extend, app, LogInButtons, LogInButton, Button;
	return {
		setters: [function (_flarumExtend) {
			extend = _flarumExtend.extend;
		}, function (_flarumApp) {
			app = _flarumApp.default;
		}, function (_flarumComponentsLogInButtons) {
			LogInButtons = _flarumComponentsLogInButtons.default;
		}, function (_flarumComponentsLogInButton) {
			LogInButton = _flarumComponentsLogInButton.default;
		}, function (_flarumComponentsButton) {
			Button = _flarumComponentsButton.default;
		}],
		execute: function () {

			app.initializers.add('flarum-auth-ssowat', function () {

				extend(LogInButtons.prototype, 'items', function (items) {
					items.add('ssowat', Button.component({
						className: 'Button LogInButton LogInButton--ssowat',
						icon: 'address-book',
						children: app.translator.trans('flarum-auth-ssowat.forum.log_in_with'),
						onclick: function onclick() {
							var width = 600;
							var height = 500;
							var $window = $(window);
							var path = window.location.protocol + "//" + window.location.hostname + "/yunohost/sso/?r=" + window.btoa(window.location.href + "auth/ssowat");
							window.open(path, 'logInPopup', 'width=' + width + ',' + ('height=' + height + ',') + ('top=' + ($window.height() / 2 - height / 2) + ',') + ('left=' + ($window.width() / 2 - width / 2) + ',') + 'status=no,scrollbars=no,resizable=no');
						}
					}));
				});
			});
		}
	};
});
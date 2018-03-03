'use strict';

System.register('tituspijean-auth-ssowat/main', ['flarum/extend', 'flarum/app', 'flarum/components/HeaderSecondary', 'flarum/components/SessionDropdown', 'flarum/components/SettingsPage', 'flarum/components/LogInButtons', 'flarum/components/LogInButton', 'flarum/components/Button', 'flarum/utils/ItemList'], function (_export, _context) {
	"use strict";

	var extend, app, HeaderSecondary, SessionDropdown, SettingsPage, LogInButtons, LogInButton, Button, ItemList, translationPrefix;
	return {
		setters: [function (_flarumExtend) {
			extend = _flarumExtend.extend;
		}, function (_flarumApp) {
			app = _flarumApp.default;
		}, function (_flarumComponentsHeaderSecondary) {
			HeaderSecondary = _flarumComponentsHeaderSecondary.default;
		}, function (_flarumComponentsSessionDropdown) {
			SessionDropdown = _flarumComponentsSessionDropdown.default;
		}, function (_flarumComponentsSettingsPage) {
			SettingsPage = _flarumComponentsSettingsPage.default;
		}, function (_flarumComponentsLogInButtons) {
			LogInButtons = _flarumComponentsLogInButtons.default;
		}, function (_flarumComponentsLogInButton) {
			LogInButton = _flarumComponentsLogInButton.default;
		}, function (_flarumComponentsButton) {
			Button = _flarumComponentsButton.default;
		}, function (_flarumUtilsItemList) {
			ItemList = _flarumUtilsItemList.default;
		}],
		execute: function () {
			translationPrefix = 'tituspijean-auth-ssowat.forum.';


			app.initializers.add('tituspijean-auth-ssowat', function () {

				extend(HeaderSecondary.prototype, 'items', addLoginLink);
				extend(LogInButtons.prototype, 'items', addLoginButton);
				extend(SessionDropdown.prototype, 'items', replaceLogOutButton);

				function addLoginLink(items) {
					if (app.forum.attribute('ssowat.onlyUse')) {
						if (items.has('signUp')) {
							items.remove('signUp');
						}
						if (items.has('logIn')) {
							items.remove('logIn');
							var width = 600;
							var height = 700;
							var $window = $(window);
							items.add('ssowatLogIn', Button.component({
								children: app.translator.trans(translationPrefix + 'log_in_with'),
								className: 'Button Button--link',
								onclick: function onclick() {
									return window.open(app.forum.attribute('baseUrl') + '/ssowat/login', 'logInPopup', 'width=' + width + ',' + ('height=' + height + ',') + ('top=' + ($window.height() / 2 - height / 2) + ',') + ('left=' + ($window.width() / 2 - width / 2) + ',') + 'status=no,scrollbars=no,resizable=yes');
								}
							}), 0);
						}
					}
				}

				function addLoginButton(items) {
					items.add('ssowat', m(
						LogInButton,
						{ className: 'Button LogInButton--ssowat',
							icon: 'address-book',
							path: '/ssowat/login' },
						app.translator.trans(translationPrefix + 'log_in_with')
					));
				}

				function logout() {
					window.location = app.forum.attribute('baseUrl') + '/ssowat/logout?token=' + app.session.csrfToken;
				}

				function replaceLogOutButton(items) {
					if (app.forum.attribute('ssowat.user')) items.replace('logOut', Button.component({
						icon: 'fa fa-sign-out-alt',
						children: app.translator.trans('core.forum.header.log_out_button'),
						onclick: logout.bind()
					}), -100);
				}
			});
		}
	};
});
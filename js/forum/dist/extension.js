'use strict';

System.register('tituspijean/flarum-ext-auth-ssowat/main', ['flarum/extend', 'flarum/app', 'flarum/components/LogInButtons', 'flarum/components/LogInButton'], function (_export, _context) {
	"use strict";

	var extend, override, app, LogInButtons, LogInButton;
	return {
		setters: [function (_flarumExtend) {
			extend = _flarumExtend.extend;
			override = _flarumExtend.override;
		}, function (_flarumApp) {
			app = _flarumApp.default;
		}, function (_flarumComponentsLogInButtons) {
			LogInButtons = _flarumComponentsLogInButtons.default;
		}, function (_flarumComponentsLogInButton) {
			LogInButton = _flarumComponentsLogInButton.default;
		}],
		execute: function () {

			app.initializers.add('tituspijean-flarum-ext-auth-ssowat', function () {

				extend(LogInButtons.prototype, 'items', function (items) {
					items.add('ssowat', m(
						LogInButton,
						{
							className: 'Button LogInButton--ssowat',
							icon: 'address-book',
							path: '/auth/ssowat' },
						app.translator.trans('flarum-ext-auth-ssowat.forum.log_in_with')
					));
				});
			});
		}
	};
});
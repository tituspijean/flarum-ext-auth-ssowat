'use strict';

System.register('tituspijean-auth-ssowat/components/SSOwatSettingsModal', ['flarum/app', 'flarum/components/SettingsModal', 'flarum/components/Switch'], function (_export, _context) {
	"use strict";

	var app, SettingsModal, Switch, settingsPrefix, translationPrefix, SSOwatSettingsModal;
	return {
		setters: [function (_flarumApp) {
			app = _flarumApp.default;
		}, function (_flarumComponentsSettingsModal) {
			SettingsModal = _flarumComponentsSettingsModal.default;
		}, function (_flarumComponentsSwitch) {
			Switch = _flarumComponentsSwitch.default;
		}],
		execute: function () {
			settingsPrefix = 'tituspijean-auth-ssowat.';
			translationPrefix = 'tituspijean-auth-ssowat.admin.settings.';

			SSOwatSettingsModal = function (_SettingsModal) {
				babelHelpers.inherits(SSOwatSettingsModal, _SettingsModal);

				function SSOwatSettingsModal() {
					babelHelpers.classCallCheck(this, SSOwatSettingsModal);
					return babelHelpers.possibleConstructorReturn(this, (SSOwatSettingsModal.__proto__ || Object.getPrototypeOf(SSOwatSettingsModal)).apply(this, arguments));
				}

				babelHelpers.createClass(SSOwatSettingsModal, [{
					key: 'title',
					value: function title() {
						return app.translator.trans(translationPrefix + 'title');
					}
				}, {
					key: 'form',
					value: function form() {
						return [m('.Form-group', [m('label', app.translator.trans(translationPrefix + 'domain')), m('input.FormControl', {
							bidi: this.setting(settingsPrefix + 'domain'),
							placeholder: ''
						})]), m('.Form-group', [m('label', Switch.component({
							state: [true, '1'].indexOf(this.setting(settingsPrefix + 'onlyUse')()) !== -1,
							onchange: this.setting(settingsPrefix + 'onlyUse'),
							children: app.translator.trans(translationPrefix + 'onlyUse')
						}))])];
					}
				}]);
				return SSOwatSettingsModal;
			}(SettingsModal);

			_export('default', SSOwatSettingsModal);
		}
	};
});;
'use strict';

System.register('tituspijean-auth-ssowat/main', ['flarum/extend', 'flarum/app', 'tituspijean-auth-ssowat/components/SSOwatSettingsModal'], function (_export, _context) {
	"use strict";

	var extend, app, SSOwatSettingsModal;
	return {
		setters: [function (_flarumExtend) {
			extend = _flarumExtend.extend;
		}, function (_flarumApp) {
			app = _flarumApp.default;
		}, function (_tituspijeanAuthSsowatComponentsSSOwatSettingsModal) {
			SSOwatSettingsModal = _tituspijeanAuthSsowatComponentsSSOwatSettingsModal.default;
		}],
		execute: function () {

			app.initializers.add('tituspijean-auth-ssowat', function (app) {
				app.extensionSettings['tituspijean-auth-ssowat'] = function () {
					return app.modal.show(new SSOwatSettingsModal());
				};
			});
		}
	};
});
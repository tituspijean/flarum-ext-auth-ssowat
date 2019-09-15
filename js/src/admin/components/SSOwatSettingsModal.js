import SettingsModal from 'flarum/components/SettingsModal';
import Switch from 'flarum/components/Switch';

const settingsPrefix = 'tituspijean-auth-ssowat.';
const translationPrefix = 'tituspijean-auth-ssowat.admin.settings.';

export default class SSOwatSettingsModal extends SettingsModal {
	title() {
		return app.translator.trans(translationPrefix + 'title');
	}

	form() {
		return [
			m('.Form-group', [
				m('label', app.translator.trans(translationPrefix + 'domain')),
				m('input.FormControl', {
					bidi: this.setting(settingsPrefix + 'domain'),
					placeholder: '',
				}),
			]),
			m('.Form-group', [
				m('label', Switch.component({
					state: [true, '1'].indexOf(this.setting(settingsPrefix + 'onlyUse')()) !== -1,
					onchange: this.setting(settingsPrefix + 'onlyUse'),
					children: app.translator.trans(translationPrefix + 'onlyUse'),
				})),
			]),
		];
	}
}

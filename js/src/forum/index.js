import { extend } from 'flarum/extend';
import app from 'flarum/app';

import HeaderSecondary from 'flarum/components/HeaderSecondary';
import SessionDropdown from "flarum/components/SessionDropdown";
import SettingsPage from "flarum/components/SettingsPage";
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';
import Button from 'flarum/components/Button';
import ItemList from 'flarum/utils/ItemList';

const translationPrefix = 'tituspijean-auth-ssowat.forum.';

app.initializers.add('tituspijean-auth-ssowat', () => {

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
				const width = 600;
				const height = 900;
				const $window = $(window);
				items.add('ssowatLogIn', Button.component({
					children: app.translator.trans(translationPrefix + 'log_in_with'),
					className: 'Button Button--link',
					onclick: () => window.open(app.forum.attribute('baseUrl') + '/ssowat/login', 'logInPopup',
						`width=${width},` +
						`height=${height},` +
						`top=${$window.height() / 2 - height / 2},` +
						`left=${$window.width() / 2 - width / 2},` +
						'status=no,scrollbars=no,resizable=yes')
				}), 0);
			}
		}
	}

	function addLoginButton(items) {
		items.add('ssowat',
			<LogInButton className = "Button LogInButton--ssowat"
				icon = "address-book"
				path = "/ssowat/login" >
				{app.translator.trans(translationPrefix + 'log_in_with')}
			</LogInButton>
		);
	}

	function logout() {
    window.location = app.forum.attribute('baseUrl') + '/ssowat/logout?token=' + app.session.csrfToken;
}

	function replaceLogOutButton(items) {
		if (app.forum.attribute('ssowat.user'))
		items.replace('logOut',
      Button.component({
        icon: 'fa fa-sign-out-alt',
        children: app.translator.trans('core.forum.header.log_out_button'),
        onclick: logout.bind()
      }),
      -100
    );
	}

});

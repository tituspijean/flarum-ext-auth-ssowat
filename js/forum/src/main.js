import { extend } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';
import Button from 'flarum/components/Button';

app.initializers.add('flarum-auth-ssowat', () => {

	extend(LogInButtons.prototype, 'items', function(items) {
		items.add('ssowat',
			Button.component({
				className: 'Button LogInButton LogInButton--ssowat',
				icon: 'address-book',
				children: app.translator.trans('flarum-auth-ssowat.forum.log_in_with'),
				onclick: function () {
					const width = 600;
					const height = 500;
					const $window = $(window);
					const path = window.location.protocol + "//" + window.location.hostname + "/yunohost/sso/?r=" + window.btoa(window.location.href + "auth/ssowat");
					window.open(path, 'logInPopup',
						`width=${width},` +
						`height=${height},` +
						`top=${$window.height() / 2 - height / 2},` +
						`left=${$window.width() / 2 - width / 2},` +
						'status=no,scrollbars=no,resizable=no');
				}
			}),
		);
  	});

});

import { extend, override } from 'flarum/extend';
import app from 'flarum/app';
import LogInButtons from 'flarum/components/LogInButtons';
import LogInButton from 'flarum/components/LogInButton';

app.initializers.add('tituspijean-flarum-ext-auth-ssowat', () => {

	extend(LogInButtons.prototype, 'items', function(items) {
		items.add('ssowat',
			<LogInButton
			className="Button LogInButton--ssowat"
			icon="address-book"
			path="/auth/ssowat">
			{app.translator.trans('flarum-ext-auth-ssowat.forum.log_in_with')}
			</LogInButton>
		);
  	});

});

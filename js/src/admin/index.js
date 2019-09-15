import app from 'flarum/app';

import SSOwatSettingsModal from './components/SSOwatSettingsModal';

app.initializers.add('tituspijean-auth-ssowat', app => {
	app.extensionSettings['tituspijean-auth-ssowat'] = () => app.modal.show(new SSOwatSettingsModal());
});

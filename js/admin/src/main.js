import app from 'flarum/app';

import addSSOwatPage from 'tituspijean/flarum-ext-auth-ssowat/addSSOwatPage';

app.initializers.add('tituspijean-flarum-ext-auth-ssowat', app => {
  addSSOwatPage();
});

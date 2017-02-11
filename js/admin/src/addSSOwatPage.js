import {extend} from "flarum/extend";
import AdminNav from "flarum/components/AdminNav";
import AdminLinkButton from "flarum/components/AdminLinkButton";
import SSOwatPage from "tituspijean/flarum-ext-auth-ssowat/components/SSOwatPage";

export default function () {
    app.routes['tituspijean-flarum-ext-auth-ssowat'] = {path: '/ssowat', component: SSOwatPage.component()};
    app.extensionSettings['tituspijean-flarum-ext-auth-ssowat'] = () => m.route(app.route('tituspijean-flarum-ext-auth-ssowat'));
    extend(AdminNav.prototype, 'items', items => {
        items.add('tituspijean-flarum-ext-auth-ssowat', AdminLinkButton.component({
            href: app.route('tituspijean-flarum-ext-auth-ssowat'),
            icon: 'address-book',
            children: 'SSOwat authentication',
            description: app.translator.trans('flarum-ext-auth-ssowat.admin.description')
        }));
    });
}

# Flarum SSOwat authentication

This extension enables users to log into [Flarum](https://github.com/flarum/core) through [SSOwat](https://github.com/YunoHost/SSOwat).

It perfects integration of the Flarum package for [YunoHost](https://yunohost.org/), [flarum_ynh](https://github.com/YunoHost-Apps/flarum_ynh).

## How to install

Installation is already included in the flarum_ynh package.

Otherwise, you can install it with `composer require tituspijean/flarum-ext-auth-ssowat` and activate it in Flarum's administration panel.

## Configuration

In Flarum's administration panel, you need to specify:

- SSOwat's main domain
- if you want to disable any other authentication method.

=== Minimal Maintenance Mode ===
Contributors: stachredeker
Tags: maintenance mode, simple, minimal, maintenance, under construction
Requires at least: 5.3
Tested up to: 6.6
Stable tag: 1.0.1
License: GPLv3 or later
License URI: https://gnu.org/licenses/gpl-3.0.html
Donate link: https://ko-fi.com/stachredeker

## Description
The Minimal Maintenance Mode plugin is a simple and lightweight solution to enable a maintenance mode on your WordPress website. The plugin is free, fast, and can be deployed within minutes.
When the maintenance mode is active, the plugin displays a customizable message for all not-logged-in users.
It is possible to set a custom secret phrase that users can append to the website URL to get access, even if they are not logged in.

## Features
- Enable a maintenance mode to restrict access to your website.
- Customize the maintenance mode message and heading.
- Automatic bypass for logged-in users and administrators.
- Option to set a secret phrase to bypass maintenance mode via URL parameter.

## Installation
1. Download the latest release of the plugin.
2. Upload the plugin via the plugin manager.
3. Activate the plugin through the WordPress admin interface.
4. Configure the plugin settings by navigating to "Maintenance Mode" in the WordPress admin menu.

## Configuration and usage
To enable maintenance mode and set the message displayed during maintenance, follow these steps:

1. Go to the WordPress admin menu and click on 'Maintenance Mode'.
2. Customize the maintenance mode heading and message.
3. (Optional) Pick a secret phrase to let not-logged-in users bypass the maintenance mode.
4. Save the settings and activate the maintenance mode by pressing 'Save and activate'.

### Bypassing maintenance mode
If you need to access your website during maintenance mode, you can use the following methods:

1. **For administrators and logged-in users**: logged-in users can access the website normally. Also, the `/wp-admin/` pages will not be blocked.
2. **Using the secret phrase**: Append `?[SECRET PHRASE]` to any URL to bypass the maintenance mode for a week. A functional cookie is placed on the user's machine to make this work. If the secret phrase is changed, access using the old secret phrase is revoked immediately. The secret phrase can be configured in the advanced plugin settings.

## Privacy notices
This plugin does not:

- track users;
- write any user personal data to the database;
- send any data to external servers.

## Screenshots
![Settings Page](/assets/settings.png)
_Image: Settings page._

## Support
Please use the appropriate forums, [issues on Github](https://github.com/StachRedeker/Minimal-Maintenance-Mode/issues), or send an [email](mailto:info@stachredeker.nl).

## Contributing
This plugin is deliberately simple. It is made as a foolproof, quick, and privacy-friendly way for site administrators to activate a maintenance mode. This plugin will not be extended with loads of features, such as templates and styling options; there are plenty of other maintenance plugins to pick that do offer these options. If you nevertheless think that you can contribute to the plugin (e.g. by submitting a bug fix), please open a pull request.

## License
The Minimal Maintenance Mode plugin is licensed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.html).

## Changelog
v1.0.0 Stable version of the plugin.
v1.0.1 Escaped variables and options in the plugin.

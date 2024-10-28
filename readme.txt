=== BCC All Emails ===
Contributors: wphelpdeskuk, watchthedot
Donate link:
Tags: emails, audit, bcc
Requires at least: 5.2
Tested up to: 6.4
Stable tag: 1.1.1
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A quick plugin to create an audit trail of emails sent from your site to another email address.

== Description ==

This plugin allows you to create an audit trail of emails sent from your site using the BCC function.

BCC (or Blind Carbon Copy) sends a copy of the email to another email address without informing the To or CC receipients.

This is very useful when testing a new site to make sure that emails are being sent at the correct times and to the correct receipients.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/bcc-all-emails` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
2. Use the Settings->BCC All Emails screen to set where all emails are sent from

== Frequently Asked Questions ==

= Why didn't my email get BCC'd? =

This plugin works by hooking into the wp_mail filter. If a plugin passes the normal wp_mail function, this plugin is set up for that.

Please report this in the support tab and we will add the functionality if possible!


== Screenshots ==


== Changelog ==
We use the Semantic Versioning system of defining versions (https://semver.org/).
This means that version 1.10 is a minor update for the version 1.x branch and version 2.0 is a MAJOR update.
We will not wrap version numbers of double digits.

= 1.1.1 =
* feat: add documentation link to plugin meta

= 1.1.0 =
* chore: update branding to Watch The Dot / support.watchthedot.com
* chore: update watchthedot/library-settings
* fix: add namespace to plugin file
  therefore using WatchTheDot\Plugins instead of global namespace
* fix: use static functions when $this is not referenced
  This fixes a memory leak standard to using anonymous functions in classes
* reactor: remove Plugin::__ helper method and instead use __ directly
* chore: tested up to WP 6.4
* fix: missing composer directory breaking plugin load

= 1.0.0 =
* Initial Version

== Upgrade Notice ==


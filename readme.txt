
=== Newsletter ===
Tags: newsletter,email,subscription,mass mail
Requires at least: 2.8
Tested up to: 3.3.1
Stable tag: trunk
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2545483
Contributors: satollo

Add a real newsletter service to your blog. In seconds. For free.

== Description ==

This plug-in lets you collect subscribers on your blog with a single or double opt-in
subscription process. Double opt-in is law compliant and it means the user has to confirm the subscription
following simple standard instructions sent to him via email.

Newsletter 2.5 works.

Take the time to read the user guide, to configure
it as if it would be a new installed plug-in, to enter the subscription form panel and
configure it. Read the user guide about WordPress cron system and how it can affect
the sending process. If you need to translate the subscription form, use the
subscription form panel, please, do not hack the language files, they are
used only on activation. On some WordPress installations the automatic update does not
trigger the activation process. It's important, so try to deactivate and reactivate the
plug-in if it does not saves e-mails. If you update manually uploading the plug-in
with FTP, deactivate it first and the reactivate.

Please, DO NOT hack the plug-in files and then, when things go wrong, try to solve
the problem writing me... I have no time to answer to all. Reinstall if you're in doubt
about the plug-in integrity.

Thank you.

Each step of subscribe and cancel process is fully configurable and translatable.

Subscription form and profile form are easily configurable from administrative panels and you
can translate every single label in your language.

Create e-mails with a visual editor with the auto compose function which extract content
for your blog. Auto composer themes can be customized.

Use the widget to let users to subscribe from anywhere in your blog or use a short code
to embed the subscription form on posts and pages.

Use the locked content feature to fire up the subscription rate.

Any option has a "hints" box with instruction to set it and the full user guide is
inside the administrative panels.

Version 2.5 is a major update and need to be reconfigured! Pay attention.

More about Newsletter plug-in official page (http://www.satollo.net/plugins/newsletter).

== Installation ==

1. Put the plug-in folder into [wordpress_dir]/wp-content/plugins/
2. Go into the WordPress admin interface and activate the plugin
3. Optional: go to the options page and configure the plugin

== Frequently Asked Questions ==

None.

== Screen shots ==

No screen shots are available at this time.

== Changelog ==

= 2.5.2.6 =

* Fix the new test subscriber extraction

= 2.5.2.5 =

* Added the "test" flag on subscriber so a real subscriber can be used while sending test emails
* Upgraded tinymce
* Graphical button for submit
* Emails like aaa@q.com are now accepted

= 2.5.2.4 =

* Import now consider 19 fields after the last name as extended profile data
* A possible fix for better concurrency management while sending

= 2.5.2.3 =

* Fixed extra field of type "list" not showing up correctly (when more than one)
* removed the collapsed sub panel on some configuration panel (people did not realized that a click would open them)
* fixed the unconfirmed status display on users panel
* fixed the status list on user edit panel

= 2.5.2.2 =

* removed the backtrace on log (some PHP installations have problems with it)

= 2.5.2.1 =

* fixed an error on subscriber panels claiming an unexisting table

= 2.5.2 =

* important change on subscription confirmation and unsubscription confirmation to avoid multiple emails
* number of subscriber overview on subscriber panel
* fix on profile form showing "no user"

= 2.5.1.7 =

* fixed the widget which was no showing the lists and extra fields

= 2.5.1.6 =

* fixed {unlock_url} tag

= 2.5.1.5 =

* fixed issues on multi email sending
* main configuration panel all options open because people was missing to expand panels
* subscriber panel does not more show up the full list on first open
* fixed privacy check box on widget

= 2.5.1.4 =

* fixed a missing form element on subscriber list panel that caused some buttons to not work

= 2.5.1.3 =

* added compatibility with lite cache
* fixed the list checkboxes on user edit panel
* removed the 100 users limit on search panel

= 2.5.1.2 =

* fixed unsubscription administrator notifications
* replaced sex with gender in notification emails
* fixed the confirm/unconfirm button on user list
* fixed some labels
* subscription form html


= 2.5.1.1 =

* added {date} tag and {date_'format'} tag, where 'format' can be any of the PHP date formats
* added {blog_description} tag
* updated custom forms documentation
* fixed extended subscriber profile collection

= 2.5.1 =

* Improved documentation about delivery engine, WordPress cron and multisite
* New button to force a run of the delivery engine
* Fixed images on theme 1
* Fixed the widget field check
* Renamed panel "user profile" in "subscription form" (since no one read the user guide.. may be due to my bad English... :-)
* Updated theme documentation
* Reintroduced theme CSS
* Added CDATA on JavaScript
* Added theme 3

= 2.5.0 =

* first major release after 1.5.9
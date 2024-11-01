=== Sugar Calendar - Gravity Forms Bridge ===
Author:            Sandhills Development, LLC
Contributors:      mordauk, johnjamesjacoby
Author URI:        https://sandhillsdev.com
Plugin URI:        https://sugarcalendar.com
Tags:              gravity forms, events,
Requires PHP:      5.6.20
Requires at least: 5.1
Tested up to:      5.6
Stable tag:        1.2.0

Add Gravity Forms support to Sugar Calendar - Great for simple event registration

== Description ==

[Sugar Calendar](https://sugarcalendar.com) is a simple, elegant calendar plugin for WordPress that keeps event management incredibly simple. This is an add-on plugin for Sugar Calendar that lets you easily display a Gravity Forms form on the event details page.

The benefit of this add-on is that you can utilize the tremendous power of Gravity Forms to create complex event registration or attendance forms, including payment options, while keeping the event management itself extremely simple and light weight.

This plugin requires both [Sugar Calendar](https://sugarcalendar.com) and [Gravity Forms](https://www.gravityforms.com/).

== Installation ==

1. Activate the plugin
2. Create a Gravity Form form
3. Add a hidden field and prepopulate field with "event_id" parameter under Advanced
4. Add a hidden field and prepopulate field with "event_title" parameter under Advanced
5. Create an event and select the form you wish to display


== Frequently Asked Questions ==

= The Event ID / Title Don't Show in my Form Entries =

You have to make sure you have added the hidden fields for Event ID and Event Title. A future update will hopefully have these added automatically.


== Screenshots ==

1. Event Configuration
2. Event Configuration 2
3. Event Details with Gravity Form
4. Form attendance entry


== Changelog ==

= 1.2.0 =

* Fixed fatal error when Gravity Forms plugin is not already active

= 1.1.0 =

* Updated textdomain for community translations
* Updated some UI elements and text
* Added support for Sugar Calendar 2.0 and higher

= 1.0.1 =

* Made the Gravity Forms options array filterable, props Luke McDonald
* Improved some code formatting and data sanitation, props Luke McDonald

= 1.0 =

* First official release!

=== NetworkPortfolio ===
Contributors: PerS
Donate link: http://example.com/
Tags: webshot, screenshot, snapshot, cloudinary, url2png
Requires at least: 5.9
Tested up to: 6.1
Stable tag: 1.1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

On a WordPress Multisite, display a portfolio of snapshots of your public sites.

== Description ==



= Use =

Add link to the site you'd like to create a snapshot of, by using the `[portfolio]` shortcode, eg:

`
[portfolio]
`

**Customize the snapshots**

Using the NetworkPortfolio Customizer you can change the size and border of the snapshots.

**Shortcode parameters**

You can override the NetworkPortfolio Customizer using the shortcode parameters, they are (with defaults):

- sites   = 0 (all). Site IDs to include in the portfolio. 0 = all sites.
- width   = 0 (Get the value from plugin customizer). Width of the snapshot.
- height  = 0 (Get the value from plugin customizer). Height of the snapshot.
- expires = 600 // 10 minutes. How long the snapshot should be cached.
- orderby = 'modified=DESC&title=DESC'
- num     = 0 (all). Max number of snapshots to show.
- list    = false. Text lisr of the public sites in the portfolio.
- all     = false
- noshow  = ([]). Array of site IDs to exclude from the portfolio.

`
[portfolio width="300" height="400" border_width="5"]
`

== Installation ==

= Prerequisites =

- A [Cloudinary account](https://cloudinary.com/signup)
- Enable the [Cloudinary URL2PNG add-on](https://cloudinary.com/console/addons#url2png)

When you have the prerequisites:

1. In Plugins->Add New, search for NetworkPortfolio
1. Click Install Now
1. When the plugin is installed, activate it.

== Screenshots ==



== Changelog ==

= 1.1.1 =

* Add missing method

= 1.1.0 =

* Update Clodinary SDK to 2.x

= 1.0.3 =
* Fix Cloudinary radius bug. Cloudinary draws a radius even though the radius = 0, so don't send radius parameter when it's 0.

= 1.0.2 =
* Harden shortcode attributes

= 1.0.1 =
* Added to the WordPress plugin directory

= 1.0.0 =
* Initial release


== Sidenote ==

I code for fun, and I code to learn. I've tried to do this plugin using OOP at my best effort. There will be couplings that could be looser and not everything is DRY. I will update the plugin as I learn more and I will fix [issues that are reported](https://github.com/soderlind/networkportfolio/issues/new).

I'm following the [WordPress Coding Standards](https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards) with one exception, I'm using the [PSR-4 autoloader](http://www.php-fig.org/psr/psr-4/) and I've disabled the following rules:
`
<rule ref="WordPress-Core">
	<exclude name="Generic.Files.LowercasedFilename" />
	<exclude name="WordPress.Files.FileName" />
	<exclude name="WordPress.Files.FileName.UnderscoresNotAllowed" />
</rule>
`


= Credits =
NetworkPortfolio is using:

- The [Plugin Customizer](https://github.com/soderlind/plugin-customizer) framework.
- The [WordPress Customizer Range Value Control](https://github.com/soderlind/class-customizer-range-value-control).
- The [PHP extension for Cloudinary](https://github.com/cloudinary/cloudinary_php)
- The [PSR-4 Autoloader](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader-examples.md#class-example)
- The jQuery [boxShadow cssHooks](https://github.com/brandonaaron/jquery-cssHooks/blob/master/boxshadow.js), Copyright (c) 2010 Burin Asavesna (http://helloburin.com)
    - I use CSS box-shadow to create [Better Rounded Borders](http://blog.teamtreehouse.com/css-tip-better-rounded-borders)

= Copyright and License =
NetworkPortfolio is copyright 2017 Per Soderlind

NetworkPortfolio is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

NetworkPortfolio is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with the Extension. If not, see http://www.gnu.org/licenses/.

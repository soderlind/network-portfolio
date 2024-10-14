# NetworkPortfolio

## Description

v2.0.0 is a simplified version, and only exposes the `[portfolio]` shortcode. The shortcode has the following attributes:

- `sites` (int) Number of sites to display. Default is 0 (all sites).
- `expires` (int) Number of seconds to cache the sites. Default is 600 (10 minutes).
- `orderby` (string) Order the sites by. Default is 'modified=DESC&title=DESC'.
- `theme` (string) List site using the theme. Default is ''.
- `num` (int) Number of sites to display. Default is 0 (all sites).
- `all` (bool) Show all sites. Default is true.
- `noshow` (array) List of sites to exclude. Default is [].

The shortcode lists sites in a network, and is used on the main site in a network. The sites are cached for the number of seconds specified in the `expires` attribute.

The old version is still available in the [previous release](https://github.com/soderlind/network-portfolio/releases/tag/1.1.1).

**TBA**

## Credits

The old version is using:

- The [Plugin Customizer](https://github.com/soderlind/plugin-customizer) framework.
- The [WordPress Customizer Range Value Control](https://github.com/soderlind/class-customizer-range-value-control).
- The [PHP extension for Cloudinary](https://github.com/cloudinary/cloudinary_php)
- The jQuery [boxShadow cssHooks](https://github.com/brandonaaron/jquery-cssHooks/blob/master/boxshadow.js), Copyright (c) 2010 Burin Asavesna (http://helloburin.com)
  - I use CSS box-shadow to create [Better Rounded Borders](http://blog.teamtreehouse.com/css-tip-better-rounded-borders)

## Copyright and License

NetworkPortfolio is copyright 2017 Per Soderlind

NetworkPortfolio is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any later version.

NetworkPortfolio is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU Lesser General Public License along with the Extension. If not, see http://www.gnu.org/licenses/.

=== NutsForPress Maintenance Mode ===

Contributors: Christian Gatti
Tags: NutsForPress,Maintenance Mode,Hide resolution, breakpoint
Donate link: https://www.paypal.com/paypalme/ChristianGatti
Requires at least: 5.3
Tested up to: 6.5
Requires PHP: 7.0.0
Stable tag: 1.8
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

With NutsForPress Maintenance Mode you can redirect not logged users to a defined page or hide website content at defined breakpoints.


== Description ==

*Maintenance Mode* is one of the several NutsForPress plugins providing some essential features that WordPress does not offer itself or offers only partially.  

*Maintenance Mode* allows you to:

* set up a "Maintenance Mode" state and redirect all website visitors to a landing page that you can define
* restrict access to REST API too, if you want to safely hide all the contents, even the textual ones provided by REST API
* hide and redirect to the defined landing page the sitemap page too (the default WordPress sitemap page or the sitemap provided by [NutsForPress Indexing and SEO](https://wordpress.org/plugins/nutsforpress-indexing-and-seo/))
* set up a "Screen Resolution Check" and hide website content at defined breakpoints, targeting devices with specific resolutions; this feature is very useful when developing for different resolutions one at a time
* prevent everyone from login while Maintenance Mode is switched on, except for some Administrators that you explicitly authorize by flagging a checkbox into their profiles

When the plugin is activated, the involved Administrator is automatically authorized to keep on working and login; if you remove all the authorization flags from all the Administrators, all of them can keep on login and working. All the logged in users and all the logged in Administrators that are not explicitly authorized, are instantly logged out when Maintenance Mode is switched on

Maintenance Mode is full compliant with WPML (you don't need to translate any option value)

Take a look at the others [NutsForPress Plugins](https://wordpress.org/plugins/search/nutsforpress/)

**Whatever is worth doing at all is worth doing well**


== Installation ==

= Installation From Plugin Repository =

* Into your WordPress plugin section, press "Add New"
* Use "NutsForPress" as search term
* Click on *Install Now* on *NutsForPress Maintenance Mode* into result page, then click on *Activate*
* Setup "NutsForPress Maintenance Mode" options by clicking on the link you find under the "NutsForPress" menu
* Enjoy!

= Manual Installation =

* Download *NutsForPress Maintenance Mode* from https://wordpress.org/plugins/nutsforpress
* Into your WordPress plugin section, press "Add New" then press "Load Plugin"
* Choose nutsforpress-maintenance-mode.zip file from your local download folder
* Press "Install Now"
* Activate *NutsForPress Maintenance Mode*
* Setup "NutsForPress Maintenance Mode" options by clicking on the link you find under the "NutsForPress" menu
* Enjoy!


== Changelog ==

= 1.8 =
* Added a new check to prevent a warning on the 'user_new_form' action: when a new user is created, the $user parameter is not empty but does not contain a valid WP_User object, causing the plugin to fail further on when trying to get the property ID (thanks to Bolz)

= 1.7 =
* New readme file and new section to be translated

= 1.6 =
* Fixed a bug that caused the 'jQuery is not defined' error and implemented a better method for warning visitors with screen resolutions not allowed to browse the website

= 1.5 =
* Now you can hide website content at defined breakpoints, targeting devices with specific resolutions. This feature is very useful when developing for different resolutions one at a time

= 1.4 =
* Fixed a bug that caused the reset of the options of this plugin when WPML was installed and activated after the configuration of this plugin

= 1.3 =
* Tested up to WordPress 6.2

= 1.2 =
* Now translations are provided by translate.wordpress.org, instead of being locally provided: please contribute!

= 1.1 =
* Now you can define wich Administrator can log in while Maintenance Mode is switched on (all others Administrators are prevented from login, like avery other user with lower roles)
* Every logged in user which is not allowed to login when Maintenace Mode is switched on, now is logged off automatically 

= 1.0 =
* First full working release


== Translations ==

* English: default language
* Italian: entirely translated


== Credits ==

* Very many thanks to [DkR](https://dkr.srl/) and [SviluppoEuropa](https://sviluppoeuropa.it/)!
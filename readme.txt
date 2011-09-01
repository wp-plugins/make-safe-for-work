=== Make Safe For Work ===
Contributors: BenIrvin
Donate link: http://innerdvations.com/
Tags: nsfw, nsw, not safe for work
Requires at least: 3.0
Tested up to: 3.2.1
Stable tag: 0.1

Shortcode for nsfw content that doesn't load the inappropriate content from server until requested.

== Description ==
Adds a shortcode for not-safe-for-work content that actually prevents loading of the
inappropriate content from the server until requested by the user. Just wrap any offending
content in [nsfw][/nsfw] and your post will bypass even restrictive work filters.  Users
can then click a link that will reload the full uncensored version of the page.  By default,
censored text is padded to take up roughly the same amount of space as the original content.

The first censored item in a post will be labeled "[Not Safe for Work. Click to View.]" and all
censored items after that will be labeled "[NSFW]" for brevity.

If you want to force long or short forms instead of using long first and short after,
just define MSFW_USE_FORM as either 'long' or 'short' in your functions.php like so:
define('MSFW_USE_FORM','long');

Using pad='false' you can disable padding of censored content:
[nsfw pad='false']

If you want to default padding to off, just define MSFW_PADDING as false in your functions.php:
define('MSFW_PADDING',false);

Using 'type', you can choose method of censorship:
[nsfw type='reload']	# Default method. Click reloads page with revealed content.
[nsfw type='deleted'] 	# NEVER show content. Note that it still appears in areas of your sitethat don't filter shortcodes.
[nsfw type='spoiler'] 	# content is just hidden, rollover reveals content. Actually works with images/links/different color text.
[nsfw type='comment'] 	# content is in an html comment, nothing is displayed on site.

If you want to change the default type, just define MSFW_DEFAULT_TYPE in your functions.php:
define('MSFW_DEFAULT_TYPE','spoiler');

You can change any of the text either via translation or the following way:
define('MSFW_LONG_FORM','[Not Safe for Work. Click to View.]');
define('MSFW_SHORT_FORM','[NSFW]');
define('MSFW_DELETED','[redacted]');
define('MSFW_PAD_STR','&nbsp; ');

This is the first release of this plugin.  The following features are not currently implemented
but are planned for future versions:
* blacked-out area to match content size and shape exactly
* blacked-out area checks and explains if there are images hidden or just text
* automatic PICS-Label meta tags on posts that use [nsfw]
* javascript "click to view" options

== Installation ==

1. Upload the plugin folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Use the [nsfw] or [msfw] shortcode to flag your content as not-safe-for-work

== Frequently Asked Questions ==

= Is this compatible with the original NSFW plugin (http://wordpress.org/extend/plugins/nsfw/)? =
Yes. If you have that one installed, we use [msfw] (Make Safe For Work) instead of [nsfw] so that
you can have access to both methods depending on how you want to censor your content.

= This plugin does a lot more than just nsfw, can you change the shortcode to [new_shortcode] instead? =
The shortcodes created are from an array called $msfw_shortcodes so if you want to rename
it, just create your own array in your functions.php like so:
$msfw_shortcodes = array('new_shortcode');

Alternatively, if you want to shortcode directly to a type or padding setting, you can use something like this:
$mswf_shortcodes = array('spoilers'=>array('type'=>'spoiler','pad'=>false));

= Will this affect my search engine optimization? =
Some people say search engines could consider this method duplicate content and penalize you for it,
but realistically there won't be any difference.  Even though I don't personally believe that you'll be
penalized for this, in a future version, I will include search engine directives to tell them to only index
one of the two versions of the page as well as an optional PICS-Label meta tag.

== Screenshots ==

== Changelog ==
= 0.1 =
* created options for types of censoring
* created options for padding
* checking if the other nsfw plugin already exists
* created shortcodes

== Upgrade Notice ==

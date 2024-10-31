=== Related Topics Simple Tag List ===
Contributors: 5t3ph
Tags: related posts, related articles, related tags, related content, tag list, tags, post, posts
Requires at least: 3.3
Tested up to: 4.9.5
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Outputs a basic inline or bulleted list of the current post's tags with intelligent display & customizable options. 

== Description ==
Related Topics Simple Tag List checks each tag attached to a post and only displays it if at least one other post exists. If exactly one other article has that tag, the tag name then links directly to that post. Otherwise, the tag name will link to the tag archive.

Not only are links intelligently created, but the link’s title attribute is updated to say “View Related Post” if linking directly to post, or “View Archive of Related Posts” if linking to tag archive.

An options page under Settings allows choosing from an inline listing with customizable separator, and a basic bulleted list. HTML can be added before and after the list. Not listing HTML before the list effectively removes the title. No additional classes or CSS are added by this plugin. Tag links will use whatever color is defined by the theme.

This plugin is best for sites that use tags in a purposeful way connected to a content strategy. It’s an especially great way to connect content for beginning blogs, but can also grow with the site.

== Installation ==
1. Activate the plugin through the 'Plugins' menu in WordPress
2. *Optional:* Customize options under Settings > Related Topics Settings
3. *Optional:* Place `<?php echo rtstl_output(); ?>` in your templates instead of allowing the output to be automatically appended to single post content

== Frequently Asked Questions ==
= How do I style the Related Topics list? =
You can add a div or other element of your choosing, along with a class or id, into the HTML before and after textareas under Settings > Related Topics Settings. Then you can add whatever style you'd like in your theme CSS.

= How do I change or remove the title? =
To change the title, edit the default HTML in the HTML before textarea under Settings > Related Topics Settings. To remove the title, leave this textarea completely blank.

= Can I put this anywhere I want in my template? =
Yup! Just make sure the first option to automatically append the list after your content is unchecked under Settings > Related Topics Settings, and then place the following template tag wherever you'd like the list: `<?php echo rtstl_output(); ?>`

== Screenshots ==
1. Related Topics display with default, freshly installed options.
2. Related Topics display using the "Bulleted" theme.
3. Related Topics Settings screen.

== Changelog ==
= 1.0: Sept. 1, 2014 =
* First official release!
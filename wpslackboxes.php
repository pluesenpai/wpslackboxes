<?php
/*
Plugin Name: Wordpress Slackboxes
Version: 1.0
Plugin URI: https://github.com/paolinux86/wpslackboxes
Description: Creates boxes for various messages (warning, info, error, etc)
Author: paolinux86
Author URI: http://paolo86.slack-counter.org
*/
/*  Copyright © 2013  paolinux86
    Support: paolo86@slack-counter.org

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; version 2 of the License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/*
fix title text in admin
*/

class wpSlackboxes
{
	public function __construct()
    {
		if(strpos($_SERVER["REQUEST_URI"], "post.php") ||
			strpos($_SERVER["REQUEST_URI"], "post-new.php") ||
			strpos($_SERVER["REQUEST_URI"], "page-new.php") ||
			strpos($_SERVER["REQUEST_URI"], "page.php") ||
			strpos($_SERVER["REQUEST_URI"], "comment.php")) {
			add_action("admin_print_footer_scripts", array(&$this, "init_html_editor_tags"), 100);
		}
    }

	public function addCSS()
	{
		$pluginbasename = plugin_basename(dirname(__FILE__));
		echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"" . plugins_url($pluginbasename) . "/{$pluginbasename}.css\" />";
	}

	public function init_html_editor_tags()
	{
		if (is_admin() == true) {
			echo "<script type=\"text/javascript\">";
?>

if(typeof QTags != "undefined") {
	QTags.addButton("wpslackboxes_info", "Info box", "[slackboxes_info title=\"\"]", "[/slackboxes_info]", "");
} else if(gac_edbar = document.getElementById("ed_toolbar")) {
	if(typeof edButtons!="undefined") {
		var gac_Nr, gac_But, gac_But;
		gac_Nr = edButtons.length;
		edButtons[gac_Nr] = new edButton("ed_"+gac_Nr, "Info box", "[slackboxes_info title=\"\"]", "[/slackboxes_info title=\"\"]", "");
		gac_But = gac_edbar.lastChild;
		while (gac_But.nodeType != 1) {
			gac_But = gac_But.previousSibling;
		}
		gac_But = gac_But.cloneNode(true);
		gac_But.id = "ed_"+gac_Nr;
		gac_But._idx = gac_Nr;
		gac_But.value = "Spoiler";
		gac_But.title = "Spoiler";
		gac_But.onclick = function() {edInsertTag(edCanvas, this._idx); return false; }
		gac_edbar.appendChild(gac_But);
	}

<?php
			echo "</script>";
		}
	}

	public function process_shortcode_info($atts, $content = null, $code = "", $expand = true)
	{
		$incomingfrompost = shortcode_atts(array("title" => "*Plin Plon*"), $atts);

		$title = wp_specialchars_decode($incomingfrompost["title"]);
		$output = "<div class=\"service_message info\"><div class=\"service_message_title\">{$title}</div><div class=\"service_message_icon\"></div><div class=\"service_message_inner\">{$content}</div></div>";
		return $output;
	}
}

	// Instantiate our class
	$wpSlackboxes = new wpSlackboxes();

	/**
	* Add filters and actions
	*/

	add_action('wp_head', array($wpSlackboxes, 'addCSS'));

	add_shortcode('slackboxes_info', array($wpSlackboxes, 'process_shortcode_info'));
?>

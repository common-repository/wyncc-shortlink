<?php
/*
Plugin Name: wyn.cc Shortlink
Plugin URI: http://406.co.za/wordpress/plugins/
Description: Creates shortlinks for your wine related posts. It also display how may times the shortlink was clicked globally.
Version: 0.02
Author: Jan Laubscher
Author URI: http://406.co.za
*/


/*  
	Copyright 2009  Jan Laubscher  (email : jan@406.co.za)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
//Call wyncc api
function get_wyncc_url($url,$calltype)
	{
		$url = 'http://wyn.cc/api.php?action='.$calltype.'&url='.urlencode($url).'&format=simple';
		$ch = curl_init();  
		$timeout = 5;  
		curl_setopt($ch,CURLOPT_URL,$url);  
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);  
		$url = curl_exec($ch);  
		curl_close($ch);
		$cleanurl = substr($url,0,20);
		return trim($cleanurl);
	}
	
//html for box
function wyncc_box() {
?>

<div id="minor-publishing-actions">
  <div class="clear"></div>
</div>
<div class="misc-pub-section">
<? if(get_permalink($_GET['post'])){ ?>
  <b><?=get_the_title($_GET['post'])?></b>
 <? }?>
  </div>
<div class="misc-pub-section">Shortlink:<span id="post-status-display">
<? if(get_permalink($_GET['post'])){ ?>
  <?=get_wyncc_url(get_permalink($_GET['post']), 'chop')?>
  </span> <a href="<?=get_permalink($_GET['post'])?>" class="edit-visibility" target="_blank" >View</a> </div>
<div class="misc-pub-section">Clicked:<span id="post-status-display">
  <?=get_wyncc_url(get_permalink($_GET['post']), 'stats')?>
  </span>
  <? }?>
  </div>
  
<? if(get_permalink($_GET['post'])){ ?>  
<div id="minor-publishing-actions">
<div id="save-action">
</div>
<div id="preview-action">
<a class="preview button" href="http://twitter.com/home/?status=<?=substr(get_the_title($_GET['post']), 0, 119)?> <?=get_wyncc_url(get_permalink($_GET['post']), 'chop')?>" target="_blank" >Tweet this</a>
</div>
<div class="clear"></div>
</div>

<?
	}
}
//Add box
function create_wyncc_box(){	
	if (function_exists('add_meta_box') ) {
		add_meta_box('wyncc-boxes', 'wyn.cc Shortlink', 'wyncc_box', 'post', 'side', 'high');
	}
}
//hook function
add_action('admin_menu', 'create_wyncc_box');
?>

<?php
/*
Plugin Name: Flickr Plus
Plugin URI: #(soon)
Description: Create a Class in PHP for get photos at Flickr account
Version: 1.0 Beta
Author: Leo Caseiro
Author URI: http://leocaseiro.com.br/
*/

/*  FLICKR_PLUS_yright 2011 Leo Caseiro (http://leocaseiro.com.br/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a FLICKR_PLUS_y of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


define( 'FLICKR_PLUS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
define( 'FLICKR_PLUS_PLUGIN_NAME', trim( dirname( FLICKR_PLUS_PLUGIN_BASENAME ), '/' ) );
define( 'FLICKR_PLUS_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . FLICKR_PLUS_PLUGIN_NAME );
define( 'FLICKR_PLUS_PLUGIN_URL', WP_PLUGIN_URL . '/' . FLICKR_PLUS_PLUGIN_NAME );

require_once('FlickrPlus.class.php');


register_activation_hook(__FILE__,'flickr_plus_install');
add_action('admin_menu', 'flickr_plus_menu');

/**
* Register Options
*/
add_action( 'admin_init', 'flickr_plus_register_options' );
function flickr_plus_register_options() {
	register_setting( 'flickr_plus', 'flickr_plus_username' );
	register_setting( 'flickr_plus', 'flickr_plus_user_id' );
	register_setting( 'flickr_plus', 'flickr_plus_api_key' );
	register_setting( 'flickr_plus', 'flickr_plus_secret' );
}


function flickr_plus_menu() {

 	global $my_plugin_hook;
 	$my_plugin_hook = add_options_page('Flickr Plus', 'Flickr Plus', 'manage_options', 'flickr_plus', 'flickr_plus_adm');

}

function flickr_plus_adm() {
	require_once('flickr-config.php');
}

function flickr_plus_list_albuns() {
	$flickr_plus = new Flickr_Plus();
	return $flickr_plus->listAlbuns();
}

function flickr_plus_list_all_photos($limit = false, $sep_before = '<li>', $sep_after = '</li>', $link = false ) {
	$flickr_plus = new Flickr_Plus();
	$list = $flickr_plus->listAllPhotos();
	
	if ($limit) {
		$total = $limit < count( $list['photo'] ) ? $limit : count( $list['photo'] );
	} else {
		$total = count( $list['photo'] );
	}
	$html = '';
	for ( $i=0; $i < $total; $i++ ) {
		$photo = $list['photo'][$i];
		
		$title = $photo['title'];
		$photo = $flickr_plus->loadPhoto( $photo['id'], $photo['secret'] );
		if ( $link ) {
			$html .= '<a title="' . $title . '" href="' . $photo['sizes']['size'][4]['url'] . '">' . $sep_before . '<img title="' . $title . '" alt="' . $title . '" src="' . $photo['sizes']['size'][0]['source'] . '" />' . $sep_after . '</a>';
		} else {
			$html .= $sep_before . '<img title="' . $title . '" alt="' . $title . '" src="' . $photo['sizes']['size'][1]['source'] . '" />' . $sep_after;
		}
	}
	return $html;
	
	
}

function flickr_plus_load_album( $id_album ) {
	$flickr_plus = new Flickr_Plus();
	return $flickr_plus->loadAlbum( $id_album );
}

function flickr_plus_load_photo( $id_foto, $secret ) {
	$flickr_plus = new Flickr_Plus();
	return $flickr_plus->loadPhoto( $id_foto, $secret );
}

function flickr_plus_load_info_photo( $id_foto, $secret ) {
	$flickr_plus = new Flickr_Plus();
	return $flickr_plus->loadInfoPhoto( $id_foto, $secret );
}
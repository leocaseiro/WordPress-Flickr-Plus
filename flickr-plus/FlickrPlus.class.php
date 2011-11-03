<?php

class Flickr_Plus
{
	private $username 	= '';
	private $user_id 	= '';
	private $api_key 	= '';
	private $secret 	= '';
	

	function __construct()
	{
		$this->username = get_option('flickr_plus_username');
		$this->user_id 	= get_option('flickr_plus_user_id');
		$this->api_key 	= get_option('flickr_plus_api_key');
		$this->secret 	= get_option('flickr_plus_secret');
	}

	

	public function listAlbuns()
	{
		$params = array(
			'api_key'	=> $this->api_key, 
			'method'	=> 'flickr.photosets.getList',
			'user_id'	=> $this->user_id,
			'format'	=> 'php_serial',
		);
		
		$albums = $this->_getParams( $this->_encode_params($params) );
		
		if ($albums)
			return $albums['photosets']['photoset'];
		else
			return false;
		
		
	}

	public function listAllPhotos()
	{
		$params = array(
			'api_key'	=> $this->api_key, 
			'method'	=> 'flickr.photos.search',
			'user_id'	=> $this->user_id,
			'format'	=> 'php_serial'
		);
			
		
		$albums = $this->_getParams( $this->_encode_params($params) );
		
		if ($albums)
			return $albums['photos'];
		else
			return false;
		
		
	}
	
	public function loadAlbum($id_album)
	{
		$params = array(
			'api_key'		=> $this->api_key, 
			'method'		=> 'flickr.photosets.getPhotos',
			'photoset_id' 	=> $id_album,
			'format'		=> 'php_serial'
		);
		
		$album = $this->_getParams( $this->_encode_params($params) );
		
		if ($album)
			return $album;
		else
			return false;
	}
	
	public function loadPhoto($id_foto, $secret)
	{
		$params = array(
			'api_key'	=> $this->api_key, 
			'method'	=> 'flickr.photos.getSizes',
			'secret'	=> $secret, 
			'photo_id' 	=> $id_foto,
			'format'	=> 'php_serial'
		);
		
		$album = $this->_getParams( $this->_encode_params($params) );
		
		if ($album)
			return $album;
		else
			return false;
	}
		
	public function loadInfoPhoto($id_foto, $secret)
	{
		$params = array(
			'api_key'	=> $this->api_key, 
			'method'	=> 'flickr.photos.getInfo',
			'secret'	=> $secret, 
			'photo_id' 	=> $id_foto,
			'format'	=> 'php_serial'
		);
		
		$album = $this->_getParams( $this->_encode_params($params) );
		
		if ($album)
			return $album;
		else
			return false;
	}

		
	
	private function _encode_params( $params = array() )
	{
		$encoded_params = array();
		
		foreach ($params as $k => $v){
		
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		return $encoded_params;
	
	}
	
	private function _getParams($encoded_params)
	{
		$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);
		
		$rsp = file_get_contents($url);
		
		$obj = unserialize($rsp);
		
		if ($obj['stat'] == 'ok')
			return $obj;
		else
			return false;		
	}	
	
}
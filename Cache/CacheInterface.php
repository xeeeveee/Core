<?php

namespace Xeeeveee\Core\Cache;

interface CacheInterface {

	public function add( $id, $data, $expiration = 3600 );

	public function get( $id );

	public function remove( $id );

	public function clear();

	public function get_cache_directory();

	public function set_cache_directory( $path );
}
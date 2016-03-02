<?php

namespace Xeeeveee\Core\Requests\Rest;

interface ClientInterface {

	public function get( $url, array $parameters = [], array $args = [ ] );

	public function post( $url, array $parameters = [], array $args = [ ] );

	public function put( $url, array $parameters = [], array $args = [ ] );

	public function patch( $url, array $parameters = [], array $args = [ ] );

	public function delete( $url, array $parameters = [], array $args = [ ] );

	public function head( $url, array $parameters = [], array $args = [ ] );

	public function options( $url, array $parameters = [], array $args = [ ] );
}


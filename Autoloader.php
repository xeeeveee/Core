<?php

namespace Xeeeveee\Core;

spl_autoload_register( function ( $class ) {

	$parts = explode( '\\', $class );
	$root  = $parts[0];
	$class = str_replace( $root . '\\', '', $class );

	$paths = [
		str_replace( '\\', DIRECTORY_SEPARATOR, ucfirst( $class ) ) . '.php',
		str_replace( '\\', DIRECTORY_SEPARATOR, lcfirst( $class ) ) . '.php'
	];

	$roots = [
		WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR,
		WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'mu-plugins' . DIRECTORY_SEPARATOR,
		WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR
	];

	foreach ( $paths as $path ) {
		foreach ( $roots as $root ) {
			if ( file_exists( $root . $path ) ) {
				include_once( $root . $path );

				return;
			}
		}
	}

} );
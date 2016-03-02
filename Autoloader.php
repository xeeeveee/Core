<?php

namespace Xeeeveee\Core;

spl_autoload_register( function ( $class ) {

	$parts = explode( '\\', __NAMESPACE__ );
	$root  = $parts[0];

	if ( strpos( $class, $root ) !== 0 ) {
		return;
	}

	$class       = str_replace( $root . '\\', '', $class );
	$path        = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
	$plugin_root = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;
	$theme_root  = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR;

	if ( file_exists( $plugin_root . $path ) ) {
		include_once( $plugin_root . $path );

		return;
	}

	if ( file_exists( $theme_root . $path ) ) {
		include_once( $theme_root . $path );

		return;
	}
} );
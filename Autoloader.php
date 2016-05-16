<?php

namespace Xeeeveee\Core;

spl_autoload_register( function ( $class ) {

	$parts = explode( '\\', $class );
	$root  = $parts[0];

	$class         = str_replace( $root . '\\', '', $class );
	$path          = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';
	$plugin_root   = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR;
	$muplugin_root = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'mu-plugins' . DIRECTORY_SEPARATOR;
	$theme_root    = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR;

	if ( file_exists( $muplugin_root . $path ) ) {
		include_once( $muplugin_root . $path );

		return;
	}

	if ( file_exists( $plugin_root . $path ) ) {
		include_once( $plugin_root . $path );

		return;
	}

	if ( file_exists( $theme_root . $path ) ) {
		include_once( $theme_root . $path );

		return;
	}
} );
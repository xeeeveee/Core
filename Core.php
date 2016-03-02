<?php
/*
 * Plugin Name: Core
 * Plugin URI: https://github.com/xeeeveee/core
 * Description: WordPress Core Plugin
 * Author: Jack Neary
 * Version: 0.1
 * Author URI: https://github.com/xeeeveee/core
 */

namespace Xeeeveee\Core;

use Xeeeveee\Core\Container\Container;
use Xeeeveee\Core\WordPress\Prepare\Post;
use Xeeeveee\Core\WordPress\Prepare\Term;

require_once( __DIR__ . DIRECTORY_SEPARATOR . 'Autoloader.php' );

add_action( 'plugins_loaded', function () {
	$container = Container::get_instance();
	$container->add( 'Xeeeveee\Core\WordPress\Prepare\Post', Post::get_instance() );
	$container->add( 'Xeeeveee\Core\WordPress\Prepare\Term', Term::get_instance() );
}, 1 );


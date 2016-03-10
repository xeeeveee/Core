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

require_once( __DIR__ . DIRECTORY_SEPARATOR . 'Autoloader.php' );

$container = Container::get_instance();
$container->add( 'Xeeeveee\Core\Core', Core::get_instance() );




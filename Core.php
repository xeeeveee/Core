<?php

namespace Xeeeveee\Core;

use Xeeeveee\Core\Utility\Singleton;
use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Container\Container;
use Xeeeveee\Core\WordPress\Enqueue\Script\AdminMain;
use Xeeeveee\Core\WordPress\Enqueue\Style\ColorPicker;
use Xeeeveee\Core\WordPress\Enqueue\Style\JQueryUi;
use Xeeeveee\Core\WordPress\Prepare\Post;
use Xeeeveee\Core\WordPress\Prepare\Term;
use Xeeeveee\Core\WordPress\Register\Decorators\PostDecorator;

class Core extends Singleton {

	use ConfigurationTrait;

	/**
	 * Registers appropriate actions with WordPress
	 */
	protected function __construct() {
		add_action( 'plugins_loaded', [ $this, 'boot' ] );
	}

	/**
	 * Initialize required classes and add them to the container
	 *
	 * @throws Exceptions\ContainerOverrideException
	 * @throws Exceptions\NotStringException
	 */
	public function boot() {
		$container = Container::get_instance();

		if ( apply_filters( $this->filter_base . 'core/register/prepare/post', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Prepare\Post', Post::get_instance() );
		}

		if ( apply_filters( $this->filter_base . 'core/register/prepare/post', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Prepare\Term', Term::get_instance() );
		}

		if ( apply_filters( $this->filter_base . 'core/register/prepare/post', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Register\Decorators\PostDecorator', PostDecorator::get_instance() );
		}

		if ( apply_filters( $this->filter_base . 'core/register/enqueue/admin_scripts', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Enqueue\Script\AdminScripts', AdminMain::get_instance() );
		}

		if ( apply_filters( $this->filter_base . 'core/register/enqueue/color_picker_styles', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Enqueue\Style\ColorPicker', ColorPicker::get_instance() );
		}

		if ( apply_filters( $this->filter_base . 'core/register/enqueue/jquery_ui_styles', true ) ) {
			$container->add( 'Xeeeveee\Core\WordPress\Enqueue\Style\JQueryUi', JQueryUi::get_instance() );
		}
	}
}
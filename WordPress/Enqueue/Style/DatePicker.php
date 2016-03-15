<?php

namespace Xeeeveee\Core\WordPress\Enqueue\Style;

use Xeeeveee\Core\WordPress\Register\Enqueue\EnqueueStyle;

class DatePicker extends EnqueueStyle {

	/**
	 * @inherit
	 */
	protected $handle = 'jquery-ui-datepicker';

	/**
	 * @inherit
	 */
	protected $resource = 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css';

	/**
	 * @inherit
	 */
	protected $frontend = false;

	/**
	 * @inherit
	 */
	protected $admin = true;
}
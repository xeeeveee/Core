<?php

namespace Xeeeveee\Core\Forms\Elements;

use Xeeeveee\Core\Configuration\ConfigurationTrait;
use Xeeeveee\Core\Exceptions\NotStringException;
use Xeeeveee\Core\Utility\Tag;

abstract class Element extends Tag implements ElementInterface {

	use ConfigurationTrait

	/**
	 * The name of the element
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The type element this is
	 *
	 * @var string
	 */
	protected $type;

	/**
	 * Wording of the label for this element
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * The current value of the element
	 *
	 * @var string|array
	 */
	protected $value;

	/**
	 * The options for the input
	 *
	 * @var array
	 */
	protected $options = [ ];

	/**
	 * The text to display as a tooltip for this element
	 *
	 * @var string|array
	 */
	protected $tooltip;

	/**
	 * The location of the tooltip within the block
	 *
	 * Default: 'block'
	 *
	 * Options:
	 * - label
	 * - element
	 * - block
	 *
	 * @var string
	 */
	protected $tooltip_location = 'block';

	/**
	 * Reserved attribute names
	 *
	 * These will be ignored if added as attributes, they will have special behaviour
	 *
	 * @var array
	 */
	protected $reserved_attributes = [
		'value',
		'selected',
		'checked',
		'name',
		'type'
	];

	/**
	 * Element block css classes
	 *
	 * @var array
	 */
	protected $block_wrap_classes = [
		'form-group'
	];

	/**
	 * Element wrapper css classes
	 *
	 * @var array
	 */
	protected $element_wrap_classes = [
		'element'
	];

	/**
	 * Prepares a new element
	 *
	 * Extending classes should call the parent to inherit full functionality
	 *
	 * @param string $name
	 * @param array $args
	 */
	public function __construct( $name, $args = [ ] ) {

		$this->set_name( $name );

		if ( ! isset( $args['attributes'] ) ) {
			$args['attributes'] = [ ];
		}

		if ( ! isset( $args['label'] ) ) {
			$args['label'] = '';
		}

		if ( ! isset( $args['value'] ) ) {
			$args['value'] = [ ];
		}

		if ( ! isset( $args['options'] ) ) {
			$args['options'] = [ ];
		}

		if ( ! isset( $args['tooltip'] ) ) {
			$args['tooltip'] = '';
		}

		if ( ! isset( $args['tooltip-location'] ) ) {
			$args['tooltip-location'] = '';
		}

		// TODO: Throw exception if type is wrong
		if ( ! isset( $args['wrappers'] ) || ! is_array( $args['wrappers'] ) ) {
			$args['wrappers'] = [ ];
		}

		/*
		 * Format: <base>/element/<type>/<name>/attributes
		 */
		$attributes = apply_filters( $this->filter_base . 'element/global/global/attributes', $args['attributes'], $this->name, $this );
		$attributes = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/attributes', $attributes, $this->name, $this );
		$attributes = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/attributes', $attributes, $this->name, $this );
		$this->set_attributes( $attributes ); // TODO: Throw exception if type is wrong

		/*
		 * Format: <base>/element/<type>/<name>/label
		 */
		$label = apply_filters( $this->filter_base . 'element/global/global/label', $args['label'], $this->name, $this );
		$label = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/label', $label, $this->name, $this );
		$label = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/label', $label, $this->name, $this );
		$this->set_label( $label ); // TODO: Throw exception if type is wrong

		/*
		 * Format: <base>/element/<type>/<name>/value
		 */
		$values = apply_filters( $this->filter_base . 'element/global/global/value', $args['value'], $this->name, $this );
		$values = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/value', $values, $this->name, $this );
		$values = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/value', $values, $this->name, $this );
		$this->set_value( $values ); // TODO: Throw exception if type is wrong

		/*
		 * Format: <base>/element/<type>/<name>/options
		 */
		$options = apply_filters( $this->filter_base . 'element/global/global/options', $args['options'], $this->name, $this );
		$options = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/options', $options, $this->name, $this );
		$options = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/options', $options, $this->name, $this );
		$this->set_options( $options ); // TODO: Throw exception if type is wrong

		/*
		 * Format: <base>/element/<type>/<name>/tooltip
		 */
		$tooltip = apply_filters( $this->filter_base . 'element/global/global/tooltip', $args['tooltip'], $this->name, $this );
		$tooltip = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/tooltip', $tooltip, $this->name, $this );
		$tooltip = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/tooltip', $tooltip, $this->name, $this );
		$this->set_tooltip( $tooltip ); // TODO: Throw exception if type is wrong

		/*
		 * Format: <base>/element/<type>/<name>/wrappers
		 */
		$wrappers = apply_filters( $this->filter_base . 'element/global/global/wrappers', $args['wrappers'], $this->name, $this );
		$wrappers = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/wrappers', $wrappers, $this->name, $this );
		$wrappers = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/wrappers', $wrappers, $this->name, $this );

		if ( is_array( $wrappers ) ) {

			// TODO: Throw exception if type is wrong
			if ( ! isset( $wrappers['block'] ) || ! is_array( $wrappers['block'] ) ) {
				$wrappers['block'] = [ ];
			}

			/*
			* Format: <base>/element/<type>/<name>/block_wrappers
			*/
			$block_wrappers = apply_filters( $this->filter_base . 'element/global/global/block_wrappers', $wrappers['block'], $this->name, $this );
			$block_wrappers = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/block_wrappers', $block_wrappers, $this->name, $this );
			$block_wrappers = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/block_wrappers', $block_wrappers, $this->name, $this );
			$this->set_block_wrappers( $block_wrappers );

			// TODO: Throw exception if type is wrong
			if ( ! isset( $wrappers['element'] ) || ! is_array( $wrappers['element'] ) ) {
				$wrappers['element'] = [ ];
			}

			/*
			 * Format: <base>/element/<type>/<name>/element_wrappers
			 */
			$element_wrappers = apply_filters( $this->filter_base . 'element/global/global/element_wrappers', $wrappers['element'], $this->name, $this );
			$element_wrappers = apply_filters( $this->filter_base . 'element/global/' . $this->type . '/element_wrappers', $element_wrappers, $this->name, $this );
			$element_wrappers = apply_filters( $this->filter_base . 'element/' . $this->type . '/' . $this->name . '/element_wrappers', $element_wrappers, $this->name, $this );
			$this->set_element_wrappers( $element_wrappers );
		}
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 *
	 * @return $this
	 * @throws NotStringException
	 */
	public function set_name( $name ) {
		if ( is_string( $name ) ) {
			$this->name = $name;
		} else {
			throw new NotStringException( 'The parameter $name must be of type string, ' . gettype( $name ) . ' given.' );
		}

		return $this;
	}

	/**
	 * Get the name
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the element type
	 *
	 * @return string
	 */
	public function get_type() {
		return $this->type;
	}

	/**
	 * Sets the label
	 *
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		if ( is_string( $label ) ) {
			$this->label = $label;
		}

		return $this;
	}

	/**
	 * Get the label
	 *
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * Set the value
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function set_value( $value ) {
		if ( is_string( $value ) || is_numeric( $value ) || is_array( $value ) ) {
			$this->value = $value;
		}

		return $this;
	}

	/**
	 * Clears the value
	 */
	public function clear_value() {
		$this->value = '';

		return $this;
	}

	/**
	 * Get the value
	 *
	 * @return string
	 */
	public function get_value() {
		return $this->value;
	}

	/**
	 * Sets the options
	 *
	 * @param array $options
	 *
	 * @return $this
	 */
	public function set_options( array $options = [ ] ) {
		if ( is_array( $options ) ) {
			$this->options = $options;
		}

		return $this;
	}

	/**
	 * Add options
	 *
	 * @param array $options
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_options( array $options = [ ], $override = true ) {
		foreach ( $options as $key => $val ) {
			if ( $override == false ) {
				if ( isset( $this->$options[ $key ] ) ) {
					continue;
				}
			}

			$this->$options[ $key ] = $val;
		}

		return $this;
	}

	/**
	 * Get the options
	 *
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * Clears the existing options
	 *
	 * @return array
	 */
	public function clear_options() {
		$this->options = [ ];

		return $this;
	}

	/**
	 * Sets the tooltip
	 *
	 * @param string $tooltip
	 *
	 * @return $this
	 */
	public function set_tooltip( $tooltip ) {
		if ( is_string( $tooltip ) || is_array( $tooltip ) ) {
			$this->tooltip = $tooltip;
		}

		return $this;
	}

	/**
	 * Get the tooltip
	 *
	 * @return string
	 */
	public function get_tooltip() {
		return $this->tooltip;
	}

	/**
	 * Sets the tooltip location
	 *
	 * @param string $location
	 *
	 * @return $this
	 */
	public function set_tooltip_location( $location ) {
		if ( is_string( $location ) && ( $location == 'label' || $location == 'element' || $location == 'block' ) ) {
			$this->tooltip_location = $location;
		}

		return $this;
	}

	/**
	 * Get the tooltip location
	 *
	 * @return string
	 */
	public function get_tooltip_location() {
		return $this->tooltip_location;
	}

	/**
	 * Sets the item classes for wrapping all form elements
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function set_block_wrappers( array $wrappers = [ ] ) {
		$this->block_wrap_classes = $wrappers;

		return $this;
	}

	/**
	 * Adds additional wrapper classes
	 *
	 * @param array $wrappers
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_block_wrappers( array $wrappers = [ ], $override = true ) {
		foreach ( $wrappers as $key => $val ) {
			if ( $override == false ) {
				if ( isset( $this->block_wrap_classes[ $key ] ) ) {
					continue;
				}
			}

			$this->block_wrap_classes[ $key ] = $val;
		}

		return $this;
	}

	/**
	 * Get the block wrappers
	 *
	 * @return array
	 */
	public function get_block_wrappers() {
		return $this->block_wrap_classes;
	}

	/**
	 * Clear the block wrappers
	 *
	 * @return $this
	 */
	public function clear_block_wrappers() {
		$this->block_wrap_classes = [ ];

		return $this;
	}

	/**
	 * Sets the item classes for wrapping the inputs only
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function set_element_wrappers( array $wrappers = [ ] ) {
		$this->element_wrap_classes = $wrappers;

		return $this;
	}

	/**
	 * Adds classes to the existing element wrapper classes
	 *
	 * @param array $wrappers
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_element_wrappers( array $wrappers = [ ], $override = true ) {
		foreach ( $wrappers as $key => $val ) {
			if ( $override == false ) {
				if ( isset( $this->element_wrap_classes[ $key ] ) ) {
					continue;
				}
			}

			$this->element_wrap_classes[ $key ] = $val;
		}

		return $this;
	}

	/**
	 * Get the element wrappers
	 *
	 * @return array
	 */
	public function get_element_wrappers() {
		return $this->element_wrap_classes;
	}

	/**
	 * Clear the element wrappers
	 *
	 * @return $this
	 */
	public function clear_element_wrappers() {
		$this->element_wrap_classes = [ ];

		return $this;
	}

	/**
	 * Gets the html to render the element
	 *
	 * @return string
	 */
	public function get_html() {
		$html = $this->get_pre_block_html();
		$html .= $this->get_pre_element_html();
		$html .= $this->get_element_html();
		$html .= $this->get_post_element_html();
		$html .= $this->get_post_block_html();

		return $html;
	}

	/**
	 * Gets the html to render the element input
	 *
	 * @return string
	 */
	public function get_element_html() {
		$html = '<input name="' . $this->name . '" type="' . $this->type . '" ';

		if ( ! empty( $this->value ) && is_string( $this->value ) ) {
			$html .= 'value="' . $this->value . '" ';
		}

		$html .= $this->get_attributes_string();
		$html .= ' />';

		return $html;
	}

	/**
	 * Gets the Tooltip HTML
	 *
	 * @return string
	 */
	public function get_tooltip_html() {
		$html = '';

		if ( ! empty( $this->tooltip ) && is_string( $this->tooltip ) ) {
			$html .= '<span class="help" >' . $this->tooltip . '</span>';
		}

		if ( ! empty( $this->tooltip ) && is_array( $this->tooltip ) ) {
			foreach ( $this->tooltip as $tooltip ) {
				if ( is_array( $tooltip ) && isset( $tooltip['content'] ) ) {

					$class = [
						'help'
					];

					if ( isset( $tooltip['class'] ) && is_string( $tooltip['class'] ) ) {
						$class[] = $tooltip['class'];
					}

					if ( isset( $tooltip['class'] ) && is_array( $tooltip['class'] ) ) {
						$class = array_merge( $class, $tooltip['class'] );
					}

					$html .= '<span class="' . join( ' ', $class ) . '" >' . $tooltip['content'] . '</span>';

				} elseif ( is_string( $tooltip ) && ! empty( $tooltip ) ) {
					$html .= '<span class="help" >' . $tooltip . '</span>';
				}
			}
		}

		return $html;
	}

	/**
	 * Get the opening part of the block wrapper
	 *
	 * @return string
	 */
	public function get_pre_block_html() {
		$html    = '';
		$classes = [ ];

		if ( isset( $this->block_wrap_classes ) && ! empty( $this->block_wrap_classes ) ) {
			$classes = array_merge( $classes, $this->block_wrap_classes );
		}

		$html .= '<div class="';
		$html .= join( ' ', $classes ) . ' ' . $this->type;
		$html .= '" >';

		if ( ! empty( $this->label ) ) {
			$html .= '<label for="' . $this->name . '" >' . $this->label . '</label>';
		}

		return $html;
	}

	/**
	 * Gets the closing part of the block wrapper
	 *
	 * @return string
	 */
	public function get_post_block_html() {
		$html = '';

		if ( $this->tooltip_location == 'block' ) {
			$html .= $this->get_tooltip_html();
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Gets the opening part of the element wrapper
	 *
	 * @return string
	 */
	public function get_pre_element_html() {
		$html    = '';
		$classes = [ ];

		if ( $this->tooltip_location == 'label' ) {
			$html .= $this->get_tooltip_html();
		}

		$html .= '<div class="';

		if ( isset( $this->element_wrap_classes ) && ! empty( $this->element_wrap_classes ) ) {
			$classes = array_merge( $classes, $this->element_wrap_classes );
		}

		$html .= $this->name . ' ' . $this->type . ' ' . join( ' ', $classes );
		$html .= '">';

		return $html;
	}

	/**
	 * Gets the closing part of the element wrapper
	 *
	 * @return string
	 */
	public function get_post_element_html() {
		$html = '';

		if ( $this->tooltip_location == 'element' ) {
			$html .= $this->get_tooltip_html();
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Generates the attributes for the element
	 *
	 * Can accept additional one off arguments
	 *
	 * @param array
	 *
	 * @return string
	 */
	protected function get_attributes_string( $input = null ) {
		$html = '';

		if ( $input != null && isset( $this->attributes[ $input ] ) ) {
			if ( ! $this->attributes[ $input ] ) {
				return $html;
			}

			foreach ( $this->attributes[ $input ] as $key => $val ) {
				$html .= $key . '="' . join( ' ', $val ) . '" ';
			}
		} else {
			if ( ! $this->attributes ) {
				return $html;
			}

			foreach ( $this->attributes as $key => $val ) {
				$html .= $key . '="' . join( ' ', $val ) . '" ';
			}
		}

		return $html;
	}
}
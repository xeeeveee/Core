<?php

namespace Xeeeveee\Core\Forms\Elements;

abstract class Element implements ElementInterface {

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
	 * Attributes to be added to the html tag
	 *
	 * @var array
	 */
	protected $attributes;

	/**
	 * Wording of the label for this element
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * The current value of the element
	 *
	 * @var string
	 */
	protected $value;

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

		if ( isset( $args['attributes'] ) ) {
			$this->set_attributes( $args['attributes'] );
		} else {
			$this->set_attributes( [ ] );
		}

		if ( isset( $args['label'] ) ) {
			$this->set_label( $args['label'] );
		}

		if ( isset( $args['value'] ) ) {
			$this->set_value( $args['value'] );
		}

		if ( isset( $args['tooltip'] ) ) {
			$this->set_tooltip( $args['tooltip'] );
			if ( isset( $args['tooltip-location'] ) ) {
				$this->set_tooltip_location( $args['tooltip-location'] );
			}
		}

		if ( isset( $args['wrappers']['block'] ) && is_array( $args['wrappers']['block'] ) ) {
			$this->set_block_wrappers( $args['wrappers']['block'] );
		}

		if ( isset( $args['wrappers']['element'] ) && is_array( $args['wrappers']['element'] ) ) {
			$this->set_element_wrappers( $args['wrappers']['element'] );
		}
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function set_name( $name ) {
		if ( is_string( $name ) ) {
			$this->name = $name;
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
	 * Set the attributes
	 *
	 * This difference from add_attributes in that is will erase all the existing attributes and assign the new ones
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	public function set_attributes( array $attributes = [ ] ) {
		$this->clear_attributes();

		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reserved_attributes ) && is_array( $val ) ) {
				$this->attributes[ $key ] = $val;
			}
		}

		return $this;
	}

	/**
	 * Add attributes
	 *
	 * This differs from set_attributes in that is does not erase the attributes before adding the new ones
	 *
	 * @param array $attributes
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function add_attributes( array $attributes = [ ], $override = true ) {
		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reserved_attributes ) && is_array( $val ) ) {
				if ( $override == false ) {
					if ( isset( $this->attributes[ $key ] ) ) {
						continue;
					}
				}

				$this->attributes[ $key ] = $val;
			}
		}

		return $this;
	}

	/**
	 * Get the attributes
	 *
	 * @return array
	 */
	public function get_attributes() {
		return $this->attributes;
	}

	/**
	 * Clears the existing attributes
	 *
	 * @return $this
	 */
	public function clear_attributes() {
		$this->attributes = [ ];

		return $this;
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
		if ( is_string( $value ) || is_numeric( $value ) ) {
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

		if ( ! empty( $this->value ) ) {
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
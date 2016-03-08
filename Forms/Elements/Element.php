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
	protected $tooltipLocation = 'block';

	/**
	 * Reserved attribute names
	 *
	 * These will be ignored if added as attributes, they will have special behaviour
	 *
	 * @var array
	 */
	protected $reservedAttributes = [
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
	protected $blockWrapClasses = [
		'form-group'
	];

	/**
	 * Element wrapper css classes
	 *
	 * @var array
	 */
	protected $elementWrapClasses = [
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

		$this->setName( $name );

		if ( isset( $args['attributes'] ) ) {
			$this->setAttributes( $args['attributes'] );
		} else {
			$this->setAttributes( [ ] );
		}

		if ( isset( $args['label'] ) ) {
			$this->setLabel( $args['label'] );
		}

		if ( isset( $args['value'] ) ) {
			$this->setValue( $args['value'] );
		}

		if ( isset( $args['tooltip'] ) ) {
			$this->setTooltip( $args['tooltip'] );
			if ( isset( $args['tooltip-location'] ) ) {
				$this->setTooltipLocation( $args['tooltip-location'] );
			}
		}

		if ( isset( $args['wrappers']['block'] ) && is_array( $args['wrappers']['block'] ) ) {
			$this->setBlockWrappers( $args['wrappers']['block'] );
		}

		if ( isset( $args['wrappers']['element'] ) && is_array( $args['wrappers']['element'] ) ) {
			$this->setElementWrappers( $args['wrappers']['element'] );
		}
	}

	/**
	 * Sets the name
	 *
	 * @param string $name
	 *
	 * @return $this
	 */
	public function setName( $name ) {
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
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the element type
	 *
	 * @return string
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Set the attributes
	 *
	 * This difference from addAttributes in that is will erase all the existing attributes and assign the new ones
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	public function setAttributes( array $attributes = [ ] ) {
		$this->clearAttributes();

		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reservedAttributes ) && is_array( $val ) ) {
				$this->attributes[ $key ] = $val;
			}
		}

		return $this;
	}

	/**
	 * Add attributes
	 *
	 * This differs from setAttributes in that is does not erase the attributes before adding the new ones
	 *
	 * @param array $attributes
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function addAttributes( array $attributes = [ ], $override = true ) {
		foreach ( $attributes as $key => $val ) {
			if ( ! in_array( $key, $this->reservedAttributes ) && is_array( $val ) ) {
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
	public function getAttributes() {
		return $this->attributes;
	}

	/**
	 * Clears the existing attributes
	 *
	 * @return $this
	 */
	public function clearAttributes() {
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
	public function setLabel( $label ) {
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
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Set the value
	 *
	 * @param string $value
	 *
	 * @return $this
	 */
	public function setValue( $value ) {
		if ( is_string( $value ) || is_numeric( $value ) ) {
			$this->value = $value;
		}

		return $this;
	}

	/**
	 * Clears the value
	 */
	public function clearValue() {
		$this->value = '';

		return $this;
	}

	/**
	 * Get the value
	 *
	 * @return string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * Sets the tooltip
	 *
	 * @param string $tooltip
	 *
	 * @return $this
	 */
	public function setTooltip( $tooltip ) {
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
	public function getTooltip() {
		return $this->tooltip;
	}

	/**
	 * Sets the tooltip location
	 *
	 * @param string $location
	 *
	 * @return $this
	 */
	public function setTooltipLocation( $location ) {
		if ( is_string( $location ) && ( $location == 'label' || $location == 'element' || $location == 'block' ) ) {
			$this->tooltipLocation = $location;
		}

		return $this;
	}

	/**
	 * Get the tooltip location
	 *
	 * @return string
	 */
	public function getTooltipLocation() {
		return $this->tooltipLocation;
	}

	/**
	 * Sets the item classes for wrapping all form elements
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function setBlockWrappers( array $wrappers = [ ] ) {
		$this->blockWrapClasses = $wrappers;

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
	public function addBlockWrappers( array $wrappers = [ ], $override = true ) {
		foreach ( $wrappers as $key => $val ) {
			if ( $override == false ) {
				if ( isset( $this->blockWrapClasses[ $key ] ) ) {
					continue;
				}
			}

			$this->blockWrapClasses[ $key ] = $val;
		}

		return $this;
	}

	/**
	 * Get the block wrappers
	 *
	 * @return array
	 */
	public function getBlockWrappers() {
		return $this->blockWrapClasses;
	}

	/**
	 * Clear the block wrappers
	 *
	 * @return $this
	 */
	public function clearBlockWrappers() {
		$this->blockWrapClasses = [ ];

		return $this;
	}

	/**
	 * Sets the item classes for wrapping the inputs only
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function setElementWrappers( array $wrappers = [ ] ) {
		$this->elementWrapClasses = $wrappers;

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
	public function addElementWrappers( array $wrappers = [ ], $override = true ) {
		foreach ( $wrappers as $key => $val ) {
			if ( $override == false ) {
				if ( isset( $this->elementWrapClasses[ $key ] ) ) {
					continue;
				}
			}

			$this->elementWrapClasses[ $key ] = $val;
		}

		return $this;
	}

	/**
	 * Get the element wrappers
	 *
	 * @return array
	 */
	public function getElementWrappers() {
		return $this->elementWrapClasses;
	}

	/**
	 * Clear the element wrappers
	 *
	 * @return $this
	 */
	public function clearElementWrappers() {
		$this->elementWrapClasses = [ ];

		return $this;
	}

	/**
	 * Gets the html to render the element
	 *
	 * @return string
	 */
	public function getHtml() {
		$html = $this->getPreBlockHtml();
		$html .= $this->getPreElementHtml();
		$html .= $this->getElementHtml();
		$html .= $this->getPostElementHtml();
		$html .= $this->getPostBlockHtml();

		return $html;
	}

	/**
	 * Gets the html to render the element input
	 *
	 * @return string
	 */
	public function getElementHtml() {
		$html = '<input name="' . $this->name . '" type="' . $this->type . '" ';

		if ( ! empty( $this->value ) ) {
			$html .= 'value="' . $this->value . '" ';
		}

		$html .= $this->getAttributesString();
		$html .= ' />';

		return $html;
	}

	/**
	 * Gets the Tooltip HTML
	 *
	 * @return string
	 */
	public function getTooltipHtml() {
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
	public function getPreBlockHtml() {
		$html    = '';
		$classes = [ ];

		if ( isset( $this->blockWrapClasses ) && ! empty( $this->blockWrapClasses ) ) {
			$classes = array_merge( $classes, $this->blockWrapClasses );
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
	public function getPostBlockHtml() {
		$html = '';

		if ( $this->tooltipLocation == 'block' ) {
			$html .= $this->getTooltipHtml();
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Gets the opening part of the element wrapper
	 *
	 * @return string
	 */
	public function getPreElementHtml() {
		$html    = '';
		$classes = [ ];

		if ( $this->tooltipLocation == 'label' ) {
			$html .= $this->getTooltipHtml();
		}

		$html .= '<div class="';

		if ( isset( $this->elementWrapClasses ) && ! empty( $this->elementWrapClasses ) ) {
			$classes = array_merge( $classes, $this->elementWrapClasses );
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
	public function getPostElementHtml() {
		$html = '';

		if ( $this->tooltipLocation == 'element' ) {
			$html .= $this->getTooltipHtml();
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
	protected function getAttributesString( $input = null ) {
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
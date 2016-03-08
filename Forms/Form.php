<?php

namespace Xeeeveee\Core\Forms;

use Xeeeveee\Core\Exceptions\ElementNotFoundException;

class Form implements FormInterface {

	/**
	 * The elements attached to this form
	 *
	 * @var array
	 */
	protected $elements = [ ];

	/**
	 * The attributes to be added to the html tag
	 *
	 * @var array
	 */
	protected $attributes = [ ];

	/**
	 * The form method
	 *
	 * @var string
	 */
	protected $method = 'POST';

	/**
	 * The form action
	 *
	 * @var string
	 */
	protected $action = '';

	/**
	 * The element wrappers
	 *
	 * @var array
	 */
	protected $wrappers = [
		'block'   => '',
		'element' => ''
	];

	/**
	 * Reserved attributes
	 *
	 * These will be ignored if added as attributes
	 *
	 * @var array
	 */
	protected $reservedAttributes = [
		'method',
		'action'
	];

	/**
	 * Reserved element types
	 *
	 * These will not have values mass assigned
	 *
	 * @var array
	 */
	protected $protectedElementTypes = array(
		'submit',
		'button',
		'html'
	);

	/**
	 * Prepares a new form
	 *
	 * @param array $args
	 */
	public function __construct( $args ) {
		if ( isset( $args['elements'] ) ) {
			$this->setElements( $args['elements'] );
		}

		if ( isset( $args['attributes'] ) ) {
			$this->setAttributes( $args['attributes'] );
		}

		if ( isset( $args['wrappers'] ) ) {
			$this->setWrappers( $args['wrappers'] );
		}

		if ( isset( $args['method'] ) ) {
			$this->setMethod( $args['method'] );
		}

		if ( isset( $args['action'] ) ) {
			$this->setAction( $args['action'] );
		}

		if ( isset( $args['values'] ) ) {
			$this->setValues( $args['values'] );
		}
	}

	/**
	 * Sets the action
	 *
	 * @param string $action
	 *
	 * @return bool
	 */
	public function setAction( $action ) {
		if ( is_string( $action ) ) {
			$this->action = $action;
		}

		return $this;
	}

	/**
	 * Get the action
	 *
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Sets the method
	 *
	 * @param string $method
	 *
	 * @return $this
	 */
	public function setMethod( $method ) {
		if ( is_string( $method ) ) {
			$this->method = strtoupper( $method );
		}

		return $this;
	}

	/**
	 * Get the method
	 *
	 * @return string
	 */
	public function getMethod() {
		return $this->method;
	}

	/**
	 * Set the reserved attributes
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	public function setReservedAttributes( array $attributes ) {
		$this->reservedAttributes = $attributes;

		return $this;
	}

	/**
	 * Get the reserved attributes
	 *
	 * @return array
	 */
	public function getReservedAttributes() {
		return $this->reservedAttributes;
	}

	/**
	 * Set the guarded types
	 *
	 * @param array $protectedElementTypes
	 *
	 * @return $this
	 */
	public function setProtectedElementTypes( array $protectedElementTypes = [ ] ) {
		$this->protectedElementTypes = $protectedElementTypes;

		return $this;
	}

	/**
	 * Get the guarded types
	 *
	 * @return array
	 */
	public function getProtectedElementTypes() {
		return $this->protectedElementTypes;
	}

	/**
	 * Set the attributes
	 *
	 * This difference from addAttributes in that is will erase all the existing attributes and assign the new ones
	 *
	 * @param array $attributes the attributes to add
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
	 * Sets the values
	 *
	 * Will clear existing value is replacement is not set
	 *
	 * @param array $values the values to set
	 *
	 * @return $this
	 */
	public function setValues( array $values = [ ] ) {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->getType(),
					$this->protectedElementTypes ) && isset( $values[ $element->getName() ] )
			) {
				$element->setValue( $values[ $element->getName() ] );
			} else {
				$element->clearValue();
			}
		}

		return $this;
	}

	/**
	 * Adds values
	 *
	 * Adds values to the form, does not clear existing values
	 *
	 * @param array $values
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function addValues( array $values = [ ], $override = true ) {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protectedElementTypes ) && isset( $values[ $element->name ] ) ) {
				$element->setValue( $values[ $element->name ] );
			}
		}

		return $this;
	}

	/**
	 * Get the element values
	 *
	 * @return array
	 */
	public function getValues() {
		$values = [ ];

		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protectedElementTypes ) ) {
				$values[] = $element->getValue();
			}
		}

		return $values;
	}

	/**
	 * Clears all the element values
	 *
	 * @return bool true on success
	 */
	public function clearValues() {
		foreach ( $this->elements as $element ) {
			if ( ! in_array( $element->type, $this->protectedElementTypes ) ) {
				$element->clearValue();
			}
		}

		return $this;
	}

	/**
	 * Sets the elements
	 *
	 * This differs from addElements in that is erases the existing elements before adding the new ones
	 *
	 * @param array $elements
	 *
	 * @return $this
	 */
	public function setElements( array $elements = [ ] ) {
		$this->elements = [ ];

		foreach ( $elements as $element ) {
			$this->addElement( $element );
		}

		return $this;
	}

	/**
	 * Adds the elements
	 *
	 * This differs from setElements in that is does not erase the existing elements before adding the new ones
	 *
	 * @param array $elements
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function addElements( array $elements = [ ], $override = true ) {
		foreach ( $elements as $element ) {
			$this->addElement( $element, $override );
		}

		return $this;
	}

	/**
	 * Adds the element
	 *
	 * This differs from setElement in that is does not erase the existing element before adding the new ones
	 *
	 * @throws ElementNotFoundException
	 *
	 * @param array $element
	 * @param bool $override
	 *
	 * @return $this
	 */
	public function addElement( array $element, $override = true ) {
		if ( isset( $element['type'] ) && isset( $element['name'] ) ) {
			if ( $override == false ) {
				if ( isset( $this->elements[ $element['name'] ] ) ) {
					return $this;
				}
			}

			$class = ucfirst( $element['type'] );
			$class = __NAMESPACE__ . '\\Elements\\' . $class;

			if ( class_exists( $class ) ) {
				$this->elements[ $element['name'] ] = new $class( $element['name'], $element );
			} else {
				throw new ElementNotFoundException( 'Field type "' . ucfirst( $element['type'] ) . '" does not exist' );
			}
		}

		return $this;
	}

	/**
	 * Get the elements
	 *
	 * @return array
	 */
	public function getElements() {
		return $this->elements;
	}

	/**
	 * Gets a specific element
	 *
	 * @param string $name
	 *
	 * @return \Xeeeveee\Core\Forms\Elements\ElementInterface|bool
	 */
	public function getElement( $name ) {
		if ( isset( $this->elements[ $name ] ) ) {
			return $this->elements[ $name ];
		}

		return false;
	}

	/**
	 * Clears the elements
	 *
	 * @return $this
	 */
	public function clearElements() {
		unset( $this->elements );

		return $this;
	}

	/**
	 * Sets the element wrappers
	 *
	 * @param array $wrappers
	 *
	 * @return $this
	 */
	public function setWrappers( array $wrappers ) {
		$this->wrappers = [ ];

		if ( isset( $wrappers['block'] ) && is_array( $wrappers['block'] ) ) {
			$this->wrappers['block'] = $wrappers['block'];
		}

		if ( isset( $wrappers['element'] ) && is_array( $wrappers['element'] ) ) {
			$this->wrappers['element'] = $wrappers['element'];
		}

		foreach ( $this->elements as $element ) {
			if ( is_array( $this->wrappers['block'] ) && ! empty( $this->wrappers['block'] ) ) {
				$element->addBlockWrappers( $this->wrappers['block'] );
			}

			if ( is_array( $this->wrappers['element'] ) && ! empty( $this->wrappers['element'] ) ) {
				$element->setElementWrappers( $this->wrappers['element'] );
			}
		}

		return $this;
	}

	/**
	 * Get the form wrappers
	 *
	 * @return array
	 */
	public function getWrappers() {
		$wrappers = [ ];

		foreach ( $this->elements as $element ) {
			$wrappers[ $element->getName() ] = [
				'block'   => $element->getBlockWrappers(),
				'element' => $element->getElementWrappers()
			];
		}

		return $wrappers;
	}

	/**
	 * Gets the html to render the form
	 *
	 * @return string
	 */
	public function getHtml() {
		$html = $this->getFormOpeningHtml();
		$html .= $this->getElementsHtml();
		$html .= $this->getFormClosingHtml();

		return $html;
	}

	/**
	 * Gets the elements without the form wrapper
	 *
	 * @return string
	 */
	public function getElementsHtml() {
		$html = '';
		foreach ( $this->elements as $element ) {
			$html .= $element->getHtml();
		}

		return $html;
	}

	/**
	 * Get form opening tag
	 *
	 * @return string
	 */
	public function getFormOpeningHtml() {
		$html = '<form ';

		if ( ! empty( $this->method ) ) {
			$html .= 'method="' . $this->method . '" ';
		}

		if ( ! empty( $this->action ) ) {
			$html .= 'action="' . $this->action . '" ';
		}

		if ( ! empty( $this->attributes ) ) {
			foreach ( $this->attributes as $key => $val ) {
				$values = join( ' ', $val );
				$html .= $key . '="' . $values . '" ';
			}
		}

		$html .= '>';

		return $html;
	}

	/**
	 * Get form closing tag
	 *
	 * @return string
	 */
	public function getFormClosingHtml() {
		return '</form>';
	}
}
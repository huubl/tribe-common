<?php
/**
 * Settings_Section.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin;

use TEC\Common\Admin\Entities\Element;

/**
 * Class Settings_Section
 *
 * @since TBD
 */
class Settings_Section extends Section {

	/**
	 * Elements for the section.
	 *
	 * @var Element[]
	 */
	protected array $elements = [];

	/**
	 * Add an element to the section.
	 *
	 * @since TBD
	 *
	 * @param Element $element The element to add.
	 *
	 * @return static
	 */
	public function add_element( Element $element ) {
		$this->elements[] = $element;

		return $this;
	}

	/**
	 * Add multiple elements to the section.
	 *
	 * @since TBD
	 *
	 * @param Element[] $elements The elements to add.
	 *
	 * @return static
	 */
	public function add_elements( array $elements ) {
		foreach ( $elements as $element ) {
			$this->add_element( $element );
		}

		return $this;
	}

	/**
	 * Render the section content.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function render() {
		?>
		<div class="tribe-settings-section">
			<?php $this->render_title(); ?>
			<?php $this->render_elements(); ?>
		</div>
		<?php
	}

	/**
	 * Render the elements for the section.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	protected function render_elements() {
		foreach ( $this->elements as $element ) {
			$element->render();
		}
	}
}

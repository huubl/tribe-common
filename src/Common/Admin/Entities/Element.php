<?php
/**
 * Element Interface.
 *
 * @since TBD
 */

declare( strict_types=1 );

namespace TEC\Common\Admin\Entities;

/**
 * Interface Element
 *
 * @since TBD
 */
interface Element {

	/**
	 * Render the element.
	 *
	 * @return void
	 */
	public function render();
}
<?php

/**
 * Class The7_Demo_Content_Revslider_Importer
 */
class The7_Demo_Content_Revslider_Importer {

	/**
	 * @var null|RevSlider
	 */
	protected $revslider = null;

	/**
	 * The7_Demo_Content_Revslider_Importer constructor.
	 */
	public function __construct() {
		if ( class_exists( 'RevSliderSlider', false ) && method_exists( 'RevSliderSlider', 'importSliderFromPost' ) ) {
			$this->revslider = new RevSlider();
		}
	}

	/**
	 * Import $slider file.
	 *
	 * @param string $slider Absolute path to slider.
	 *
	 * @return array|bool
	 */
	public function import_slider( $slider ) {
		if ( is_null( $this->revslider ) ) {
			return false;
		}

		return $this->revslider->importSliderFromPost( true, true, $slider, false, false, true );
	}
}

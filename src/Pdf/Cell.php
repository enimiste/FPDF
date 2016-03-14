<?php
/**
 * Created by PhpStorm.
 * User: elbachirnouni
 * Date: 14/03/2016
 * Time: 17:34
 */

namespace Pdf;


class Cell {

	/** @var  int */
	protected $width;
	/** @var  int */
	protected $height;
	/** @var  string */
	protected $text;
	/** @var  int 0 or 1 */
	protected $border;
	/** @var  string J, L, C, R */
	protected $align;
	/** @var  bool */
	protected $fill;

	/**
	 * Cell constructor.
	 *
	 * @param int    $width
	 * @param int    $height
	 * @param string $text
	 * @param int    $border 1 or 0
	 * @param string $align  J, L, C or R
	 * @param bool   $fill
	 */
	public function __construct( $width, $height, $text, $border = 0, $align = 'J', $fill = false ) {
		$this->width  = $width;
		$this->height = $height;
		$this->text   = $text;
		$this->border = $border;
		$this->align  = $align;
		$this->fill   = $fill;
	}

	/**
	 * @return int
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return int
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->text;
	}

	/**
	 * @return int
	 */
	public function getBorder() {
		return $this->border;
	}

	/**
	 * @return string
	 */
	public function getAlign() {
		return $this->align;
	}

	/**
	 * @return boolean
	 */
	public function isFill() {
		return $this->fill;
	}


}
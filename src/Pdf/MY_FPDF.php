<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 14/03/2016
 * Time: 14:43
 */

namespace Com\NickelIT\Pdf;


use fpdf\FPDF_EXTENDED;

class MY_FPDF extends FPDF_EXTENDED {

	/**
	 * Print mult-iline cells
	 *
	 * @param            $w
	 * @param            $h
	 * @param            $txt
	 * @param int        $border
	 * @param string     $align
	 * @param bool|false $fill
	 * @param int        $ln Indicates where the current position should go after the
	 *                       call. Possible values are: 0 - to the rigth. Default to 2
	 */
	function MultiCell( $w, $h, $txt, $border = 0, $align = 'J', $fill = false, $ln = 2 ) {
		if ( $ln == 0 ) {
			$x = $this->GetX();
			$y = $this->GetY();
			parent::MultiCell( $w, $h, $txt, $border, $align, $fill );
			$this->SetXY( $x + $w, $y );
		} else {
			parent::MultiCell( $w, $h, $txt, $border, $align, $fill );
		}
	}

	/**
	 * Make a ligne in table
	 * This function put the bottom of cells in the same height
	 *
	 * @param array $cells of Cell object
	 */
	public function MutliCellTable( array $cells ) {
		$x0        = $this->GetX();
		$y0        = $this->GetY();
		$maxY      = $y0;
		$sumWidth  = 0;
		$hasBorder = false;
		$hasFill   = false;
		/** @var Cell $cell */
		foreach ( $cells as $cell ) {
			$hasBorder = $hasBorder || ( $cell->getBorder() == 1 );
			$hasFill   = $hasFill || $cell->isFill();
			$by        = $this->GetY();//before
			$bx        = $this->GetX();//before
			$this->MultiCell( $cell->getWidth(), $cell->getHeight(), $cell->getText(), 0, $cell->getAlign(), false, 2 );
			$ay = $this->GetY();//after
			if ( $ay > $maxY ) {
				$maxY = $ay;
			}
			$this->SetXY( $bx + $cell->getWidth(), $by );
			$sumWidth += $cell->getWidth();

		}
		if ( $sumWidth > 0 ) {
			$maxHeight = $maxY - $y0;
			foreach ( $cells as $cell ) {
				$this->Rect( $x0, $y0, $cell->getWidth(), $maxHeight );
				$x0 += $cell->getWidth();
			}
		}
		$this->SetY( $maxY );
	}
}
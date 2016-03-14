<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 14/03/2016
 * Time: 14:43
 */

namespace Com\NickelIT\Pdf;


use fpdf\FPDF_EXTENDED;
use Pdf\Cell;

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

		$x0       = $this->GetX();
		$y0       = $this->GetY();
		$maxY     = $y0;
		$sumWidth = 0;
		$borders  = [ ];
		/** @var Cell $cell */
		foreach ( $cells as $cell ) {
			$borders[] = $cell->getBorder();
			$this->MultiCell( $cell->getWidth(), $cell->getHeight(), $cell->getText(), 0, $cell->getAlign(), $cell->isFill(), 2 );
			$y = $this->GetY();
			if ( $y > $maxY ) {
				$maxY = $y;
			}
			$this->SetXY( $this->GetX() + $cell->getWidth(), $y0 );
			$sumWidth += $cell->getWidth();
		}
		if ( $sumWidth > 0 ) {
			$maxHeight = $maxY - $y0;
			foreach ( $cells as $cell ) {
				$this->Rect( $x0, $y0, $cell->getWidth(), $maxHeight );
				$x0 += $cell->getWidth();
			}
		}
	}

	/**
	 * Calculate the final hieght of a cell before draw it
	 *
	 * @param            $w
	 * @param            $h
	 * @param            $txt
	 * @param int        $border
	 * @param string     $align
	 *
	 * @return int
	 */
	protected function __calculateCellMultiLineHight( $w, $h, $txt, $border = 0, $align = 'J' ) {
		$totalHeight = 0;
		$ws          = $this->ws;
		// Output text with automatic or explicit line breaks
		$cw = &$this->CurrentFont['cw'];
		if ( $w == 0 ) {
			$w = $this->w - $this->rMargin - $this->x;
		}
		$wmax = ( $w - 2 * $this->cMargin ) * 1000 / $this->FontSize;
		$s    = str_replace( "\r", '', $txt );
		$nb   = strlen( $s );
		if ( $nb > 0 && $s[ $nb - 1 ] == "\n" ) {
			$nb --;
		}
		$b = 0;
		if ( $border ) {
			if ( $border == 1 ) {
				$border = 'LTRB';
				$b      = 'LRT';
				$b2     = 'LR';
			} else {
				$b2 = '';
				if ( strpos( $border, 'L' ) !== false ) {
					$b2 .= 'L';
				}
				if ( strpos( $border, 'R' ) !== false ) {
					$b2 .= 'R';
				}
				$b = ( strpos( $border, 'T' ) !== false ) ? $b2 . 'T' : $b2;
			}
		}
		$sep = - 1;
		$i   = 0;
		$j   = 0;
		$l   = 0;
		$ns  = 0;
		$nl  = 1;
		while ( $i < $nb ) {
			// Get next character
			$c = $s[ $i ];
			if ( $c == "\n" ) {
				// Explicit line break
				if ( $ws > 0 ) {
					$ws = 0;
					//$this->_out( '0 Tw' );
				}
				//$this->Cell( $w, $h, substr( $s, $j, $i - $j ), $b, 2, $align, $fill );
				$totalHeight += $h;
				$i ++;
				$sep = - 1;
				$j   = $i;
				$l   = 0;
				$ns  = 0;
				$nl ++;
				if ( $border && $nl == 2 ) {
					$b = $b2;
				}
				continue;
			}
			if ( $c == ' ' ) {
				$sep = $i;
				$ls  = $l;
				$ns ++;
			}
			$l += $cw[ $c ];
			if ( $l > $wmax ) {
				// Automatic line break
				if ( $sep == - 1 ) {
					if ( $i == $j ) {
						$i ++;
					}
					if ( $ws > 0 ) {
						$ws = 0;
						//$this->_out( '0 Tw' );
					}
					//$this->Cell( $w, $h, substr( $s, $j, $i - $j ), $b, 2, $align, $fill );
					$totalHeight += $h;
				} else {
					if ( $align == 'J' ) {
						$ws = ( $ns > 1 ) ? ( $wmax - $ls ) / 1000 * $this->FontSize / ( $ns - 1 ) : 0;
						$this->_out( sprintf( '%.3F Tw', $ws * $this->k ) );
					}
					//$this->Cell( $w, $h, substr( $s, $j, $sep - $j ), $b, 2, $align, $fill );
					$totalHeight += $h;
					$i = $sep + 1;
				}
				$sep = - 1;
				$j   = $i;
				$l   = 0;
				$ns  = 0;
				$nl ++;
				if ( $border && $nl == 2 ) {
					$b = $b2;
				}
			} else {
				$i ++;
			}
		}
		// Last chunk
		if ( $ws > 0 ) {
			$ws = 0;
			//$this->_out( '0 Tw' );
		}
		if ( $border && strpos( $border, 'B' ) !== false ) {
			$b .= 'B';
		}
		//$this->Cell( $w, $h, substr( $s, $j, $i - $j ), $b, 2, $align, $fill );
		$totalHeight += $h;

		//$this->x = $this->lMargin;

		return $totalHeight;
	}
}
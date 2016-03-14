<?php
/**
 * Created by PhpStorm.
 * User: e.nouni
 * Date: 14/03/2016
 * Time: 14:43
 */

namespace Com\NickelIT\Pdf;


use fpdf\FPDF_EXTENDED;

class MY_FPDF extends FPDF_EXTENDED
{

    /**
     * Print mult-iline cells
     *
     * @param $w
     * @param $h
     * @param $txt
     * @param int $border
     * @param string $align
     * @param bool|false $fill
     * @param int $ln
     */
    function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false, $ln = 0)
    {
        if ($ln == 0) {
            $x = $this->GetX();
            $y = $this->GetY();
            parent::MultiCell($w, $h, $txt, $border, $align, $fill);
            $this->SetXY($x + $w, $y);
        } else parent::MultiCell($w, $h, $txt, $border, $align, $fill);
    }
}
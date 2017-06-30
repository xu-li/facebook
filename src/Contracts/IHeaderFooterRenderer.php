<?php
namespace Xu\Facebook\Contracts;

interface IHeaderFooterRenderer
{
    /**
     * render header
     *
     * @param FPDF  $pdf
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     */
    public function renderHeader(\FPDF $pdf, $x, $y, $w, $h);

    /**
     * render footer
     *
     * @param FPDF $pdf
     * @param float $x
     * @param float $y
     * @param float $w
     * @param float $h
     */
    public function renderFooter(\FPDF $pdf, $x, $y, $w, $h);
}

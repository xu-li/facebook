<?php
namespace Xu\Facebook\Contracts;

use Xu\Facebook\Cell;

interface ICellRenderer
{
    /**
     * render a cell
     * 
     * @param FPDF $pdf
     * @param Cell $cell
     */
    public function renderCell(\FPDF $pdf, Cell $cell);
}

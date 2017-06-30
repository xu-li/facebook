<?php
namespace Xu\Facebook\Traits;

use Xu\Facebook\Cell;
use Xu\Facebook\Contracts\ICellRenderer;

trait Griddable
{
    use Helper, Stateable;

    // columns
    protected $cols = 5;

    // rows
    protected $rows = 4;

    // cell margins, [top, right, bottom, left]
    protected $cell_margins  = [8, 3, 8, 3];

    // cell paddings, [top, right, bottom, left]
    protected $cell_paddings = [3, 1, 4, 1];

    // cell renderer
    protected $cell_renderer = null;

    // cell border color
    protected $cell_border_color = [0, 0, 0];

    // cell border width (this will not affect the width/height of the cell)
    protected $cell_border_width = 0.2;

    /**
     * Set cell renderer
     *
     * @param  ICellRenderer $renderer
     * @return FPDF
     */
    public function setCellRenderer(ICellRenderer $renderer)
    {
        $this->cell_renderer = $renderer;
        return $this;
    }

    /**
     * Render grid
     */
    public function renderGrid()
    {
        $this->AddPage();

        if (!$this->cell_renderer) {
            return ;
        }

        // page
        $page = $this->PageNo();

        // get geometries
        list($w, $h)   = $this->getCellSize();
        $page_margins  = $this->getPageMargins();
        $cell_margins  = $this->getCellMargins();
        $cell_paddings = $this->getCellPaddings();


        // loop rows and cols
        for ($i = 0; $i < $this->rows; $i++) {
            for ($j = 0; $j < $this->cols; $j++) {
                $x = $j * ($w + $cell_margins[3] + $cell_margins[1]) + $cell_margins[3] + $page_margins[3];
                $y = $i * ($h + $cell_margins[0] + $cell_margins[2]) + $cell_margins[0] + $page_margins[0];

                // render cell
                $this->cell_renderer->renderCell($this, Cell::fromArray([
                    'x'        => $x,
                    'y'        => $y,
                    'w'        => $w,
                    'h'        => $h,
                    'page'     => $page - 1, // starts from 0
                    'col'      => $j,
                    'row'      => $i,
                    'margins'  => $cell_margins,
                    'paddings' => $cell_paddings,
                ]));
            }
        }
    }

    /**
     * Render the cell border
     *
     * @param  Cell $cell
     * @return FPDF
     */
    public function renderCellBorder(Cell $cell)
    {
        if ($this->cell_border_width == 0) {
            return $this;
        }

        // border color
        $border_color = $this->cell_border_color;

        // save state
        $this->saveLineState();

        // draw border
        $this->SetDrawColor($border_color[0], $border_color[1], $border_color[2]);
        $this->Rect($cell->x, $cell->y, $cell->w, $cell->h);

        // restore draw color and line width
        $this->restoreLineState();

        return $this;
    }

    /**
     * Get cell size (width, height)
     *
     * @return array
     */
    public function getCellSize()
    {
        $w = $this->getContentWidth();
        $h = $this->getContentHeight();

        return [
            $w / $this->cols - $this->cell_margins[1] - $this->cell_margins[3],
            $h / $this->rows - $this->cell_margins[0] - $this->cell_margins[2]
        ];
    }

    /**
     * Get cell margins
     *
     * @return array
     */
    public function getCellMargins()
    {
        return $this->cell_margins;
    }

    /**
     * Set cell margins
     *
     * @param  array $margins
     * @return FPDF
     */
    public function setCellMargins($margins)
    {
        $this->cell_margins = $margins;
        return $this;
    }

    /**
     * Get cell paddings
     *
     * @return array
     */
    public function getCellPaddings()
    {
        return $this->cell_paddings;
    }

    /**
     * Set cell paddings
     *
     * @param  array $paddings
     * @return FPDF
     */
    public function setCellPaddings($paddings)
    {
        $this->cell_paddings = $paddings;
        return $this;
    }

    /**
     * Get cell border color
     *
     * @return array
     */
    public function getCellBorderColor()
    {
        return $this->cell_border_color;
    }

    /**
     * Set cell border color
     *
     * @param  array $color
     * @return FPDF
     */
    public function setCellBorderColor($color)
    {
        $this->cell_border_color = $color;
        return $this;
    }

    /**
     * Get cell border width
     *
     * @return float
     */
    public function getCellBorderWidth()
    {
        return $this->cell_border_width;
    }

    /**
     * Set cell border width
     *
     * @param  float $width
     * @return FPDF
     */
    public function setCellBorderWidth($width)
    {
        $this->cell_border_width = $width;
        return $this;
    }
    
    /**
     * Get cols
     *
     * @return int
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * Set cols
     *
     * @param  int  $cols
     * @return FPDF
     */
    public function setCols($cols)
    {
        $this->cols = $cols;
        return $this;
    }

    /**
     * Get rows
     *
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Set rows
     *
     * @param  int  $rows
     * @return FPDF
     */
    public function setRows($rows)
    {
        $this->rows = $rows;
        return $this;
    }
}

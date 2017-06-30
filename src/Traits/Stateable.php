<?php
namespace Xu\Facebook\Traits;

trait Stateable
{
    protected $text_stack = [];
    protected $line_stack = [];

    /**
     * Save text state
     *
     * @return FPDF
     */
    public function saveTextState()
    {
        $this->text_stack[] = [
            'font' => [
                'family' => $this->FontFamily,
                'style'  => $this->FontStyle,
                'size'   => $this->FontSizePt,
            ],

            'color' => $this->TextColor,
        ];

        return $this;
    }

    /**
     * Restore a saved text state
     *
     * @return FPDF
     */
    public function restoreTextState()
    {
        $state = array_pop($this->text_stack);

        if ($state) {
            $this->SetFont($state['font']['family'], $state['font']['style'], $state['font']['size']);

            $this->TextColor = $state['color'];
            $this->ColorFlag = $this->FillColor != $this->TextColor;
        }

        return $this;
    }

    /**
     * Save line state
     *
     * @return FPDF
     */
    public function saveLineState()
    {
        $this->line_stack[] = [
            'draw_color' => $this->DrawColor,
            'line_width' => $this->LineWidth,
        ];

        return $this;
    }

    /**
     * Restore a saved line state
     *
     * @return FPDF
     */
    public function restoreLineState()
    {
        $state = array_pop($this->line_stack);

        if ($state) {
            $this->DrawColor = $state['draw_color'];
            $this->SetLineWidth($state['line_width']);
        }

        return $this;
    }
}

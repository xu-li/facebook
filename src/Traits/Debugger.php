<?php
namespace Xu\Facebook\Traits;

trait Debugger
{
    /**
     * Draw frame
     *
     * It draws a frame using lMargin, tMargin, content width and content height.
     */
    public function frame()
    {
        $w = $this->GetPageWidth() - $this->lMargin - $this->rMargin;
        $h = $this->GetPageHeight() - $this->tMargin - $this->bMargin;
        
        $this->Rect(
            $this->lMargin,
            $this->tMargin,
            $w,
            $h
        );
    }

}

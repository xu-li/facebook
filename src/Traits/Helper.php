<?php
namespace Xu\Facebook\Traits;

trait Helper
{
    /**
     * Count number of lines for a string
     *
     * @param  string $text
     * @param  float  $width
     * @return int
     */
    public function getStringLines($text, $width)
    {
        $lines = 0;

        // remove the margin on both sides
        $width = $width - 2 * $this->cMargin;

        // loop each line
        foreach (explode(PHP_EOL, $text) as $line) {
            if ($line) {
                $lines += ceil($this->GetStringWidth($line) / $width);
            } else {
                // even for empty lines, increase one.
                $lines++;
            }
        }

        // make sure an integer is returned.
        return intval($lines);
    }
    
    /**
     * Get content width
     * 
     * Content width = Page width - Left margin - Right margin
     *
     * @return float
     */
    public function getContentWidth()
    {
        return $this->GetPageWidth() - $this->lMargin - $this->rMargin;
    }

    /**
     * Get content height
     *
     * Content height = Page height - Top margin - Bottom margin
     *
     * @return float
     */
    public function getContentHeight()
    {
        return $this->GetPageHeight() - $this->tMargin - $this->bMargin;
    }

    /**
     * Get top, right, bottom and left margins
     *
     * @return array
     */
    public function getPageMargins()
    {
        return [$this->tMargin, $this->rMargin, $this->bMargin, $this->lMargin];
    }
}

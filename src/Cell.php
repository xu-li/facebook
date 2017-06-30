<?php
namespace Xu\Facebook;

use Xu\Facebook\Traits\ArrayInstantiable;

class Cell
{
    use ArrayInstantiable;

    public $x        = 0;
    public $y        = 0;
    public $w        = 0;
    public $h        = 0;

    public $page     = 0;
    public $col      = 0;
    public $row      = 0;
    public $margins  = [0, 0, 0, 0];
    public $paddings = [0, 0, 0, 0];

    /**
     * Get size without paddings
     *
     * @return array
     */
    public function getSizeWithoutPaddings()
    {
        return [
            $this->w - $this->paddings[3] - $this->paddings[1],
            $this->h - $this->paddings[0] - $this->paddings[2]
        ];
    }
}

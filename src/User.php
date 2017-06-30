<?php
namespace Xu\Facebook;

use Xu\Facebook\Traits\ArrayInstantiable;

class User
{
    use ArrayInstantiable;

    public $image      = '';
    public $image_size = [22, 24];

    public $name       = '';
    public $name_size  = [0, 0];
    public $name_font  = null;
    public $name_color = [0, 0, 0];

    public $desc       = '';
    public $desc_size  = [0, 10];
    public $desc_font  = null;
    public $desc_color = [0, 0, 0];
}

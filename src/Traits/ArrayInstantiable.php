<?php
namespace Xu\Facebook\Traits;

trait ArrayInstantiable
{
    public static function fromArray($props)
    {
        $instance = new static;

        foreach ($props as $k => $v) {
            if (property_exists($instance, $k)) {
                $instance->$k = $v;
            }
        }

        return $instance;
    }
}

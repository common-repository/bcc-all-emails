<?php

declare (strict_types=1);
namespace WatchTheDot\Plugins\Dependencies\Watchthedot\Library\Settings\Field\Number;

class Decimal extends Integer
{
    public function __construct(string $key, ?string $label = null, float $step = 0.1)
    {
        parent::__construct($key, $label);
        $this->attributes['step'] = $step;
    }
    public function sanitize($value)
    {
        if (!\is_numeric($value)) {
            return $this->default ?? 0;
        }
        return \round(\floatval($value), \abs(\log(\floatval($this->attributes['step']), 10)));
    }
}

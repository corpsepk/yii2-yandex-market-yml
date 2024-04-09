<?php

namespace corpsepk\yml\dto;

use corpsepk\yml\enums\ConditionQuality;
use corpsepk\yml\enums\ConditionType;

class Condition
{
    public function __construct(
        public readonly ConditionType $type,
        public readonly ConditionQuality $quality,
        // Элемент внутри condition. Короткое описание следов использования или дефектов товара.
        public readonly string $reason,
    )
    {
    }
}
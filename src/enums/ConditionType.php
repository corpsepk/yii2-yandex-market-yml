<?php

namespace corpsepk\yml\enums;

/**
 * Признак товара бывшего в употреблении (б/у).
 *
 * Обязательно указывайте атрибут type:
 * preowned — бывший в употреблении, раньше принадлежал другому человеку.
 * showcasesample — витринный образец.
 * reduction — уцененный товар.
 */
enum ConditionType: string
{
    case PREOWNED = 'preowned';
    case SHOWCASESAMPLE = 'showcasesample';
    case REDUCTION = 'reduction';
}
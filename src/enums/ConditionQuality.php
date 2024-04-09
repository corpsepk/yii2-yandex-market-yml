<?php

namespace corpsepk\yml\enums;

/**
 * Элемент внутри condition. Состояние, внешний вид товара.
 *
 * Возможные значения:
 * perfect — как новый, товар в идеальном состоянии.
 * excellent — отличный, следы использования или дефекты едва заметные.
 * good — хороший, есть заметные следы использования или дефекты.
 */
enum ConditionQuality: string
{
    case PERFECT = 'perfect';
    case EXCELLENT = 'excellent';
    case GOOD = 'good';
}

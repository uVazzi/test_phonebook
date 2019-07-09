<?php

namespace common\models\enums;

use yii2mod\enum\helpers\BaseEnum;

/**
 * Class NumberType
 */
class NumberType extends BaseEnum
{
    const MOBILE = 0;
    const WORKER = 1;
    const HOME = 2;

    public static $messageCategory = 'app';

    public static $list = [
        self::MOBILE => 'Сотовый',
        self::WORKER => 'Рабочий',
        self::HOME => 'Домашний',
    ];
}
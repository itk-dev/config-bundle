<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Form;

use Symfony\Component\Form\AbstractType;

abstract class AbstractSettingType extends AbstractType
{
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_RICH_TEXT = 'richtext';
    const TYPE_ARRAY = 'array';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_TIME = 'time';
    const TYPE_INTEGER = 'integer';

    protected static $baseType = self::TYPE_STRING;

    public function getDisplayValue($value)
    {
        return $value;
    }

    public function getValue($data)
    {
        return $data;
    }

    public function getBaseType()
    {
        return static::$baseType;
    }
}

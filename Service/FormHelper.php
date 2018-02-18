<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Service;

use ItkDev\ConfigBundle\Entity\Setting;
use ItkDev\ConfigBundle\Form\AbstractSettingType;
use Psr\Container\ContainerInterface;

class FormHelper
{
    /** @var ContainerInterface */
    private $container;
    private $locales;
    private $formTypes;

    public function __construct(ContainerInterface $container, $formTypes, $locales)
    {
        $this->container = $container;
        $this->formTypes = $formTypes;
        $this->locales = $locales;
    }

    public function getLocales()
    {
        return $this->locales;
    }

    public function getFormTypes()
    {
        return $this->formTypes;
    }

    public function getFormType($type)
    {
        if (!isset($this->formTypes[$type])) {
            if ($this->container->has($type)) {
                return get_class($this->container->get($type));
            }

            throw new \RuntimeException('Invalid type: '.$type);
        }

        return $this->formTypes[$type];
    }

    public function getValue(Setting $setting)
    {
        $handler = $this->getValueHandler($setting);
        $type = null !== $handler
            ? $handler->getBaseType()
            : $setting->getType() ?: AbstractSettingType::TYPE_STRING;

        switch ($type) {
            case AbstractSettingType::TYPE_RICH_TEXT:
            case AbstractSettingType::TYPE_TEXT:
                return $setting->getValueText();
            case AbstractSettingType::TYPE_DATE:
            case AbstractSettingType::TYPE_DATETIME:
            case AbstractSettingType::TYPE_TIME:
                return $setting->getValueDateTime();
            case AbstractSettingType::TYPE_INTEGER:
                return $setting->getValueInteger();
            case AbstractSettingType::TYPE_STRING:
                return $setting->getValue();
            case AbstractSettingType::TYPE_ARRAY:
                return $setting->getValueArray();
            default:
                return null !== $handler ? $handler->getValue($setting->getValueText()) : $setting->getValue();
        }
    }

    public function getDisplayValue(Setting $setting)
    {
        $value = $this->getValue($setting);
        $handler = $this->getValueHandler($setting);

        return null !== $handler ? $handler->getDisplayValue($value) : $value;
    }

    public function getType(Setting $setting)
    {
        $type = $setting->getType() ?: AbstractSettingType::TYPE_STRING;

        if (class_exists($type)) {
            $handler = new $type();
            if ($handler instanceof AbstractSettingType) {
                return $handler->getBaseType();
            }
        }

        return $type;
    }

    private function getValueHandler(Setting $setting)
    {
        $type = $setting->getType();
        if (class_exists($type)) {
            $handler = new $type();
            if ($handler instanceof AbstractSettingType) {
                return $handler;
            }
        }

        return null;
    }
}

<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Entity;

use Craue\ConfigBundle\Entity\BaseSetting;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Craue\ConfigBundle\Repository\SettingRepository")
 * @ORM\Table(name="itkdev_setting")
 */
class Setting extends BaseSetting
{
    const TYPE_ARRAY = 'array';
    const TYPE_BOOLEAN = 'boolean';
    const TYPE_DATE = 'date';
    const TYPE_DATETIME = 'datetime';
    const TYPE_INTEGER = 'integer';
    const TYPE_STRING = 'string';
    const TYPE_TEXT = 'text';
    const TYPE_TIME = 'time';

    /**
     * @var null|string
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var null|string
     * @ORM\Column(name="type", type="string", nullable=true)
     */
    protected $type;

    /**
     * @var null|string
     * @ORM\Column(name="form_type", type="string", nullable=true)
     */
    protected $formType;

    /**
     * @var null|string
     * @ORM\Column(name="value_string", type="string", nullable=true)
     */
    protected $value;

    /**
     * @var null|string
     * @ORM\Column(name="value_text", type="text", nullable=true)
     */
    protected $valueText;

    /**
     * @var null|\DateTime
     * @ORM\Column(name="value_datetime", type="datetime", nullable=true)
     */
    protected $valueDateTime;

    /**
     * @var null|int
     * @ORM\Column(name="value_integer", type="integer", nullable=true)
     */
    protected $valueInteger;

    /**
     * @var null|int
     * @ORM\Column(name="value_boolean", type="boolean", nullable=true)
     */
    protected $valueBoolean;

    /**
     * @var null|int
     * @ORM\Column(name="value_array", type="json_array", nullable=true)
     */
    protected $valueArray;

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setFormType($formType)
    {
        $this->formType = $formType;

        return $this;
    }

    public function getFormType()
    {
        return $this->formType;
    }

    public function setValue($value)
    {
        switch ($this->getType()) {
            case self::TYPE_ARRAY:
                return $this->setValueArray($value);
            case self::TYPE_BOOLEAN:
                return $this->setValueBoolean($value);
            case self::TYPE_DATE:
            case self::TYPE_DATETIME:
            case self::TYPE_TIME:
                return $this->setValueDateTime($value);
            case self::TYPE_INTEGER:
                return $this->setValueInteger($value);
            case self::TYPE_STRING:
                return $this->setValueString($value);
            case self::TYPE_TEXT:
                return $this->setValueText($value);
        }

        // Fallback (type is null).
        return $this->setValueString($value);
    }

    public function getValue()
    {
        switch ($this->getType()) {
            case self::TYPE_ARRAY:
                return $this->getValueArray();
            case self::TYPE_BOOLEAN:
                return $this->getValueBoolean();
            case self::TYPE_DATE:
            case self::TYPE_DATETIME:
            case self::TYPE_TIME:
                return $this->getValueDateTime();
            case self::TYPE_INTEGER:
                return $this->getValueInteger();
            case self::TYPE_STRING:
                return $this->getValueString();
            case self::TYPE_TEXT:
                return $this->getValueText();
        }

        // Fallback (type is null).
        return $this->getValueString();
    }

    public function getPropertyPath()
    {
        switch ($this->getType()) {
            case self::TYPE_ARRAY:
                return 'valueArray';
            case self::TYPE_BOOLEAN:
                return 'valueBoolean';
            case self::TYPE_DATE:
            case self::TYPE_DATETIME:
            case self::TYPE_TIME:
                return 'valueDateTime';
            case self::TYPE_INTEGER:
                return 'valueInteger';
            case self::TYPE_STRING:
                return 'value';
            case self::TYPE_TEXT:
                return 'valueText';
        }

        // Fallback (type is null).
        return 'value';
    }

    public function getValueString()
    {
        return $this->value;
    }

    public function setValueString($valueString)
    {
        $this->value = $valueString;

        return $this;
    }

    public function getValueText()
    {
        return $this->valueText;
    }

    public function setValueText($valueText)
    {
        $this->valueText = $valueText;

        return $this;
    }

    public function getValueDateTime()
    {
        return $this->valueDateTime;
    }

    public function setValueDateTime($valueDateTime)
    {
        $this->valueDateTime = $valueDateTime;

        return $this;
    }

    public function getValueInteger()
    {
        return $this->valueInteger;
    }

    public function setValueInteger($valueInteger)
    {
        $this->valueInteger = $valueInteger;

        return $this;
    }

    public function getValueArray()
    {
        return $this->valueArray;
    }

    public function setValueArray($valueArray)
    {
        $this->valueArray = $valueArray;

        return $this;
    }

    public function getValueBoolean()
    {
        return $this->valueBoolean;
    }

    public function setValueBoolean($valueBoolean)
    {
        $this->valueBoolean = $valueBoolean;

        return $this;
    }
}

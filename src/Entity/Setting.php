<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018–2024 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Entity;

use Craue\ConfigBundle\Entity\BaseSetting;
use Craue\ConfigBundle\Repository\SettingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'itkdev_config_setting')]
#[ORM\Entity(repositoryClass: SettingRepository::class)]
class Setting extends BaseSetting
{
    public const TYPE_ARRAY = 'array';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DATE = 'date';
    public const TYPE_DATETIME = 'datetime';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_STRING = 'string';
    public const TYPE_TEXT = 'text';
    public const TYPE_TIME = 'time';
    public const TYPE_YAML = 'yaml';
    public const TYPE_COLOR = 'color';

    public const FORM_TYPE_TEXT = 'text';
    public const FORM_TYPE_YAML = 'yaml';

    /**
     * @var string|null
     */
    #[ORM\Column(name: 'description', type: Types::TEXT)]
    protected string $description;

    #[ORM\Column(name: 'type', type: Types::STRING)]
    protected string $type;

    #[ORM\Column(name: 'form_type', type: Types::STRING)]
    protected string $formType;

    /**
     * @var mixed|null
     */
    #[ORM\Column(name: 'value', type: Types::JSON, nullable: true)]
    protected $value;

    /**
     * Roles that can edit this setting.
     */
    #[ORM\Column(name: 'roles', type: Types::JSON, nullable: true)]
    protected ?array $roles;

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

    public function setValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getRoles(): ?array
    {
        return $this->roles;
    }
}

<?php

namespace ItkDev\ConfigBundle\Trait;

use Doctrine\DBAL\ArrayParameterType;
use ItkDev\ConfigBundle\Entity\Setting;

trait SettingMigrationTrait
{
    private function createSetting(
        string $name,
        string $description,
        mixed $value = null,
        ?array $roles = null,
        string $type = Setting::TYPE_STRING,
        string $formType = Setting::FORM_TYPE_TEXT
    ) {
        var_export(func_get_args());

        $parameters = [
            'name' => $name,
            'description' => trim($description),
            'value' => json_encode(is_string($value) ? trim($value) : $value),
            'type' => $type,
            'form_type' => $formType,
            'roles' => json_encode($roles),
        ];
        $this->addSql(
            'INSERT INTO `itkdev_config_setting` (`name`, `description`, `type`, `form_type`, `value`, `roles`) VALUES (:name, :description, :type, :form_type, :value, :roles)',
            $parameters
        );
    }

    private function removeSettings(string ...$names)
    {
        if (!empty($names)) {
            $this->addSql("DELETE FROM `itkdev_config_setting` WHERE `name` IN (:names)", ['names' => $names], ['names' => ArrayParameterType::STRING]);
        }
    }
}

<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Controller;

use Craue\ConfigBundle\Util\Config;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use ItkDev\ConfigBundle\Entity\Setting;
use ItkDev\ConfigBundle\Form\AbstractSettingType;
use ItkDev\ConfigBundle\Service\FormHelper;

class SettingController extends BaseAdminController
{
    /** @var FormHelper */
    private $formHelper;

    /** @var Config */
    private $settings;

    public function __construct(FormHelper $formHelper, Config $settings)
    {
        $this->formHelper = $formHelper;
        $this->settings = $settings;
    }

    protected function createEntityForm($entity, array $entityProperties, $view)
    {
        $form = parent::createEntityForm($entity, $entityProperties, $view);

        if ($entity instanceof Setting) {
            list($type, $propertyPath) = $this->getFormTypeAndPropertyPath($entity);
            $form->add('value', $type, [
                'property_path' => $propertyPath,
            ]);
        }

        return $form;
    }

    protected function updateEntity($entity)
    {
        if ($entity instanceof Setting) {
            $this->settings->set($entity->getName(), $entity->getValue());
        }
    }

    private function getFormTypeAndPropertyPath(Setting $setting)
    {
        $type = $setting->getFormType() ?: AbstractSettingType::TYPE_STRING;
        $formType = $this->formHelper->getFormType($type);
//        if ($formType instanceof AbstractSettingType) {
//            $type = $formType->getBaseType();
//        }

        $propertyPath = $setting->getPropertyPath();

        return [$formType, $propertyPath];
    }
}

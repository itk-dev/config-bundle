<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018–2024 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Service;

use EasyCorp\Bundle\EasyAdminBundle\Field\CodeEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ItkDev\ConfigBundle\Entity\Setting;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

use function Symfony\Component\String\u;

class FormHelper
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function getShortDescription(string $description): string
    {
        $lines = u(strip_tags($description))->trim()->split(\PHP_EOL);
        $description = reset($lines);

        return u($description)->truncate(64, '…');
    }

    public function getFormField(Setting $setting): array
    {
        [$type, $formType] = [$setting->getType(), $setting->getFormType()];
        $fieldType = match ($type) {
            Setting::TYPE_COLOR => [
                ColorField::class,
                [
                    ColorField::OPTION_SHOW_VALUE => true,
                ],
            ],
            Setting::TYPE_STRING => TextField::class,
            Setting::TYPE_TEXT => match ($formType) {
                'texteditor' => TextEditorField::class,
                'textarea' => TextareaField::class,
                default => throw new \RuntimeException(sprintf('Unhandled form type: %s', $formType))
            },
            Setting::TYPE_YAML => [
                CodeEditorField::class,
                [
                    CodeEditorField::OPTION_LANGUAGE => 'yaml',
                ],
            ],
            default => throw new \RuntimeException(sprintf('Unhandled data type: %s', $type))
        };
        $options = [];
        if (\is_array($fieldType)) {
            [$fieldType, $options] = $fieldType;
        }

        return [$fieldType, $options];
    }

    /**
     * Check if setting is editable by current user.
     */
    public function isEditable(Setting $setting): bool
    {
        $roles = $setting->getRoles();

        // Edit access is granted if setting has no roles …
        return empty($roles)
            // … or at least one role is shared with the current user.
            || !empty(array_filter($roles, fn ($role) => $this->authorizationChecker->isGranted($role)));
    }
}

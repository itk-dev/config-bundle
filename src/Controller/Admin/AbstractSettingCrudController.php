<?php

/*
 * This file is part of itk-dev/config-bundle.
 *
 * (c) 2018–2024 ITK Development
 *
 * This source file is subject to the MIT license.
 */

namespace ItkDev\ConfigBundle\Controller\Admin;

use App\Controller\Admin\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use ItkDev\ConfigBundle\Admin\Field\SettingValueField;
use ItkDev\ConfigBundle\Entity\Setting;
use ItkDev\ConfigBundle\Service\FormHelper;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatableMessage;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class AbstractSettingCrudController extends AbstractCrudController
{
    public function __construct(
        private readonly FormHelper $formHelper,
        private readonly TranslatorInterface $translator,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return Setting::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::DELETE)
            ->disable(Action::NEW);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return parent::configureCrud($crud)
            ->setPageTitle(Crud::PAGE_INDEX, $this->translator->trans('Settings', domain: 'itkdev_config'))
            ->setPageTitle(Crud::PAGE_EDIT, function () {
                /** @var \App\Entity\Setting $setting */
                $setting = $this->getContext()->getEntity()->getInstance();

                return $this->translator->trans('Edit setting {name}', ['name' => $this->translator->trans($setting->getName(), domain: 'itkdev_config')], domain: 'itkdev_config');
            })
            ->overrideTemplate('crud/index', '@ItkDevConfig/crud/setting/index.html.twig')
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', $this->translator->trans('Name', domain: 'itkdev_config'))
            ->formatValue(fn ($value) => $this->translator->trans($value, domain: 'itkdev_config'))
            ->onlyOnIndex();
        yield TextField::new('section', $this->translator->trans('Section', domain: 'itkdev_config'))
            ->formatValue(fn ($value) => $value ? $this->translator->trans('section.'.$value, domain: 'itkdev_config') : '')
            ->onlyOnIndex();
        yield TextField::new('description', $this->translator->trans('Description', domain: 'itkdev_config'))
            ->formatValue($this->formHelper->getShortDescription(...))
            ->onlyOnIndex();

        if (Crud::PAGE_INDEX === $pageName) {
            yield SettingValueField::new('value', $this->translator->trans('Value', domain: 'itkdev_config'));
        } elseif (Crud::PAGE_EDIT === $pageName) {
            $setting = $this->getContext()->getEntity()->getInstance();
            if ($setting instanceof Setting) {
                [$fieldType, $options] = $this->formHelper->getFormField($setting);
                /** @var FieldTrait $field */
                $field = $fieldType::new('value', false);
                foreach ($options as $name => $value) {
                    $field->setCustomOption($name, $value);
                }
                yield $field
                    ->setHelp($setting->getDescription() ?: '');
            }
        }
    }

    public function edit(AdminContext $context)
    {
        $setting = $context->getEntity()->getInstance();
        if ($setting instanceof Setting
            && !$this->formHelper->isEditable($setting)) {
            throw new AccessDeniedException();
        }

        return parent::edit($context);
    }
}

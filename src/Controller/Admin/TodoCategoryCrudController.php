<?php

namespace App\Controller\Admin;

use App\Entity\TodoCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TodoCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TodoCategory::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Catégorie')
            ->setEntityLabelInPlural('Catégories');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', '#')->onlyOnIndex();
        yield TextField::new('title', 'Nom');
    }
}

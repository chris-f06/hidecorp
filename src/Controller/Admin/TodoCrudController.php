<?php

namespace App\Controller\Admin;

use App\Entity\Todo;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TodoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Todo::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Tâche')
            ->setEntityLabelInPlural('Tâches');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', '#')->onlyOnIndex();
        yield TextField::new('task', 'Tâche');
        yield AssociationField::new('category', 'Catégorie');
        yield DateTimeField::new('endedAt', 'Statut')->setTemplatePath('parts/admin/_todo_status.html.twig')->onlyOnIndex();
    }
}

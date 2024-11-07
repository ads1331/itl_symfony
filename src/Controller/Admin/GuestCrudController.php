<?
namespace App\Controller\Admin;

use App\Entity\Guest;
use App\Entity\Table;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class GuestCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Guest::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Гости')
            ->setPageTitle(Crud::PAGE_NEW, 'Добавить гостя')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактировать гостя');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Имя'),  
            BooleanField::new('isPresent', 'Присутствует'),  

            AssociationField::new('table', 'Стол')
                ->autocomplete() 
        ];
    }
}



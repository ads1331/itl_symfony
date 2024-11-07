<?
namespace App\Controller\Admin;

use App\Entity\GuestList;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Fields;

class GuestListCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return GuestList::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Список гостей')
            ->setPageTitle(Crud::PAGE_NEW, 'Добавить список гостей')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактировать список гостей');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'id',
            'name',
            'isPresent',
        ];
    }
}
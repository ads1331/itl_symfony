<?
namespace App\Controller\Admin;

use App\Entity\Table;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Fields;

class TableCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Table::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle(Crud::PAGE_INDEX, 'Столы')
            ->setPageTitle(Crud::PAGE_NEW, 'Добавить стол')
            ->setPageTitle(Crud::PAGE_EDIT, 'Редактировать стол');
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            'num',
            'description',
            'maxGuests',

        ];
    }
}

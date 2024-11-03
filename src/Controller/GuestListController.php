<?php

namespace App\Controller;

use App\Entity\Guest;
use App\Entity\GuestList;
use App\Entity\Table;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GuestListController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/guest_lists", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $guestLists = $this->entityManager->getRepository(GuestList::class)->findAll();

        return $this->json(array_map(function ($guestList) {
            return [
                'id' => $guestList->getId(),
                'name' => $guestList->getGuest()->getName(),
                'isPresent' => $guestList->getIsPresent(),
                'tables' => [
                    'id' => $guestList->getTable()->getId(),
                    'num' => $guestList->getTable()->getNum(),
                    'description' => $guestList->getTable()->getDescription(),
                    'maxGuests' => $guestList->getTable()->getMaxGuests(),
                    'guestsDef' => $guestList->getTable()->getGuestsDef(),
                    'guestsNow' => $guestList->getTable()->getGuestsNow(),
                    'guests' => array_map(function ($guest) {
                        return $this->generateUrl('guests_show', ['id' => $guest->getId()]);
                    }, $guestList->getTable()->getGuests()->toArray()), 
                ],
            ];
        }, $guestLists));
    }

    /**
     * @Route("/api/guest_lists/{id}", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $guestList = $this->entityManager->getRepository(GuestList::class)->find($id);

        if (!$guestList) {
            throw $this->createNotFoundException('Список гостей не найден');
        }

        return $this->json([
            'id' => $guestList->getId(),
            'name' => $guestList->getGuest()->getName(),
            'isPresent' => $guestList->getIsPresent(),
            'tables' => $guestList->getTable() ? [
                'id' => $guestList->getTable()->getId(),
                'num' => $guestList->getTable()->getNum(),
                'description' => $guestList->getTable()->getDescription(),
                'maxGuests' => $guestList->getTable()->getMaxGuests(),
                'guestsDef' => $guestList->getTable()->getGuestsDef(),
                'guestsNow' => $guestList->getTable()->getGuestsNow(),
                'guests' => array_map(function ($guest) {
                    return $this->generateUrl('guests_show', ['id' => $guest->getId()]);
                }, $guestList->getTable()->getGuests()->toArray()),
            ] : null,
        ]);
    }

    /**
    * @Route("/api/guest_lists/{id}", methods={"PATCH"})
    */
    public function update(Request $request, int $id): JsonResponse
    {
    $guestList = $this->entityManager->getRepository(GuestList::class)->find($id);

    if (!$guestList) {
        throw $this->createNotFoundException('Список гостей не найден');
    }

    $data = json_decode($request->getContent(), true);


    if (isset($data['name'])) {
        $guestList->getGuest()->setName($data['name']);
    }


    if (isset($data['isPresent'])) {
        $guestList->getGuest()->setIsPresent($data['isPresent']);
    }

    if (isset($data['tables'])) {
        $tableId = basename(parse_url($data['tables'], PHP_URL_PATH));

        if (is_numeric($tableId)) {
            $table = $this->entityManager->getRepository(Table::class)->find($tableId);
            
            if ($table) {
                $guestList->setTable($table);
            } else {
                return $this->json(['error' => 'Таблица с таким ID не найдена.'], 404);
            }
        } else {
            return $this->json(['error' => 'Некорректный URL таблицы.'], 400);
        }
    }

    $this->entityManager->flush();

    $tableUrl = $this->generateUrl('tables_show', ['id' => $guestList->getTable()->getId()]);

    return $this->json([
        'id' => $guestList->getId(),
        'name' => $guestList->getGuest()->getName(),
        'isPresent' => $guestList->getGuest()->getIsPresent(),
        'tables' => $tableUrl,
    ]);
    }

    /**
    * @Route("/api/tables/{id}/guests", methods={"GET"})
    */
    public function guests(int $id): JsonResponse
    {
    $table = $this->entityManager->getRepository(Table::class)->find($id);
    
    if (!$table) {
        throw $this->createNotFoundException('Стол не найден');
    }

   
    $guests = $table->getGuests(); 

    return $this->json(array_map(function ($guest) {
        return [
            'id' => $guest->getId(),
            'name' => $guest->getName(),
            'isPresent' => $guest->getIsPresent(),
            
        ];
    }, $guests->toArray()));
    }

}

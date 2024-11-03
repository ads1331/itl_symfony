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

class TableController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/tables", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $tables = $this->entityManager->getRepository(Table::class)->findAll();

        return $this->json(array_map(function ($table) {
            return [
                'id' => $table->getId(),
                'num' => $table->getNum(),
                'description' => $table->getDescription(),
                'maxGuests' => $table->getMaxGuests(),
                'guestsDef' => $table->getGuestsDef(),
                'guestsNow' => $table->getGuestsNow(),
                'guests' => array_map(function ($guest) {
                    return $this->generateUrl('guests_show', ['id' => $guest->getId()]);
                }, $table->getGuests()->toArray()),
            ];
        }, $tables));
    }

    /**
    * @Route("/api/tables/{id}", methods={"GET"}, name="tables_show")
    */
public function show(int $id): JsonResponse
{
    $table = $this->entityManager->getRepository(Table::class)->find($id);

    if (!$table) {
        throw $this->createNotFoundException('Стол не найден');
    }

    return $this->json([
        'id' => $table->getId(),
        'num' => $table->getNum(),
        'description' => $table->getDescription(),
        'maxGuests' => $table->getMaxGuests(),
        'guestsDef' => $table->getGuestsDef(),
        'guestsNow' => $table->getGuestsNow(),
        'guests' => array_map(function ($guest) {
            return $this->generateUrl('guests_show', ['id' => $guest->getId()]);
        }, $table->getGuests()->toArray()),
    ]);
}


    /**
    * @Route("/api/tables/{id}", methods={"PATCH"})
    */
public function update(Request $request, int $id): JsonResponse
{
    $table = $this->entityManager->getRepository(Table::class)->find($id);

    if (!$table) {
        throw $this->createNotFoundException('Стол не найден');
    }

    $validatedData = json_decode($request->getContent(), true);

    if (isset($validatedData['num'])) {
        $table->setNum($validatedData['num']);
    }
    if (isset($validatedData['description'])) {
        $table->setDescription($validatedData['description']);
    }
    if (isset($validatedData['maxGuests'])) {
        $table->setMaxGuests($validatedData['maxGuests']);
    }

    $this->entityManager->flush();

    return $this->json([
        'id' => $table->getId(),
        'num' => $table->getNum(),
        'description' => $table->getDescription(),
        'maxGuests' => $table->getMaxGuests(),
        'guestsDef' => $table->getGuestsDef(),
        'guestsNow' => $table->getGuestsNow(),
    ]);
}

    

    /**
    * @Route("/api/tables_stats", methods={"GET"})
    */
    public function stats(): JsonResponse
    {
    $tables = $this->entityManager->getRepository(Table::class)->findAll();

    return $this->json(array_map(function ($table) {
        return [
            'id' => $table->getId(),
            'num' => $table->getNum(),
            'description' => $table->getDescription(),
            'maxGuests' => $table->getMaxGuests(),
            'guestsDef' => $table->getGuestsDef(), 
            'guestsNow' => $table->getGuestsNow(), 
            'guests' => array_map(function ($guest) {
                return $guest->getName(); 
            }, $table->getGuests()->toArray()),
        ];
    }, $tables));
    }

    
}

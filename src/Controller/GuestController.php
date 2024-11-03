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

class GuestController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/guests", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        return $this->json($this->entityManager->getRepository(Guest::class)->findAll());
    }

     /**
     * @Route("/api/guests/{id}", methods={"GET"}, name="guests_show")
     */
    public function show(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $guest = $entityManager->getRepository(Guest::class)->find($id);

        if (!$guest) {
            throw $this->createNotFoundException('Гость не найден');
        }

        return $this->json([
            'id' => $guest->getId(),
            'name' => $guest->getName(),
            'is_present' => $guest->isIsPresent(),
        ]);
    }

    /**
    * @Route("/api/guests/{id}", methods={"PATCH"})
    */
    public function update(Request $request, int $id): JsonResponse
    {
    $guest = $this->entityManager->getRepository(Guest::class)->find($id);

    if (!$guest) {
        throw $this->createNotFoundException('Гость не найден');
    }

    $data = json_decode($request->getContent(), true);
    
    if (isset($data['name'])) {
        $guest->setName($data['name']);
    }
    if (isset($data['is_present'])) {
        $guest->setIsPresent($data['is_present']);
    }

    if (isset($data['table_id'])) {
        $table = $this->entityManager->getRepository(Table::class)->find($data['table_id']);
        if (!$table) {
            throw $this->createNotFoundException('Таблица не найдена');
        }
        $guest->setTable($table);
    }

    $this->entityManager->flush();

    return $this->json([
        'id' => $guest->getId(),
        'name' => $guest->getName(),
        'isPresent' => $guest->getIsPresent(),
        'tables' => $guest->getTable() ? '/api/tables/' . $guest->getTable()->getId() : null,
    ]);
}



}

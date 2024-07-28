<?php

namespace App\Controller;

use App\Entity\Unit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UnitController extends AbstractController
{
    #[Route('/unit/new', name: 'app_uni', methods:['GET'])]
    public function post(EntityManagerInterface $entityManager): JsonResponse
    {
        $unit = new Unit();
        $unit->setVerificationStatus(null);

        $entityManager->persist($unit);
        $entityManager->flush();

        return $this->json([
            'id' => $unit->getId(),
            'verificationStatus' => $unit->getVerificationStatus(),
            
        ]);
    }
    #[Route('/unit/{id}', name: 'app_unit', methods:['GET'])]
    public function index(int $id, EntityManagerInterface $entityManager): JsonResponse
    {
        $unit = $entityManager->getRepository(Unit::class)->find($id);
        
        return $this->json([
            'id' => $unit->getId(),
            'verificationStatus' => $unit->getVerificationStatus(),

        ]);
    }
    #[Route('/unit/{id}', name: 'app_unit_patch', methods: ['PATCH'])]
    public function patch(int $id, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): JsonResponse
    {
        // Pobranie jednostki z bazy danych
        $unit = $entityManager->getRepository(Unit::class)->find($id);
        
        // Pobranie danych z żądania
        $data = json_decode($request->getContent(), true);
        $verificationStatus = $data['verificationStatus'] ?? null;

        if ($verificationStatus !== 'verified' && $verificationStatus !== null) {
            return $this->json(['error' => 'invalid']);
        }

        $unit->setVerificationStatus($verificationStatus);

        $errors = $validator->validate($unit);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return new Response($errorsString);
        }

        $entityManager->flush();

        return $this->json([
            'id' => $unit->getId(),
            'verificationStatus' => $unit->getVerificationStatus(),
        ]);
    }
}

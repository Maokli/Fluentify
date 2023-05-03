<?php

namespace App\Controller;

use App\Entity\Quizz;   
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/api')]
class QuizzController extends AbstractController
{

    #[Route('/quizz/{id}', methods:"GET")]
    public function getQuizzById(ManagerRegistry $doctrine, Request $request, int $id) : JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $quizz = $entityManager->getRepository(Quizz::class)->findOneByID($id);

        if (!$quizz) {
            return new JsonResponse(['error' => 'Quizz not found'], 404);
        }

        $response = [
            'id' => $quizz->getId(),
            'question' => $quizz->getQuestions(),
            'option' => $quizz->getOptions(),
        ];

        return new JsonResponse($response, 200);
    }
    #[Route('/quizz/language/{languageId}', methods: ['GET'])]
    public function getQuizzsByLanguage(ManagerRegistry $doctrine, int $languageId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $quizzList = $entityManager->getRepository(Quizz::class)->findByLanguageid($languageId);

        $response = [];
        foreach ($quizzList as $quizz) {
            $response[] = [
                'id' => $quizz->getId(),
                'question' => $quizz->getQuestions(),
                'option' =>  $quizz->getOptions(),
            ];
        }

        return new JsonResponse($response, 200);
    }

    #[Route('/quizz/category/{category}', methods: ['GET'])]
    public function getQuizzsByGategories(ManagerRegistry $doctrine, int $category): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $quizzList = $entityManager->getRepository(Quizz::class)->findByCategory($category);

        $response = [];
        foreach ($quizzList as $quizz) {
            $response[] = [
                'id' => $quizz->getId(),
                'question' => $quizz->getQuestions(),
                'option' =>  $quizz->getOptions(),
            ];
        }

        return new JsonResponse($response, 200);
    }
}
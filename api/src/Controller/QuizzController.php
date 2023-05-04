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
    }
      //extract request body
      $body = json_decode($request->getContent());
      $categoryId = $body->categoryId;
      $languageId = $body->languageId;
      $questions = $body->questions;
      $options = $body->options;
      $answers = $body->answers;
      // create entity
      $quizz = new Quizz();

      // save
      $entityManager->persist($quizz);
      $entityManager->flush();

        return new JsonResponse($response, 200);
    }

    #[Route('/quizz/byCategoryAndLanguage/{categoryId}/{languageId}', methods: ['GET'])]
    public function getQuizzsByGategoryAndLanguage(ManagerRegistry $doctrine, int $categoryId, int $languageId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $quizzList = $entityManager->getRepository(Quizz::class)->findByCategoryAndLanguage($categoryId, $languageId);

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

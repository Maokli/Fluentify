<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Language;
use App\Entity\Quizz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use App\Helpers;
use App\Entity\UserQuizz;

#[Route('/api')]
class QuizzController extends AbstractController
{

    #[Route('/quizz/{id}', methods: "GET")]
    public function getQuizzById(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
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

        return new JsonResponse($response);
    }

    #[Route('/quizz/byCategoryAndLanguage/{categoryId}/{languageId}', methods: ['GET'])]
    public function getQuizzsByGategoryAndLanguage(ManagerRegistry $doctrine, int $categoryId, int $languageId): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $quizzList = $entityManager->getRepository(Quizz::class)->findByCategoryAndLanguage($categoryId, $languageId);
        $headers = apache_request_headers();
        $token = $headers['Authorization'];

        $userInDb = Helpers\getUserFromToken($entityManager, $token);
        if ($userInDb == null)
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);
        // get the ids of solved quizzes
        $userSolvedQuizzes = array_map(function($quizz) {
            return $quizz->getQuizz()->getId();
        }, $userInDb->getUserQuizzes()->toArray());

        $response = [];
        foreach ($quizzList as $quizz) {
            if (in_array($quizz->getId(), $userSolvedQuizzes)) {
                continue;
            }
            $response[] = [
                'id' => $quizz->getId(),
                'question' => $quizz->getQuestions(),
                'option' =>  $quizz->getOptions(),
            ];
        }

        return new JsonResponse($response, 200);
    }

    #[Route('/quizz/check', methods: ['POST'])]
    public function checkQuizzAnswer(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $body = json_decode($request->getContent());
        $quizzId = $body->quizzId;
        $answer = $body->answer;
        $quizz = $entityManager->getRepository(Quizz::class)->findOneByID($quizzId);
        $correctAnswer = $quizz->getAnswers();
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        $userInDb = Helpers\getUserFromToken($entityManager, $token);
        if ($userInDb == null)
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);

        $response = [
            'Answer' => $answer == $correctAnswer,
        ];
        // $userInDb return instance of that user
        if ($answer == $correctAnswer) {
            $userQuizz = new UserQuizz();
            $userQuizz->setOwner($userInDb);
            $userQuizz->setQuizz($quizz);
            $entityManager->persist($userQuizz);
            $userInDb->addUserQuizz($userQuizz);

            $entityManager->persist($userInDb);
            $entityManager->flush();
        }

        return new JsonResponse($response, 200);
    }
    //TODO: make this available to admins only
    #[Route('/admin/quizz/add', methods: "POST")]
    public function add(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();

        //extract request body
        $body = json_decode($request->getContent());
        $categoryId = $body->categoryId;
        $languageId = $body->languageId;
        $questions = $body->questions;
        $options = $body->options;
        $answers = $body->answers;

        // getting join entities
        $languageRepo = $entityManager->getRepository(Language::class);
        $categoriesRepo = $entityManager->getRepository(Category::class);

        $language = $languageRepo->findOneByID($languageId);
        $category = $categoriesRepo->findOneByID($categoryId);

        // create entity
        $quizz = new Quizz();
        $quizz->setLanguage($language);
        $quizz->setCategory($category);
        $quizz->setQuestions($questions);
        $quizz->setOptions($options);
        $quizz->setAnswers($answers);

        // save
        $entityManager->persist($quizz);
        $entityManager->flush();

        return $this->json(["message" => "Added Successfully"]);
    }
}

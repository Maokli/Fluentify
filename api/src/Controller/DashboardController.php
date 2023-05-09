<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Quizz;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers;

#[Route('/api', name: 'app_auth')]
class DashboardController extends AbstractController
{
    #[Route('/dashboard')]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();
        $languagesRepo = $entityManager->getRepository(Language::class);
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        

        $userInDb = Helpers\getUserFromToken($entityManager, $token);
        if($userInDb == null)
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);
        
        $userLanguages = explode("/",$userInDb->getPreferredLanguages());
        $userLanguages = array_filter($userLanguages, function ($value) {
            return !empty($value);
        });
        $data = [];
        
        foreach ($userLanguages as $userLanguage)
        {
            $languageInDb = $languagesRepo->findOneById($userLanguage);
            $totalQuizzesByLanguage = $languageInDb->getQuizzs()->count();
            $userQuizzes = $userInDb->getUserQuizzes()->toArray();
            // solved quizzes with the given language id only
            $userQuizzesByLanguage = array_filter($userQuizzes, function($value) use ($userLanguage) {
                $quizz = $value->getQuizz();

                return $quizz->getLanguage()->getId() == $userLanguage;
            });
            $solvedQuizzes = count($userQuizzesByLanguage);
            $data[]= [
                "languageId" => $languageInDb->getId(),
                "languageName" => $languageInDb->getName(),
                "photo" => $languageInDb->getPhotoUrl(),
                "progress" => $solvedQuizzes / $totalQuizzesByLanguage,
            ];
        }
        

        return $this->json($data);
    }
}

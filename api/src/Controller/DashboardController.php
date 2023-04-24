<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

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
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload);

        $userInDb = $entityManager->getRepository(User::class)->findOneByEmail($jwtPayload->username);
        if($userInDb == null)
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);
        
        $userLanguages = explode("/",$userInDb->getPreferredLanguages());
        $data = [];
        
        foreach ($userLanguages as $userLanguage)
        {
            $languageInDb = $languagesRepo->findOneById($userLanguage);
            $totalQuizzesByLanguage = $languageInDb->getQuizzs()->count();
            $solvedQuizzes = $userInDb->getUserQuizzes()->count();
            $data[]= [
                "languageId" => $languageInDb->id,
                "languageName" => $languageInDb->name,
                "progress" => $solvedQuizzes / $totalQuizzesByLanguage,
            ];
        }
        

        return $this->json($data);
    }
}

<?php

namespace App\Controller;

use App\Entity\Language;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Helpers;

#[Route('/api')]
class LanguagesController extends AbstractController
{
    #[Route('/languages', methods: "GET")]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();
        $languages = $entityManager->getRepository(Language::class)->findAll();

        // we map them to a dto
        $data = [];
        foreach ($languages as $language) {
            $data[] = [
                'id' => $language->getId(),
                'name' => $language->getName(),
                'photoUrl' => $language->getPhotoURl(),
                'description' => $language->getDescription(),
            ];
        }

        return $this->json($data);
    }

    //TODO: make this available to admins only
    #[Route('/admin/languages/add', methods: "POST")]
    public function add(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();

        //extract request body
        $body = json_decode($request->getContent());
        $name = $body->name;
        $photoUrl = $body->photoUrl;
        $description = $body->description;
        // create entity
        $language = new Language();
        $language->setName($name);
        $language->setPhotoUrl($photoUrl);
        $language->setDescription($description);
        // save
        $entityManager->persist($language);
        $entityManager->flush();

        return $this->json(["message" => "Added Successfully"]);
    }
    #[Route('/languages/Favorite', methods: "GET")]
    public function getFavorite(ManagerRegistry $doctrine): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        $userInDb = Helpers\getUserFromToken($entityManager, $token);
        if ($userInDb == null)
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);
        $userLanguages = explode("/", $userInDb->getPreferredLanguages());
        //making sure there are no empty strings it took me a while to figure out that the explode function adds an empty string at the end of the array lol
        $userLanguages = array_filter($userLanguages, function ($value) {
            return !empty($value);
        });
        $data = [];
        foreach ($userLanguages as $userLanguage) {
            $languageInDb = $entityManager->getRepository(Language::class)->findOneById($userLanguage);
            if ($languageInDb) {
                $data[] = [
                    "languageId" => $languageInDb->getId(),
                    "languageName" => $languageInDb->getName(),
                    "photoUrl" => $languageInDb->getPhotoURl(),
                    "description" => $languageInDb->getDescription(),
                ];
            }
        }
        return $this->json($data);
    }
    #[Route('/languages/Favorite', methods: "POST")]
    public function setFavorite(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();
        $headers = apache_request_headers();
        $token = $headers['Authorization'];
        $userInDb = Helpers\getUserFromToken($entityManager, $token);
        // Check if user exists
        if ($userInDb == null) {
            return $this->json([
                'Error' => 'No user exists with this Email',
            ], 404);
        }
        // Get the new language ID from the request body and the user's current languages hehehe
        $body = json_decode($request->getContent());
        $newLanguageId = $body->id;
        $userLanguages = explode("/", $userInDb->getPreferredLanguages());
        $userLanguages = array_filter($userLanguages, function ($value) {
            return !empty($value);
        });
        // Only add new language ID if it doesn't already exist in the list (these feel like eastereggs)
        if (!in_array($newLanguageId, $userLanguages)) {
            $userLanguages[] = $newLanguageId;
            $userInDb->setPreferredLanguages(implode("/", $userLanguages));
            $entityManager->persist($userInDb);
            $entityManager->flush();
        }
        return $this->json(["message" => "Added Successfully"]);
    }


}
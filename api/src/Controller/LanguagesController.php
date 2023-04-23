<?php

namespace App\Controller;

use App\Entity\Language;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class LanguagesController extends AbstractController
{
    #[Route('/languages', methods:"GET")]
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
}

<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/api')]
class CategoriesController extends AbstractController
{
    #[Route('/categories', methods: "GET")]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();
        $categories = $entityManager->getRepository(Category::class)->findAll();

        // we map them to a dto
        $data = [];
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->getId(),
                'name' => $category->getName(),
            ];
        }

        return $this->json($data);
    }

    //TODO: make this available to admins only
    #[Route('/admin/categories/add', methods: "POST")]
    public function add(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        // get the entities from DB
        $entityManager = $doctrine->getManager();

        //extract request body
        $body = json_decode($request->getContent());
        $name = $body->name;
        // create entity
        $category = new Category();
        $category->setName($name);
        // save
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->json(["message" => "Added Successfully"]);
    }
}

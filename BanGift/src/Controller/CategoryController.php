<?php

namespace App\Controller;

use App\Entity\Category; // Entité
use Doctrine\ORM\EntityManagerInterface;  // Gestionnaire d'entités pour les interactions avec la base de données
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;  // Classe de base pour tous les contrôleurs dans Symfony
use Symfony\Component\HttpFoundation\JsonResponse;  // Pour envoyer des réponses JSON
use Symfony\Component\HttpFoundation\Request;  // Pour gérer les requêtes HTTP
use Symfony\Component\HttpFoundation\Response;  // Classe de base pour les réponses HTTP
use Symfony\Component\Routing\Annotation\Route;  // Annotation pour définir les routes
use Symfony\Component\Serializer\SerializerInterface;  // Pour sérialiser/désérialiser les données
use Symfony\Component\Validator\Validator\ValidatorInterface;  // Pour la validation des entités

class CategoryController extends AbstractController
{

    // Route pour obtenir tous les catégories
    #[Route('/Categories', name: 'app_categories', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        // Récupération de tous les catégories de la base de données
        $categories = $entityManager->getRepository(Category::class)->findAll();
        // Sérialisation des catégories en JSON
        $json = $serializer->serialize($categories, 'json', ["groups" => "category_group"]);
        // Retourne une réponse JSON avec les catégories
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Route pour afficher un catégorie par son identifiant
    #[Route('/Category/{id}', name: 'category_show', methods: ['GET'])]
    public function showByID(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        // Recherche du catégorie par son identifiant
        $category = $entityManager->getRepository(Category::class)->find($id);

        // Si aucun catégorie n'est trouvé, déclenche une exception
        if (!$category) {
            throw $this->createNotFoundException(
                'No category found for id ' . $id
            );
        }

        // Sérialisation du catégorie trouvé en JSON
        $json = $serializer->serialize($category, 'json', ["groups" => "category_group"]);
        // Retourne une réponse JSON avec le catégorie
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    // Route pour créer un nouveau catégorie
    #[Route('/Category', name: 'create_category', methods: ['POST'])]
    public function createCategory(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        // Désérialisation de la requête JSON en un objet Category
        $category = $serializer->deserialize($request->getContent(), Category::class, 'json');
        // Validation de l'objet Category
        $errors = $validator->validate($category);
        // Si des erreurs sont détectées, renvoie une réponse avec ces erreurs
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }

        // Persistance du nouveau catégorie en base de données
        $entityManager->persist($category);
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la création du catégorie
        return new JsonResponse(['status' => 'Category created!'], Response::HTTP_CREATED);
    }

    // Route pour mettre à jour une catégorie existante
    #[Route('/Category/edit/{id}', name: 'category_edit', methods: ['PUT'])]
    public function update(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, int $id): Response
    {
        // Recherche du catégorie à mettre à jour
        $category = $entityManager->getRepository(Category::class)->find($id);
        // Si aucun catégorie n'est trouvé, déclenche une exception
        if (!$category) {
            throw $this->createNotFoundException('No category found for id ' . $id);
        }

        // Désérialisation des données de la requête dans l'objet Category existant
        $serializer->deserialize($request->getContent(), Category::class, 'json', ['object_to_populate' => $category]);
        // Sauvegarde des modifications en base de données
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la mise à jour du catégorie
        return new JsonResponse(['status' => 'Category updated!'], Response::HTTP_OK);
    }

    // Route pour supprimer un catégorie
    #[Route('/Category/delete/{id}', name: 'delete_category', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        // Recherche du catégorie à supprimer
        $category = $entityManager->getRepository(Category::class)->find($id);
        // Si aucun catégorie n'est trouvé, déclenche une exception
        if (!$category) {
            throw $this->createNotFoundException('No category found for id ' . $id);
        }

        // Suppression du catégorie de la base de données
        $entityManager->remove($category);
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la suppression du catégorie
        return new JsonResponse(['status' => 'Category deleted!'], Response::HTTP_OK);
    }
}

<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;  // Gestionnaire d'entités pour les interactions avec la base de données
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;  // Classe de base pour tous les contrôleurs dans Symfony
use Symfony\Component\HttpFoundation\JsonResponse;  // Pour envoyer des réponses JSON
use Symfony\Component\HttpFoundation\Request;  // Pour gérer les requêtes HTTP
use Symfony\Component\HttpFoundation\Response;  // Classe de base pour les réponses HTTP
use Symfony\Component\Routing\Annotation\Route;  // Annotation pour définir les routes
use Symfony\Component\Serializer\SerializerInterface;  // Pour sérialiser/désérialiser les données
use Symfony\Component\Validator\Validator\ValidatorInterface;  // Pour la validation des entités
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserController extends AbstractController
{

    #[Route('/Users', name: 'app_users', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $entityManager): Response
    {
        // Récupération de tous les users de la base de données
        $users = $entityManager->getRepository(User::class)->findAll();
        // Sérialisation des Utilisateurs en JSON
        $json = $serializer->serialize($users, 'json', ["groups" => "user_group"]);
        // Retourne une réponse JSON avec les Utilisateurs
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    #[Route('/User/{id}', name: 'user_show', methods: ['GET'])]
    public function showByID(EntityManagerInterface $entityManager, SerializerInterface $serializer, int $id): Response
    {
        // Recherche du Utilisateur par son identifiant
        $user = $entityManager->getRepository(User::class)->findOneBy(["id" => $id]);

        // Si aucun Utilisateur n'est trouvé, déclenche une exception
        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for id ' . $id
            );
        }

        // Sérialisation du Utilisateur trouvé en JSON
        $json = $serializer->serialize($user, 'json', ["groups" => "user_group"]);
        // Retourne une réponse JSON avec le Utilisateur
        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }
    #[Route('/User', name: 'create_user', methods: ['POST'])]
    public function createUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Désérialisation de la requête JSON en un objet User
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        // Validation de l'objet User
        $errors = $validator->validate($user);
        // Si des erreurs sont détectées, renvoie une réponse avec ces erreurs
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
        // Hashage du mot de passe
        $plainPassword = $user->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        // Persistance du nouveau Utilisateur en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la création du Utilisateur
        return new JsonResponse(['status' => 'User created!'], Response::HTTP_CREATED);
    }
    #[Route('/User/edit/{id}', name: 'user_edit', methods: ['PUT'])]
    public function update(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, int $id, UserPasswordHasherInterface $passwordHasher): Response
    {
        // Recherche du Utilisateur à mettre à jour
        $user = $entityManager->getRepository(User::class)->find($id);
        // Si aucun Utilisateur n'est trouvé, déclenche une exception
        if (!$user) {
            throw $this->createNotFoundException('No User found for id ' . $id);
        }

        // Désérialisation des données de la requête dans l'objet User existant
        $serializer->deserialize($request->getContent(), User::class, 'json', ['object_to_populate' => $user]);
        // Hashage du mot de passe
        $plainPassword = $user->getPassword();
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        // Sauvegarde des modifications en base de données
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la mise à jour du Utilisateur
        return new JsonResponse(['status' => 'User updated!'], Response::HTTP_OK);
    }
    #[Route('/User/delete/{id}', name: 'delete_user', methods: ['DELETE'])]
    public function delete(EntityManagerInterface $entityManager, int $id): Response
    {
        // Recherche du Utilisateur à supprimer
        $user = $entityManager->getRepository(User::class)->find($id);
        // Si aucun Utilisateur n'est trouvé, déclenche une exception
        if (!$user) {
            throw $this->createNotFoundException('No User found for id ' . $id);
        }

        // Suppression du Utilisateur de la base de données
        $entityManager->remove($user);
        $entityManager->flush();

        // Retourne une réponse JSON indiquant la suppression du Utilisateur
        return new JsonResponse(['status' => 'User deleted!'], Response::HTTP_OK);
    }
}

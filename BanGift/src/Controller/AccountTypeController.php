<?php

namespace App\Controller;

use App\Entity\AccountType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class AccountTypeController extends AbstractController
{

    // A voir 
    #[Route("/AccountTypes", name: 'app_account_type', methods: ['GET'])]
    public function getAllAccount(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $accountType = $em->getRepository(AccountType::class)->findAll();
        $json = $serializer->serialize($accountType, 'json', ["groups" => "account_type_groups"]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route("/AccountType/{id}", name: "app_account_type_id", methods: ['GET'])]
    public function getAccountTypeById(int $id, serializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $accountType = $em->getRepository(AccountType::class)->find($id);
        $json = $serializer->serialize($accountType, 'json', ["groups" => "account_type_groups"]);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route("/AccountType", name: "app_create_account_type", methods: ["POST"])]
    public function createAccount(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $accountType = $serializer->deserialize($data, AccountType::class, 'json');
        $entityManager->persist($accountType);
        $entityManager->flush();
        $json = $serializer->serialize($accountType, 'json', ["groups" => "account_type_groups"]);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route("/AccountType/edit/{id}", name: "app_update_account_type", methods: ["PUT"])]
    public function updateAccount(int $id, Request $request, EntityManagerInterface $em, SerializerInterface $serializer): Response
    {
        $accountType = $em->getRepository(AccountType::class)->find($id);
        $data = $request->getContent();
        $updateAccount = $serializer->deserialize($data, AccountType::class, 'json');
        $accountType->setActType($updateAccount->getActType());
        $em->persist($accountType);
        $em->flush();
        $json = $serializer->serialize($accountType, 'json', ["groups" => "account_type_groups"]);

        return new JsonResponse($json, Response::HTTP_CREATED, [], true);
    }

    #[Route("/AccountType/delete/{id}", name: "app_delete_account_type", methods: ["DELETE"])]
    public function deleteAccount(int $id, EntityManagerInterface $entityManager): Response
    {
        $accountType = $entityManager->getRepository(AccountType::class)->find($id);
        if (!$accountType) {
            throw $this->createNotFoundException(
                'Not found' . $id
            );
        }
        $entityManager->remove($accountType);
        $entityManager->flush();
        return new JsonResponse(['status' => 'Account Type deleted!'], Response::HTTP_OK);
    }
}

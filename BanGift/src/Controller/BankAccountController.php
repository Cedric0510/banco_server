<?php

namespace App\Controller;

use App\Entity\BankAccount;
use App\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BankAccountController extends AbstractController
{

    #[Route('/BankAccounts', name: 'app_BanKAccount', methods: ['GET'])]
    public function getAll(SerializerInterface $serializer, EntityManagerInterface $em): Response
    {
        $BankAccount = $em->getRepository(BankAccount::class)->findAll();
        $json = $serializer->serialize($BankAccount, 'json', ['groups' => 'banqueAccount_group']);

        return new JsonResponse($json, Response::HTTP_OK, [], true);
    }

    #[Route('/BankAccount/{id}', name: 'BanKAccount_show')]
    public function show(EntityManagerInterface $entityManager, int $id, SerializerInterface $serializer): Response
    {
        $BankAccount = $entityManager->getRepository(BankAccount::class)->find($id);

        if (!$BankAccount) {
            throw $this->createNotFoundException(
                'No BankAccount found for id ' . $id
            );
        }

        $json = $serializer->serialize($BankAccount, 'json', ['groups' => 'banqueAccount_group']);

        return new Response($json, Response::HTTP_OK, [], true);
    }

    #[Route('/BankAccount', name: 'create_banKAccount', methods: ['POST'])]
    public function createBankAccount(Request $request, EntityManagerInterface $em, ValidatorInterface $validator, SerializerInterface $serializer,): Response
    {

        $data = $request->getContent();
        $BankAccount = $serializer->deserialize($data, BankAccount::class, 'json');
        $em->persist($BankAccount);
        $em->flush();
        $json = $serializer->serialize($BankAccount, 'json', ["groups" => "banqueAccount_group"]);
        return new JsonResponse($json, Response::HTTP_CREATED, [], true);

        $errors = $validator->validate($BankAccount);
        if (count($errors) > 0) {
            return new Response((string) $errors, 400);
        }
    }

    #[Route('/BankAccount/edit/{id}', name: 'banKAccount_edit', methods: ['PUT'])]
    public function update(
        int $id,
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $entityManager
    ): Response {
        $BankAccount = $entityManager->getRepository(BankAccount::class)->find($id);
        $data = $request->getContent();
        $updatedBanKAccount = $serializer->deserialize($data, BankAccount::class, 'json');
        $BankAccount->setBnkBalance($updatedBanKAccount->getBnkBalance());
        $BankAccount->setBnkDebit($updatedBanKAccount->isBnkDebit());
        $BankAccount->setFkUsrId($updatedBanKAccount->getFkUsrId());
        $BankAccount->setFkActId($updatedBanKAccount->getFkActId());
        $BankAccount->setFkFrcId($updatedBanKAccount->getFkFrcId());
        $entityManager->flush();
        $json = $serializer->serialize($BankAccount, 'json', ["groups" => "banqueAccount_group"]);
        return new JsonResponse($json, Response::HTTP_ACCEPTED, [], true);
    }


    #[Route('/BankAccount/delete/{id}', name: 'banKAccount_delete')]
    public function delete(EntityManagerInterface $em, int $id, Transaction $transaction): Response
    {
        $BankAccount = $em->getRepository(BankAccount::class)->find($id);

        if (!$BankAccount) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        $BankAccount->removeTransaction($transaction);
        $em->remove($BankAccount);
        $em->flush();

        return new JsonResponse(['status' => 'BanKAccount deleted'], Response::HTTP_OK);
    }
}

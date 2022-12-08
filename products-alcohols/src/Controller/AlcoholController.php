<?php

namespace App\Controller;

use App\Entity\Alcohol;
use App\Repository\AlcoholRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class AlcoholController extends AbstractController
{

    public function __construct(
        private AlcoholRepository $alcoholRepository,
        private EntityManagerInterface $em
    ) {}

    #[Route('/alcohols', methods:['GET'], name: 'alcohols_get_collection')]
    public function index(): JsonResponse
    {
        $alcohols = $this->alcoholRepository->findAll();

        return $this->json(
            $alcohols,
            200,
            [],
            ['groups' => 'alcohol']
        );
    }

    #[Route('/alcohols/{id<\d+>}', methods:['GET'], name: 'alcohols_get_item')]
    public function show($id): JsonResponse
    {
        $alcohol = $this->alcoholRepository->find($id);
        if (empty($alcohol)) {
            return $this->json(["message" => "Not found!"], 404);
        }
  
        return $this->json(
            $alcohol,
            200,
            [],
            ['groups' => 'alcohol']
        );
    }

    #[Route('/alcohols/{id<\d+>}', methods:['DELETE'], name: 'alcohols_delete_item')]
    public function delete($id): JsonResponse
    {
        $alcohol = $this->alcoholRepository->find($id);
        if (empty($alcohol)) {
            return $this->json(["message" => "Not found!"], 404);
        }

        $this->em->remove($alcohol);
        $this->em->flush();
        return $this->json(["message" => "Deleted item!"], 200);
    }

    #[Route('/alcohols', methods:['POST'], name: 'alcohols_add_item')]
    public function add(
        Request $request, 
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) : JsonResponse
    {   
        $data = $serializer->deserialize(
            $request->getContent(), 
            Alcohol::class,
            'json'
        );

        $errors = $validator->validate($data);
        $messages = ['message' => 'Validations Failed!', 'errors' => []];

        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            return new JsonResponse($messages, 400);
        }

        $this->em->persist($data);
        $this->em->flush();

        return $this->json(
            $data,
            200,
            ['message' => 'Alcohol added succesfully!'],
            ['groups' => 'alcohol']
        );
    }

    #[Route('/alcohols/{id<\d+>}', methods:['PUT'], name: 'alcohols_update_item')]
    public function update(
        $id, 
        Request $request, 
        ValidatorInterface $validator, 
        SerializerInterface $serializer)
    { 
        $alcohol = $this->alcoholRepository->find($id);

        if (empty($alcohol)) {
            return $this->json(["message" => "Not found!"], 404);
        }

        $data = $serializer->deserialize(
            $request->getContent(), 
            Alcohol::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $alcohol]
        );
  
        $errors = $validator->validate($data);
        $messages = ['message' => 'Validations Failed!', 'errors' => []];

        foreach ($errors as $message) {
            $messages['errors'][] = [
                'property' => $message->getPropertyPath(),
                'value' => $message->getInvalidValue(),
                'message' => $message->getMessage(),
            ];
        }

        if (count($messages['errors']) > 0) {
            return new JsonResponse($messages, 400);
        }

        $this->em->persist($alcohol);
        $this->em->flush();

        return $this->json(
            $alcohol,
            200,
            ['message' => 'Alcohol updated succesfully!'],
            ['groups' => 'alcohol']
        );
    }
}

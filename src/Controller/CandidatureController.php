<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Formationn;
use App\Repository\CandidatureRepository;
use App\Repository\FormationnRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CandidatureController extends AbstractController
{
  
    #[Route('/api/candidatures/list', name: 'ListCandidature', methods: ['GET'])]
    public function index(CandidatureRepository $candidatureRepository, SerializerInterface $serializer): JsonResponse
    {
       $candidaturelist = $candidatureRepository->findAll();
       $jsonCandidatureList = $serializer->serialize($candidaturelist, 'json', ['groups' => 'getCandidatures']); 
       return new JsonResponse($jsonCandidatureList, Response::HTTP_OK, [], true);
    }
 

    
    #[Route('/api/candidature/{id}', name: 'showOneCandidature', methods: ['GET'])]
    public function indexOneCandidature (Candidature $candidature, SerializerInterface $serializer): JsonResponse
    {
        $jsonCandidature = $serializer->serialize($candidature, 'json', ['groups' => 'getCandidatures']);
        return new JsonResponse($jsonCandidature, Response::HTTP_OK, [], true);
    }
   

    #[Route('/api/candidature/update/{id}', name:"refuseCandidature", methods:['PUT'])]

    public function RefuseCandidature(Request $request, SerializerInterface $serializer, Candidature $currentCandidature, 
                                 EntityManagerInterface $entityManager, ): JsonResponse 
    {
        $updatedCandidature = $serializer->deserialize($request->getContent(), 
                Candidature::class, 
                'json', 
                [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCandidature]);
 
        $entityManager->persist($updatedCandidature);
        $entityManager->flush();
        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
   }

   #[Route('/api/candidature/store/{formation}', name:"addCandidature", methods: ['POST'])]
    public function addOneCandidature(Request $request, Formationn $formation, SerializerInterface $serializer, EntityManagerInterface $entityManager,
    FormationnRepository $formationRepository, UserRepository $userRepository, ValidatorInterface $validator, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $candidature = $serializer->deserialize($request->getContent(), Candidature::class, 'json');
        
        // On vérifie les erreurs
        $errors = $validator->validate($candidature);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        }

        // On attribue une formation à la candidature qu'on crée 
        $content = $request->toArray();
        $formation= $content['formation_id'] ?? -1;
        $candidature->setFormation($formationRepository->find($formation));
        
        // On attribue un user à la candidature qu'on crée 
        $content = $request->toArray();
        $user_id = $content['user_id'] ?? -1;
        $candidature->setUser($userRepository->find($user_id));
        // if ($candidature->getId() == null) {
        //     $entityManager->persist($candidature);
        //     }
        // dd($candidature);
        
        $entityManager->persist($candidature);
        $entityManager->flush();
        $jsonCandidature = $serializer->serialize($candidature, 'json', ['groups' => 'getCandidatures']);
        
        $location = $urlGenerator->generate('addCandidature', ['id' => $candidature->getId()], UrlGeneratorInterface::ABSOLUTE_URL); 

        return new JsonResponse($jsonCandidature, Response::HTTP_CREATED, ["Location" => $location], true);
   }
}

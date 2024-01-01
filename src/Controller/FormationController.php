<?php

namespace App\Controller;

use App\Entity\Formationn;
use App\Repository\FormationnRepository;
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

class FormationController extends AbstractController
{
    #[Route('/api/formations/list', name: 'ListFormation', methods: ['GET'])]
    public function index(FormationnRepository $formationnRepository, SerializerInterface $serializer): JsonResponse
    {
       $formationlist = $formationnRepository->findAll();
       $jsonFormationList = $serializer->serialize($formationlist, 'json', ['groups' => 'getFormations']); //
       return new JsonResponse($jsonFormationList, Response::HTTP_OK, [], true);
    }

    #[Route('/api/formation/{id}', name: 'showOneFormation', methods: ['GET'])]
    public function indexOneFormation(Formationn $formation, SerializerInterface $serializer): JsonResponse
    {
        $jsonFormation = $serializer->serialize($formation, 'json', ['groups' => 'getFormations']);
        return new JsonResponse($jsonFormation, Response::HTTP_OK, [], true);
    }
   
    #[Route('/api/formation/store', name:"addFormation", methods: ['POST'])]
    public function addOneFormation(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator): JsonResponse 
    {
        $formation = $serializer->deserialize($request->getContent(), Formationn::class, 'json');
        
          // On vérifie les erreurs
          $errors = $validator->validate($formation);

          if ($errors->count() > 0) {
              return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
          }
  
        $entityManager->persist($formation);
        $entityManager->flush();

        $jsonFormation = $serializer->serialize($formation, 'json', ['groups' => 'getFormations']);
        
        $location = $urlGenerator->generate('addFormation', ['id' => $formation->getId()], UrlGeneratorInterface::ABSOLUTE_URL); 

        return new JsonResponse($jsonFormation, Response::HTTP_CREATED, ["Location" => $location], true);
   }


   #[Route('/api/formation/update/{id}', name:"updateFormation", methods:['PUT'])]
   public function updateOneFormation(Request $request, SerializerInterface $serializer, Formationn $currentFormation, 
                                EntityManagerInterface $entityManager, ): JsonResponse
   {
       $updatedFormation = $serializer->deserialize($request->getContent(), 
               Formationn::class, 
               'json', 
               [AbstractNormalizer::OBJECT_TO_POPULATE => $currentFormation]);
       
       $entityManager->persist($updatedFormation);
       $entityManager->flush();
       return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
  }

  /**Supprime une formation */
  #[Route('/api/formation/delete/{id}', name: 'deleteFormation', methods: ['DELETE'])]
  public function deleteOneFormation(Formationn $formation, EntityManagerInterface $entityManager) // le param coverter envoie directement la donnée dont nous avons besoin avec l'instanciation de l'entité Book et sa variable $book grâce à l'id précisé dans la route 
  {
      $entityManager->remove($formation);
      $entityManager->flush();
    return new JsonResponse(null, Response::HTTP_NO_CONTENT); // code 204 car il est correct et qu'il n'ya rien à retourner 
  }

}

<?php

namespace App\Controller;

use App\Repository\BandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BandController extends AbstractController
{
    public function __construct(private BandRepository $bandRepository, private EntityManagerInterface $entityManager)
    {
    }
    #[Route('/api/bands', name: 'app_get_all_band', methods: ['GET'])]
    public function getAllBand(Request $request): JsonResponse
    {
        $name = $request->query->get('name');
        try {
            $results = [];
            $bands = $this->bandRepository->findAllByName($name);
            foreach ($bands as $band) {
                $founders = [];
                $musicType = null;

                if (!is_null($band->getMusicType()))
                    $musicType = $band->getMusicType()->getType();

                foreach ($band->getFounder()->getValues() as $founder) {
                    $founders[] = ['id' => $founder->getId(), 'name' => $founder->getName()];
                }

                $results[] = [
                    'id' => $band->getId(),
                    'groupName' => $band->getGroupName(),
                    'country' => $band->getCountry()->getName(),
                    'city' => $band->getCity()->getName(),
                    'beginningYears' => $band->getBeginningYears(),
                    'endingYears' => $band->getEndingYears(),
                    'founders' => $founders,
                    'members' => $band->getMembers(),
                    'musicType' => [
                        'type' => $musicType
                    ],
                    'description' => $band->getDescription()
                ];
            }
            return $this->json(
                $results,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        } catch (Error $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'there was an error',
                'error' => $e
            ]);
        }
    }

    #[Route('/api/bands/{id}', name: 'app_get_band_by_id', methods: ['GET'])]
    public function getBandById(int $id): JsonResponse
    {
        try {
            $results = [];
            $band = $this->entityManager->getRepository(Band::class)->findOneBy(['id' => $id]);
            $founders = [];
            $musicType = null;

            if (!is_null($band->getMusicType()))
                $musicType = $band->getMusicType()->getType();

            foreach ($band->getFounder()->getValues() as $founder) {
                $founders[] = ['id' => $founder->getId(), 'name' => $founder->getName()];
            }

            $results[] = [
                'id' => $band->getId(),
                'groupName' => $band->getGroupName(),
                'country' => $band->getCountry()->getName(),
                'city' => $band->getCity()->getName(),
                'beginningYears' => $band->getBeginningYears(),
                'endingYears' => $band->getEndingYears(),
                'founders' => $founders,
                'members' => $band->getMembers(),
                'musicType' => [
                    'type' => $musicType
                ],
                'description' => $band->getDescription()
            ];
            return $this->json(
                $results,
                headers: ['Content-Type' => 'application/json;charset=UTF-8']
            );
        } catch (Error $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'there was an error',
                'error' => $e
            ]);
        }
    }
}

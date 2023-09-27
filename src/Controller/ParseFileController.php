<?php

namespace App\Controller;

use App\Entity\Band;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Founder;
use App\Entity\MusicType;
use App\Repository\BandRepository;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\MusicTypeRepository;
use App\Service\ParseFIleService;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\BranchPruner;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParseFileController extends AbstractController
{
    private $cityRepository;
    private $countryRepository;
    private $musicTypeRepository;
    private $bandRepository;
    private $entityManager;

    public function __construct(CityRepository $cityRepository, CountryRepository $countryRepository, MusicTypeRepository $musicTypeRepository, BandRepository $bandRepository, EntityManagerInterface $entityManager)
    {
        $this->cityRepository = $cityRepository;
        $this->countryRepository = $countryRepository;
        $this->musicTypeRepository = $musicTypeRepository;
        $this->bandRepository = $bandRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/api/parse-file', name: 'app_parse_file', methods: ['POST'])]
    public function uploadXlsFile(Request $request, ParseFIleService $parseFIleService): JsonResponse
    {
        $file = $request->files->get('file');
        $allCities = $this->cityRepository->findAll();
        $allCountries = $this->countryRepository->findAll();
        $allMusicType = $this->musicTypeRepository->findAll();
        $cities = [];
        $countries = [];
        $musicTypes = [];


        foreach ($allCities as $city) {
            $cities[$city->getName()] = $city;
        }

        foreach ($allCountries as $country) {
            $countries[$country->getName()] = $country;
        }

        foreach ($allMusicType as $musicType) {
            $musicTypes[$musicType->getType()] = $musicType;
        }

        try {
            $parsedFile = $parseFIleService->parseFile($file);
            foreach ($parsedFile as $brand) {
                $bandToAdd = new Band();
                if (null !== $brand['C'] && array_key_exists($brand['C'], $cities)) {
                    $city =  $cities[$brand['C']];
                } else {
                    $city = new City($brand['C']);
                    $city->setName($brand['C']);
                    $this->entityManager->persist($city);
                    $cities[$brand['C']] = $city;
                }

                if (null !== $brand['B'] && array_key_exists($brand['B'], $countries)) {
                    $country =  $countries[$brand['B']];
                } else {
                    $country = new Country($brand['B']);
                    $country->setName($brand['B']);
                    $this->entityManager->persist($country);
                    $countries[$brand['B']] = $country;
                }

                if (null !== $brand['H'] && array_key_exists($brand['H'], $musicTypes)) {
                    $musicType =  $musicTypes[$brand['H']];
                } else {
                    $musicType = new MusicType($brand['H']);
                    $musicType->setType($brand['H']);
                    $this->entityManager->persist($musicType);
                    $musicTypes[$brand['H']] = $musicType;
                }

                $bandToAdd->setGrouName($brand['A']);
                if ($country)
                    $bandToAdd->setCountry($country);
                if ($city)
                    $bandToAdd->setCity($city);
                $bandToAdd->setBeginningYears($brand['D']);
                $bandToAdd->setEndingYears($brand['E']);
                $bandToAdd->setMembers($brand['G']);
                if ($musicType)
                    $bandToAdd->setMusicType($musicType);
                $bandToAdd->setDescription($brand['I']);
                $this->entityManager->persist($bandToAdd);
                $this->entityManager->flush();

                foreach ($brand['F'] as $founders) {
                    if (!is_null($founders)) {
                        $founder = new Founder();
                        $founder->setBand($bandToAdd);
                        $founder->setName($founders);
                        $this->entityManager->persist($founder);
                        $this->entityManager->flush();
                    }
                }
            }
            return new JsonResponse([
                'success' => true,
                'message' => 'File uploaded successfully',
            ]);
        } catch (Error $e) {
            return new JsonResponse([
                'success' => false,
                'message' => 'there was an error',
                'error' => $e
            ]);
        }
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

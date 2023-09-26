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

    #[Route('/api/parse-file', name: 'app_parse_file')]
    public function uploadXlsFile(Request $request, ParseFIleService $parseFIleService): JsonResponse
    {
        $file = $request->files->get('file');

        $parsedFile = $parseFIleService->parseFile($file);
        try {
            foreach ($parsedFile as $brand) {
                $bandToAdd = new Band();
                $city = $this->cityRepository->findByName($brand['C']);
                $country = $this->countryRepository->findByName($brand['B']);
                $musicType = $this->musicTypeRepository->findByName($brand['H']);

                if (is_null($city) && !is_null($brand['C'])) {
                    $city = new City();
                    $city->setName($brand['C']);
                    $this->entityManager->persist($city);
                    $this->entityManager->flush();
                }

                if (is_null($country) && !is_null($brand['B'])) {
                    $country = new Country();
                    $country->setName($brand['B']);
                    $this->entityManager->persist($country);
                    $this->entityManager->flush();
                }

                if (is_null($musicType) && !is_null($brand['H'])) {
                    $musicType = new MusicType();
                    $musicType->settype($brand['H']);
                    $this->entityManager->persist($musicType);
                    $this->entityManager->flush();
                }
                $bandToAdd->setGrouName($brand['A']);
                $bandToAdd->setCountry($country);
                $bandToAdd->setCity($city);
                $bandToAdd->setBeginningYears($brand['D']);
                $bandToAdd->setEndingYears($brand['E']);
                $bandToAdd->setMembers($brand['G']);
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

    #[Route('/api/bands', name: 'app_get_all_band')]
    function getAllBand()
    {
        // try {
        $results = [];
        $bands = $this->entityManager->getRepository(Band::class)->findAll();
        // dd($bands);
        foreach ($bands as $band) {
            $founders = [];
            $musicType = null;

            if(!is_null($band->getMusicType())) 
                $musicType = $band->getMusicType()->getType();
            
            foreach($band->getFounder()->getValues() as $founder) {
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
        // dd($bands);
        return $this->json(
            ['bands' => $results],
            headers: ['Content-Type' => 'application/json;charset=UTF-8']
        );
        // } catch (Error $e) {
        //     return new JsonResponse([
        //         'success' => false,
        //         'message' => 'there was an error',
        //         'error' => $e
        //     ]);
        // }
    }
}

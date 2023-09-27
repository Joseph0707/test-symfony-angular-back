<?php

namespace App\Controller;

use App\Manager\FileManager as ManagerFileManager;
use App\Repository\BandRepository;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\MusicTypeRepository;
use App\Service\ParseFileService;
use Doctrine\ORM\EntityManagerInterface;
use Error;
use FileManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParseFileController extends AbstractController
{

    public function __construct(
        private CityRepository $cityRepository,
        private CountryRepository $countryRepository,
        private MusicTypeRepository $musicTypeRepository,
        private BandRepository $bandRepository,
        private EntityManagerInterface $entityManager,
        private ManagerFileManager $fileManager,
    ) {
    }


    #[Route('/api/parse-file', name: 'app_parse_file', methods: ['POST'])]
    public function uploadXlsFile(Request $request, ParseFileService $parseFileService): JsonResponse
    {
        $file = $request->files->get('file');

        try {
            $parsedFile = $parseFileService->parseFile($file);
            $this->fileManager->addBand($parsedFile);
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
}

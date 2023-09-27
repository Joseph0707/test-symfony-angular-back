<?php

namespace App\Manager;

use App\Entity\Band;
use App\Entity\City;
use App\Entity\Country;
use App\Entity\Founder;
use App\Entity\MusicType;
use App\Repository\CityRepository;
use App\Repository\CountryRepository;
use App\Repository\MusicTypeRepository;
use Doctrine\ORM\EntityManagerInterface;

class FileManager
{
    public function __construct(
        private CityRepository $cityRepository, 
        private CountryRepository $countryRepository, 
        private MusicTypeRepository $musicTypeRepository, 
        private EntityManagerInterface $entityManager)
    {
    }
    private function allCities(): array
    {
        $cities = [];
        $allCities = $this->cityRepository->findAll();
        foreach ($allCities as $city) {
            $cities[$city->getName()] = $city;
        }
        return $cities;
    }

    private function allCountries(): array
    {
        $countries = [];
        $allCountries = $this->countryRepository->findAll();
        foreach ($allCountries as $country) {
            $countries[$country->getName()] = $country;
        }

        return $countries;
    }

    private function allMusicTypes(): array
    {
        $musicTypes = [];
        $allMusicType = $this->musicTypeRepository->findAll();
        foreach ($allMusicType as $musicType) {
            $musicTypes[$musicType->getType()] = $musicType;
        }

        return $musicTypes;
    }

    public function addBand(array $parsedFile): void
    {
        $cities = $this->allCities();
        $countries = $this->allCountries();
        $musicTypes = $this->allMusicTypes();
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
            $bandToAdd->setCountry($country);
            $bandToAdd->setCity($city);
            $bandToAdd->setBeginningYears($brand['D']);
            $bandToAdd->setEndingYears($brand['E']);
            $bandToAdd->setMembers($brand['G']);
            $bandToAdd->setMusicType($musicType);
            $bandToAdd->setDescription($brand['I']);
            $this->entityManager->persist($bandToAdd);

            foreach ($brand['F'] as $founders) {
                if (!is_null($founders)) {
                    $founder = new Founder();
                    $founder->setBand($bandToAdd);
                    $founder->setName($founders);
                    $this->entityManager->persist($founder);
                }
            }
        }
        $this->entityManager->flush();
    }
}

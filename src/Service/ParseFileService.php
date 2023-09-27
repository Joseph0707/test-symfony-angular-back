<?php

namespace App\Service;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ParseFileService
{

    public function parseFile($file)
    {
        $loadFile = IOFactory::load($file->getPathName());

        $fileToArray = $loadFile->getActiveSheet()->toArray(null, true, true, true);
        unset($fileToArray["1"]);
        $result = [];
        foreach ($fileToArray as $value) {
            $founderArray = [];
            if (strpos($value['F'], " et ")) {
                $founders = explode(' et ', $value['F']);
                foreach ($founders as $founder) {
                    if (strpos($value['F'], ",")) {
                        foreach (explode(',', $founder) as $separedByComas) {
                            $founderArray[] = $separedByComas;
                        }
                    } else $founderArray[] = $founder;
                }
            } else if (strpos($value['F'], ",")){
                $founders = explode(',', $value['F']);
                foreach($founders as $founder){
                    $founderArray[] = $founder;
                }
            }
            else $founderArray[] = $value['F'];
            $value['F'] = $founderArray;
            $result[] = $value;
        }
        
        return $result;
    }
}

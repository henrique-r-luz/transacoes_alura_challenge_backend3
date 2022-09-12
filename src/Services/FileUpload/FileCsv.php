<?php

namespace App\Services\FileUpload;

class FileCsv implements FileInterface
{
    private $file;
    private $arraydados = [];
    function __construct($file)
    {
        $this->file = $file;
        $this->analisaDados();
    }


    private function analisaDados()
    {
        $vetCsv = [];
        $arquivo = explode(PHP_EOL, $this->file->getData()->getContent());
        foreach ($arquivo as $line) {
            $linhaObj = str_getcsv($line);
            $vetCsv[] = $linhaObj;
        }
        $this->arraydados = $vetCsv;
    }

    public function getDados()
    {
        return $this->arraydados;
    }
}

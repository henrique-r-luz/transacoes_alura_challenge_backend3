<?php

namespace App\Services\FileUpload;

use App\Helper\ArulaException;

class AnalisaFileFactory
{

    public static function getObjeto($arquivo)
    {
        switch ($arquivo->getNormData()->getClientMimeType()) {
            case 'text/csv':
                return new FileCsv($arquivo);
            case 'application/xml':
                return new FileXml($arquivo);
            case 'text/xml':
                return new FileXml($arquivo);
        }

        throw new ArulaException('O tipo do arquivo não pode ser lido pela sistema');
    }
}

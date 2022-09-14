<?php

namespace App\Services\FileUpload;

class FileXml implements FileInterface
{

    private $file;
    private $arraydados;
    function __construct($file)
    {
        $this->file = $file;
        $this->analisaDados();
    }
    private function analisaDados()
    {
        // Read entire file into string
        $xmlfile = $this->file->getData()->getContent();

        // Convert xml string into an object
        $new = simplexml_load_string($xmlfile);

        // Convert into json
        $con = json_encode($new);

        // Convert into associative array
        $newArr = json_decode($con, true);
        $transacoes = $newArr['transacao'];

        $vetCsv = [];
        foreach ($transacoes as $transacao) {
            $vetCsv[] = [
                $transacao['origem']['banco'],
                $transacao['origem']['agencia'],
                $transacao['origem']['conta'],
                $transacao['destino']['banco'],
                $transacao['destino']['agencia'],
                $transacao['destino']['conta'],
                $transacao['valor'],
                $transacao['data']
            ];
        }
        $this->arraydados = $vetCsv;
    }
    public function getDados()
    {
        return $this->arraydados;
    }
}

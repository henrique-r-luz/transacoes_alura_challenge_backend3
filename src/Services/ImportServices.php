<?php

namespace App\Services;

use App\Entity\Import;
use Exception;
use App\Entity\Transacao;
use App\Entity\ContaBancaria;
use App\Repository\Operacoes\Operacao;
use Doctrine\Persistence\ManagerRegistry;


class ImportServices
{
    private $contaBanco;
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function importa($dado)
    {
        $file = $dado->getContent();
        $arquivo = explode(PHP_EOL, $file);
        $this->vetCsv = [];
        foreach ($arquivo as $line) {
            $linhaObj = str_getcsv($line);
            $this->contaBanco[$linhaObj[0] . $linhaObj[1] . $linhaObj[2]] = [$linhaObj[0], $linhaObj[1], $linhaObj[2]];
            $this->contaBanco[$linhaObj[3] . $linhaObj[4] . $linhaObj[5]] = [$linhaObj[3], $linhaObj[4], $linhaObj[5]];
            $this->vetCsv[] = $linhaObj;
        }
    }

    public function salva()
    {
        if (empty($this->vetCsv)) {
            throw new Exception('A leitura do arquivo não foi realizada! ');
        }
        $this->doctrine->getConnection()->beginTransaction();
        try {
            $importModel = $this->insereImport();
            foreach ($this->contaBanco as $item) {
                $this->insereContaBancaria($item);
            }
            foreach ($this->vetCsv as $item) {
                $this->insereContaBancariaTransacao($item,$importModel);
            }
            $this->doctrine->getConnection()->commit();
        } catch (Exception $e) {
            $this->doctrine->getConnection()->rollBack();
            throw $e;
        }
    }

    private function insereContaBancaria($conta)
    {
        $contaBancaria = new ContaBancaria();
        $contaBancaria->setAgencia($conta[1]);
        $contaBancaria->setConta($conta[2]);
        $contaBancaria->setNome_banco($conta[0]);
        $operacao = new Operacao($this->doctrine);
        $operacao->save($contaBancaria);
    }


    private function insereContaBancariaTransacao($transacao,$importModel)
    {
        $contaBancariaRepository = $this->doctrine->getManager()->getRepository(ContaBancaria::class);
        $contaOrigem = $contaBancariaRepository->findOneBy([
            'nome_banco' => $transacao[0],
            'agencia' => $transacao[1],
            'conta' => $transacao[2]
        ]);
        $contaDestino = $contaBancariaRepository->findOneBy([
            'nome_banco' => $transacao[3],
            'agencia' => $transacao[4],
            'conta' => $transacao[5]
        ]);
        $transacaoModel = new Transacao();
        $transacaoModel->setContaBancariaOrigem($contaOrigem);
        $transacaoModel->setContaBancariaDestino($contaDestino);
        $transacaoModel->setImport($importModel);
        $transacaoModel->setData(new \DateTime($transacao[7]));
        $transacaoModel->setValor($transacao[6]);
        $operacao = new Operacao($this->doctrine);
        $operacao->save($transacaoModel);
    }

    private function insereImport(){
        $importModel = new Import();
        $importModel->setData(new \DateTime());
        $operacao = new Operacao($this->doctrine);
        $operacao->save($importModel);
        return $importModel;
    }

}

<?php

namespace App\Services;

use Exception;
use App\Entity\Import;
use App\Entity\Transacao;
use App\Entity\ContaBancaria;
use App\Helper\ArulaException;
use App\Repository\Operacoes\Operacao;
use App\Validacao\Import\ValidaImport;
use Doctrine\Persistence\ManagerRegistry;
use App\Validacao\Import\ValidaDataTransacao;


class ImportServices
{
    private $contaBanco;
    private $doctrine;
    private  $dataInicio = '';

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
            foreach ($linhaObj as $colunas) {
                //impede que valore em branco ou nulos sejam inserido
                if ($colunas == '') {
                    continue 2;
                }
            }
            $this->contaBanco[$linhaObj[0] . $linhaObj[1] . $linhaObj[2]] = [$linhaObj[0], $linhaObj[1], $linhaObj[2]];
            $this->contaBanco[$linhaObj[3] . $linhaObj[4] . $linhaObj[5]] = [$linhaObj[3], $linhaObj[4], $linhaObj[5]];
            $this->vetCsv[] = $linhaObj;
        }
    }

    public function salva()
    {
        if (empty($this->vetCsv)) {
            throw new ArulaException('A leitura do arquivo não foi realizada! ');
        }
        $this->dataInicio = new \DateTime($this->vetCsv[0][7]);
        $valida =  new ValidaImport($this->dataInicio, $this->doctrine);
        if (!$valida->verificaTransacao()) {
            throw new ArulaException($valida->getMessage());
        }
        $this->doctrine->getConnection()->beginTransaction();
        try {
            $importModel = $this->insereImport();
            foreach ($this->contaBanco as $item) {
                $this->insereContaBancaria($item);
            }
            foreach ($this->vetCsv as $id => $item) {
                $this->insereContaBancariaTransacao($item, $importModel);
            }
            $this->doctrine->getConnection()->commit();
        } catch (ArulaException $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new ArulaException($e->getMessage());
        }
    }

    private function insereContaBancaria($conta)
    {
        $contaBancariaRepository = $this->doctrine->getManager()->getRepository(ContaBancaria::class);
        $contaModel = $contaBancariaRepository->findOneBy([
            'nome_banco' => $conta[0],
            'agencia' => $conta[1],
            'conta' => $conta[2]
        ]);
        if (empty($contaModel)) {
            $contaBancaria = new ContaBancaria();
            $contaBancaria->setAgencia($conta[1]);
            $contaBancaria->setConta($conta[2]);
            $contaBancaria->setNome_banco($conta[0]);
            $operacao = new Operacao($this->doctrine);
            $operacao->save($contaBancaria);
        }
    }


    private function insereContaBancariaTransacao($transacao, $importModel)
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
        $validaDataTransacao = new ValidaDataTransacao($transacaoModel, $this->dataInicio);
        if (!$validaDataTransacao->valida()) {
            return;
        }
        $operacao = new Operacao($this->doctrine);
        $operacao->save($transacaoModel);
    }

    private function insereImport()
    {
        $importModel = new Import();
        $importModel->setData(new \DateTime());
        $operacao = new Operacao($this->doctrine);
        $operacao->save($importModel);
        return $importModel;
    }
}

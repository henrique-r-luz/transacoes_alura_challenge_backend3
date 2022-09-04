<?php

namespace App\Services;

use App\Entity\User;
use App\Helper\ArulaException;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use App\Repository\Operacoes\Operacao;
use Symfony\Component\Mailer\Transport;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Throwable;

class UserServices
{

    private User $user;
    private $form;
    private $senha;

    public function salvar(ManagerRegistry $doctrine)
    {
        try {
            $doctrine->getConnection()->beginTransaction();
            $this->user = $this->form->getData();
            $this->senha = $this->geraNumero();
            $this->sendEmail();
            $this->user->setSenha(Bcrypt::hash($this->senha));
            $operacao = new Operacao($doctrine);
            $operacao->save($this->user);
            $doctrine->getConnection()->commit();
        } catch (TransportExceptionInterface $e) {
            $doctrine->getConnection()->rollBack();
            throw new ArulaException($e->getMessage());
        } catch (Throwable $e) {
            $doctrine->getConnection()->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(ManagerRegistry $doctrine)
    {
        try {
            $doctrine->getConnection()->beginTransaction();
            $this->user = $this->form->getData();
            $operacao = new Operacao($doctrine);
            $operacao->save($this->user);
            $doctrine->getConnection()->commit();
        } catch (Throwable $e) {
            $doctrine->getConnection()->rollBack();
            throw new Exception($e->getMessage());
        }
    }



    private function sendEmail()
    {
        $email = (new Email())
            ->from('transacao@app.com.br')
            ->to($this->user->getEmail())
            ->subject('Suporte Transacão App!')
            ->html('<p>Sua senha para acesso ao sistema é: ' . $this->senha . ' </p>');

        $dsn = 'smtp://mailhog:1025';
        $transport = Transport::fromDsn($dsn);
        $mailer = new Mailer($transport);
        $mailer->send($email);
    }


    private function geraNumero()
    {
        return strval(rand(0, 9)) . strval(rand(0, 9)) . strval(rand(0, 9)) .
            strval(rand(0, 9)) .
            strval(rand(0, 9)) .
            strval(rand(0, 9));
    }



    /**
     * Set the value of form
     *
     * @return  self
     */
    public function setForm($form)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Set the value of user
     *
     * @return  self
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }
}

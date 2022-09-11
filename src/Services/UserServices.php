<?php

namespace App\Services;

use Exception;
use Throwable;
use App\Entity\User;
use App\Entity\Roles;
use App\Helper\Rules;
use App\Helper\ArulaException;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Mailer;
use App\Repository\Operacoes\Operacao;
use Symfony\Component\Mailer\Transport;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServices
{

    private User $user;
    private $form;
    private $senha;
    private $passwordHasher;
    private $doctrine;
    private $security;


    function __construct(
        UserPasswordHasherInterface $passwordHasher,
        ManagerRegistry $doctrine,
        Security $security,
    ) {
        $this->doctrine = $doctrine;
        $this->passwordHasher = $passwordHasher;
        $this->security = $security;
    }

    public function salvar()
    {
        try {
            $this->doctrine->getConnection()->beginTransaction();
            $this->user = $this->form->getData();
            $this->senha = $this->geraNumero();
            $this->sendEmail();
            $hashedPassword = $this->passwordHasher->hashPassword(
                $this->user,
                $this->senha,
            );
            $this->user->setSenha($hashedPassword);
            $this->cadastra();
            $this->doctrine->getConnection()->commit();
        } catch (TransportExceptionInterface $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new ArulaException($e->getMessage());
        } catch (Throwable $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->doctrine->getConnection()->beginTransaction();
            $this->user = $this->form->getData();
            $this->cadastra();
            $this->doctrine->getConnection()->commit();
        } catch (Throwable $e) {
            $this->doctrine->getConnection()->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    private function cadastra()
    {
        $this->user->setRoles([Roles::ROLE_ADM]);
        $operacao = new Operacao($this->doctrine);
        $operacao->save($this->user);
    }


    public function delete($id)
    {
        /** @var User */
        $userAuth = $this->security->getUser();
        /** @var User */
        $user = $this->doctrine->getRepository(User::class)->find($id);
        if ($user->getEmail() === User::emailAdmin) {
            throw new ArulaException('Esse email não pode ser excluido!');
        }
        if ($userAuth->getId() === $user->getId()) {
            throw new ArulaException('O usuario não pode excluir sua conta!');
        }
        $user->setAtivo(false);
        $operacao = new Operacao($this->doctrine);
        $operacao->save($user);
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

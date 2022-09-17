<h1 align="center"!>Alura Challenge Back-End 3</h1>

<p align="center">
<img src="http://img.shields.io/static/v1?label=STATUS&message=EM%20DESENVOLVIMENTO&color=GREEN&style=for-the-badge"/>
</p>

Sistema de análise de transações financeira proposto pelo Challenge Back-End 3 da Arula. O sistema possui as seguintes funcionalidade :

 - Imprtação de transações no formato csv e xml.
 - Detalhamento das transações importadas.
 - Cadastro de usuário.
 - Envio de senha pelo email
 - Controle de acesso.
 - Análise de transações suspeitas.

 ## Pré-requisito
   - Git
   - Docker
   - Docker-compose

## Tecnologias utilizadas

- ``PHP 8``
- ``Symfony 6``
- ``Bootstrap 5``
- ``Twig``
- ``PostgresSql``

## Instalação
 Baixar o projeto no github.
 ~~~
 git clone https://github.com/henrique-r-luz/transacoes_alura_challenge_backend3.git
 ~~~ 
 Após a conclusão do download entre na pasta transacoes_alura_challenge_backend3 e execute o comando abaixo.
 Esse processo pode levar alguns minutos porque o docker irá criar e configurar
 cada container. 
 ~~~
 sudo docker-compose up
 ~~~ 
 Com os contêineres ligados, acesse o app com o seguinte comando:
 ~~~
 docker exec -it <nome do containe app criado no seu sistema> bash
 ~~~
 Execute o compose para instalar as dependências
 ~~~
 composer install
 ~~~
 Execute Execute o Migrate para configurar a base de dados 
 ~~~
 bin/console doctrine:migrations:migrate
 ~~~
 Com os migrates executados os sistema está pronto para uso, acesse:
 ~~~
 http://localhost:81
 ~~~
 Aparecerá a tela de login
 ~~~
 login:admin@email.com.br
 senha:123999
 ~~~
 
 
 
  ## Autor

 [<img src="https://user-images.githubusercontent.com/12544898/174133076-fc3467c3-3908-436f-af3d-2635e4312180.png" width=115><br><sub>Henrique Rodrigues Luz</sub>](https://github.com/henrique-r-luz) 



 

# Primeira parte do trinamento
Como teste foi feito um projeto para crawler dos cursos de php da Alura

Foram utilizados as seguintes ferramentas
Docker e Compose
  Usados para criar o ambiente Docker com o php e o compose para administrar os containers do docker, neste caso foi utilizado apena 1 com o php.
    - docker-compose build (para baixar as dependencias da imagem)
    - docker-compose up -d (construir a imagem e subir os containers)
    No compose foi utilizado a versão 3
    Os services criados foram
      PHP
        Dockerfile-php
          utilizando a imagem do php:fpm-stratch (imagem:versao)
          instalando algumas ferramentas junto
            git,zlib1g,libxml2,libzip,mysqli
          Instalando o composer para orquestrar o projeto
            Fazendo um curl para o instalador do composer, e depois movendo o arquivo composer.phar para o diretorio do composer no container
          Definido o WORKDIR para /var/www dentro do container
    Volumes
      os volumes são o local onde o projeto vai ser copiado e utilizado dentro do container do docker
        Utilizado neste projeto foi ".:/var/www/composerTest"
    Networks
      São as redes de comunicação internas dos containers, por onde eles fazem as comunicações substituindo o ip pelo nome do container
        networks:
          - symfony
          
 
 Composer
  Orquestrador de dependecias do php
  
  com o composer require podemos adicionar dependecias existentes no packgist no projeto, como exemplo adiconamos algumas: phpunit, phpcs
    para adionar dentro do projeto "composer require distribuidor/dependencias" exemplo > "composer require guzzlehttp/guzzle"
    ou no arquivo composer.json podemos adicionar a dependencias dentro do indice de require EX
    "require": {
        "guzzlehttp/guzzle": "^7.3",
        "symfony/dom-crawler": "^4.2",
        "symfony/css-selector": "^5.2"
    }
    ou se a dependencia for somente para o ambiente de desenvolvimento usamos a tag --dev no composer require ou no composer.json
        "require-dev": {
        "phpunit/phpunit": "^9.5",
        "squizlabs/php_codesniffer": "^3.6",
        "phan/phan": "^4.0"
    }

  Vimos o autoload, para facilitar a importação de classes dentro do composer
    temos a opção de importar arquivos de funções importantes que podem ser usadas em qualquer lugar do projeto
    "autoload": {
        "files": [
            "./functions.php"
        ]
    }
    Importar classes que estão fora do padrão PSR4 sobre as definições de nomes de classe e namesapce    
    "autoload": {
        "files": [
            "./functions.php"
        ],
        "classmap": [
            "./Teste.php"
        ]
    }
    Utilizar as classes do projeto como namespace adequada a PSR4
    "autoload": {
        "files": [
            "./functions.php"
        ],
        "classmap": [
            "./Teste.php"
        ],
        "psr-4": {
            "Alura\\BuscadorDeCursos\\" : "src/"
        }
    }
  
  na aula utilizamos algumas dependecias de teste e suas configurações, criamos scripts para usar as dependencias.
    - phpunit
    - phpcs
    - phan
    Criamos scripts para executar as ferramentas direto com o composer
      "scripts": {
        "test": "phpunit tests/TestBuscadorDeCursos.php",
        "cs" : "phpcs --standard=PSR12 src/",
        "phan" : "phan --allow-polyfill-parser",
       }
    e para facilitar criamos um script que executa todas de uma vez
      "scripts": {
        "test": "phpunit tests/TestBuscadorDeCursos.php",
        "cs" : "phpcs --standard=PSR12 src/",
        "phan" : "phan --allow-polyfill-parser",
        "check" : [
            "@phan",
            "@cs",
            "@test"
        ]
       }
    E também é possivel executar scripts com o composer após algum evento, como exemplo apos o composer update rodar o check
      "scripts": {
        "test": "phpunit tests/TestBuscadorDeCursos.php",
        "cs" : "phpcs --standard=PSR12 src/",
        "phan" : "phan --allow-polyfill-parser",
        "check" : [
            "@phan",
            "@cs",
            "@test"
        ],
        "post-update-cmd": [
            "teste"
        ]
      }

# TarefasApp-API (CODIGO NO BRANCH MASTER)
API utilizada no sistema para registro de tarefas no dia a dia.

Essa Api foi desenvolvida em laravel e foi consumida por pelo front desenvolvido em React. Foi o primeiro projeto que fiz uma integração separada de backend e front-end e por conta do PHP ser a linguagem de backend que eu desenvolvo, criei a integração com o laravel, que é excelente para API. Utilizando a própri autenticação do laravel usando o JWT. Acabou sendo poucas coisas criadas, mas ja que o laravel ja tem muitas coisas prontas para o desenvolvimento, foi de extrema ajuda utiliza-lo.

Para utilizar a api, primeiramente use o composer update para criar a pasta vendor. Após isso importe o arquivo sql agenda.sql no seu banco de dados. Ao fazer isso, vá no arquivo .env e mude as configurações para as escolhidas por você.As principais são as citadas abaixo: 

API_URL="url local ou da hospedagem"
APP_ENV=local ou developmente
DB_DATABASE=nome banco de dados
DB_USERNAME=usuario do banco de dados
DB_PASSWORD=senha do banco de dados

Essa api utiliza o storage do laravel para upload de imagens, então caso você use localmente utilize o comando php artisan storage:link para criar o link simbólico na pasta public. Caso esteja usando em hospedagem e não tiver acesso ao promp de comando, use a rota "nomedoseudominio/foo" para criar o link simbólico.

Após essas configurações utilize o php artisan key:generate para criar um nova chave, agora basta usar o php artisan serve para iniciar a aplicação caso esteja local.


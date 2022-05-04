# API utilizada no sistema para registro de tarefas no dia a dia.
Essa Api foi desenvolvida em laravel e foi consumida por pelo front desenvolvido em React. Foi o primeiro projeto que fiz uma integração separada de backend e front-end e por conta do PHP ser a linguagem de backend que eu desenvolvo, criei a integração com o laravel, que é excelente para API. Utilizando a própria autenticação do laravel usando o JWT , acabou sendo poucas coisas criadas mas ja que o laravel tem muitas coisas prontas para o desenvolvimento, foi de extrema ajuda utiliza-lo.

# Instalação
Para utilizar a api, primeiramente use o composer update ou composer install para criar a pasta vendor. Execute também o npm install porque o laravel tem alguns pacotes em node para instalar. Após isso importe o arquivo sql agenda.sql no seu banco de dados (Não fiz o uso de migrations por não conhecer ainda). Ao fazer isso, vá no arquivo .env.exemple e mude as configurações para as escolhidas por você. As principais são as citadas abaixo:

# ENV Configurações
### API_URL="url local ou da hospedagem" 
### APP_ENV=local ou developmente 
### DB_DATABASE=nome banco de dados 
### DB_USERNAME=usuario do banco de dados 
### DB_PASSWORD=senha do banco de dados

### Após preencher o arquivo env.example renomeie o arquivo para .env

# Observações
Essa api utiliza o storage do laravel para upload de imagens, então caso você use localmente utilize o comando php artisan storage:link para criar o link simbólico na pasta public. Caso esteja usando em hospedagem e não tiver acesso ao promp de comando, use a rota "nomedoseudominio/foo" para criar o link simbólico.

Após todas essas configurações executar os comandos laravel para evitar erros:
### php artisan jwt:secret
### php artisan cache:clear
### php artisan config:clear

Utilize o php artisan key:generate para criar um nova chave, agora basta usar o php artisan serve para iniciar a aplicação caso esteja local.

# Front-end dessa aplicação.
Você pode baixar e utilizar o front da aplicação no link https://github.com/Mauricio720/TarefasApp-Front.

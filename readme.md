**Market**

Para criar o Ambiente você deve clonar o repositório:

    git clone https://github.com/FilipeVoges/Market.git

Com o projeto clonado em seu ambiente acesse a pasta do projeto:

    cd Market/

Na pasta do Projeto você deve instalar todas as dependências do Projeto:

    composer install

Com as dependências inclusas, acesse a pasta de configuração do sistema e crie seu arquivo de configuração local:

    cd config/
    cp application.sample.ini application.ini
   
   Abra o arquivo *application.ini* recém criado e preencha as informações solicitadas.
   Você deve ter uma base de dados pré-definida para preencher as configurações
   Após todas as configurações feitas, execute o seguinte comando para criar o schema do Banco de Dados:
   

    composer migrate
   
   com o Banco de Dados criado execute o seguinte comando para iniciar a aplicação:
   

    composer server
   Caso tenha alterado a URL base do sistema você deve atualizar no seu arquivo *composer.json:25* para a URL desejada.


**Bugs Conhecidos:**

 - Ao adicionar um material á uma venda, o POST continua sendo enviado para a página, ocasionando o erro de cadastro, uma solução paliativa é voltar para a tela inicial e acessar a venda novamente.

    

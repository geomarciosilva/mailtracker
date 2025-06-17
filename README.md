**Mail Tracker - Rastreamento de E-mail Markting Simplificado**

**Mail Tracker** é um sistema simples de rastreamento de e-mails que permite monitorar aberturas, cliques e conversões de campanhas de e-mail marketing por meio de pixels invisíveis e redirecionamentos rastreáveis.

## Funcionalidades

- **Rastreamento de Abertura:** Utilização de pixel invisível para registrar a data e hora em que o e-mail foi aberto.
- **Rastreamento de Cliques:** Redirecionamento rastreável que coleta informações detalhadas, como URL acessada, data e hora do clique.
- **Rastreamento de Conversões:** Monitoramento de ações concluídas, vinculando cada conversão à campanha correspondente, com dados sobre a URL, data e hora do clique que originou a conversão.

- Com esses recursos, você terá acesso a dados personalizados e precisos sobre o desempenho de suas campanhas de e-mail marketing, facilitando a identificação de pontos de melhoria e a realização de otimizações estratégicas.

OBSERVAÇÕES:
Todos os registro das interações são salvas em banco de dados.
Acesso ao painel via senha definida manualmente.

## Instalação e Configuração

Siga o passo a passo abaixo para colocar o MailPainel em funcionamento:

**1. Hospedagem:**
Hospede a pasta do projeto `mailtracker` na **raiz do seu site**.  
Exemplo: `https://seudominio.com/mailtracker`

**2. Banco de Dados:**
- Crie um banco de dados MySQL no seu servidor.
- Acesse o **phpMyAdmin**.
- Importe o arquivo `bd.sql` disponível na pasta do projeto para criar as tabelas necessárias.

**3. Configuração do Banco de Dados:**
Abra o arquivo `config.php` e insira as informações de conexão com o banco de dados.

**4. Defina a senha de acesso:**
Defina a senha de acesso no arquivo index.php

**5. Defina a URL do botão voltar:**
Defina o url do botão voltar no arquivo gerador.php

## Como utilizar o rastreamento/trackeamento
Leia o arquivo instruções.txt na pasta do projeto para instruções de uso.

## Licença de uso
Este código foi disponibilizado como código aberto para que qualquer pessoa possa utilizar, modificar e distribuir livremente.

**Condições:**

É permitido usar, copiar, modificar e distribuir o código para qualquer fim, pessoal ou comercial, desde que mencionado o criador do código.

- A única condição é que o autor original seja sempre mencionado em qualquer uso, modificação ou distribuição do código.

- Não há garantias quanto a segurança do sistema, o mesmo foi criado para um fim de validação interna, use por sua conta e risco.

Seja livre para explorar, aprender, melhorar essa ferramenta e contribuir com este projeto.

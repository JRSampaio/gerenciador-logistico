# Gerenciador Logístico

## Sobre o projeto

O Gerenciador Logístico é um sistema web desenvolvido para auxiliar no controle de produtos, endereços, transportadores e fretes.

O sistema permite cadastrar informações, editar registros, gerar fretes relacionando produtos e transportadores e consultar relatórios por data.

## Funcionalidades

- Cadastro e edição de produtos.
- Cadastro e edição de endereços de origem e destino.
- Cadastro e edição de transportadores.
- Geração de fretes com seleção de vários produtos.
- Associação de transportadores, produtos e endereços aos fretes.
- Consulta de fretes por data.
- Exportação de relatórios em formato CSV.
- Painel inicial com quantidade de registros cadastrados.

## Tecnologias utilizadas

- PHP
- HTML
- CSS
- JavaScript
- Bootstrap 5
- XAMPP

## Organização do projeto

```text
gerenciador_logistico/
│
├── index.php
├── README.md
│
├── css/
│   └── style.css
│
├── js/
│   └── script.js
│
└── pages/
    ├── produtos.php
    ├── enderecos.php
    ├── transportadores.php
    ├── fretes.php
    └── relatorios.php
```

## Como executar o sistema

1. Instale o XAMPP.
2. Inicie o Apache.
3. Coloque a pasta `gerenciador_logistico` dentro da pasta `htdocs`.
4. Abra o navegador.
5. Acesse o endereço:

```text
http://localhost/gerenciador_logistico/
```

## Armazenamento dos dados

Nesta versão, os registros são armazenados utilizando sessões PHP. O sistema ainda não utiliza banco de dados permanente.
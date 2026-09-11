# API REST - Gestão de Produtos e Estudos JSON

Atividade prática desenvolvida no SENAI com o objetivo de compreender a arquitetura de APIs RESTful, a manipulação de dados em formato JSON e a integração com o banco de dados PostgreSQL utilizando PHP.

---

## Objetivo da Atividade

1. **Introdução Teórica e Prática com Python (`api_cep.py`):** Compreender o funcionamento de requisições HTTP e consumo de dados estruturados em formato JSON através de um script de apoio.
2. **Desenvolvimento da API em PHP (`produtos.php`):** Construir um endpoint funcional capaz de processar requisições HTTP (`GET` e `POST`), manipular objetos JSON e persistir as informações com segurança em banco de dados relacional.

---

## Tecnologias Utilizadas

* **PHP 8.x** — Construção dos endpoints e lógica de negócios.
* **PostgreSQL** — Banco de dados relacional para persistência de dados.
* **PDO (PHP Data Objects)** — Abstração da camada de dados e proteção contra ataques de SQL Injection.
* **Python 3.x** — Script auxiliar de estudo para consumo de APIs.

---

## Estrutura de Arquivos

| Arquivo | Descrição |
| --- | --- |
| `conexao.php` | Configura e estabelece a conexão PDO com o banco de dados PostgreSQL (`lojasenai`). |
| `produtos.php` | Endpoint principal responsável pelas operações de cadastro (`POST`) e consulta (`GET`). |
| `api_cep.py` | Script Python utilizado em aula para demonstrar a lógica de consumo de APIs e arquivos JSON. |

---

## Funcionamento da API (`produtos.php`)

### 1. Inserção de Produtos (`POST`)

O script foi desenvolvido para suportar **Bulk Insert** (inserção em lote), permitindo o envio de um único item ou de múltiplos registros em uma única requisição.

* **Normalização de Dados:** Caso a requisição receba apenas um objeto (`{ "nome": "...", "preco": ... }`), o código o converte internamente em uma lista de objetos para manter um fluxo de processamento único.
* **Montagem Dinâmica de Query:** A instrução SQL `INSERT INTO produtos (nome, preco) VALUES (?, ?)` é gerada dinamicamente conforme a quantidade de elementos presentes no JSON.
* **Segurança na Execução:** O mapeamento dos parâmetros é feito através de *Prepared Statements* via PDO, passando os valores em um array plano para garantir a integridade da operação.

**Exemplo de Payload (`POST`):**

```json
[
  { "nome": "Mouse Gamer", "preco": 120.50 },
  { "nome": "Teclado Mecânico", "preco": 250.00 }
]

```

### 2. Consulta de Produtos (`GET`)

Executa a busca de todos os registros contidos na tabela `produtos`, ordenados pelo `id`, e retorna uma estrutura JSON com o cabeçalho HTTP configurado como `Content-Type: application/json`.

---

## Instruções de Configuração e Execução

1. **Criação da tabela no PostgreSQL:**
```sql
CREATE DATABASE lojasenai;

CREATE TABLE produtos (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    preco NUMERIC(10, 2) NOT NULL
);

```


2. **Ajuste das credenciais em `conexao.php`:**
```php
$host = "localhost";
$usuario = "postgres";
$banco = "lojasenai";
$senha = "sua_senha";

```


3. **Execução do servidor embutido do PHP:**
```bash
php -S localhost:8000

```



---

## Segurança e Boas Práticas

* **Proteção de Credenciais:** Arquivos contendo dados de acesso local e credenciais (`conexao.php`), além de scripts de estudo (`api_cep.py`), foram definidos no arquivo `.gitignore` para evitar o versionamento de dados sensíveis em repositórios públicos.
* **Prevenção contra SQL Injection:** Todas as interações de escrita no banco de dados utilizam consultas preparadas (`$pdo->prepare()`), garantindo o tratamento adequado das entradas do usuário.
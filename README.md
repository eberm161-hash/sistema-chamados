# ⚡ Sistema de Gestão de Chamados de Suporte (ITSM)

Aplicação Web completa para gerenciamento e ciclo de vida de chamados de TI, desenvolvida em PHP nativo e banco de dados relacional MySQL.

## 🚀 Tecnologias Utilizadas

- **Backend:** PHP 8.x (PDO com Prepared Statements)
- **Banco de Dados:** MySQL / MariaDB
- **Frontend:** HTML5, CSS3, Bootstrap 5
- **Servidor Local:** Apache (XAMPP)

## 🔒 Recursos de Segurança

- Proteção contra **SQL Injection** via consultas preparadas no PDO.
- Tratamento contra **XSS (Cross-Site Scripting)** utilizando sanitização de saída (`htmlspecialchars`).
- Validação estrita dos dados de formulário (*Whitelisting*).

## 🛠️ Como Executar o Projeto

1. Clone este repositório para a sua pasta `htdocs` do XAMPP:
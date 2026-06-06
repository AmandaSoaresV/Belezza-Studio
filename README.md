![Status](https://img.shields.io/badge/Status-Conclu%C3%ADdo-2EA44F?style=for-the-badge)
![Licença](https://img.shields.io/badge/Licença-MIT-0A66C2?style=for-the-badge)

![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)
![html2pdf.js](https://img.shields.io/badge/html2pdf.js-4B32C3?style=for-the-badge&logo=javascript&logoColor=white)

# 💅✨💇‍♀️ Belezza Studio 💇‍♀️✨💅

Projeto acadêmico desenvolvido no **segundo módulo** do curso de Engenharia de Software.

---

## 📖 Sobre o Projeto

O **Belezza Studio** é um sistema web para gerenciamento de um salão de beleza, as páginas consomem e exibem os dados do banco de forma dinâmica, com foco em apresentação das informações. O gerenciamento dos registros é feito diretamente no banco de dados.

---

## 🖥️ Demonstração
[Acessar o Figma](https://www.figma.com/design/JVJyAJ1bdYh6oRQKnPj9nM/BelezzaStudio?node-id=0-1&t=2cHLFckRdHt7PBO8-1)
![Imagem da prototipação das telas](public/assets/img/figma.png)

---

## 🚀 Funcionalidades

- 👤 Cadastro de usuários com perfis **admin** e **cliente** via banco,
- 💇 Cadastro de serviços vinculados a profissionais via banco,
- 📋 Gerenciamento de serviços com nome, descrição, preço e duração
- 📅 Agendamentos com controle de status (`pendente`, `confirmado`, `cancelado`, `concluído`)
- 📊 Relatório com gráficos dinâmicos via **Chart.js** e exportação em PDF via **html2pdf.js**
- 🔎 Busca e filtro de dados com validação de regras de negócio
- 🎨 Layout responsivo com **Bootstrap 5**
- 🧩 Código modularizado com funções PHP reutilizáveis e templates
- 🔒 Servidor configurado sem listagem de diretórios
- 🌐 Aplicação acessível via DNS local na porta **8080**
- 🗄️ Banco de dados: pode ser local (ex.: XAMPP) ou em máquina separada (IP fixo). Ajuste conforme seu ambiente.

---

## 🛠 Tecnologias Utilizadas

| Tecnologia                                                                                                          | Descrição                                   |
| ------------------------------------------------------------------------------------------------------------------- | ------------------------------------------- |
| ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=for-the-badge&logo=html5&logoColor=white)                  | Estrutura das páginas.                      |
| ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=for-the-badge&logo=css3&logoColor=white)                     | Alguns estilos e responsividade.            |
| ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)   | Interações dinâmicas na interface.          |
| ![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)                        | Templates e lógica de negócio.              |
| ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white)                  | Banco de dados relacional.                  |
| ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)      | Framework CSS para layout e componentes.    |
| ![Chart.js](https://img.shields.io/badge/Chart.js-FF6384?style=for-the-badge&logo=chartdotjs&logoColor=white)       | Geração de gráficos dinâmicos no dashboard. |
| ![html2pdf.js](https://img.shields.io/badge/html2pdf.js-4B32C3?style=for-the-badge&logo=javascript&logoColor=white) | Exportação de relatórios em PDF.            |

---

## 🗄️ Banco de Dados

### 📐 Diagrama de Entidade-Relacionamento (DER)

> ![Imagem dO DER ](public/assets/img/DER.png)

### 📋 Tabelas

| Tabela                 | Descrição                                                    |
| ---------------------- | ------------------------------------------------------------ |
| `usuarios`             | Clientes e administradores do sistema                        |
| `profissionais`        | Profissionais do salão e suas especialidades                 |
| `servicos`             | Serviços oferecidos com preço e duração                      |
| `agendamentos`         | Registro de agendamentos por cliente, profissional e serviço |
| `profissional_servico` | Relacionamento N:N entre profissionais e serviços            |

### 🔗 Relacionamentos

- `agendamentos.id_cliente` → `usuarios.id_usuario`
- `agendamentos.id_profissional` → `profissionais.id_profissional`
- `agendamentos.id_servico` → `servicos.id_servico`
- `profissional_servico.id_profissional` → `profissionais.id_profissional` _(N:N)_
- `profissional_servico.id_servico` → `servicos.id_servico` _(N:N)_

---

## Como Executar

1. Clone o repositório:

```bash
git clone https://github.com/AmandaSoaresV/belezza-studio.git
```

2. Opções de execução (escolha a que se aplica ao seu ambiente):

- Usando XAMPP (mais simples para desenvolvimento local):
  1.  Copie a pasta do projeto para `C:\xampp\htdocs\belezzaStudio`.
  2.  Abra o XAMPP Control Panel e inicie o **Apache** e o **MySQL**.
  3.  Importe o banco de dados:

```bash
mysql -u <usuario> -p <nome_do_banco> < config/schema.sql
```

4.  Ajuste as credenciais em `config/conexao.php` para apontar para seu banco local.
5.  Acesse em: `http://localhost/belezzaStudio` (ou configure VirtualHost, veja abaixo).

- Usando VirtualHost / DNS local (ex.: `belezza.local`) e porta customizada:
  1.  Configure seu VirtualHost no Apache apontando para a pasta `public/` do projeto.
  2.  Adicione a entrada no `hosts` (ex.: `127.0.0.1 belezza.local`).
  3.  Se usar porta diferente (ex.: `8080`), inclua na URL: `http://belezza.local:8080`.
  4.  Importe o banco e ajuste `config/conexao.php` conforme acima.

Notas:

- A porta `8080` mencionada é um exemplo — adapte conforme seu servidor.
- O projeto aceita banco local (XAMPP) ou remoto; ajuste as configurações em `config/conexao.php`.

3. Segurança recomendada (ambiente de produção):

- Desabilite listagem de diretórios no Apache (`Options -Indexes`) ou configure via `.htaccess`/VirtualHost.

4. Acesse a aplicação (exemplos):

```
http://localhost/belezzaStudio
http://belezza.local:8080
```

---

## 📂 Estrutura do Projeto

```bash
.
├── LICENSE
├── README.md
├── app/
│   └── views/
│       ├── agendamento/
│       │   └── agendamento.php
│       ├── dashboard/
│       │   └── dashboard.php
│       ├── erro/
│       │   └── erro.php
│       ├── includes/
│       │   ├── footer.php
│       │   ├── header.php
│       ├── index/
│       │   └── index.php
│       ├── relatorio/
│       │   └── relatorio.php
│       └── seushorarios/
│           └── seushorarios.php
├── config/
│   ├── conexao.php
│   └── schema.sql
└── public/
   ├── index.php
  ├── .htaccess
   └── assets/
      ├── css/
      │   ├── dashboard.css
      │   ├── erro.css
      │   ├── global.css
      │   ├── index.css
      │   ├── relatorio.css
      │   ├── seushorarios.css
      │   ├── sidebar.css
      │   └── style.css
      ├── img/
      └── js/
         └── relatorio.js
```

---

## 👩‍💻 Desenvolvedora

| [Amanda Soares Vieira](https://github.com/amandasoaresv) |
| -------------------------------------------------------- |

---

## Licença

Este projeto está sob a **licença MIT.** Consulte o arquivo `LICENSE` para mais detalhes.

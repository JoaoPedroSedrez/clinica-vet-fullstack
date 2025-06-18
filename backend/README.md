# 🐾 Clínica Veterinária - Projeto Full Stack (PHP + HTML + CSS + JS)

Este é o repositório da **versão full stack** da aplicação de Clínica Veterinária, evoluindo a partir do projeto em PHP orientado a objetos via terminal. Agora com uma **interface web moderna**, o projeto permite gerenciar atendimentos de pets de forma interativa e acessível pelo navegador.

---

## 🧱 Tecnologias Utilizadas

- **PHP (POO + Endpoints RESTful)** – Backend estruturado com rotas REST
- **JSON** – Simulação simples de banco de dados para armazenar atendimentos
- **HTML5** – Estrutura da interface
- **CSS3** – Estilização responsiva e visual
- **JavaScript (fetch API)** – Comunicação assíncrona com o backend via AJAX

---

## 📁 Estrutura do Projeto

clinica-vet-fullstack/

│

├── backend/

│ ├── index.php # Arquivo principal da API RESTful

│ ├── Clinica.php # Lógica da clínica e manipulação de dados │ ├── Veterinario.php # Classe Veterinário

│ ├── Cachorro.php # Classe Cachorro (Pet)

│ ├── Gato.php # Classe Gato (Pet)

│ ├── Pet.php # Classe abstrata Pet

│ ├── Atendivel.php # Interface atendível

│ └── historico.json # Banco de dados JSON dos atendimentos

│

├── public/

│ ├── index.html # Interface web principal

│ ├── style.css # Estilos CSS

│ └── script.js # Comunicação com a API via fetch()


---

## ⚙️ Funcionalidades

### ✅ Cadastrar Atendimento
- Preencha o formulário com veterinário, tipo de animal, nome e idade do pet
- Envia uma requisição `POST` para a API para registrar o atendimento

### 📋 Listar Atendimentos
- Faz uma requisição `GET` para listar todos os atendimentos cadastrados
- Apresenta a lista atualizada na interface

### 📝 Editar Atendimento
- Permite editar a **mensagem** de um atendimento diretamente na lista (edição inline)
- Envia uma requisição `PUT` para atualizar a mensagem do atendimento via API

### 🗑 Excluir Atendimento
- Exclui atendimento específico pelo ID
- Confirmação antes da exclusão
- Requisição `DELETE` para remover o atendimento via API

### ❌ Limpar Histórico
- Remove **todos os atendimentos** armazenados no `historico.json`
- Requisição especial `POST` com parâmetro `?acao=limpar`

---

## 📡 Endpoints da API

| Método | Endpoint                      | Descrição                                      |
|--------|------------------------------|------------------------------------------------|
| GET    | `/atendimentos`              | Lista todos os atendimentos                    |
| GET    | `/atendimentos?id=3`         | Busca atendimento específico pelo ID          |
| POST   | `/atendimentos`              | Registra um novo atendimento                   |
| PUT    | `/atendimentos?id=3`         | Edita a mensagem de um atendimento existente   |
| DELETE | `/atendimentos?id=3`         | Exclui atendimento específico                  |
| POST   | `/atendimentos?acao=limpar`  | Limpa todo o histórico de atendimentos         |

---

## 💻 Como usar

1. Clone o repositório na pasta `htdocs` do XAMPP (ou equivalente)
2. Inicie o servidor Apache no painel do XAMPP
3. Acesse `http://localhost/clinica-vet-fullstack/public/` no navegador
4. Utilize a interface para cadastrar, editar, excluir e limpar atendimentos

---

## 🔮 Futuras melhorias

- Validação dos dados no front-end para melhorar UX
- Sistema de autenticação para veterinários
- Migração para banco de dados MySQL ou outro SGBD real
- Upload e exibição de fotos dos pets
- Filtros para busca e ordenação do histórico de atendimentos

---

## 🧠 Conceitos aplicados

- Programação Orientada a Objetos (PHP)
- Desenvolvimento de API RESTful com PHP
- Manipulação de arquivos JSON para persistência de dados
- Comunicação assíncrona com JavaScript via `fetch()`
- Separação clara entre front-end (interface) e back-end (API)

---

## 🧑‍💻 Autor

Desenvolvido por **João Pedro Sedrez** – projeto educacional focado em aprendizado full stack com PHP e tecnologias web.

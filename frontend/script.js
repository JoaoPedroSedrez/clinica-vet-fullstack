// URL base da API
const API_URL = "http://localhost/clinica-vet-fullstack/backend/index.php/atendimentos";

// Função que busca e exibe todos os atendimentos cadastrados
function listarAtendimentos() {
    fetch(API_URL) // Faz uma requisição GET para a API
        .then(res => res.json()) // Converte resposta JSON em objeto JS
        .then(data => {
            const ul = document.getElementById("lista-atendimentos");
            ul.innerHTML = "";

            data.forEach(item => {
                const li = document.createElement("li");

                li.innerHTML = `
                    <div class="info-view">
                        <div><strong>Veterinário:</strong> <span>${item.veterinario}</span></div>
                        <div><strong>Animal:</strong> <span>${item.animal}</span></div>
                        <div><strong>Pet:</strong> <span>${item.pet}</span></div>
                        <div><strong>Mensagem:</strong> <span>${item.mensagem}</span></div>
                       
                        <div class="acoes">
                            <button onclick="editarInline(this, ${item.id}, '${item.veterinario}', '${item.pet}', '${item.mensagem}', '${item.animal}')">✏️</button>
                            <button onclick="confirmarExclusao(${item.id})">🗑️</button>
                        </div>
                    </div>
                `;

                ul.appendChild(li);
            });
        });
}

// Evento para cadastrar novo atendimento
document.getElementById("form-cadastrar").addEventListener("submit", function (e) {
    e.preventDefault(); // Impede recarregar a página
    const formData = new FormData(e.target); // Lê dados do formulário
    const dados = Object.fromEntries(formData.entries()); // Converte para objeto JS

    fetch(API_URL, {
        method: "POST", // Requisição do tipo POST
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados) // Envia os dados em formato JSON
    })
        .then(res => res.json())
        .then(alertarResultado) // Mostra alerta com resultado
        .then(listarAtendimentos); // Atualiza a lista
});

// Evento para editar mensagem do atendimento (via modal ou inline)
document.getElementById("form-editar").addEventListener("submit", function (e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get("id");
    const mensagem = formData.get("mensagem");

    fetch(`${API_URL}?id=${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ mensagem }) // Envia só a nova mensagem
    })
        .then(res => res.json())
        .then(alertarResultado)
        .then(listarAtendimentos);
});
function editarInline(button, id, veterinario, pet, mensagem, tipo) {
    // Encontra o <li> pai do botão clicado
    const li = button.closest("li");

    // Tipos possíveis de animal
    const tipos = ["Cachorro", "Gato"];

    // Gera as opções do select, marcando a opção correta como selecionada
    const opcoes = tipos.map(t =>
        `<option value="${t}" ${t === tipo ? "selected" : ""}>${t}</option>`
    ).join("");

    console.log("Tipo recebido:", tipo);
    // Substitui o conteúdo do <li> por um formulário de edição
    li.innerHTML = `
        <form onsubmit="salvarEdicao(event, ${id})" class="form-edicao">
            <input type="text" name="veterinario" value="${veterinario}" disabled />
            <select name="tipo" disabled>${opcoes}</select>
            <input type="text" name="pet" value="${pet}" disabled />
            <label for="mensagem">Mensagem</label>
            <textarea name="mensagem" required>${mensagem}</textarea>
            <div class="acoes">
                <button type="submit">✅ Salvar</button>
                <button type="button" onclick="listarAtendimentos()">❌ Cancelar</button>
            </div>
        </form>
    `;
}


// Salva edição feita inline
function salvarEdicao(e, id) {
    e.preventDefault();
    const form = e.target;

    const dados = {
        veterinario: form.veterinario.value,
        pet: form.pet.value,
        mensagem: form.mensagem.value
    };

    // Envia a nova mensagem para a API via PUT
    fetch(`${API_URL}?id=${id}`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dados)
    })
        .then(res => res.json())
        .then(alertarResultado)
        .then(listarAtendimentos);
}

// Exclui atendimento pelo ID digitado em um input
function excluirAtendimento() {
    const id = document.getElementById("excluir-id").value;
    if (!id) return alert("Informe o ID");

    fetch(`${API_URL}?id=${id}`, {
        method: "DELETE"
    })
        .then(res => res.json())
        .then(alertarResultado)
        .then(listarAtendimentos);
}

// Confirma antes de excluir um item clicando no botão da lista
function confirmarExclusao(id) {
    if (confirm(`Tem certeza que deseja excluir o atendimento ID ${id}?`)) {
        fetch(`${API_URL}?id=${id}`, { method: "DELETE" })
            .then(res => res.json())
            .then(alertarResultado)
            .then(listarAtendimentos);
    }
}

// Mostra alertas com as mensagens vindas do backend
function alertarResultado(res) {
    if (res.mensagem) alert(res.mensagem);
    else if (res.erro) alert("Erro: " + res.erro);
}

// Função que limpa o histórico inteiro chamando endpoint POST com ação "limpar"
function limparHistorico() {
    const confirmar = confirm("Tem certeza que deseja apagar TODOS os atendimentos? Essa ação não pode ser desfeita.");
    if (!confirmar) return;

    fetch(`${API_URL}?acao=limpar`, {
        method: "POST"
    })
        .then(res => res.json())
        .then(alertarResultado)
        .then(listarAtendimentos)
        .catch(err => alert("Erro ao limpar histórico: " + err));
}

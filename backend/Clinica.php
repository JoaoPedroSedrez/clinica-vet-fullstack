<?php

require_once 'Veterinario.php';
require_once 'Pet.php';

# Fuso Horário
date_default_timezone_set('America/Sao_Paulo');

class Clinica
{
    private string $arquivo = "historico.json";
    public function registrarAtendimento(Veterinario $vet, Pet $pet): string {

        # Historico recebe array da função listarHistorico
        $historico = $this->listarHistorico();

        $ultimoId = 0;
        # se o array $historico não estiver vazio, cria array com todos os ids e cria variável $ultimoId com o maior valor do array, usando max()
        if (!empty($historico)) {
            $ids = array_column($historico, 'id');
            $ultimoId = !empty($ids) ? max($ids) : 0;
        }

        #Registra novo atendimento
        $novoRegistro = [
            'id' => $ultimoId + 1,
            'veterinario' => $vet->getNome(),
            'animal' => $pet->getEspecie(),
            'pet' => $pet->getNome(),
            'mensagem' => $pet->atender(),
            'data' => date('d/m/Y H:i')
        ];

        # Adiciona atendimento no histórico
        $historico[] =  $novoRegistro;

        # file_put_contents salva o texto json gerado no arquivo selecionado
        # json_encode transforma string em formato json identado
        file_put_contents($this->arquivo, json_encode($historico,  JSON_PRETTY_PRINT));

        return "Atendimeneto do {$vet->getNome()} com o {$pet->getEspecie()} {$pet->getNome()} foi cadastrado com sucesso!\n";
    }

    public function listarHistorico(): array {
        if (!file_exists($this->arquivo)) return [];

        # file_get_contents lê o arquivo json
        # json_decode converte arquivo json em array
        return json_decode(file_get_contents($this->arquivo), true);
    }



    public function limparHistorico() {

        # json_encode([], JSON_PRETTY_PRINT) cria um JSON vazio
        # file_put_contents(...) escreve isso dentro do arquivo $this->arquivo, sobrescrevendo tudo que havia antes
        file_put_contents($this->arquivo, json_encode([],  JSON_PRETTY_PRINT));
        return "Histórico limpo com sucesso!\n";
    }

    public function excluirAtendimento(int $id): string
    {
        # Historico recebe array da função listarHistorico
        $historico = $this->listarHistorico();
        $encontrado = false;
        # Vai armazenar registros que vão ficar depois da exclusão
        $novoHistorico = [];

        # Percorre cada atendimento em historico
        # Se o Id bater com o Id que queremos excluir, apenas pula ele e não adiciona o atendimento em $novoHistorico
        foreach ($historico as $registro) {
            if ($registro['id'] == $id) {
                $encontrado = true;
                continue;
            }
            $novoHistorico[] = $registro;
        }

        # Se encontrar o Id, salva o novoHistorico no arquivo .json
        if($encontrado) {
            file_put_contents($this->arquivo, json_encode($novoHistorico,  JSON_PRETTY_PRINT));
            return "Atendimento com ID $id excluído com sucesso." . PHP_EOL;
        } else {
            return "Atendimento com ID $id não encontrado." . PHP_EOL;
        }
    }

    public function editarMensagemId(int $id, string $novaMensagem): string {

        # $historico recebe array de listarHistorico
        $historico = $this->listarHistorico();
        $atualizado = false;

        # Percorre o array e se encontrar o Id, edita o array $registro['mensagem'] pela mensagem escolhida ($novaMensagem)
        foreach ($historico as &$registro) {
            if ($registro['id'] === $id) {
                $registro['mensagem'] = $novaMensagem;
                $atualizado = true;
                break;
            }
        }

        # Se conseguiu encontrar o Id e atualizou a mensagem, salva o novo historico com mensagem atualizada no arquivo .json
        if ($atualizado) {
            file_put_contents($this->arquivo, json_encode($historico,  JSON_PRETTY_PRINT));
            return "Mensagem do atendimento ID $id atualizada com sucesso!" . PHP_EOL;
        } else {
            return "Mensagem do atendimento ID $id atualizada com sucesso!" . PHP_EOL;
        }
    }
    public function buscarAtendimentoPorId($id) {
        # $historico recebe array de listarHistorico
        $historico = $this->listarHistorico();

        # Percorre o array de atendimentos e procura o Id, depoois retorna apenas o atendimento com Id escolhido
        foreach ($historico as $atendimento) {
            if ($atendimento['id'] == $id) {
                return $atendimento;
            }
        }
        return null;
    }

}
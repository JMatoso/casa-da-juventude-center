<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Models\AlunoModel;

final class AlunoController extends BaseController
{
    public function list(): void
    {
        $page = $this->pageFromQuery();
        $q = $this->searchQuery();
        $result = AlunoModel::paginate($page, $this->perPage(), $q);
        $meta = $this->paginationMeta($result['total'], $page);

        view('alunos/index', [
            'items' => $result['items'],
            'q' => $q,
            'meta' => $meta,
            'total' => $result['total'],
            'pageTitle' => 'Alunos',
            'activeNav' => 'alunos',
        ]);
    }

    public function create(): void
    {
        clear_old();
        view('alunos/create', ['pageTitle' => 'Novo aluno', 'activeNav' => 'alunos', 'item' => null]);
    }

    public function store(): void
    {
        $this->requirePost();
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('alunos/create');
        }
        AlunoModel::create($data);
        clear_old();
        Flash::success('Aluno criado com sucesso.');
        redirect('alunos/list');
    }

    public function edit(): void
    {
        $id = $this->idFromQuery();
        if (!$id) {
            Flash::error('Registo não encontrado.');
            redirect('alunos/list');
        }
        $item = AlunoModel::find($id);
        if (!$item) {
            Flash::error('Aluno não encontrado.');
            redirect('alunos/list');
        }
        clear_old();
        view('alunos/edit', ['item' => $item, 'pageTitle' => 'Editar aluno', 'activeNav' => 'alunos']);
    }

    public function update(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('alunos/list');
        }
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('alunos/edit', ['id' => $id]);
        }
        AlunoModel::update($id, $data);
        clear_old();
        Flash::success('Aluno atualizado.');
        redirect('alunos/list');
    }

    public function delete(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('alunos/list');
        }
        if (AlunoModel::hasInscricoes($id)) {
            Flash::error('Não é possível apagar: existem inscrições associadas.');
            redirect('alunos/list');
        }
        AlunoModel::delete($id);
        Flash::success('Aluno removido.');
        redirect('alunos/list');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        return [
            'nome_completo' => trim((string) ($_POST['nome_completo'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'telefone' => trim((string) ($_POST['telefone'] ?? '')),
            'data_nascimento' => trim((string) ($_POST['data_nascimento'] ?? '')),
            'bi' => trim((string) ($_POST['bi'] ?? '')),
            'morada' => trim((string) ($_POST['morada'] ?? '')),
        ];
    }

    /** @param array<string, string> $data */
    private function validate(array $data): bool
    {
        $validator = new Validator();
        $rules = [
            'nome_completo' => 'required|max:180',
            'email' => 'email|max:180',
            'telefone' => 'max:40',
            'bi' => 'max:50',
            'morada' => 'max:255',
        ];
        if ($data['data_nascimento'] !== '') {
            $rules['data_nascimento'] = 'date';
        }
        if (!$validator->validate($data, $rules)) {
            Flash::error($validator->firstError());
            return false;
        }
        return true;
    }
}

<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Models\FuncionarioModel;

final class FuncionarioController extends BaseController
{
    public function list(): void
    {
        $page = $this->pageFromQuery();
        $q = $this->searchQuery();
        $result = FuncionarioModel::paginate($page, $this->perPage(), $q);
        $meta = $this->paginationMeta($result['total'], $page);

        view('funcionarios/index', [
            'items' => $result['items'],
            'q' => $q,
            'meta' => $meta,
            'total' => $result['total'],
            'pageTitle' => 'Funcionários',
            'activeNav' => 'funcionarios',
        ]);
    }

    public function create(): void
    {
        clear_old();
        view('funcionarios/create', ['pageTitle' => 'Novo funcionário', 'activeNav' => 'funcionarios', 'item' => null]);
    }

    public function store(): void
    {
        $this->requirePost();
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('funcionarios/create');
        }
        FuncionarioModel::create($data);
        clear_old();
        Flash::success('Funcionário criado com sucesso.');
        redirect('funcionarios/list');
    }

    public function edit(): void
    {
        $id = $this->idFromQuery();
        if (!$id) {
            Flash::error('Registo não encontrado.');
            redirect('funcionarios/list');
        }
        $item = FuncionarioModel::find($id);
        if (!$item) {
            Flash::error('Funcionário não encontrado.');
            redirect('funcionarios/list');
        }
        clear_old();
        view('funcionarios/edit', ['item' => $item, 'pageTitle' => 'Editar funcionário', 'activeNav' => 'funcionarios']);
    }

    public function update(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('funcionarios/list');
        }
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('funcionarios/edit', ['id' => $id]);
        }
        FuncionarioModel::update($id, $data);
        clear_old();
        Flash::success('Funcionário atualizado.');
        redirect('funcionarios/list');
    }

    public function delete(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('funcionarios/list');
        }
        FuncionarioModel::delete($id);
        Flash::success('Funcionário removido.');
        redirect('funcionarios/list');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        return [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'telefone' => trim((string) ($_POST['telefone'] ?? '')),
            'cargo' => trim((string) ($_POST['cargo'] ?? '')),
        ];
    }

    /** @param array<string, string> $data */
    private function validate(array $data): bool
    {
        $validator = new Validator();
        if (!$validator->validate($data, [
            'nome' => 'required|max:180',
            'cargo' => 'required|max:120',
            'email' => 'email|max:180',
            'telefone' => 'max:40',
        ])) {
            Flash::error($validator->firstError());
            return false;
        }
        return true;
    }
}

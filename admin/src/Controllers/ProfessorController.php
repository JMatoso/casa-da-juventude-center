<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Models\ProfessorModel;

final class ProfessorController extends BaseController
{
    public function list(): void
    {
        $page = $this->pageFromQuery();
        $q = $this->searchQuery();
        $result = ProfessorModel::paginate($page, $this->perPage(), $q);
        $meta = $this->paginationMeta($result['total'], $page);

        view('professores/index', [
            'items' => $result['items'],
            'q' => $q,
            'meta' => $meta,
            'total' => $result['total'],
            'pageTitle' => 'Professores',
            'activeNav' => 'professores',
        ]);
    }

    public function create(): void
    {
        clear_old();
        view('professores/create', ['pageTitle' => 'Novo professor', 'activeNav' => 'professores', 'item' => null]);
    }

    public function store(): void
    {
        $this->requirePost();
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('professores/create');
        }
        ProfessorModel::create($data);
        clear_old();
        Flash::success('Professor criado com sucesso.');
        redirect('professores/list');
    }

    public function edit(): void
    {
        $id = $this->idFromQuery();
        if (!$id) {
            Flash::error('Registo não encontrado.');
            redirect('professores/list');
        }
        $item = ProfessorModel::find($id);
        if (!$item) {
            Flash::error('Professor não encontrado.');
            redirect('professores/list');
        }
        clear_old();
        view('professores/edit', ['item' => $item, 'pageTitle' => 'Editar professor', 'activeNav' => 'professores']);
    }

    public function update(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('professores/list');
        }
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('professores/edit', ['id' => $id]);
        }
        ProfessorModel::update($id, $data);
        clear_old();
        Flash::success('Professor atualizado.');
        redirect('professores/list');
    }

    public function delete(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('professores/list');
        }
        ProfessorModel::delete($id);
        Flash::success('Professor removido.');
        redirect('professores/list');
    }

    /** @return array<string, string> */
    private function input(): array
    {
        return [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'email' => trim((string) ($_POST['email'] ?? '')),
            'telefone' => trim((string) ($_POST['telefone'] ?? '')),
            'especialidade' => trim((string) ($_POST['especialidade'] ?? '')),
        ];
    }

    /** @param array<string, string> $data */
    private function validate(array $data): bool
    {
        $validator = new Validator();
        if (!$validator->validate($data, [
            'nome' => 'required|max:180',
            'email' => 'email|max:180',
            'telefone' => 'max:40',
            'especialidade' => 'max:180',
        ])) {
            Flash::error($validator->firstError());
            return false;
        }
        return true;
    }
}

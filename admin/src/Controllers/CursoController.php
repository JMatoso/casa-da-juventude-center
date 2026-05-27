<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Models\CursoModel;

final class CursoController extends BaseController
{
    public function list(): void
    {
        $page = $this->pageFromQuery();
        $q = $this->searchQuery();
        $result = CursoModel::paginate($page, $this->perPage(), $q);
        $meta = $this->paginationMeta($result['total'], $page);

        view('cursos/index', [
            'items' => $result['items'],
            'q' => $q,
            'meta' => $meta,
            'total' => $result['total'],
            'pageTitle' => 'Cursos',
            'activeNav' => 'cursos',
        ]);
    }

    public function create(): void
    {
        clear_old();
        view('cursos/create', ['pageTitle' => 'Novo curso', 'activeNav' => 'cursos', 'item' => null]);
    }

    public function store(): void
    {
        $this->requirePost();
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('cursos/create');
        }
        CursoModel::create($data);
        clear_old();
        Flash::success('Curso criado com sucesso.');
        redirect('cursos/list');
    }

    public function edit(): void
    {
        $id = $this->idFromQuery();
        if (!$id) {
            Flash::error('Registo não encontrado.');
            redirect('cursos/list');
        }
        $item = CursoModel::find($id);
        if (!$item) {
            Flash::error('Curso não encontrado.');
            redirect('cursos/list');
        }
        clear_old();
        view('cursos/edit', ['item' => $item, 'pageTitle' => 'Editar curso', 'activeNav' => 'cursos']);
    }

    public function update(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('cursos/list');
        }
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('cursos/edit', ['id' => $id]);
        }
        CursoModel::update($id, $data);
        clear_old();
        Flash::success('Curso atualizado.');
        redirect('cursos/list');
    }

    public function delete(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('cursos/list');
        }
        if (CursoModel::hasInscricoes($id)) {
            Flash::error('Não é possível apagar: existem inscrições associadas.');
            redirect('cursos/list');
        }
        CursoModel::delete($id);
        Flash::success('Curso removido.');
        redirect('cursos/list');
    }

    /** @return array<string, mixed> */
    private function input(): array
    {
        return [
            'nome' => trim((string) ($_POST['nome'] ?? '')),
            'duracao' => trim((string) ($_POST['duracao'] ?? '')),
            'preco_kz' => (float) str_replace([' ', ','], ['', '.'], (string) ($_POST['preco_kz'] ?? '0')),
            'periodo' => trim((string) ($_POST['periodo'] ?? '')),
            'horario' => trim((string) ($_POST['horario'] ?? '')),
            'ativo' => isset($_POST['ativo']),
        ];
    }

    /** @param array<string, mixed> $data */
    private function validate(array $data): bool
    {
        $validator = new Validator();
        if (!$validator->validate($data, [
            'nome' => 'required|max:180',
            'duracao' => 'required|max:80',
            'preco_kz' => 'required|numeric',
            'periodo' => 'required|max:80',
            'horario' => 'required|max:80',
        ])) {
            Flash::error($validator->firstError());
            return false;
        }
        if ($data['preco_kz'] < 0) {
            Flash::error('O preço não pode ser negativo.');
            return false;
        }
        return true;
    }
}

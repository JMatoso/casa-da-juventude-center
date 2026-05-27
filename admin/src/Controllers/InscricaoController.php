<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Helpers\Flash;
use App\Helpers\Validator;
use App\Models\AlunoModel;
use App\Models\CursoModel;
use App\Models\InscricaoModel;

final class InscricaoController extends BaseController
{
    public function list(): void
    {
        $page = $this->pageFromQuery();
        $q = $this->searchQuery();
        $result = InscricaoModel::paginate($page, $this->perPage(), $q);
        $meta = $this->paginationMeta($result['total'], $page);

        view('inscricoes/index', [
            'items' => $result['items'],
            'q' => $q,
            'meta' => $meta,
            'total' => $result['total'],
            'statuses' => InscricaoModel::STATUSES,
            'pageTitle' => 'Inscrições',
            'activeNav' => 'inscricoes',
        ]);
    }

    public function create(): void
    {
        clear_old();
        view('inscricoes/create', $this->formData(null));
    }

    public function store(): void
    {
        $this->requirePost();
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('inscricoes/create');
        }
        if (InscricaoModel::hasActiveDuplicate($data['aluno_id'], $data['curso_id'])) {
            Flash::error('Já existe uma inscrição pendente ou ativa para este aluno neste curso.');
            set_old($data);
            redirect('inscricoes/create');
        }
        InscricaoModel::create($data);
        clear_old();
        Flash::success('Inscrição criada com sucesso.');
        redirect('inscricoes/list');
    }

    public function edit(): void
    {
        $id = $this->idFromQuery();
        if (!$id) {
            Flash::error('Registo não encontrado.');
            redirect('inscricoes/list');
        }
        $item = InscricaoModel::find($id);
        if (!$item) {
            Flash::error('Inscrição não encontrada.');
            redirect('inscricoes/list');
        }
        clear_old();
        view('inscricoes/edit', $this->formData($item));
    }

    public function update(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('inscricoes/list');
        }
        $data = $this->input();
        if (!$this->validate($data)) {
            set_old($data);
            redirect('inscricoes/edit', ['id' => $id]);
        }
        if (InscricaoModel::hasActiveDuplicate($data['aluno_id'], $data['curso_id'], $id)) {
            Flash::error('Já existe uma inscrição pendente ou ativa para este aluno neste curso.');
            set_old($data);
            redirect('inscricoes/edit', ['id' => $id]);
        }
        InscricaoModel::update($id, $data);
        clear_old();
        Flash::success('Inscrição atualizada.');
        redirect('inscricoes/list');
    }

    public function delete(): void
    {
        $this->requirePost();
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            Flash::error('Registo inválido.');
            redirect('inscricoes/list');
        }
        InscricaoModel::delete($id);
        Flash::success('Inscrição removida.');
        redirect('inscricoes/list');
    }

    /** @param array<string, mixed>|null $item */
    private function formData(?array $item): array
    {
        return [
            'item' => $item,
            'alunos' => AlunoModel::allForSelect(),
            'cursos' => CursoModel::allForSelect(),
            'statuses' => InscricaoModel::STATUSES,
            'pageTitle' => $item ? 'Editar inscrição' : 'Nova inscrição',
            'activeNav' => 'inscricoes',
        ];
    }

    /** @return array<string, mixed> */
    private function input(): array
    {
        return [
            'aluno_id' => (int) ($_POST['aluno_id'] ?? 0),
            'curso_id' => (int) ($_POST['curso_id'] ?? 0),
            'data_inscricao' => trim((string) ($_POST['data_inscricao'] ?? '')),
            'status' => trim((string) ($_POST['status'] ?? 'pendente')),
            'observacoes' => trim((string) ($_POST['observacoes'] ?? '')),
        ];
    }

    /** @param array<string, mixed> $data */
    private function validate(array $data): bool
    {
        $validator = new Validator();
        if (!$validator->validate($data, [
            'data_inscricao' => 'required|date',
        ])) {
            Flash::error($validator->firstError());
            return false;
        }
        if ($data['aluno_id'] <= 0 || !AlunoModel::find($data['aluno_id'])) {
            Flash::error('Selecione um aluno válido.');
            return false;
        }
        if ($data['curso_id'] <= 0 || !CursoModel::find($data['curso_id'])) {
            Flash::error('Selecione um curso válido.');
            return false;
        }
        if (!in_array($data['status'], InscricaoModel::STATUSES, true)) {
            Flash::error('Estado inválido.');
            return false;
        }
        return true;
    }
}

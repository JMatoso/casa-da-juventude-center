<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AlunoModel;
use App\Models\CursoModel;
use App\Models\FuncionarioModel;
use App\Models\InscricaoModel;
use App\Models\ProfessorModel;

final class DashboardController
{
    public function index(): void
    {
        view('dashboard', [
            'stats' => [
                'alunos' => AlunoModel::count(),
                'cursos' => CursoModel::countActive(),
                'professores' => ProfessorModel::count(),
                'funcionarios' => FuncionarioModel::count(),
                'inscricoes' => InscricaoModel::count(),
            ],
            'recentInscricoes' => InscricaoModel::latest(10),
            'pageTitle' => 'Dashboard',
            'activeNav' => 'dashboard',
        ]);
    }
}

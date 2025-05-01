<?php

namespace App\Http\Controllers;
use App\Models\Funcionario;
use App\Models\Departamento;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
class DepartamentoController extends Controller
{
    public function index()
    {
        return Departamento::all();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|unique:departamentos',
        ]);

        return Departamento::create($data);
    }

    public function show($id)
    {
        return Departamento::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $departamento = Departamento::findOrFail($id);
        $data = $request->validate([
            'nome' => 'required|string|unique:departamentos,nome,' . $id,
        ]);
        $departamento->update($data);

        return $departamento;
    }

    public function destroy($id)
{
    $departamento = Departamento::find($id);

    if (!$departamento) {
        return response()->json(['message' => 'Departamento não encontrado.'], 404);
    }

    $departamento->delete();

    return response()->json(['message' => 'Departamento deletado com sucesso.']);
}

    public function indexWithFuncionarios()
    {
        try {
            $departamentos = Departamento::with('funcionarios')->get();
            if ($departamentos->isEmpty()) {
                return response()->json(['message' => 'Nenhum departamento encontrado.'], 404);
            }

            return response()->json($departamentos, 200);

        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar departamentos: ' . $e->getMessage()], 500);
        }
    }

    public function getDepartamento($id)
    {
        try {
            $funcionario = Funcionario::find($id);

            if (!$funcionario) {
                return response()->json(['message' => 'Funcionário não encontrado.'], 404);
            }

            $departamento = $funcionario->departamento;

            if (!$departamento) {
                return response()->json(['message' => 'Este funcionário não pertence a nenhum departamento.'], 404);
            }

            return response()->json($departamento, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar departamento do funcionário: ' . $e->getMessage()], 500);
        }
    }

    public function getFuncionarios($id)
    {
        try {
            $departamento = Departamento::find($id);

            if (!$departamento) {
                return response()->json(['message' => 'Departamento não encontrado.'], 404);
            }

            $funcionarios = $departamento->funcionarios;

            if ($funcionarios->isEmpty()) {
                return response()->json(['message' => 'Nenhum funcionário encontrado para este departamento.'], 404);
            }

            return response()->json($funcionarios, 200);
        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar funcionários do departamento: ' . $e->getMessage()], 500);
        }
    }
}


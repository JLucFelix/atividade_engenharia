<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class FuncionarioController extends Controller
{
    public function index()
    {
        return Funcionario::all();
    }

    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nome' => 'required|string',
            'email' => 'required|email|unique:funcionarios,email',
            'departamento_id' => 'required|exists:departamentos,id',
        ]);

        $funcionario = Funcionario::create($validated);

        return response()->json($funcionario, 201);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Erro ao criar funcionário',
            'message' => $e->getMessage()
        ], 500);
    }
}

public function update(Request $request, $id)
{
    $funcionario = Funcionario::find($id);

    if (!$funcionario) {
        return response()->json(['message' => 'Funcionário não encontrado.'], 404);
    }


    $request->validate([
        'nome' => 'required|string|max:255',
        'email' => 'required|email|unique:funcionarios,email,' . $id,
        'departamento_id' => 'required|exists:departamentos,id',
    ]);

    // Atualiza os campos
    $funcionario->update([
        'nome' => $request->nome,
        'email' => $request->email,
        'departamento_id' => $request->departamento_id,
    ]);

    return response()->json($funcionario); 
}
    public function destroy($id)
{
    $funcionario = Funcionario::find($id);

    if ($funcionario) {
        $funcionario->delete();
        return response()->json(['message' => 'Funcionário deletado com sucesso.']);
    }

    return response()->json(['message' => 'Funcionário não encontrado.'], 404);
}

    // Extras
    public function indexWithDepartamento()
{
    try {
        $funcionarios = Funcionario::with('departamento')->get();
        return response()->json($funcionarios);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
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
                return response()->json(['message' => 'Este funcionário não tem departamento.'], 404);
            }

            return response()->json($departamento, 200);

        } catch (QueryException $e) {
            return response()->json(['error' => 'Erro ao buscar departamento do funcionário: ' . $e->getMessage()], 500);
        }
    }

    public function show($id)
{
    $funcionario = Funcionario::find($id);  
    if (!$funcionario) {
        return response()->json(['message' => 'Funcionário não encontrado.'], 404);
    }

    return response()->json($funcionario);
}

}

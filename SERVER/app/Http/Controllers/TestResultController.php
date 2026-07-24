<?php

namespace App\Http\Controllers;

use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestResultController extends Controller
{
    /**
     * Recibe los resultados de los tests desde la aplicación móvil.
     */
    public function store(Request $request)
    {
        // Validar la estructura de entrada básica
        $validator = Validator::make($request->all(), [
            'user.name' => 'required|string',
            'testType' => 'required|string',
            'timestamp' => 'required',
            'results' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Estructura de datos inválida',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $data = $request->all();

            // Extraer metadata (todo lo que no es la estructura base)
            $metadata = collect($data)->except(['user', 'testType', 'timestamp', 'results'])->toArray();

            // Guardar en la base de datos
            $testResult = TestResult::create([
                'user_name' => $data['user']['name'],
                'pair_name' => $data['user']['pair_name'] ?? null,
                'center_name' => $data['user']['center_name'] ?? null,
                'test_type' => $data['testType'],
                'completed_at' => $data['timestamp'],
                'answers' => $data['results'],
                'metadata' => $metadata,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Resultados guardados correctamente',
                'id' => $testResult->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error interno al guardar los datos',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

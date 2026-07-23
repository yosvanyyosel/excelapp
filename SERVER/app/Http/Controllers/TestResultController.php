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
        // Validar la estructura de entrada
        $validator = Validator::make($request->all(), [
            'user.name' => 'required|string',
            'user.surname' => 'required|string',
            'user.trainingNumber' => 'nullable|string',
            'testType' => 'required|string',
            'timestamp' => 'required|date',
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
            // Guardar en la base de datos
            $testResult = TestResult::create([
                'user_name' => $request->input('user.name'),
                'user_surname' => $request->input('user.surname'),
                'training_number' => $request->input('user.trainingNumber'),
                'test_type' => $request->input('testType'),
                'completed_at' => $request->input('timestamp'),
                'answers' => $request->input('results'),
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

import 'package:dio/dio.dart';
import '../models/question.dart';
import '../services/persistence_service.dart';

class ApiService {
  static const String baseUrl = 'http://192.168.42.96:8000';
  static const String apiUrl = '$baseUrl/api';

  static final Dio _dio = Dio(
    BaseOptions(
      baseUrl: apiUrl,
      connectTimeout: Duration(seconds: 30),
      receiveTimeout: Duration(seconds: 30),
    ),
  );

  static Future<Map<String, dynamic>> login(
    String username,
    String password,
  ) async {
    try {
      final response = await _dio.post(
        '/login',
        data: {'username': username, 'password': password},
      );
      return response.data;
    } on DioException catch (e) {
      print(e);
      return {
        'status': 'error',
        'message': e.response?.data['message'] ?? 'Error de conexión',
      };
    }
  }

  static Future<void> sendResults({
    required String testType,
    required List<Question> questions,
    Map<String, dynamic>? extraData,
  }) async {
    try {
      final token = PersistenceService.getLoginToken();

      // Construimos el payload con la información del perfil actual
      final payload = {
        'user': {
          'name': PersistenceService.getName(),
          'center_name': PersistenceService.getCenterName(),
          'pair_name': PersistenceService.getPairName(),
        },
        'testType': testType,
        'timestamp': DateTime.now().toIso8601String(),
        'results': questions
            .map((q) => {'number': q.number, 'answer': q.answer})
            .toList(),
        if (extraData != null) ...extraData,
      };

      await _dio.post(
        '/results',
        data: payload,
        options: Options(headers: {'Authorization': 'Bearer $token'}),
      );
      print("Resultados enviados con éxito");
    } catch (e) {
      print("Error enviando resultados: $e");
      rethrow; // Lanzamos el error para que la UI pueda manejarlo
    }
  }
}

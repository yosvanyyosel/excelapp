import 'package:dio/dio.dart';
import '../models/question.dart';
import '../pages/profile_page.dart';

class ApiService {
  static final Dio _dio = Dio();

  static Future<void> sendResults({
    required String testType,
    required List<Question> questions,
    Map<String, dynamic>? extraData,
  }) async {
    try {
      final payload = {
        'user': {
          'name': UserData.name,
          'surname': UserData.surname,
        },
        'testType': testType,
        'timestamp': DateTime.now().toIso8601String(),
        'results': questions.map((q) => {
          'number': q.number,
          'answer': q.answer,
        }).toList(),
        if (extraData != null) ...extraData,
      };

      // Nota: Resultados.com es un ejemplo, ajusta la URL según sea necesario.
      await _dio.post('https://Resultados.com/api/results', data: payload);
      print("Resultados enviados con éxito");
    } catch (e) {
      print("Error enviando resultados: $e");
      // En una app real, podrías reintentar luego o informar al usuario.
    }
  }
}

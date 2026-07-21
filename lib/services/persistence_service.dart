import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/question.dart';

class PersistenceService {
  static late SharedPreferences _prefs;

  static Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Perfil
  static String getName() => _prefs.getString('name') ?? "";
  static String getSurname() => _prefs.getString('surname') ?? "";

  static Future<void> saveProfile(String name, String surname) async {
    await _prefs.setString('name', name);
    await _prefs.setString('surname', surname);
  }

  // Método solicitado para verificar si el perfil está completo
  static bool isProfileComplete() => getName().isNotEmpty;

  static bool hasProfile() => isProfileComplete();

  // Estado de los tests
  static bool isTestCompleted(String type) => _prefs.getBool('completed_$type') ?? false;

  static Future<void> setTestCompleted(String type, bool completed) async {
    await _prefs.setBool('completed_$type', completed);
  }

  // Guardar respuestas
  static Future<void> saveAnswers(String type, List<Question> questions) async {
    final List<Map<String, dynamic>> data = questions.map((q) => {
      'number': q.number,
      'text': q.text,
      'answer': q.answer,
    }).toList();
    await _prefs.setString('answers_$type', jsonEncode(data));
  }

  static List<Question>? getSavedAnswers(String type) {
    final String? json = _prefs.getString('answers_$type');
    if (json == null) return null;
    final List<dynamic> decoded = jsonDecode(json);
    return decoded.map((item) => Question(
      number: item['number'],
      text: item['text'],
      answer: item['answer'],
    )).toList();
  }

  static Future<void> clearAll() async {
    await _prefs.clear();
  }
}

import 'package:shared_preferences/shared_preferences.dart';
import 'dart:convert';
import '../models/question.dart';

class PersistenceService {
  static late SharedPreferences _prefs;

  static Future<void> init() async {
    _prefs = await SharedPreferences.getInstance();
  }

  // Auth
  static Future<void> saveLoginToken(String token) async {
    await _prefs.setString('auth_token', token);
  }

  static String? getLoginToken() => _prefs.getString('auth_token');

  static bool isLoggedIn() => getLoginToken() != null;

  // Perfil
  static String getName() => _prefs.getString('name') ?? "";

  static Future<void> saveProfile(String name) async {
    await _prefs.setString('name', name);
  }

  // Info del Centro y Pareja
  static Future<void> saveCenterInfo(Map<String, dynamic>? center) async {
    if (center != null) {
      await _prefs.setString('center_name', center['name'] ?? "");
      await _prefs.setInt('quiz_timer', center['timer'] ?? 15);
    }
  }

  static String getCenterName() => _prefs.getString('center_name') ?? "Centro de Descubrimiento";
  static int getQuizTimer() => _prefs.getInt('quiz_timer') ?? 15;

  static Future<void> savePairInfo(String? name, String? photo) async {
    await _prefs.setString('pair_name', name ?? "");
    await _prefs.setString('pair_photo', photo ?? "");
  }

  static String getPairName() => _prefs.getString('pair_name') ?? "";
  static String getPairPhoto() => _prefs.getString('pair_photo') ?? "";

  static bool isProfileComplete() => getName().isNotEmpty;

  static bool hasProfile() => isProfileComplete();

  // Estado de los tests
  static bool isTestCompleted(String type) => _prefs.getBool('completed_$type') ?? false;

  static Future<void> setTestCompleted(String type, bool completed) async {
    await _prefs.setBool('completed_$type', completed);
  }

  // Sincronización con el servidor
  static bool isTestSent(String type) => _prefs.getBool('sent_$type') ?? false;

  static Future<void> setTestSent(String type, bool sent) async {
    await _prefs.setBool('sent_$type', sent);
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

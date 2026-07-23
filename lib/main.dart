import 'package:flutter/material.dart';
import 'pages/selection_page.dart';
import 'pages/quiz_page.dart';
import 'pages/result_page.dart';
import 'pages/mbti_result_page.dart';
import 'pages/mbti_detail_page.dart';
import 'pages/profile_page.dart';
import 'pages/login_page.dart';
import 'services/persistence_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await PersistenceService.init();
  runApp(DonesApp());
}

class DonesApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    // La prioridad es el Login si no hay sesión
    String initialRoute = '/login';
    
    if (PersistenceService.isLoggedIn()) {
      // Si está logueado pero no ha completado el perfil (nombre/apellido)
      if (!PersistenceService.isProfileComplete()) {
        initialRoute = '/profile';
      } else {
        initialRoute = '/';
      }
    }

    return MaterialApp(
      title: 'Centro de Descubrimiento',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        primarySwatch: Colors.indigo,
        useMaterial3: true,
      ),
      initialRoute: initialRoute,
      routes: {
        '/': (context) => SelectionPage(),
        '/login': (context) => LoginPage(),
        '/quiz': (context) => QuizPage(),
        '/result_dones': (context) => ResultPage(),
        '/result_mbti': (context) => MbtiResultPage(),
        '/mbti_detail': (context) => MbtiDetailPage(),
        '/profile': (context) => ProfilePage(),
      },
    );
  }
}

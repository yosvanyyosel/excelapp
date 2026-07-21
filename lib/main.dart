import 'package:flutter/material.dart';
import 'pages/selection_page.dart';
import 'pages/quiz_page.dart';
import 'pages/result_page.dart';
import 'pages/mbti_result_page.dart';
import 'pages/mbti_detail_page.dart';
import 'pages/profile_page.dart';
import 'services/persistence_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await PersistenceService.init();
  runApp(DonesApp());
}

class DonesApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    // Determine the initial route
    String initialRoute = '/';
    if (!PersistenceService.isProfileComplete()) {
      initialRoute = '/profile';
    }

    return MaterialApp(
      title: 'Centro de Descubrimiento',
      theme: ThemeData(
        primarySwatch: Colors.indigo,
        useMaterial3: true,
      ),
      initialRoute: initialRoute,
      routes: {
        '/': (context) => SelectionPage(),
        '/quiz': (context) => QuizPage(),
        '/result_dones': (context) => ResultPage(),
        '/result_mbti': (context) => MbtiResultPage(),
        '/mbti_detail': (context) => MbtiDetailPage(),
        '/profile': (context) => ProfilePage(),
      },
    );
  }
}

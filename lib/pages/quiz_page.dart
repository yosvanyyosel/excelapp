import 'package:flutter/material.dart';
import '../models/question.dart';
import '../widgets/question_card.dart';
import '../data/mbti_questions.dart';
import '../data/dones_questions.dart';
import '../models/test.dart';
import '../services/persistence_service.dart';
import '../services/api_service.dart';

class QuizPage extends StatefulWidget {
  @override
  _QuizPageState createState() => _QuizPageState();
}

class _QuizPageState extends State<QuizPage> {
  int currentIndex = 0;
  List<Question> questions = [];
  String? testType;
  bool _initialized = false;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    if (!_initialized) {
      testType = ModalRoute.of(context)!.settings.arguments as String?;
      if (testType != null) {
        // Check if test is already completed
        if (PersistenceService.isTestCompleted(testType!)) {
          WidgetsBinding.instance.addPostFrameCallback((_) {
            _navigateToResults();
          });
        }

        // Load saved answers or original questions
        final saved = PersistenceService.getSavedAnswers(testType!);
        if (saved != null) {
          questions = saved;
          // Find the first unanswered question
          int firstUnanswered = questions.indexWhere((q) => q.answer == null);
          currentIndex = firstUnanswered != -1 ? firstUnanswered : 0;
        } else {
          if (testType == 'mbti') {
            questions = mbtiQuestions.map((q) => Question(number: q.number, text: q.text)).toList();
          } else {
            questions = donesQuestions.map((q) => Question(number: q.number, text: q.text)).toList();
          }
        }
      }
      _initialized = true;
    }
  }

  void _navigateToResults() {
    if (testType == 'mbti') {
      Test mbtiTest = Test(name: "Meyer-Briggs", questions: questions);
      for (var q in questions) {
        String code = _extractMbtiCode(q.text);
        if (code.isNotEmpty) {
          mbtiTest.addAnswer(questions.indexOf(q), q.answer ?? 0, code);
        }
      }
      Navigator.pushReplacementNamed(context, '/result_mbti', arguments: mbtiTest);
    } else {
      Navigator.pushReplacementNamed(context, '/result_dones', arguments: questions);
    }
  }

  void _answerQuestion(int value) async {
    setState(() {
      questions[currentIndex].answer = value;
    });
    
    // Auto-save progress
    await PersistenceService.saveAnswers(testType!, questions);

    if (currentIndex < questions.length - 1) {
      setState(() {
        currentIndex++;
      });
    } else {
      // Mark as completed
      await PersistenceService.setTestCompleted(testType!, true);
      
      // Send to API
      ApiService.sendResults(testType: testType!, questions: questions);
      
      _navigateToResults();
    }
  }

  String _extractMbtiCode(String text) {
    final codes = ["E", "I", "S", "N", "T", "F", "J", "P"];
    for (var code in codes) {
      if (text.contains("[$code]")) return code;
    }
    return "";
  }

  @override
  Widget build(BuildContext context) {
    if (questions.isEmpty) return Scaffold(body: Center(child: CircularProgressIndicator()));

    return Scaffold(
      backgroundColor: Color(0xFFF5F7FA),
      appBar: AppBar(
        title: Text(
          testType == 'mbti' ? "Test de Personalidad" : "Test de Dones",
          style: TextStyle(fontWeight: FontWeight.bold, color: Colors.indigo[900]),
        ),
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        leading: IconButton(
          icon: Icon(Icons.close, color: Colors.black54),
          onPressed: () => Navigator.pop(context),
        ),
      ),
      body: SafeArea(
        child: Column(
          children: [
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8.0),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(10),
                child: LinearProgressIndicator(
                  value: (currentIndex + 1) / questions.length,
                  minHeight: 10,
                  backgroundColor: Colors.indigo.withOpacity(0.1),
                  valueColor: AlwaysStoppedAnimation<Color>(Colors.indigo),
                ),
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 24.0, vertical: 8),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    "PREGUNTA ${currentIndex + 1}/${questions.length}",
                    style: TextStyle(
                      color: Colors.grey[600],
                      fontWeight: FontWeight.bold,
                      fontSize: 12,
                      letterSpacing: 1.1,
                    ),
                  ),
                  if (currentIndex > 0)
                    GestureDetector(
                      onTap: () => setState(() => currentIndex--),
                      child: Text(
                        "ANTERIOR",
                        style: TextStyle(
                          color: Colors.indigo,
                          fontWeight: FontWeight.bold,
                          fontSize: 12,
                        ),
                      ),
                    )
                ],
              ),
            ),
            Expanded(
              child: Padding(
                padding: const EdgeInsets.all(24.0),
                child: Center(
                  child: SingleChildScrollView(
                    child: QuestionCard(
                      question: questions[currentIndex],
                      onAnswer: _answerQuestion,
                      isBinary: testType == 'mbti',
                    ),
                  ),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}

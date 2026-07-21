// models/question.dart
class Question {
  final int number;
  final String text;
  int? answer;

  Question({required this.number, required this.text, this.answer});
}

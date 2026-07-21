// widgets/mbti_result.dart
import 'package:flutter/material.dart';

class MbtiResult extends StatelessWidget {
  final String type;
  final Map<String, int> scores;

  MbtiResult({required this.type, required this.scores});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text("Tu tipo MBTI es:", style: TextStyle(fontSize: 20)),
        SizedBox(height: 10),
        Text(type, style: TextStyle(fontSize: 32, fontWeight: FontWeight.bold)),
        SizedBox(height: 20),
        Text("Puntajes:"),
        ...scores.entries.map((e) => Text("${e.key}: ${e.value}")),
      ],
    );
  }
}

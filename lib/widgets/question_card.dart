import 'package:flutter/material.dart';
import '../models/question.dart';

class QuestionCard extends StatelessWidget {
  final Question question;
  final Function(int) onAnswer;
  final bool isBinary; // True for MBTI (Yes/No), False for Dones (0-4)

  QuestionCard({
    required this.question,
    required this.onAnswer,
    this.isBinary = false,
  });

  @override
  Widget build(BuildContext context) {
    return Container(
      padding: EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.05),
            blurRadius: 20,
            offset: Offset(0, 10),
          ),
        ],
      ),
      child: Column(
        mainAxisSize: MainAxisSize.min,
        crossAxisAlignment: CrossAxisAlignment.stretch,
        children: [
          Text(
            "Pregunta ${question.number}",
            style: TextStyle(
              fontSize: 14,
              fontWeight: FontWeight.bold,
              color: Colors.indigo.withOpacity(0.6),
              letterSpacing: 1.2,
            ),
          ),
          SizedBox(height: 16),
          Text(
            question.text.replaceAll(RegExp(r'\[[A-Z]\]\s*'), ''),
            style: TextStyle(
              fontSize: 20,
              fontWeight: FontWeight.w600,
              color: Colors.black87,
              height: 1.4,
            ),
          ),
          SizedBox(height: 32),
          if (isBinary)
            Row(
              children: [
                Expanded(
                  child: _buildActionButton(
                    context,
                    label: "SÍ",
                    color: Colors.green,
                    onPressed: () => onAnswer(1),
                  ),
                ),
                SizedBox(width: 16),
                Expanded(
                  child: _buildActionButton(
                    context,
                    label: "NO",
                    color: Colors.redAccent,
                    onPressed: () => onAnswer(0),
                  ),
                ),
              ],
            )
          else
            Column(
              children: [
                Text(
                  "Puntúa de 0 a 4 (0: Nunca, 4: Siempre)",
                  style: TextStyle(color: Colors.grey[600], fontSize: 13),
                  textAlign: TextAlign.center,
                ),
                SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: List.generate(5, (i) {
                    return _buildScoreButton(context, i);
                  }),
                ),
              ],
            )
        ],
      ),
    );
  }

  Widget _buildScoreButton(BuildContext context, int value) {
    return InkWell(
      onTap: () => onAnswer(value),
      borderRadius: BorderRadius.circular(12),
      child: Container(
        width: 50,
        height: 50,
        decoration: BoxDecoration(
          border: Border.all(color: Colors.indigo.withOpacity(0.2)),
          borderRadius: BorderRadius.circular(12),
          color: question.answer == value ? Colors.indigo : Colors.white,
        ),
        child: Center(
          child: Text(
            "$value",
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: question.answer == value ? Colors.white : Colors.indigo,
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildActionButton(BuildContext context, {required String label, required Color color, required VoidCallback onPressed}) {
    return ElevatedButton(
      onPressed: onPressed,
      style: ElevatedButton.styleFrom(
        backgroundColor: color,
        foregroundColor: Colors.white,
        padding: EdgeInsets.symmetric(vertical: 16),
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        elevation: 0,
      ),
      child: Text(
        label,
        style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import '../models/question.dart';

class DonesResultTable extends StatelessWidget {
  final List<Question> questions;

  DonesResultTable({required this.questions});

  @override
  Widget build(BuildContext context) {
    const int cols = 14;
    const int rows = 7;

    final List<String> codes = [
      "Adm",
      "Dis",
      "Evan",
      "Exh",
      "Fe",
      "Dar",
      "Con",
      "Lid",
      "Mis",
      "Past",
      "Pro",
      "Serv",
      "Ense",
      "Sab",
    ];

    List<List<int>> grid = List.generate(rows, (_) => List.filled(cols, 0));
    List<int> totals = List.filled(cols, 0);

    for (int i = 0; i < questions.length; i++) {
      int row = i ~/ cols;
      int col = i % cols;
      if (row < rows && col < cols) {
        int val = questions[i].answer ?? 0;
        grid[row][col] = val;
        totals[col] += val;
      }
    }

    return SingleChildScrollView(
      scrollDirection: Axis.horizontal,
      child: Container(
        decoration: BoxDecoration(
          color: Colors.white,
          border: Border.all(color: Colors.white, width: 1),
        ),
        child: Column(
          children: [
            // Question Headers and Data
            ...List.generate(
              rows,
              (r) => Column(
                children: [
                  // Number Row
                  Row(
                    children: List.generate(
                      cols,
                      (c) => _buildCell(
                        text: "${(r * cols) + c + 1}",
                        isHeader: true,
                        height: 30,
                        fontSize: 10,
                        textColor: Colors.black38,
                      ),
                    ),
                  ),
                  // Score Row
                  Row(
                    children: List.generate(
                      cols,
                      (c) => _buildCell(
                        text: "${grid[r][c]}",
                        height: 40,
                        fontSize: 16,
                      ),
                    ),
                  ),
                ],
              ),
            ),
            // Total Puntos Row
            Row(
              children: [
                // _buildLabelCell("Total\nPuntos", width: 60),
                ...List.generate(
                  cols,
                  (c) => _buildCell(
                    text: "${totals[c]}",
                    height: 50,
                    fontWeight: FontWeight.bold,
                    textColor: Colors.black,
                  ),
                ),
              ],
            ),
            // CODIGO Row
            Row(
              children: [
                //_buildLabelCell("CODIGO", width: 60),
                ...List.generate(
                  cols,
                  (c) => _buildCell(
                    text: codes[c],
                    height: 40,
                    fontSize: 11,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildCell({
    required String text,
    double width = 45,
    double height = 40,
    double fontSize = 14,
    bool isHeader = false,
    Color textColor = Colors.black,
    FontWeight fontWeight = FontWeight.normal,
  }) {
    return Container(
      width: width,
      height: height,
      decoration: BoxDecoration(
        border: Border.all(color: Colors.white, width: 0.5),
      ),
      alignment: Alignment.center,
      child: Text(
        text,
        style: TextStyle(
          color: textColor,
          fontWeight: fontWeight,
          fontSize: fontSize,
        ),
      ),
    );
  }

  Widget _buildLabelCell(String text, {double width = 60}) {
    return Container(
      width: width,
      height: 50,
      decoration: BoxDecoration(
        border: Border.all(color: Colors.white, width: 0.5),
      ),
      alignment: Alignment.center,
      child: Text(
        text,
        textAlign: TextAlign.center,
        style: TextStyle(
          color: Colors.black,
          fontWeight: FontWeight.bold,
          fontSize: 10,
        ),
      ),
    );
  }
}

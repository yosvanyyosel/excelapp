import 'dart:typed_data';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import 'package:printing/printing.dart';
import '../models/question.dart';
import '../models/don.dart';
import '../models/test.dart';
import '../pages/profile_page.dart';
import '../data/mbti_details.dart';

class PdfService {
  static Future<void> printDonesPdf(
    List<Question> questions,
    List<Don> sortedDones,
  ) async {
    final pdf = pw.Document();

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

    pdf.addPage(
      pw.MultiPage(
        pageFormat: PdfPageFormat.a4,
        build: (context) => [
          pw.Header(
            level: 0,
            child: pw.Row(
              mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
              children: [
                pw.Text(
                  "Test de Dones Espirituales",
                  style: pw.TextStyle(
                    fontSize: 24,
                    fontWeight: pw.FontWeight.bold,
                  ),
                ),
                pw.Text(DateTime.now().toString().substring(0, 10)),
              ],
            ),
          ),
          pw.Paragraph(text: "Nombre: ${UserData.name} ${UserData.surname}"),
          pw.SizedBox(height: 20),
          pw.Text(
            "Cuadro de Calificación",
            style: pw.TextStyle(fontSize: 18, fontWeight: pw.FontWeight.bold),
          ),
          pw.SizedBox(height: 10),
          _buildDonesTable(questions, codes),
          pw.SizedBox(height: 30),
          pw.Text(
            "Dones Identificados (Orden de Fortaleza)",
            style: pw.TextStyle(fontSize: 18, fontWeight: pw.FontWeight.bold),
          ),
          pw.SizedBox(height: 10),
          pw.Column(
            children: sortedDones.asMap().entries.map((entry) {
              final index = entry.key;
              final don = entry.value;
              return pw.Padding(
                padding: const pw.EdgeInsets.symmetric(vertical: 4),
                child: pw.Row(
                  children: [
                    pw.Text(
                      "${index + 1}. ",
                      style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                    ),
                    pw.Text("${don.name} (${don.code}): "),
                    pw.Spacer(),
                    pw.Text(
                      "${don.score} pts",
                      style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                    ),
                  ],
                ),
              );
            }).toList(),
          ),
        ],
      ),
    );

    await Printing.layoutPdf(
      onLayout: (PdfPageFormat format) async => pdf.save(),
    );
  }

  static pw.Widget _buildDonesTable(
    List<Question> questions,
    List<String> codes,
  ) {
    const int cols = 14;
    const int rows = 7;
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

    return pw.Table(
      border: pw.TableBorder.all(),
      children: [
        // Rows
        ...List.generate(
          rows,
          (r) => pw.TableRow(
            children: List.generate(
              cols,
              (c) => pw.Container(
                padding: pw.EdgeInsets.all(2),
                alignment: pw.Alignment.center,
                child: pw.Column(
                  children: [
                    pw.Text(
                      "${(r * cols) + c + 1}",
                      style: pw.TextStyle(fontSize: 8),
                    ),
                    pw.Text(
                      "${grid[r][c]}",
                      style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
        // Totals
        pw.TableRow(
          children: List.generate(
            cols,
            (c) => pw.Container(
              padding: pw.EdgeInsets.all(4),
              alignment: pw.Alignment.center,
              child: pw.Text(
                "${totals[c]}",
                style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
              ),
            ),
          ),
        ),
        // Codes
        pw.TableRow(
          children: List.generate(
            cols,
            (c) => pw.Container(
              padding: pw.EdgeInsets.all(4),
              alignment: pw.Alignment.center,
              child: pw.Text(
                codes[c],
                style: pw.TextStyle(
                  fontSize: 8,
                  fontWeight: pw.FontWeight.bold,
                ),
              ),
            ),
          ),
        ),
      ],
    );
  }

  static Future<void> printMbtiPdf(Test test, List<String> types) async {
    final pdf = pw.Document();

    pdf.addPage(
      pw.MultiPage(
        pageFormat: PdfPageFormat.a4,
        build: (context) => [
          pw.Header(
            level: 0,
            child: pw.Row(
              mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
              children: [
                pw.Text(
                  "Test de Personalidad MBTI",
                  style: pw.TextStyle(
                    fontSize: 24,
                    fontWeight: pw.FontWeight.bold,
                  ),
                ),
                pw.Text(DateTime.now().toString().substring(0, 10)),
              ],
            ),
          ),
          pw.Paragraph(text: "Nombre: ${UserData.name} ${UserData.surname}"),
          pw.SizedBox(height: 20),
          pw.Center(
            child: pw.Column(
              children: [
                pw.Text(
                  "Tipo(s) de Personalidad:",
                  style: pw.TextStyle(fontSize: 16),
                ),
                pw.Text(
                  types.join(" / "),
                  style: pw.TextStyle(
                    fontSize: 32,
                    fontWeight: pw.FontWeight.bold,
                    color: PdfColors.indigo,
                  ),
                ),
              ],
            ),
          ),
          pw.SizedBox(height: 30),
          pw.Text(
            "Puntajes por Dimensión",
            style: pw.TextStyle(fontSize: 18, fontWeight: pw.FontWeight.bold),
          ),
          pw.SizedBox(height: 10),
          _buildMbtiScores(test),
          pw.SizedBox(height: 30),
          pw.Text(
            "Interpretación Detallada",
            style: pw.TextStyle(fontSize: 18, fontWeight: pw.FontWeight.bold),
          ),
          pw.SizedBox(height: 10),
          ...types.map((type) {
            final detail = mbtiDetails[type];
            if (detail == null) return pw.SizedBox();
            return pw.Column(
              crossAxisAlignment: pw.CrossAxisAlignment.start,
              children: [
                pw.Text(
                  "${detail.name} ($type)",
                  style: pw.TextStyle(
                    fontSize: 14,
                    fontWeight: pw.FontWeight.bold,
                  ),
                ),
                pw.SizedBox(height: 5),
                pw.Text(detail.description),
                pw.SizedBox(height: 10),
                pw.Row(
                  crossAxisAlignment: pw.CrossAxisAlignment.start,
                  children: [
                    pw.Expanded(
                      child: pw.Column(
                        crossAxisAlignment: pw.CrossAxisAlignment.start,
                        children: [
                          pw.Text(
                            "Fortalezas:",
                            style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                          ),
                          ...detail.strengths.map((s) => pw.Text("• $s")),
                        ],
                      ),
                    ),
                    pw.Expanded(
                      child: pw.Column(
                        crossAxisAlignment: pw.CrossAxisAlignment.start,
                        children: [
                          pw.Text(
                            "Debilidades:",
                            style: pw.TextStyle(fontWeight: pw.FontWeight.bold),
                          ),
                          ...detail.weaknesses.map((w) => pw.Text("• $w")),
                        ],
                      ),
                    ),
                  ],
                ),
                pw.Divider(),
                pw.SizedBox(height: 20),
              ],
            );
          }).toList(),
        ],
      ),
    );

    await Printing.layoutPdf(
      onLayout: (PdfPageFormat format) async => pdf.save(),
    );
  }

  static pw.Widget _buildMbtiScores(Test test) {
    return pw.Column(
      children: [
        _buildPdfDimensionRow(
          "Extroversión (E)",
          "Introversión (I)",
          test.scores["E"] ?? 0,
          test.scores["I"] ?? 0,
        ),
        _buildPdfDimensionRow(
          "Sensación (S)",
          "Intuición (N)",
          test.scores["S"] ?? 0,
          test.scores["N"] ?? 0,
        ),
        _buildPdfDimensionRow(
          "Pensamiento (T)",
          "Sentimiento (F)",
          test.scores["T"] ?? 0,
          test.scores["F"] ?? 0,
        ),
        _buildPdfDimensionRow(
          "Juicio (J)",
          "Percepción (P)",
          test.scores["J"] ?? 0,
          test.scores["P"] ?? 0,
        ),
      ],
    );
  }

  static pw.Widget _buildPdfDimensionRow(
    String left,
    String right,
    int leftVal,
    int rightVal,
  ) {
    return pw.Padding(
      padding: const pw.EdgeInsets.symmetric(vertical: 4),
      child: pw.Row(
        mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
        children: [
          pw.Text(
            "$left: $leftVal",
            style: pw.TextStyle(
              fontWeight: leftVal >= rightVal ? pw.FontWeight.bold : null,
            ),
          ),
          pw.Text(
            "$rightVal :$right",
            style: pw.TextStyle(
              fontWeight: rightVal >= leftVal ? pw.FontWeight.bold : null,
            ),
          ),
        ],
      ),
    );
  }
}

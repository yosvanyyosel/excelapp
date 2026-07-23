import 'package:flutter/material.dart';
import '../models/question.dart';
import '../models/don.dart';
import '../widgets/result_table.dart';
import '../services/pdf_service.dart';
import '../services/api_service.dart';
import '../services/persistence_service.dart';

class ResultPage extends StatefulWidget {
  @override
  _ResultPageState createState() => _ResultPageState();
}

class _ResultPageState extends State<ResultPage> {
  bool _isSending = false;
  bool _isSent = false;

  @override
  void initState() {
    super.initState();
    _isSent = PersistenceService.isTestCompleted('dones');
  }

  void _sendResults(List<Question> questions, List<Don> sortedDones) async {
    setState(() => _isSending = true);
    try {
      await ApiService.sendResults(
        testType: 'dones',
        questions: questions,
        extraData: {
          'dones_ranking': sortedDones.map((d) => {'code': d.code, 'score': d.score}).toList(),
        },
      );
      await PersistenceService.setTestCompleted('dones', true);
      setState(() {
        _isSent = true;
        _isSending = false;
      });
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Resultados de Dones enviados con éxito")),
      );
    } catch (e) {
      setState(() => _isSending = false);
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Error al enviar resultados")),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    final questions = ModalRoute.of(context)!.settings.arguments as List<Question>;

    // Definición de los dones en el orden de las columnas de la tabla
    final List<Map<String, String>> donesInfo = [
      {"code": "Adm", "name": "Administración"},
      {"code": "Dis", "name": "Discernimiento"},
      {"code": "Evan", "name": "Evangelismo"},
      {"code": "Exh", "name": "Exhortación"},
      {"code": "Fe", "name": "Fe"},
      {"code": "Dar", "name": "Dar"},
      {"code": "Con", "name": "Conocimiento"},
      {"code": "Lid", "name": "Liderazgo"},
      {"code": "Mis", "name": "Misericordia"},
      {"code": "Past", "name": "Pastoreo"},
      {"code": "Pro", "name": "Profecía"},
      {"code": "Serv", "name": "Servicio / Ministerio"},
      {"code": "Ense", "name": "Enseñanza"},
      {"code": "Sab", "name": "Sabiduría"},
    ];

    // Calcular totales
    List<Don> calculatedDones = donesInfo.map((info) => Don(
      code: info['code']!, 
      name: info['name']!, 
      score: 0
    )).toList();

    for (int i = 0; i < questions.length; i++) {
      int col = i % 14;
      if (col < calculatedDones.length) {
        calculatedDones[col].score += questions[i].answer ?? 0;
      }
    }

    // Ordenar de mayor a menor puntaje
    List<Don> sortedDones = List.from(calculatedDones);
    sortedDones.sort((a, b) => b.score.compareTo(a.score));

    return Scaffold(
      backgroundColor: Color(0xFFF5F7FA),
      appBar: AppBar(
        title: Text(
          "Resultados del Test",
          style: TextStyle(fontWeight: FontWeight.bold, color: Colors.indigo[900]),
        ),
        backgroundColor: Colors.transparent,
        elevation: 0,
        centerTitle: true,
        actions: [
          IconButton(
            icon: Icon(Icons.print, color: Colors.indigo[900]),
            onPressed: () => PdfService.printDonesPdf(questions, sortedDones),
            tooltip: "Imprimir PDF",
          ),
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.stretch,
            children: [
              _buildSectionTitle("Cuadro de Calificación"),
              SizedBox(height: 12),
              DonesResultTable(questions: questions),
              
              SizedBox(height: 24),
              
              // Botón de Envío al Servidor
              _buildSendButton(questions, sortedDones),

              SizedBox(height: 32),
              _buildSectionTitle("Dones Identificados (Orden de Fortaleza)"),
              SizedBox(height: 12),
              
              // Lista ordenada de dones
              Container(
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(16),
                  boxShadow: [
                    BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 15, offset: Offset(0, 5)),
                  ],
                ),
                child: ListView.separated(
                  shrinkWrap: true,
                  physics: NeverScrollableScrollPhysics(),
                  itemCount: sortedDones.length,
                  separatorBuilder: (context, index) => Divider(height: 1, indent: 20, endIndent: 20),
                  itemBuilder: (context, index) {
                    final don = sortedDones[index];
                    final isTop = index < 3; // Resaltar los 3 principales
                    return ListTile(
                      leading: CircleAvatar(
                        backgroundColor: isTop ? Colors.indigo : Colors.grey[200],
                        child: Text(
                          "${index + 1}",
                          style: TextStyle(
                            color: isTop ? Colors.white : Colors.grey[700],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                      title: Text(
                        don.name,
                        style: TextStyle(
                          fontWeight: isTop ? FontWeight.bold : FontWeight.normal,
                          fontSize: 16,
                        ),
                      ),
                      subtitle: Text(don.code),
                      trailing: Container(
                        padding: EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                        decoration: BoxDecoration(
                          color: isTop ? Colors.indigo[50] : Colors.grey[100],
                          borderRadius: BorderRadius.circular(20),
                        ),
                        child: Text(
                          "${don.score} pts",
                          style: TextStyle(
                            color: isTop ? Colors.indigo : Colors.grey[800],
                            fontWeight: FontWeight.bold,
                          ),
                        ),
                      ),
                    );
                  },
                ),
              ),
              
              SizedBox(height: 32),
              ElevatedButton(
                onPressed: () => Navigator.pushNamedAndRemoveUntil(context, '/', (route) => false),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.indigo,
                  foregroundColor: Colors.white,
                  padding: EdgeInsets.symmetric(vertical: 18),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(15)),
                  elevation: 2,
                ),
                child: Text(
                  "VOLVER AL INICIO",
                  style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold, letterSpacing: 1.2),
                ),
              ),
              SizedBox(height: 30),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildSendButton(List<Question> questions, List<Don> sortedDones) {
    return Container(
      width: double.infinity,
      child: ElevatedButton.icon(
        onPressed: (_isSending || _isSent) ? null : () => _sendResults(questions, sortedDones),
        style: ElevatedButton.styleFrom(
          backgroundColor: _isSent ? Colors.green : Colors.indigo[700],
          foregroundColor: Colors.white,
          disabledBackgroundColor: _isSent ? Colors.green : Colors.grey,
          disabledForegroundColor: Colors.white,
          padding: EdgeInsets.symmetric(vertical: 16),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
        ),
        icon: _isSending 
          ? SizedBox(width: 20, height: 20, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2))
          : Icon(_isSent ? Icons.cloud_done : Icons.cloud_upload),
        label: Text(
          _isSending ? "ENVIANDO..." : (_isSent ? "RESULTADOS ENVIADOS" : "ENVIAR RESULTADOS AL SERVIDOR"),
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
      ),
    );
  }

  Widget _buildSectionTitle(String title) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 4.0),
      child: Text(
        title,
        style: TextStyle(
          fontSize: 18,
          fontWeight: FontWeight.bold,
          color: Colors.indigo[800],
          letterSpacing: 0.5,
        ),
      ),
    );
  }
}

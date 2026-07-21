import 'package:flutter/material.dart';
import '../models/test.dart';
import '../services/pdf_service.dart';

class MbtiResultPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    final Test test = ModalRoute.of(context)!.settings.arguments as Test;
    final List<String> types = test.getMbtiTypes();

    return Scaffold(
      backgroundColor: Color(0xFFF5F7FA),
      appBar: AppBar(
        title: Text(
          "Resultados MBTI",
          style: TextStyle(fontWeight: FontWeight.bold),
        ),
        backgroundColor: Colors.indigo,
        foregroundColor: Colors.white,
        centerTitle: true,
        actions: [
          IconButton(
            icon: Icon(Icons.print),
            onPressed: () => PdfService.printMbtiPdf(test, types),
            tooltip: "Imprimir PDF",
          ),
        ],
      ),
      body: SingleChildScrollView(
        padding: EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            _buildResultHeader(types),
            SizedBox(height: 24),
            Text(
              "Puntajes por Dimensión",
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.indigo[900],
              ),
            ),
            SizedBox(height: 12),
            _buildDimensionComparison(test),
            SizedBox(height: 32),
            Text(
              "Interpretación",
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: Colors.indigo[900],
              ),
            ),
            SizedBox(height: 8),
            Text(
              "Toca sobre una tarjeta para ver detalles completos",
              style: TextStyle(fontSize: 13, color: Colors.grey[600]),
            ),
            SizedBox(height: 12),
            ...types.map((type) => _buildTypeCard(context, type)).toList(),
            SizedBox(height: 20),
            ElevatedButton(
              onPressed: () => Navigator.pushNamedAndRemoveUntil(
                context,
                '/',
                (route) => false,
              ),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.indigo,
                foregroundColor: Colors.white,
                minimumSize: Size(double.infinity, 50),
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: Text(
                "VOLVER AL INICIO",
                style: TextStyle(fontWeight: FontWeight.bold),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildResultHeader(List<String> types) {
    return Container(
      width: double.infinity,
      padding: EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 20),
        ],
      ),
      child: Column(
        children: [
          Text(
            types.length > 1
                ? "Tus posibles tipos son:"
                : "Tu tipo de personalidad es:",
            style: TextStyle(fontSize: 16, color: Colors.grey[600]),
          ),
          SizedBox(height: 12),
          Wrap(
            alignment: WrapAlignment.center,
            spacing: 12,
            children: types
                .map(
                  (type) => Text(
                    type,
                    style: TextStyle(
                      fontSize: 42,
                      fontWeight: FontWeight.bold,
                      color: Colors.indigo,
                      letterSpacing: 2,
                    ),
                  ),
                )
                .toList(),
          ),
        ],
      ),
    );
  }

  Widget _buildDimensionComparison(Test test) {
    return Container(
      padding: EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [
          BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 20),
        ],
      ),
      child: Column(
        children: [
          _buildProgressBar(
            "Extroversión (E)",
            "Introversión (I)",
            test.scores["E"] ?? 0,
            test.scores["I"] ?? 0,
          ),
          Divider(height: 30),
          _buildProgressBar(
            "Sensación (S)",
            "Intuición (N)",
            test.scores["S"] ?? 0,
            test.scores["N"] ?? 0,
          ),
          Divider(height: 30),
          _buildProgressBar(
            "Pensamiento (T)",
            "Sentimiento (F)",
            test.scores["T"] ?? 0,
            test.scores["F"] ?? 0,
          ),
          Divider(height: 30),
          _buildProgressBar(
            "Juicio (J)",
            "Percepción (P)",
            test.scores["J"] ?? 0,
            test.scores["P"] ?? 0,
          ),
        ],
      ),
    );
  }

  Widget _buildProgressBar(
    String leftLabel,
    String rightLabel,
    int leftVal,
    int rightVal,
  ) {
    double total = (leftVal + rightVal).toDouble();
    if (total == 0) total = 1;
    double percent = leftVal / total;

    return Column(
      children: [
        Row(
          mainAxisAlignment: MainAxisAlignment.spaceBetween,
          children: [
            Text(
              "$leftLabel: $leftVal",
              style: TextStyle(
                fontWeight: leftVal >= rightVal
                    ? FontWeight.bold
                    : FontWeight.normal,
                color: leftVal >= rightVal ? Colors.indigo : Colors.grey,
              ),
            ),
            Text(
              "$rightVal :$rightLabel",
              style: TextStyle(
                fontWeight: rightVal >= leftVal
                    ? FontWeight.bold
                    : FontWeight.normal,
                color: rightVal >= leftVal ? Colors.indigo : Colors.grey,
              ),
            ),
          ],
        ),
        SizedBox(height: 8),
        Stack(
          children: [
            Container(
              height: 12,
              width: double.infinity,
              decoration: BoxDecoration(
                color: Colors.grey[200],
                borderRadius: BorderRadius.circular(6),
              ),
            ),
            LayoutBuilder(
              builder: (context, constraints) {
                return Container(
                  height: 12,
                  width: constraints.maxWidth * percent,
                  decoration: BoxDecoration(
                    gradient: LinearGradient(
                      colors: [Colors.indigo, Colors.indigoAccent],
                    ),
                    borderRadius: BorderRadius.circular(6),
                  ),
                );
              },
            ),
          ],
        ),
      ],
    );
  }

  Widget _buildTypeCard(BuildContext context, String type) {
    return Card(
      elevation: 0,
      margin: EdgeInsets.only(bottom: 16),
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(15),
        side: BorderSide(color: Colors.indigo.withOpacity(0.1)),
      ),
      child: InkWell(
        onTap: () => Navigator.pushNamed(context, '/mbti_detail', arguments: type),
        borderRadius: BorderRadius.circular(15),
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                children: [
                  Container(
                    padding: EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                    decoration: BoxDecoration(
                      color: Colors.indigo,
                      borderRadius: BorderRadius.circular(8),
                    ),
                    child: Text(
                      type,
                      style: TextStyle(
                        color: Colors.white,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  SizedBox(width: 12),
                  Text(
                    _getTypeName(type),
                    style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                  ),
                  Spacer(),
                  Icon(Icons.arrow_forward_ios, size: 16, color: Colors.grey),
                ],
              ),
              SizedBox(height: 12),
              Text(
                _interpretType(type),
                style: TextStyle(color: Colors.black87, height: 1.5),
              ),
              SizedBox(height: 12),
              Row(
                children: [
                  Icon(Icons.info_outline, size: 14, color: Colors.indigo),
                  SizedBox(width: 4),
                  Text(
                    "Ver fortalezas y debilidades",
                    style: TextStyle(
                      color: Colors.indigo,
                      fontSize: 12,
                      fontWeight: FontWeight.w600,
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  String _getTypeName(String type) {
    switch (type) {
      case "ISTJ":
        return "El Inspector";
      case "ISFJ":
        return "El Protector";
      case "INFJ":
        return "El Consejero";
      case "INTJ":
        return "La Mente Maestra";
      case "ISTP":
        return "El Artesano";
      case "ISFP":
        return "El Compositor";
      case "INFP":
        return "El Sanador";
      case "INTP":
        return "El Arquitecto";
      case "ESTP":
        return "El Promotor";
      case "ESFP":
        return "El Actor";
      case "ENFP":
        return "El Campeón";
      case "ENTP":
        return "El Inventor";
      case "ESTJ":
        return "El Supervisor";
      case "ESFJ":
        return "El Proveedor";
      case "ENFJ":
        return "El Profesor";
      case "ENTJ":
        return "El Mariscal de Campo";
      default:
        return "";
    }
  }

  String _interpretType(String type) {
    switch (type) {
      case "ISTJ":
        return "Personas reservadas, prácticas y silenciosas. Disfrutan del orden y la organización en todas las áreas de sus vidas, incluidos el hogar, el trabajo, la familia y los proyectos. Valoran la lealtad en sí mismos y en los demás, y ponen énfasis en las tradiciones.";
      case "ISFJ":
        return "Convencionales y con los pies en la tierra, disfrutan de la continuidad y la tradición. Tienen un fuerte sentido de la responsabilidad y el deber. Son personas cálidas y protectoras que valoran la armonía.";
      case "INFJ":
        return "Idealistas que tienen un gran sentido de la integridad personal y un impulso para ayudar a otros a alcanzar su potencial. Creativos, dedicados y con una visión profunda.";
      case "INTJ":
        return "Analíticos, lógicos y creativos. Tienen una fuerte necesidad de autonomía y competencia. Son pensadores estratégicos con planes para todo.";
      case "ISTP":
        return "Prácticos y realistas, tienen una afinidad natural por las máquinas y herramientas. Son observadores y valoran la eficiencia y la resolución de problemas de forma inmediata.";
      case "ISFP":
        return "Artísticos, sensibles y amables. Disfrutan del momento presente y de lo que les rodea. Valoran su propio espacio y trabajar a su propio ritmo.";
      case "INFP":
        return "Sensibles, idealistas y leales a sus valores. Tienen curiosidad por las posibilidades del futuro y buscan entender a los demás y ayudarlos.";
      case "INTP":
        return "Lógicos, precisos y reservados. Valoran la inteligencia y el conocimiento. Son teóricos y abstractos, más interesados en las ideas que en la interacción social.";
      case "ESTP":
        return "Enérgicos y orientados a la acción. Disfrutan de los resultados inmediatos y de resolver problemas de forma pragmática. Son sociables y observadores.";
      case "ESFP":
        return "Amantes de la diversión, sociables y entusiastas. Les gusta trabajar con otros para hacer que las cosas sucedan. Tienen un fuerte sentido común y son realistas.";
      case "ENFP":
        return "Entusiastas, creativos e imaginativos. Ven la vida como algo lleno de posibilidades. Son cálidos y están dispuestos a ayudar a cualquiera.";
      case "ENTP":
        return "Rápidos, ingeniosos y estimulantes. Son muy buenos resolviendo problemas nuevos y desafiantes. Valoran la competencia y el pensamiento lógico.";
      case "ESTJ":
        return "Prácticos, realistas y orientados a los hechos. Tienen una habilidad natural para organizar proyectos y personas para que las cosas se hagan.";
      case "ESFJ":
        return "Cooperativos, sociables y de buen corazón. Buscan la armonía en su entorno y trabajan con determinación para lograrla. Les gusta trabajar con otros.";
      case "ENFJ":
        return "Cálidos, empáticos y responsables. Son muy sensibles a las necesidades y sentimientos de los demás. Encuentran potencial en todos.";
      case "ENTJ":
        return "Francos, decididos y líderes naturales. Identifican rápidamente procedimientos ineficientes y desarrollan sistemas para resolver problemas organizativos.";
      default:
        return "Tipo no identificado.";
    }
  }
}

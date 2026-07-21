import 'question.dart';

class Test {
  final String name;
  final List<Question> questions;
  final Map<String, int> scores = {};

  Test({required this.name, required this.questions});

  void addAnswer(int index, int value, String code) {
    questions[index].answer = value;
    scores[code] = (scores[code] ?? 0) + value;
  }

  List<String> getMbtiTypes() {
    List<List<String>> dimensions = [];

    // E vs I
    int eScore = scores["E"] ?? 0;
    int iScore = scores["I"] ?? 0;
    if (eScore > iScore) dimensions.add(["E"]);
    else if (iScore > eScore) dimensions.add(["I"]);
    else dimensions.add(["E", "I"]);

    // S vs N
    int sScore = scores["S"] ?? 0;
    int nScore = scores["N"] ?? 0;
    if (sScore > nScore) dimensions.add(["S"]);
    else if (nScore > sScore) dimensions.add(["N"]);
    else dimensions.add(["S", "N"]);

    // T vs F
    int tScore = scores["T"] ?? 0;
    int fScore = scores["F"] ?? 0;
    if (tScore > fScore) dimensions.add(["T"]);
    else if (fScore > tScore) dimensions.add(["F"]);
    else dimensions.add(["T", "F"]);

    // J vs P
    int jScore = scores["J"] ?? 0;
    int pScore = scores["P"] ?? 0;
    if (jScore > pScore) dimensions.add(["J"]);
    else if (pScore > jScore) dimensions.add(["P"]);
    else dimensions.add(["J", "P"]);

    List<String> results = [""];
    for (var dimension in dimensions) {
      List<String> nextResults = [];
      for (var prefix in results) {
        for (var option in dimension) {
          nextResults.add(prefix + option);
        }
      }
      results = nextResults;
    }

    return results;
  }

  // Keep for backward compatibility if needed, but we'll use getMbtiTypes
  String getMbtiType() => getMbtiTypes().first;
}

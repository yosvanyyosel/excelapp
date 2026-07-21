// widgets/don_list.dart
import 'package:excelcentrodescubrimiento/models/don.dart';
import 'package:flutter/material.dart';

class DonList extends StatelessWidget {
  final List<Don> dones;

  DonList({required this.dones});

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        Text(
          "Dones ordenados de más fuerte a más bajo",
          style: TextStyle(fontSize: 18),
        ),
        SizedBox(height: 10),
        Column(
          children: dones
              .map(
                (d) => ListTile(
                  title: Text("${d.code} - ${d.name}"),
                  trailing: Text("${d.score}"),
                ),
              )
              .toList(),
        ),
      ],
    );
  }
}

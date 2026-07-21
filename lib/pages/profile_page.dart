import 'package:flutter/material.dart';
import '../services/persistence_service.dart';

class UserData {
  static String get name => PersistenceService.getName();
  static String get surname => PersistenceService.getSurname();
}

class ProfilePage extends StatefulWidget {
  @override
  _ProfilePageState createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final _nameController = TextEditingController();
  final _surnameController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _nameController.text = PersistenceService.getName();
    _surnameController.text = PersistenceService.getSurname();
  }

  void _save() async {
    if (_nameController.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("El nombre es obligatorio")),
      );
      return;
    }
    await PersistenceService.saveProfile(
      _nameController.text.trim(),
      _surnameController.text.trim(),
    );
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text("Perfil guardado")),
    );
    // If we are here from the initial route check, we go to selection
    if (Navigator.canPop(context)) {
      Navigator.pop(context);
    } else {
      Navigator.pushReplacementNamed(context, '/');
    }
  }

  void _resetData() async {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text("Borrar todos los datos"),
        content: Text("¿Estás seguro? Esto eliminará tu perfil y todos los resultados de los tests."),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: Text("CANCELAR")),
          TextButton(
            onPressed: () async {
              await PersistenceService.clearAll();
              Navigator.pushNamedAndRemoveUntil(context, '/profile', (route) => false);
            },
            child: Text("BORRAR TODO", style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    bool isFirstRun = !PersistenceService.hasProfile();

    return Scaffold(
      appBar: AppBar(
        title: Text(isFirstRun ? "Configuración Inicial" : "Mi Perfil"),
        backgroundColor: Colors.indigo,
        foregroundColor: Colors.white,
        actions: [
          if (!isFirstRun)
            IconButton(
              icon: Icon(Icons.delete_forever),
              onPressed: _resetData,
              tooltip: "Borrar datos",
            )
        ],
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            if (isFirstRun)
              Padding(
                padding: const EdgeInsets.only(bottom: 24.0),
                child: Text(
                  "¡Bienvenido! Por favor, introduce tus datos para comenzar.",
                  style: TextStyle(fontSize: 16, color: Colors.indigo, fontWeight: FontWeight.bold),
                  textAlign: TextAlign.center,
                ),
              ),
            TextField(
              controller: _nameController,
              decoration: InputDecoration(
                labelText: "Nombre",
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.person),
              ),
            ),
            SizedBox(height: 20),
            TextField(
              controller: _surnameController,
              decoration: InputDecoration(
                labelText: "Apellidos",
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.person_outline),
              ),
            ),
            SizedBox(height: 40),
            ElevatedButton(
              onPressed: _save,
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.indigo,
                foregroundColor: Colors.white,
                padding: EdgeInsets.symmetric(vertical: 16),
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
              ),
              child: Text("GUARDAR Y CONTINUAR", style: TextStyle(fontWeight: FontWeight.bold)),
            ),
          ],
        ),
      ),
    );
  }
}

import 'package:flutter/material.dart';
import '../services/persistence_service.dart';
import '../services/api_service.dart';

class UserData {
  static String get name => PersistenceService.getName();
}

class ProfilePage extends StatefulWidget {
  @override
  _ProfilePageState createState() => _ProfilePageState();
}

class _ProfilePageState extends State<ProfilePage> {
  final _nameController = TextEditingController();

  @override
  void initState() {
    super.initState();
    _nameController.text = PersistenceService.getName();
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
    );
    ScaffoldMessenger.of(context).showSnackBar(
      SnackBar(content: Text("Perfil actualizado")),
    );
    
    if (Navigator.canPop(context)) {
      Navigator.pop(context);
    } else {
      Navigator.pushReplacementNamed(context, '/');
    }
  }

  void _logout() async {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: Text("Cerrar Sesión"),
        content: Text("¿Estás seguro de que deseas cerrar sesión y borrar todos los datos locales?"),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: Text("CANCELAR"),
          ),
          TextButton(
            onPressed: () async {
              await PersistenceService.clearAll();
              Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
            },
            child: Text("CERRAR SESIÓN", style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final String photoPath = PersistenceService.getPairPhoto();
    final String photoUrl = photoPath.isNotEmpty 
        ? "${ApiService.baseUrl}/storage/$photoPath" 
        : "";

    return Scaffold(
      backgroundColor: Color(0xFFF5F7FA),
      appBar: AppBar(
        title: Text("Mi Perfil"),
        backgroundColor: Colors.indigo,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: Icon(Icons.logout),
            onPressed: _logout,
            tooltip: "Cerrar Sesión",
          )
        ],
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          children: [
            // Info de la Pareja (Solo Lectura)
            Container(
              padding: EdgeInsets.all(20),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)],
              ),
              child: Column(
                children: [
                  CircleAvatar(
                    radius: 50,
                    backgroundColor: Colors.indigo[50],
                    backgroundImage: photoUrl.isNotEmpty ? NetworkImage(photoUrl) : null,
                    child: photoUrl.isEmpty ? Icon(Icons.people, size: 50, color: Colors.indigo) : null,
                  ),
                  SizedBox(height: 16),
                  Text(
                    PersistenceService.getPairName(),
                    style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.indigo[900]),
                  ),
                  Text(
                    PersistenceService.getCenterName(),
                    style: TextStyle(color: Colors.grey[600], fontSize: 14),
                  ),
                ],
              ),
            ),
            
            SizedBox(height: 32),
            
            // Editar Nombre del Participante
            Container(
              padding: EdgeInsets.all(24),
              decoration: BoxDecoration(
                color: Colors.white,
                borderRadius: BorderRadius.circular(20),
                boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)],
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Datos del Participante", style: TextStyle(fontWeight: FontWeight.bold, color: Colors.indigo)),
                  SizedBox(height: 20),
                  TextField(
                    controller: _nameController,
                    decoration: InputDecoration(
                      labelText: "Tu Nombre Completo",
                      border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
                      prefixIcon: Icon(Icons.person),
                    ),
                  ),
                  SizedBox(height: 32),
                  ElevatedButton(
                    onPressed: _save,
                    style: ElevatedButton.styleFrom(
                      backgroundColor: Colors.indigo,
                      foregroundColor: Colors.white,
                      minimumSize: Size(double.infinity, 55),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    ),
                    child: Text("GUARDAR CAMBIOS", style: TextStyle(fontWeight: FontWeight.bold)),
                  ),
                ],
              ),
            ),
            
            SizedBox(height: 40),
            Text(
              "Esta información es necesaria para identificar tus resultados en el servidor.",
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey, fontSize: 12),
            ),
          ],
        ),
      ),
    );
  }
}

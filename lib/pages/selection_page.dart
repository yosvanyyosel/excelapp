import 'package:flutter/material.dart';
import '../services/persistence_service.dart';
import '../services/api_service.dart';
import 'profile_page.dart';

class SelectionPage extends StatefulWidget {
  @override
  _SelectionPageState createState() => _SelectionPageState();
}

class _SelectionPageState extends State<SelectionPage> {
  @override
  Widget build(BuildContext context) {
    // Construimos la URL de la foto (Laravel storage)
    final String photoPath = PersistenceService.getPairPhoto();
    final String photoUrl = photoPath.isNotEmpty
        ? "${ApiService.baseUrl}/storage/$photoPath"
        : "";

    return Scaffold(
      body: Container(
        width: double.infinity,
        decoration: BoxDecoration(
          gradient: LinearGradient(
            begin: Alignment.topCenter,
            end: Alignment.bottomCenter,
            colors: [Colors.indigo[900]!, Colors.indigo[600]!],
          ),
        ),
        child: SafeArea(
          child: Column(
            children: [
              // Barra Superior con Dropdown de Opciones
              Align(
                alignment: Alignment.topRight,
                child: Padding(
                  padding: const EdgeInsets.only(right: 8.0),
                  child: PopupMenuButton<String>(
                    icon: Icon(Icons.more_vert, color: Colors.white, size: 30),
                    onSelected: (value) async {
                      if (value == 'perfil') {
                        await Navigator.pushNamed(context, '/profile');
                        setState(() {}); // Refrescar al volver
                      } else if (value == 'logout') {
                        await PersistenceService.clearAll();
                        Navigator.pushReplacementNamed(context, '/login');
                      }
                    },
                    itemBuilder: (context) => [
                      PopupMenuItem(
                        value: 'perfil',
                        child: Row(
                          children: [
                            Icon(Icons.person, color: Colors.indigo),
                            SizedBox(width: 10),
                            Text("Mi Perfil"),
                          ],
                        ),
                      ),
                      PopupMenuItem(
                        value: 'logout',
                        child: Row(
                          children: [
                            Icon(Icons.exit_to_app, color: Colors.red),
                            SizedBox(width: 10),
                            Text("Cerrar Sesión"),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),

              // Información de la Pareja (Desde Servidor)
              Spacer(),
              Container(
                decoration: BoxDecoration(
                  shape: BoxShape.circle,
                  border: Border.all(color: Colors.white, width: 4),
                  boxShadow: [BoxShadow(color: Colors.black26, blurRadius: 10)],
                ),
                child: CircleAvatar(
                  radius: 60,
                  backgroundColor: Colors.grey[300],
                  backgroundImage: photoUrl.isNotEmpty
                      ? NetworkImage(photoUrl)
                      : null,
                  child: photoUrl.isEmpty
                      ? Icon(Icons.people, size: 60, color: Colors.white)
                      : null,
                ),
              ),
              SizedBox(height: 20),
              Text(
                PersistenceService.getPairName().toUpperCase(),
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                  letterSpacing: 2,
                ),
              ),
              Text(
                PersistenceService.getCenterName(),
                style: TextStyle(color: Colors.white70, fontSize: 14),
              ),

              SizedBox(height: 50),

              // Botones de Test
              _buildMenuButton(
                context,
                "Test de Personalidad",
                "mbti",
                Icons.psychology,
              ),
              SizedBox(height: 20),
              _buildMenuButton(
                context,
                "Test de Dones",
                "dones",
                Icons.auto_awesome,
              ),

              Spacer(flex: 2),

              Padding(
                padding: const EdgeInsets.only(bottom: 20),
                child: Text(
                  "Bienvenido, ${UserData.name}",
                  style: TextStyle(
                    color: Colors.white,
                    fontWeight: FontWeight.w300,
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMenuButton(
    BuildContext context,
    String title,
    String type,
    IconData icon,
  ) {
    bool isDone = PersistenceService.isTestCompleted(type);
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 40),
      child: ElevatedButton(
        style: ElevatedButton.styleFrom(
          backgroundColor: isDone ? Colors.green[400] : Colors.white,
          foregroundColor: isDone ? Colors.white : Colors.indigo[900],
          minimumSize: Size(double.infinity, 65),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(15),
          ),
          elevation: 5,
        ),
        onPressed: () => Navigator.pushNamed(context, '/quiz', arguments: type),
        child: Row(
          children: [
            Icon(isDone ? Icons.check_circle : icon),
            SizedBox(width: 20),
            Text(
              title,
              style: TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
            ),
            Spacer(),
            Icon(Icons.arrow_forward_ios, size: 14),
          ],
        ),
      ),
    );
  }
}

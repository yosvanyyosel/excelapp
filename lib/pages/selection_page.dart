import 'package:flutter/material.dart';
import 'profile_page.dart';

class SelectionPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
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
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Align(
                alignment: Alignment.topRight,
                child: Padding(
                  padding: const EdgeInsets.only(right: 16.0),
                  child: PopupMenuButton<String>(
                    icon: Icon(Icons.more_vert, color: Colors.white, size: 30),
                    onSelected: (value) {
                      if (value == 'perfil') {
                        Navigator.pushNamed(context, '/profile');
                      }
                    },
                    itemBuilder: (BuildContext context) => [
                      PopupMenuItem<String>(
                        value: 'perfil',
                        child: Row(
                          children: [
                            Icon(Icons.person, color: Colors.indigo),
                            SizedBox(width: 8),
                            Text("Perfil"),
                          ],
                        ),
                      ),
                    ],
                  ),
                ),
              ),
              Spacer(),
              Icon(Icons.auto_awesome, size: 80, color: Colors.white),
              SizedBox(height: 20),
              Text(
                "Centro de\nDescubrimiento",
                textAlign: TextAlign.center,
                style: TextStyle(
                  fontSize: 32,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                  letterSpacing: 1.5,
                ),
              ),
              SizedBox(height: 10),
              Text(
                "Descubre tu potencial y tus dones",
                style: TextStyle(color: Colors.white70, fontSize: 16),
              ),
              SizedBox(height: 60),
              _buildMenuButton(
                context,
                title: "Test de Personalidad",
                subtitle: "Indicador Meyer-Briggs (MBTI)",
                icon: Icons.person_search,
                route: '/quiz',
                arg: 'mbti',
              ),
              SizedBox(height: 20),
              _buildMenuButton(
                context,
                title: "Test de Dones",
                subtitle: "Dones Espirituales (98 ítems)",
                icon: Icons.card_giftcard,
                route: '/quiz',
                arg: 'dones',
              ),
              Spacer(flex: 2),
              if (UserData.name.isNotEmpty)
                Padding(
                  padding: const EdgeInsets.only(bottom: 20.0),
                  child: Text(
                    "Bienvenido, ${UserData.name} ${UserData.surname}",
                    style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold),
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMenuButton(
    BuildContext context, {
    required String title,
    required String subtitle,
    required IconData icon,
    required String route,
    required String arg,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(horizontal: 30),
      child: ElevatedButton(
        onPressed: () => Navigator.pushNamed(context, route, arguments: arg),
        style: ElevatedButton.styleFrom(
          backgroundColor: Colors.white,
          foregroundColor: Colors.indigo[900],
          padding: EdgeInsets.symmetric(vertical: 20, horizontal: 20),
          shape: RoundedRectangleBorder(
            borderRadius: BorderRadius.circular(15),
          ),
          elevation: 5,
        ),
        child: Row(
          children: [
            Container(
              padding: EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: Colors.indigo[50],
                borderRadius: BorderRadius.circular(12),
              ),
              child: Icon(icon, size: 30),
            ),
            SizedBox(width: 20),
            Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  title,
                  style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                ),
                Text(
                  subtitle,
                  style: TextStyle(fontSize: 12, color: Colors.grey[600]),
                ),
              ],
            ),
            Spacer(),
            Icon(Icons.arrow_forward_ios, size: 16, color: Colors.grey),
          ],
        ),
      ),
    );
  }
}

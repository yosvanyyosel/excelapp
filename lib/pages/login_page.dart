import 'package:flutter/material.dart';
import '../services/api_service.dart';
import '../services/persistence_service.dart';

class LoginPage extends StatefulWidget {
  @override
  _LoginPageState createState() => _LoginPageState();
}

class _LoginPageState extends State<LoginPage> {
  final _usernameController = TextEditingController();
  final _passwordController = TextEditingController();
  bool _isLoading = false;

  void _login() async {
    setState(() => _isLoading = true);
    try {
      final response = await ApiService.login(
        _usernameController.text.trim(),
        _passwordController.text.trim(),
      );

      if (response['status'] == 'success') {
        final userData = response['user'];
        await PersistenceService.saveProfile(
          userData['name'],
          '', // Surname can be empty if not provided by API
          '', // Training number if not provided
        );
        // Save other data like pair_name, pair_photo, center_info
        await PersistenceService.saveCenterInfo(userData['center']);
        await PersistenceService.savePairInfo(userData['pair_name'], userData['pair_photo']);

        Navigator.pushReplacementNamed(context, '/');
      } else {
        _showError(response['message'] ?? "Error de login");
      }
    } catch (e) {
      _showError("Error de conexión al servidor");
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _showError(String message) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(message)));
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.indigo[900],
      body: Center(
        child: SingleChildScrollView(
          padding: EdgeInsets.all(32),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              Icon(Icons.auto_awesome, size: 80, color: Colors.white),
              SizedBox(height: 24),
              Text(
                "Centro de Descubrimiento",
                style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.white),
              ),
              SizedBox(height: 48),
              TextField(
                controller: _usernameController,
                style: TextStyle(color: Colors.white),
                decoration: InputDecoration(
                  labelText: "Usuario",
                  labelStyle: TextStyle(color: Colors.white70),
                  enabledBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white70)),
                  focusedBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white)),
                ),
              ),
              SizedBox(height: 16),
              TextField(
                controller: _passwordController,
                obscureText: true,
                style: TextStyle(color: Colors.white),
                decoration: InputDecoration(
                  labelText: "Contraseña",
                  labelStyle: TextStyle(color: Colors.white70),
                  enabledBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white70)),
                  focusedBorder: OutlineInputBorder(borderSide: BorderSide(color: Colors.white)),
                ),
              ),
              SizedBox(height: 32),
              _isLoading
                  ? CircularProgressIndicator(color: Colors.white)
                  : ElevatedButton(
                      onPressed: _login,
                      style: ElevatedButton.styleFrom(
                        backgroundColor: Colors.white,
                        foregroundColor: Colors.indigo[900],
                        minimumSize: Size(double.infinity, 50),
                      ),
                      child: Text("ENTRAR"),
                    ),
            ],
          ),
        ),
      ),
    );
  }
}

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Portada Pareja</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            border: 10px solid #1e3a8a;
            padding: 40px;
            height: 100%;
        }
        .pair-name {
            font-size: 48px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 30px;
        }
        .participant-name {
            font-size: 24px;
            margin-bottom: 40px;
        }
        .photo-container {
            margin: 40px 0;
        }
        .photo {
            max-width: 500px;
            border-radius: 20px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        .footer {
            margin-top: 50px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="pair-name">{{ $user->pair_name }}</div>
        <div class="participant-name">{{ $user->name }}</div>

        <div class="photo-container">
            @if($user->pair_photo)
                <img src="{{ public_path('storage/' . $user->pair_photo) }}" class="photo">
            @else
                <div style="height: 300px; background: #eee; line-height: 300px; color: #999;">
                    Sin Foto de Pareja
                </div>
            @endif
        </div>

        <div class="footer">
            Generado por el Sistema del Centro de Descubrimiento
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Expediente de Pareja - {{ $user->pair_name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
            color: #1e293b;
        }
        .container {
            width: 210mm;
            height: 297mm;
            box-sizing: border-box;
            position: relative;
            padding: 20mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }
        .banner {
            width: 100%;
            height: 120mm;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            margin-bottom: 20mm;
        }
        .banner img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .no-banner {
            width: 100%;
            height: 120mm;
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 10mm;
        }
        .pair-name {
            font-size: 54px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -2px;
        }
        .center-name {
            font-size: 24px;
            color: #6366f1;
            font-weight: 600;
            margin-top: 5mm;
        }
        .content {
            text-align: center;
            width: 100%;
        }
        .info-box {
            background: white;
            padding: 10mm;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            margin-top: 10mm;
        }
        .footer {
            width: 100%;
            text-align: center;
            color: #94a3b8;
            font-size: 14px;
            border-top: 1px solid #e2e8f0;
            padding-top: 10mm;
        }
        .accent {
            width: 60mm;
            height: 2mm;
            background: #6366f1;
            margin: 10mm auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="center-name">{{ $user->center->name }}</div>
            <div class="accent"></div>
            <h1 class="pair-name">{{ $user->pair_name }}</h1>
        </div>

        <div class="banner">
            @php
                $centerPhoto = $user->center->banner_photo ? public_path('storage/' . $user->center->banner_photo) : null;
            @endphp
            @if($centerPhoto && file_exists($centerPhoto))
                <img src="{{ $centerPhoto }}">
            @else
                <div class="no-banner">CENTRO DE DESCUBRIMIENTO</div>
            @endif
        </div>

        <div class="content">
            <div class="info-box">
                <p style="font-size: 18px; color: #64748b; margin-bottom: 5mm;">EXPEDIENTE DE RESULTADOS</p>
                <h2 style="font-size: 32px; color: #1e293b; margin: 0;">{{ $user->name }} & {{ $user->pair_name }}</h2>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} {{ $user->center->name }} &bull; Sistema de Gestión Discovery
        </div>
    </div>
</body>
</html>

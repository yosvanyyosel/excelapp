<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Galería de Parejas - {{ $center->name }}</title>
    <style>
        @page {
            margin: 10mm;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fff;
            color: #1a1a1a;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #22c55e;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #000;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
        .grid {
            width: 100%;
        }
        .item {
            width: 31%;
            float: left;
            margin: 1%;
            box-sizing: border-box;
            text-align: center;
            padding: 5px;
            margin-bottom: 20px;
        }
        .photo-container {
            width: 100%;
            height: 160px;
            background-color: #f3f4f6;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
            margin-bottom: 8px;
        }
        .photo {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .name {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            word-wrap: break-word;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $center->name }}</h1>
        <p>Galería de Participantes - Total: {{ $pairs->count() }} parejas</p>
    </div>

    @foreach($pairs->chunk(9) as $chunk)
        <div class="grid {{ !$loop->last ? 'page-break' : '' }}">
            @foreach($chunk as $pairName => $users)
                @php
                    $husband = $users->first();
                    $photoPath = $husband->pair_photo ? public_path('storage/' . $husband->pair_photo) : null;
                @endphp
                <div class="item">
                    <div class="photo-container">
                        @if($photoPath && file_exists($photoPath))
                            <img src="{{ $photoPath }}" class="photo">
                        @else
                            <div style="padding-top: 60px; color: #9ca3af; font-size: 10px;">
                                <small>SIN FOTO</small>
                            </div>
                        @endif
                    </div>
                    <div class="name">{{ $pairName }}</div>
                </div>
            @endforeach
            <div class="clear"></div>
        </div>
    @endforeach
</body>
</html>

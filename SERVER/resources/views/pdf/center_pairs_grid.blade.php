<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gafetes/Tarjetas - {{ $center->name }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
        }
        .page-break {
            page-break-after: always;
        }
        .container {
            width: 210mm;
            height: 297mm;
            padding: 10mm;
            box-sizing: border-box;
        }
        .grid {
            width: 100%;
            height: 100%;
        }
        .item {
            width: 48%;
            height: 31%;
            float: left;
            margin: 1%;
            border: 1px dashed #ccc;
            box-sizing: border-box;
            text-align: center;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .photo-box {
            width: 100%;
            height: 70%;
            margin-bottom: 10px;
            overflow: hidden;
            display: block;
        }
        .photo {
            max-width: 100%;
            max-height: 100%;
            border-radius: 10px;
        }
        .name {
            font-size: 18px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    @foreach($pairs->chunk(6) as $chunk)
        <div class="container {{ !$loop->last ? 'page-break' : '' }}">
            <div class="grid">
                @foreach($chunk as $pairName => $users)
                    @php
                        $husband = $users->first();
                        $photoPath = $husband->pair_photo ? public_path('storage/' . $husband->pair_photo) : null;
                    @endphp
                    <div class="item">
                        <div class="photo-box">
                            @if($photoPath && file_exists($photoPath))
                                <img src="{{ $photoPath }}" class="photo">
                            @else
                                <div style="width: 100%; height: 100%; background: #f3f4f6; border-radius: 10px; border: 1px solid #e5e7eb;"></div>
                            @endif
                        </div>
                        <div class="name">{{ $pairName }}</div>
                    </div>
                @endforeach
                <div class="clear"></div>
            </div>
        </div>
    @endforeach
</body>
</html>

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
            background-color: white;
            color: #333;
        }
        .page {
            width: 216mm;
            height: 275mm;
            position: relative;
            overflow: hidden;
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }

        /* PORTADA */
        .decoration-left {
            position: absolute;
            top: 0;
            left: 0;
            width: 60mm;
            height: 100%;
            z-index: 1;
        }
        .tri-1 {
            position: absolute;
            top: -50mm;
            left: -60mm;
            width: 120mm;
            height: 80mm;
            background-color: #122003;
            transform: rotate(-45deg);
        }
        .tri-2 {
            position: absolute;
            top: 20mm;
            left: -100mm;
            width: 120mm;
            height: 120mm;
            background-color: #689f38;
            border: 5px #fff solid;
            transform: rotate(-45deg);
        }
        .tri-3 {
            position: absolute;
            top: 110mm;
            left: -80mm;
            width: 80mm;
            height: 80mm;
            background-color: #122003;
            border: 5px #fff solid;
            transform: rotate(-45deg);
        }
        .cover-content {
            position: relative;
            z-index: 10;
            padding-left: 40mm;
            padding-right: 20mm;
            padding-top: 20mm;
            text-align: center;
        }
        .logo-x { font-size: 80px; color: #7cb342; font-weight: bold; margin-bottom: 0; line-height: 1; }
        .title-main { font-size: 42px; font-weight: bold; color: #1a1a1a; margin-top: 10px; margin-bottom: 0; }
        .title-sub { font-size: 60px; color: #7cb342; margin-top: 0; margin-bottom: 0; font-weight: bold; }
        .date-box { background-color: #7cb342; color: white; border-radius: 20px; padding: 8px 30px; display: inline-block; margin: 15px 0; font-weight: bold; font-size: 16px; }
        .photo-frame { background-color: white; padding: 10px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); margin: 20px auto; width: 160mm; border: 1px solid #ddd; }
        .photo-frame img { width: 100%; height: 90mm; object-fit: cover; border-radius: 15px; }
        .pair-names { font-size: 32px; font-weight: bold; font-style: italic; color: #000; margin-top: 15px; font-family: 'Times New Roman', serif; }
        .footer { position: absolute; bottom: 0; left: 0; width: 100%; height: 25mm; background-color: #000; color: white; display: table; table-layout: fixed; }
        .footer-col { display: table-cell; vertical-align: middle; text-align: center; border-right: 1px solid #333; }
        .footer-text { font-size: 10px; text-transform: uppercase; font-weight: bold; letter-spacing: 1px; }

        /* PAGINAS DE CONTENIDO */
        .content-header {
            padding: 30mm 20mm 10mm 20mm;
            position: relative;
        }
        .green-bar {
            position: absolute;
            top: 0;
            right: 0;
            width: 100mm;
            height: 15mm;
            background: linear-gradient(to right, #7cb342, #33691e);
        }
        .page-title {
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 10mm;
        }
        .pair-header {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 5mm;
        }
        .section-text {
            line-height: 1.6;
            font-size: 14px;
            margin-bottom: 15mm;
            text-align: justify;
        }
        .decision-box {
            border-top: 2px solid #000;
            padding-top: 5mm;
            margin-top: 10mm;
            font-weight: bold;
            font-size: 18px;
            font-style: italic;
        }
        .decision-label { text-decoration: underline; }
        .decision-value { color: #2e7d32; }
        .decision-value.yellow { color: #f9a825; }
        .decision-value.red { color: #c62828; }

        .section-title {
            font-size: 22px;
            font-weight: bold;
            font-style: italic;
            margin-bottom: 5mm;
            border-bottom: 1px solid #ccc;
        }
        .person-name {
            font-size: 18px;
            font-weight: bold;
            font-style: italic;
            margin-top: 8mm;
            margin-bottom: 4mm;
        }
        .item-list {
            padding-left: 10mm;
        }
        .item-list li {
            margin-bottom: 2mm;
            font-size: 13px;
        }

        .signatures {
            margin-top: 20mm;
            width: 100%;
        }
        .sig-block {
            display: inline-block;
            width: 45%;
            text-align: center;
            vertical-align: top;
        }
        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 2mm;
        }
    </style>
</head>
<body>
    <!-- PAGINA 1: PORTADA -->
    <div class="page">
        <div class="decoration-left">
            <div class="tri-1"></div>
            <div class="tri-3"></div>
            <div class="tri-2"></div>
        </div>
        <div class="cover-content">
            <div class="logo-x"><img src="{{ public_path('logoexcel.png') }}" style="height: 41.4mm"> </div>
            <h1 class="title-main">Centro de</h1>
            <h1 class="title-sub">Descubrimiento</h1>
            <p style="font-size: 20px; font-weight: bold; color: #1a1a1a; margin-top: 0px; margin-bottom: 0;">Para Plantadores de Iglesias</p>
            <div class="leaf-divider">--------------------------------------</div>
            <div class="date-box">
                {{ $user->center->name }}
            </div>
            <div class="photo-frame">
               @php
                $centerPhoto = $user->center->banner_photo ? public_path('storage/' . $user->center->banner_photo) : null;
            @endphp
            @if($centerPhoto && file_exists($centerPhoto))
                <img src="{{ $centerPhoto }}">
            @else
                <div class="no-banner">CENTRO DE DESCUBRIMIENTO</div>
            @endif

            </div>
            <div class="pair-names">{{ $user->pair_name }}</div>
        </div>
        <div class="footer">
            <div class="footer-col"><span class="footer-text">Descubrir la Visión</span></div>
            <div class="footer-col"><span class="footer-text">Equipar a los Líderes</span></div>
            <div class="footer-col"><span class="footer-text">Plantar Iglesias</span></div>
        </div>
    </div>

    <!-- PAGINA 2: INTRODUCCION Y DECISION -->
    <div class="page">
        <div class="green-bar"></div>
        <div class="content-header">
            <div class="page-title">Perfil de Plantador de Iglesias Centro de Descubrimiento</div>
            <div class="pair-header">• {{ $user->pair_name }}</div>
            <div style="font-weight: bold; margin-bottom: 10mm;">{{ date('F d, Y') }}</div>

            <div class="section-text">
                El siguiente reporte es tu perfil preparado por el Centro de Descubrimiento. Nuestras observaciones han sido sacadas de tu perfil de personalidad, observación en múltiples simulaciones de plantación de iglesias y tres días de pasar tiempo conociéndote. Un panel de ministros dotados con experiencia han deliberado profunda y cuidadosamente con la información que hemos recibido y observado. El Centro de Descubrimiento, a través de análisis y oración, desea darte su percepción para aconsejarte en cuanto a tus próximos pasos. El siguiente reporte muestra sus conclusiones, observaciones y recomendaciones.
                <br><br>
                Queremos aclararte que este reporte refleja su mejor recomendación en relación a tu idoneidad para plantación de iglesias, en este momento. No estamos evaluando tu idoneidad para el ministerio en general, aunque si podemos, referirnos a esto dentro del reporte. Los apoyamos como hermanos y hermanas en Cristo guiándoles en como cumplir cualquiera de las recomendaciones hechas por el Centro de Descubrimiento.
                <br><br>
                Que Dios les bendiga ricamente y les guíe mientras consideran el siguiente reporte dentro de su continua jornada espiritual en el ministerio.
            </div>

            @if($pairEvaluation)
            <div class="decision-box">
                Reporte Final del Centro de Descubrimiento:
                <span class="decision-value @if($pairEvaluation->decision == 'yellow') yellow @elseif($pairEvaluation->decision == 'red') red @endif">
                    {{ $pairEvaluation->decision_text }}
                </span>
            </div>
            @endif
        </div>
    </div>

    <!-- PAGINA 3: FORTALEZAS -->
    <div class="page">
        <div class="green-bar"></div>
        <div class="content-header">
            <div class="section-title">Fortalezas:</div>
            <div class="section-text" style="margin-bottom: 5mm;">
                Las siguientes son áreas de fortaleza y dones espirituales observados por el personal durante el proceso de descubrimiento. Estas son áreas que resaltan como factores positivos que contribuyen a tu estilo de ministerio. Estar consciente de estas fortalezas y de su beneficio a tu ministerio, te ayudara a aumentar tu efectividad. Puedes utilizar estos dones para compensar y crecer en áreas que necesitan mejorar.
            </div>

            <div class="person-name">{{ $husband->name }}:</div>
            <ol class="item-list">
                @foreach($husband->evaluationItems->where('type', 'strength') as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>

            @if($wife)
                <div class="person-name">{{ $wife->name }}:</div>
                <ol class="item-list">
                    @foreach($wife->evaluationItems->where('type', 'strength') as $item)
                        <li>{{ $item->content }}</li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <!-- PAGINA 4: AREAS DE CRECIMIENTO -->
    <div class="page">
        <div class="green-bar"></div>
        <div class="content-header">
            <div class="section-title">Áreas de Crecimiento:</div>
            <div class="section-text" style="margin-bottom: 5mm;">
                Estas son áreas identificadas que requieren atención y desarrollo para fortalecer tu capacidad de liderazgo y efectividad en la plantación de iglesias.
            </div>

            <div class="person-name">{{ $husband->name }}:</div>
            <ol class="item-list">
                @foreach($husband->evaluationItems->where('type', 'growth') as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>

            @if($wife)
                <div class="person-name">{{ $wife->name }}:</div>
                <ol class="item-list">
                    @foreach($wife->evaluationItems->where('type', 'growth') as $item)
                        <li>{{ $item->content }}</li>
                    @endforeach
                </ol>
            @endif
        </div>
    </div>

    <!-- PAGINA 5: SUGERENCIAS Y FIRMAS -->
    <div class="page">
        <div class="green-bar"></div>
        <div class="content-header">
            <div class="section-title">Sugerencias:</div>

            <div class="person-name">{{ $husband->name }}:</div>
            <ol class="item-list">
                @foreach($husband->evaluationItems->where('type', 'suggestion') as $item)
                    <li>{{ $item->content }}</li>
                @endforeach
            </ol>

            @if($wife)
                <div class="person-name">{{ $wife->name }}:</div>
                <ol class="item-list">
                    @foreach($wife->evaluationItems->where('type', 'suggestion') as $item)
                        <li>{{ $item->content }}</li>
                    @endforeach
                </ol>
            @endif

            <div class="signatures">
                <div class="sig-block" style="margin-right: 5%;">
                    <div class="sig-line"></div>
                    <div style="font-size: 12px; font-weight: bold;">Representante del personal</div>
                    <div style="font-size: 11px;">{{ date('d-m-Y') }}</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div style="font-size: 12px; font-weight: bold;">Director del Centro de Descubrimiento</div>
                    <div style="font-size: 11px;">{{ date('d-m-Y') }}</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

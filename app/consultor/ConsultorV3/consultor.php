<?php
session_start();

// Restringir a consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}

$usuario = $_SESSION['usuario'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Criminal Nexus - Consultor</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../login.css">

    <!-- Cambia TU_API_KEY_AQUI por tu API key real -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD4lJG7qjyagaSLaD1KSVt5QYZJDJhyKLY&callback=initMap" async defer></script>

    <style>
        .consultor-card {
            margin-top: 12px;
            border-radius: 22px;
            background: rgba(15, 23, 42, 0.97);
            border: 1px solid rgba(55, 65, 81, 0.95);
            padding: 20px 18px 22px;
            box-shadow:
                0 22px 70px rgba(15, 23, 42, 0.96),
                0 0 0 1px rgba(15, 23, 42, 0.95);
        }

        .consultor-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 14px;
        }

        .consultor-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #f9fafb;
        }

        .consultor-header p {
            font-size: 0.88rem;
            color: #9ca3af;
        }

        .consultor-pill {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.75rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            border: 1px solid rgba(55, 65, 81, 0.9);
            background: rgba(15, 23, 42, 0.96);
            color: #e5e7eb;
        }

        .consultor-filter {
            margin-bottom: 12px;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            color: #d1d5db;
        }

        .consultor-filter label {
            font-weight: 500;
        }

        .consultor-filter select {
            min-width: 180px;
            padding: 7px 12px;
            border-radius: 999px;
            border: 1px solid rgba(75, 85, 99, 0.9);
            background: rgba(15, 23, 42, 0.96);
            color: #e5e7eb;
            font-size: 0.88rem;
            outline: none;
        }

        .consultor-filter select:focus {
            border-color: #22c55e;
            box-shadow: 0 0 0 1px rgba(34, 197, 94, 0.75);
        }

        #map {
            width: 100%;
            min-height: 430px;
            border-radius: 18px;
            border: 1px solid rgba(55, 65, 81, 0.9);
            overflow: hidden;
        }

        .consultor-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Panel de consultor</h1>
                <p class="app-subtitle">
                    Sesión iniciada como <?php echo htmlspecialchars(ucfirst($usuario)); ?>.
                </p>
            </div>
            <div class="app-header-right">
                <span class="badge">
                    Consultor
                </span>
                <form action="../../home2.php" method="post">
                    <button type="submit" class="btn-ghost">
                        Volver
                    </button>
                </form>
            </div>
        </header>

        <main class="app-main">
            <section class="consultor-card">
                <div class="consultor-header">
                    <div>
                        <h2>Mapa de pandillas</h2>
                        <p>
                            Visualiza la ubicación aproximada de las pandillas registradas y filtra por zona
                            para análisis rápido.
                        </p>
                    </div>
                    <span class="consultor-pill">
                        Mapa activo
                    </span>
                </div>

                <div class="consultor-filter">
                    <label for="zona">Zona:</label>
                    <select id="zona" onchange="filterByZone()">
                        <option value="todas">Todas las zonas</option>
                        <option value="Centro">Centro</option>
                        <option value="Este">Este</option>
                        <option value="Oeste">Oeste</option>
                        <option value="Norte">Norte</option>
                        <option value="Sur">Sur</option>
                    </select>
                </div>

                <div id="map"></div>

                <div class="consultor-links">
                    <a href="modulo_pandillas.php" class="link-pill">
                        <span class="link-pill-label">Módulo Pandillas</span>
                    </a>
                    <a href="cambiar_contra.html" class="link-pill">
                        <span class="link-pill-label">Cambiar contraseña</span>
                    </a>
                    <a href="form_error.html" class="link-pill">
                        <span class="link-pill-label">Reportar error</span>
                    </a>
                    <a href="estadisticas.php" class="link-pill">
                        <span class="link-pill-label">Ver Estadísticas</span>
                    </a>
                    <a href="modulo_incidentes.php" class="link-pill">
                        <span class="link-pill-label">Captura de Incidentes</span>
                    </a>
                </div>
            </section>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 Criminal Nexus. |
                <a target="_blank" href="http://upslp.edu.mx/">Universidad Politécnica de San Luis Potosí</a>
            </p>
        </footer>
    </div>

    <script>
        let map;
        let markers = [];

        const zonas = {
            Centro: { lat: 22.1565, lng: -100.9850 },
            Este:   { lat: 22.1800, lng: -100.9000 },
            Oeste:  { lat: 22.1400, lng: -101.0500 },
            Norte:  { lat: 22.2100, lng: -100.9500 },
            Sur:    { lat: 22.1300, lng: -100.9600 }
        };

        function initMap() {
            const slpCenter = { lat: 22.1565, lng: -100.9850 };

            map = new google.maps.Map(document.getElementById('map'), {
                center: slpCenter,
                zoom: 12
            });

            loadPandillasData('todas');
        }

        function clearMarkers() {
            markers.forEach(marker => marker.setMap(null));
            markers = [];
        }

        function loadPandillasData(zone) {
            clearMarkers();

            let url = 'obtener_pandillas.php';
            if (zone !== 'todas') {
                url += '?zona=' + encodeURIComponent(zone);
            }

            fetch(url)
                .then(response => response.json())
                .then(pandillas => {
                    if (!Array.isArray(pandillas)) {
                        console.error('Respuesta inválida:', pandillas);
                        return;
                    }

                    pandillas.forEach(pandilla => {
                        const lat = parseFloat(pandilla.latitud);
                        const lng = parseFloat(pandilla.longitud);

                        if (isNaN(lat) || isNaN(lng)) {
                            console.error('Latitud/Longitud inválidas:', pandilla);
                            return;
                        }

                        const marker = new google.maps.Marker({
                            position: { lat, lng },
                            map: map,
                            title: pandilla.nombre || ''
                        });

                        const contenido = `
                            <h3>${pandilla.nombre || ''}</h3>
                            <p><strong>Descripción:</strong> ${pandilla.descripcion || ''}</p>
                            <p><strong>Líder:</strong> ${pandilla.lider || ''}</p>
                            <p><strong>Integrantes aprox.:</strong> ${pandilla.numero_aproximado_de_integrantes || ''}</p>
                            <p><strong>Peligrosidad:</strong> ${pandilla.peligrosidad || ''}</p>
                            <p><strong>Zona:</strong> ${pandilla.zona || ''}</p>
                            <p><strong>Colonia:</strong> ${pandilla.colonia || ''}</p>
                        `;

                        const infoWindow = new google.maps.InfoWindow({ content: contenido });

                        marker.addListener('click', () => {
                            infoWindow.open(map, marker);
                        });

                        markers.push(marker);
                    });
                })
                .catch(err => {
                    console.error('Error al cargar pandillas:', err);
                });
        }

        function filterByZone() {
            const selectedZone = document.getElementById('zona').value;

            if (selectedZone !== 'todas' && zonas[selectedZone]) {
                map.setCenter(zonas[selectedZone]);
                map.setZoom(13);
            } else {
                map.setCenter({ lat: 22.1565, lng: -100.9850 });
                map.setZoom(12);
            }

            loadPandillasData(selectedZone);
        }

        window.initMap = initMap;
    </script>
</body>
</html>

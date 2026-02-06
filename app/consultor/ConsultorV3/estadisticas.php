<?php
session_start();

// Restringir a consultor
if (!isset($_SESSION['usuario']) || (($_SESSION['tipo_usuario'] ?? '') !== 'consultor')) {
    header('Location: ../../index.html');
    exit();
}

$usuario = $_SESSION['usuario'];

require __DIR__ . '/../../php/conexion.php';

// 1. Datos para gráfica de Pandillas por Zona
$sqlZonas = "
    SELECT u.zona, COUNT(p.id_Pandilla) as total
    FROM pandillas p
    JOIN ubicacion u ON p.id_Pandilla = u.id_Pandilla
    GROUP BY u.zona
";
$resZonas = mysqli_query($conexion, $sqlZonas);
$zonasLabels = [];
$zonasData = [];

while ($row = mysqli_fetch_assoc($resZonas)) {
    $zonasLabels[] = $row['zona'] ?: 'Sin Zona';
    $zonasData[] = $row['total'];
}

// 2. Datos para gráfica de Peligrosidad
$sqlPeligrosidad = "
    SELECT peligrosidad, COUNT(*) as total
    FROM pandillas
    GROUP BY peligrosidad
";
$resPeligrosidad = mysqli_query($conexion, $sqlPeligrosidad);
$peligrosidadLabels = [];
$peligrosidadData = [];

while ($row = mysqli_fetch_assoc($resPeligrosidad)) {
    $peligrosidadLabels[] = $row['peligrosidad'] ?: 'No definida';
    $peligrosidadData[] = $row['total'];
}

mysqli_close($conexion);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estadísticas - Criminal Nexus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../login.css">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .chart-card {
            background: rgba(15, 23, 42, 0.97);
            border: 1px solid rgba(55, 65, 81, 0.95);
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .chart-header {
            margin-bottom: 15px;
            color: #f9fafb;
            font-size: 1.1rem;
            font-weight: 600;
            text-align: center;
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
        
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 999px;
            background: rgba(55, 65, 81, 0.5);
            color: #e5e7eb;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        
        .btn-back:hover {
            background: rgba(55, 65, 81, 0.8);
        }
    </style>
</head>
<body>
    <div class="app-shell">
        <header class="app-header">
            <div class="app-header-left">
                <h1 class="app-title">Panel de consultor</h1>
                <p class="app-subtitle">
                    Estadísticas generales.
                </p>
            </div>
            <div class="app-header-right">
                <span class="badge">Consultor</span>
                <a href="../../home2.php" class="btn-ghost">Volver</a>
            </div>
        </header>

        <main class="app-main">
            <div class="consultor-header">
                <div>
                    <h2>Resumen Estadístico</h2>
                    <p>Visualización de datos clave sobre las pandillas registradas.</p>
                </div>
            </div>

            <div class="stats-container">
                <!-- Gráfica 1: Pandillas por Zona -->
                <div class="chart-card">
                    <div class="chart-header">Pandillas por Zona</div>
                    <canvas id="chartZonas"></canvas>
                </div>

                <!-- Gráfica 2: Peligrosidad -->
                <div class="chart-card">
                    <div class="chart-header">Distribución por Peligrosidad</div>
                    <canvas id="chartPeligrosidad"></canvas>
                </div>
            </div>
        </main>

        <footer class="site-footer">
            <p>&copy; 2025 Criminal Nexus. |
                <a target="_blank" href="http://upslp.edu.mx/">Universidad Politécnica de San Luis Potosí</a>
            </p>
        </footer>
    </div>

    <script>
        // Configuración común para estilo oscuro
        Chart.defaults.color = '#9ca3af';
        Chart.defaults.borderColor = 'rgba(55, 65, 81, 0.5)';

        // 1. Gráfica de Zonas (Barras)
        const ctxZonas = document.getElementById('chartZonas').getContext('2d');
        new Chart(ctxZonas, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($zonasLabels); ?>,
                datasets: [{
                    label: 'Número de Pandillas',
                    data: <?php echo json_encode($zonasData); ?>,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.7)', // Blue
                        'rgba(16, 185, 129, 0.7)', // Green
                        'rgba(245, 158, 11, 0.7)', // Amber
                        'rgba(239, 68, 68, 0.7)',  // Red
                        'rgba(139, 92, 246, 0.7)'  // Purple
                    ],
                    borderColor: [
                        'rgba(59, 130, 246, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(239, 68, 68, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });

        // 2. Gráfica de Peligrosidad (Doughnut)
        const ctxPeligrosidad = document.getElementById('chartPeligrosidad').getContext('2d');
        new Chart(ctxPeligrosidad, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($peligrosidadLabels); ?>,
                datasets: [{
                    data: <?php echo json_encode($peligrosidadData); ?>,
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.7)', // Baja - Green
                        'rgba(245, 158, 11, 0.7)', // Media - Amber
                        'rgba(239, 68, 68, 0.7)',  // Alta - Red
                        'rgba(107, 114, 128, 0.7)' // Otro - Gray
                    ],
                    borderColor: 'rgba(15, 23, 42, 1)',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>

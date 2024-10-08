<?php
//KLASEAK ETA KONEXIOAK GEHITU
session_start();
require 'conexion.php';
require 'models/Usuario.php';
require 'models/Kurtsoa.php'; 

//LOGEATUTA EZ BADAGO LOGINERA BIDERATU SEGURTASUNA BERMATZEKO
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

//ERABILTZAILEAREN INFORMAZIOA HARTU SESIOTIK
$user_type = $_SESSION['user_type'];
$user_id = $_SESSION['user_id'];

// DATUBASERA KONEKTATU
$db = new Conexion();
$conn = $db->getConnection();//KLASEAREN GET CONNCTION METODOA ERABILI KONEKTATZEKO
$usuario = new Usuario($conn);
$kurtso = new Kurtsoa($conn);

// USUARIO ETA KURTSOEN LISTAK SORTU
$usuarios = [];
$kurtsoak = [];
$currentUserName = ""; // USERNAME ALDAGAIA INIZIALIZATU

if ($user_type === 'admin') {
    // ADMINA BADA USUARIOAK HARTU
    $usuarios = $usuario->getAll();
    foreach ($usuarios as $user) {
        if ($user['id'] == $user_id) {
            $currentUserName = $user['izena']; // USUARIOAREN IZENA GORDE
            break; // USUARIOA LORTZERAKOAN FOR-ETIK ATERA
        }
    }
} else {
    $usuarios = $usuario->getAll();

    //ORAINGO USUARIOA HARTU
    foreach ($usuarios as $user) {
        if ($user['id'] == $user_id) {
            $currentUserName = $user['izena']; // USUARIOAREN IZENA GORDE
            break; // USUARIOA LORTZERAKOAN FOR-ETIK ATERA
    }
}
}
// KURTSO GUZTIAK LORTU
$kurtsoak = $kurtso->getAll();
$usuariosMatriculadosPorKurtso = []; // KURTSO BAKOIZTAKO USUARIOAK GORDETZEKO ARRAY-A INIZIALIZATU

foreach ($kurtsoak as $kurtso) {
    //DATUBASE QUERY-A PARAMETROEKIN SEGURTASUNA BERMATZEKO
    $query = "SELECT u.id, u.izena, u.abizena, u.email 
              FROM usuariokurtsoak uk 
              JOIN usuarios u ON uk.usuario_id = u.id 
              WHERE uk.kurtso_id = :kurtso_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':kurtso_id', $kurtso['id']);
    $stmt->execute();
    $usuariosMatriculadosPorKurtso[$kurtso['id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// USUARIOA MATRIKULATUTA DAGOEN KURTSOA LORTU
$query = "SELECT kurtso_id FROM usuariokurtsoak WHERE usuario_id = :usuario_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':usuario_id', $user_id);
$stmt->execute();
$usuarioMatriculadoKurtsoId = $stmt->fetchColumn(); // USUARIOA MATRIKULATUTA DAGOEN KURTSOAREN ID-A LORTU
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/dashboard.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet"> <!-- FONT AWESOME SARE SOZIALEN IKONOAK -->
</head>

<body>
    <!-- ALERTA BAT BALDIN BADAGO ERAKUTSI -->
    <?php if (isset($_GET['message'])): ?>
        <div class="message">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>
    <div class="bodyContent">
        <div class="dashboard-container">
            <!-- INDEXERA BUELTZTZEKO ETXE IKONOA -->
            <a href="index.php" class="home-icon"> <!-- INDEX LINKA -->
                <i class="fas fa-home"></i>
            </a>
            <h2>Kaixo, <?php echo htmlspecialchars($currentUserName); ?></h2> <!-- SESIOAREN IZENA ERABILI USUARIOA AGURTZEKO-->
            <?php if ($user_type === 'admin'): ?>
            <!-- ADMINA BADA DAGOKIONA ERAKUTSI PANTAILAN -->
             <!-- COLLAPSIVE CONTENT SORTU, NAHI DUGUNA ERRAZAGO BILATZEKO -->
                <h3 class="collapsible-header" onclick="toggleCollapsible(this)">Erabiltzaile gestioa</h3>
<div class="collapsible-content">
    <table>
        <thead>
            <tr>
                <th>Izena</th>
                <th>Abizena</th>
                <th>Email</th>
                <th>Pasahitza (Hasheatuta)</th>
                <th>Erabiltzaile Mota</th>
                <th>Akzioak</th>
            </tr>
        </thead>
        <tbody>
            <!-- USUARIOEN TABLA ERAKUTSI -->
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <!-- SPECIALCHARS ERABILI KARAKTERE ESPEZIALAK EZABATZEKO SEGURTASUNA BERMATZEKO -->
                    <td><?php echo htmlspecialchars($usuario['izena']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['abizena']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['pasahitza']); ?></td> <!-- Contraseña hasheada -->
                    <td><?php echo htmlspecialchars($usuario['mota']); ?></td>
                    <td>
                        <!-- UPDATE DELETE KONTROLADOREETARA BIDERAKETAK -->
                        <a href="updateUsuarioa.php?id=<?php echo $usuario['id']; ?>">Update</a>
                        <a href="deleteUsuarioa.php?id=<?php echo $usuario['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- USUARIOA ERREGISTRATZERA BIDERATU -->
    <a href="register.php" class="usuarioaErregistratu">Usuarioa Erregistratu</a>
</div>
<!-- HURRENGO COLLAPSIVE-A -->
<h3 class="collapsible-header" onclick="toggleCollapsible(this)">Kurtso gestioa</h3>
<div class="collapsible-content">
    <!-- FKURTSOEN TAULA SORTU -->
    <table>
        <thead>
            <tr>
                <th>Izena</th>
                <th>Deskripzioa</th>
                <th>Akzioak</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($kurtsoak as $kurtso): ?>
                <tr>
                    <td><?php echo htmlspecialchars($kurtso['izena']); ?></td>
                    <td><?php echo htmlspecialchars($kurtso['deskripzioa']); ?></td>
                    <td>
                        <!-- UPDATE DELETE BOTOIAK-->
                        <a href="updateKurtsoa.php?id=<?php echo $kurtso['id']; ?>">Update</a>
                        <a href="deleteKurtsoa.php?id=<?php echo $kurtso['id']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <!-- KURTSO BERRI BAT GEHITZEKO BOTOIA -->
    <a href="kurtsoaGehitu.php" class="kurtsoaGehitu">Kurtsoa gehitu</a>
</div>
<!-- MATRIKULAZIOEN COLLAPSIVE-A -->
<h3 class="collapsible-header" onclick="toggleCollapsible(this)">Kurtso bakoitzeko matrikulak</h3>
<div class="collapsible-content">
    <?php foreach ($kurtsoak as $kurtso): ?>
        <h4><?php echo htmlspecialchars($kurtso['izena']); ?></h4>
        <table>
            <thead>
                <tr>
                    <th>Izena</th>
                    <th>Abizena</th>
                    <th>Email</th>
                    <th>Akzioak</th>
                </tr>
            </thead>
            <tbody>
                <!-- MATRIKULAZIOEN TAULA DAGOKION INFORMAZIOAREKIN -->
                 <!-- MATRIKULAZIO ERREGISTRORIK BADAUDE... -->
                <?php if (!empty($usuariosMatriculadosPorKurtso[$kurtso['id']])): ?>

                    <?php foreach ($usuariosMatriculadosPorKurtso[$kurtso['id']] as $usuarioMatriculado): ?>
                        <tr>
                            <!-- FONT HTMLSPECIALCHARS SEGURTASUN BERMAKETA -->
                            <td><?php echo htmlspecialchars($usuarioMatriculado['izena']); ?></td>
                            <td><?php echo htmlspecialchars($usuarioMatriculado['abizena']); ?></td>
                            <td><?php echo htmlspecialchars($usuarioMatriculado['email']); ?></td>
                            <td>
                                <!-- MATRIKULA EZABATZEKO BTOIAK USUARIOKO -->
                                <form method="POST" action="matrikulaEzabatu.php">
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuarioMatriculado['id']; ?>">
                                    <input type="hidden" name="kurtso_id" value="<?php echo $kurtso['id']; ?>">
                                    <button type="submit">Matrikula ezabatu</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <!-- MATRIKULAZIOAK EZ BADAUDE MEZUA AGERTU TAULAN -->
                    <tr>
                        <td colspan="4">Ez daude matrikulaziorik kurtso honetan.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    <?php endforeach; ?>
</div>
<!-- USUARIOA EZ BADA ADMIN MOTAKOA KURTSOEN TAULA AGERTU BAKARRIK -->
            <?php else: ?>
                <h3>Kurtsoak</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Izena</th>
                            <th>Deskripzioa</th>
                            <th>Akzioak</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- KURTSO GUZTIAK BISTARATU -->
                        <?php foreach ($kurtsoak as $kurtso): ?>
                            <tr>
                                <!-- SPECIALCHARS ERABILITA SEGURTASUNA BERMATZEKO -->
                                <td><?php echo htmlspecialchars($kurtso['izena']); ?></td>
                                <td><?php echo htmlspecialchars($kurtso['deskripzioa']); ?></td>
                                <td>
                                    <form method="POST" action="matrikulatu.php">
                                        <input type="hidden" name="kurtso_id" value="<?php echo $kurtso['id']; ?>">
                                        <!-- MATRIKULATUTA BADAGO EDO EZ BADAGO KONPROBATU ETA DAGOKION BOTOIAREN KLASEAK ETA CSS APLIKATU -->
                                        <button type="submit"
                                            class="<?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'btn-matriculado' : 'btn-matricular'; ?>"
                                            <?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'disabled' : ''; ?>>
                                            <?php echo ($usuarioMatriculadoKurtsoId == $kurtso['id']) ? 'Matrikulatuta!' : 'Matrikulatu'; ?>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
<!-- COLLAPSIVEAK ERAKUTSI ETA EZKUTATU -->
<script>
    function toggleCollapsible(header) {
        var content = header.nextElementSibling; // Obtener el contenido colapsable
        if (content.style.display === "block") {
            content.style.display = "none"; // Ocultar si está visible
        } else {
            content.style.display = "block"; // Mostrar si está oculto
        }
    }
</script>

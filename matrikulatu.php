<?php
//SESIOA HASI $_SESSION ALDAGAIAK ERABILI AHAL IZATEKO
session_start();
require 'conexion.php';

// LOGEATUTA EZ BADAGO LOGINEARA BIDALI
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// USUARIOAREN ID ETA KURTSO ID-AK HARTU
$usuario_id = $_SESSION['user_id'];
$kurtso_id = $_POST['kurtso_id'];

// DATUBASE KONEXIOA
$db = new Conexion();
$conn = $db->getConnection();

// USUARIOA BESTE KURTSOREN BATEAN MATRIKULATUTA BADAGO KONPROBARU
$query = "SELECT * FROM usuariokurtsoak WHERE usuario_id = :usuario_id";
$stmt = $conn->prepare($query);
$stmt->bindParam(':usuario_id', $usuario_id);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // MATRIKULATUTA BADAGO ERROREA IKUSI
    echo "<script>alert('Kurtso batean matrikulatuta zaude, bakarrik batean matrikulatu ahal zara urtero, errorea bada eskatu administrariari.'); window.location.href='index.php';</script>";
    exit;
} else {
    // EZ BADAGO MATRUKULATUTA QUERYA PRESTATU
    $query = "INSERT INTO usuariokurtsoak (usuario_id, kurtso_id) VALUES (:usuario_id, :kurtso_id)";
    //PARAMETROAK ERABILITA SEGURTASUNA BERMATZEKO
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':usuario_id', $usuario_id);
    $stmt->bindParam(':kurtso_id', $kurtso_id);
    //QUERY-A EXEKUTATU
    if ($stmt->execute()) {
        // QUERYA ERRORE BARIK EXEKUTZATZEN BADA, POPUP BAT RAKUTSI, HAREN HTMLA ETA CSS JAVASCRIPT ERABILIZ.
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    let popup = document.createElement('div');
                    popup.style.position = 'fixed';
                    popup.style.top = '50%';
                    popup.style.left = '50%';
                    popup.style.transform = 'translate(-50%, -50%)';
                    popup.style.backgroundColor = '#4CAF50'; /* Verde */
                    popup.style.padding = '20px';
                    popup.style.color = 'white';
                    popup.style.borderRadius = '5px';
                    popup.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
                    popup.innerHTML = 'Matrikulazioa ondo joan da! Ongi etorri Uni RHEM-era, pasatu idazkaritzatik informazio gehiago lortzeko.';

                    let closeButton = document.createElement('button');
                    closeButton.innerText = 'Itxi';
                    closeButton.style.marginTop = '10px';
                    closeButton.style.marginLeft = '10px';
                    closeButton.style.padding = '5px 10px';
                    closeButton.style.backgroundColor = 'white';
                    closeButton.style.color = '#4CAF50';
                    closeButton.style.border = 'none';
                    closeButton.style.borderRadius = '3px';
                    closeButton.style.cursor = 'pointer';

                    closeButton.addEventListener('click', function() {
                        popup.remove();
                        window.location.href = 'index.php';
                    });

                    popup.appendChild(closeButton);
                    document.body.appendChild(popup);
                });
              </script>";
    } else {
        // ERROREA EMATEN BADU QUERY-A ERROREA AZALDU
        echo "<script>alert('Errorea matrikulatzerakoan.'); window.location.href='index.php';</script>";
    }
}
?>

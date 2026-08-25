<?php
// modules/medicine/delete_medicine.php
require_once "../../includes/auth_check.php";
require_once "../../config/database.php";

$id = $_GET['id'] ?? null;

if ($id) {
    // លុប hình រូបភាពចាស់បើមិនមែនជា default.png
    $stmt = $pdo->prepare("SELECT image FROM medicines WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $med = $stmt->fetch();
    
    if ($med && $med['image'] !== 'default.png') {
        @unlink("../../assets/uploads/medicine_images/" . $med['image']);
    }

    $stmt = $pdo->prepare("DELETE FROM medicines WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $_SESSION['success'] = "លុបទិន្នន័យថ្នាំបានជោគជ័យ!";
}

header("Location: medicine_list.php");
exit();
?>
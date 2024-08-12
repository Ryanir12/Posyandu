<?php
include '../../koneksi.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT nama_ibu FROM orang_tua WHERE no = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        echo json_encode($row);
    } else {
        echo json_encode(['nama_ibu' => '']);
    }
} else {
    echo json_encode(['nama_ibu' => '']);
}

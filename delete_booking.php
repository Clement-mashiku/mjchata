<?php
// delete_booking.php
include 'db_connect.php';

// Get the raw DELETE data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['id'])) {
    echo json_encode(["success" => false, "message" => "Booking ID is required."]);
    exit();
}

$id = $conn->real_escape_string($data['id']);

// SQL to delete booking
$sql = "DELETE FROM bookings WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Booking deleted successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "No booking found with that ID."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error deleting booking: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

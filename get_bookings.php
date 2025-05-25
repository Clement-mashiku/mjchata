<?php
// get_bookings.php
include 'db_connect.php';

$current_time = date('Y-m-d H:i:s'); // Get current time in MySQL format

// SQL to select valid (non-expired) bookings, ordered by start time
$sql = "SELECT id, ground_type, ground_name, captain_name, course_name, phone_number, start_time, end_time
        FROM bookings
        WHERE end_time > ?
        ORDER BY start_time ASC";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("s", $current_time);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $bookings = [];
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    echo json_encode(["success" => true, "bookings" => $bookings]);
} else {
    echo json_encode(["success" => false, "message" => "Error fetching bookings: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

<?php
// add_booking.php
include 'db_connect.php';

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['groundType'], $data['captainName'], $data['courseName'], $data['phoneNumber'], $data['startTime'], $data['endTime'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit();
}

$id = uniqid(); // Generate a unique ID for the booking
$groundType = $conn->real_escape_string($data['groundType']);
$groundName = isset($data['groundName']) ? $conn->real_escape_string($data['groundName']) : null;
$captainName = $conn->real_escape_string($data['captainName']);
$courseName = $conn->real_escape_string($data['courseName']);
$phoneNumber = $conn->real_escape_string($data['phoneNumber']);
$startTime = $conn->real_escape_string($data['startTime']);
$endTime = $conn->real_escape_string($data['endTime']);

// SQL to insert new booking
$sql = "INSERT INTO bookings (id, ground_type, ground_name, captain_name, course_name, phone_number, start_time, end_time)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    $conn->close();
    exit();
}

// 's' for string, 's' for string, 's' for string (ground_name can be null), etc.
$stmt->bind_param("ssssssss", $id, $groundType, $groundName, $captainName, $courseName, $phoneNumber, $startTime, $endTime);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Booking added successfully!", "bookingId" => $id]);
} else {
    echo json_encode(["success" => false, "message" => "Error adding booking: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

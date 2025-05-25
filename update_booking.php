<?php
// update_booking.php
include 'db_connect.php';

// Get the raw PUT data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['id'], $data['groundType'], $data['captainName'], $data['courseName'], $data['phoneNumber'], $data['startTime'], $data['endTime'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit();
}

$id = $conn->real_escape_string($data['id']);
$groundType = $conn->real_escape_string($data['groundType']);
$groundName = isset($data['groundName']) ? $conn->real_escape_string($data['groundName']) : null;
$captainName = $conn->real_escape_string($data['captainName']);
$courseName = $conn->real_escape_string($data['courseName']);
$phoneNumber = $conn->real_escape_string($data['phoneNumber']);
$startTime = $conn->real_escape_string($data['startTime']);
$endTime = $conn->real_escape_string($data['endTime']);

// SQL to update booking
$sql = "UPDATE bookings SET
            ground_type = ?,
            ground_name = ?,
            captain_name = ?,
            course_name = ?,
            phone_number = ?,
            start_time = ?,
            end_time = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . $conn->error]);
    $conn->close();
    exit();
}

$stmt->bind_param("ssssssss", $groundType, $groundName, $captainName, $courseName, $phoneNumber, $startTime, $endTime, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Booking updated successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "No booking found with that ID or no changes made."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Error updating booking: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>

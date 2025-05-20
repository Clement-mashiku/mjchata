
<?php

// Database connection details
$servername = "localhost"; // Usually localhost for XAMPP
$username = "root";     // Default MySQL username in XAMPP
$password = "";         // Default MySQL password in XAMPP is usually empty
$dbname = "football_bookings"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get data from the form
    $team_name = $_POST["team_name"];
    $contact_person = $_POST["contact_person"];
    $ground_id = $_POST["ground_id"];
    $start_time = $_POST["start_time"];
    $end_time = $_POST["end_time"];

    // Basic validation (you might want to add more robust validation later)
    if (empty($team_name) || empty($contact_person) || empty($ground_id) || empty($start_time) || empty($end_time)) {
        echo "All fields are required.";
    } else {
        // Prepare SQL statement to insert data into the bookings table
        $sql = "INSERT INTO bookings (ground_id, start_time, end_time, team_name, contact_person)
                VALUES (?, ?, ?, ?, ?)";

        // Use prepared statement to prevent SQL injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issss", $ground_id, $start_time, $end_time, $team_name, $contact_person);

        if ($stmt->execute()) {
            // Redirect to the confirmation page upon successful booking
            header("Location: confirmation.html");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();

?>

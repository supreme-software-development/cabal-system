<?php
declare(strict_types=1);

session_start();

ini_set('error_log', __DIR__ . '/logs/error.log');
if (!file_exists(__DIR__ . '/logs')) {
    mkdir(__DIR__ . '/logs', 0777, true);
}

// Load database configuration
$config = require_once __DIR__ . '/config/database.php';

if (!extension_loaded('sqlsrv')) {
    throw new Exception(
        "SQL Server Driver for PHP is not installed. Please install the sqlsrv extension:\n" .
        "For Windows: Enable php_sqlsrv.dll in php.ini\n" .
        "For Linux: Install via PECL: pecl install sqlsrv\n" .
        "More info: https://learn.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac"
    );
}

function sanitize_input(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Initialize response array
$response = [
    'success' => false,
    'message' => '',
    'redirect' => ''
];

try {
    // Check if the request method is POST
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        throw new Exception("Invalid request method");
    }

    // Validate and sanitize inputs
    $username = isset($_POST['username']) ? sanitize_input($_POST['username']) : '';
    $email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validate username
    if (empty($username)) {
        throw new Exception("Username is required");
    }
    if (strlen($username) < 3 || strlen($username) > 50) {
        throw new Exception("Username must be between 3 and 50 characters");
    }

    // Validate username format
    if (!preg_match('/^[a-zA-Z0-9_-]{3,50}$/', $username)) {
        throw new Exception("Username can only contain letters, numbers, underscores and hyphens");
    }

    // Validate email
    if (empty($email)) {
        throw new Exception("Email is required");
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }

    // Additional email validation
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        throw new Exception("Invalid email format");
    }

    // Validate password
    if (empty($password)) {
        throw new Exception("Password is required");
    }
    if (strlen($password) < 8) {
        throw new Exception("Password must be at least 8 characters long");
    }
    if ($password !== $confirm_password) {
        throw new Exception("Passwords do not match");
    }

    // Simple password check - only alphanumeric characters
    if (!preg_match('/^[a-zA-Z0-9]{8,}$/', $password)) {
        throw new Exception("Password can only contain letters and numbers");
    }

    // Connect to database with better error reporting
    try {
        $conn = sqlsrv_connect($config['db_host'], array(
            "Database" => $config['db_name'],
            "UID" => $config['db_user'],
            "PWD" => $config['db_pass']
        ));
        
        if (!$conn) {
            throw new Exception("Connection failed: " . print_r(sqlsrv_errors(), true));
        }
    } catch (Exception $e) {
        throw new Exception("Database connection error: " . $e->getMessage() . 
                          " (Host: {$config['db_host']}, Database: {$config['db_name']})");
    }

    // Call the stored procedure for registration
    $proc_query = "{CALL cabal_tool_registerAccount_web(?, ?, ?)}";
    $params = array(
        array($username, SQLSRV_PARAM_IN),
        array($email, SQLSRV_PARAM_IN),
        array($password, SQLSRV_PARAM_IN)
    );
    
    $stmt = sqlsrv_prepare($conn, $proc_query, $params);
    
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . print_r(sqlsrv_errors(), true));
    }
    
    if (sqlsrv_execute($stmt) === false) {
        $errors = sqlsrv_errors();
        if ($errors) {
            foreach ($errors as $error) {
                switch ($error['code']) {
                    case 50001:
                        throw new Exception("Username already exists");
                    case 50002:
                        throw new Exception("Email already exists");
                    case 50000:
                        throw new Exception('Username or Email is already exists.');
                    default:
                        throw new Exception("Error registering user: " . $error['message']);
                }
            }
        }
        throw new Exception("Error registering user: " . print_r(sqlsrv_errors(), true));
    }

    $response['success'] = true;
    $response['message'] = "Registration successful!<br>Please login in the Game.";
    $response['redirect'] = "index.php";

    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    $response['details'] = [
        'error' => $e->getMessage()
    ];
    error_log("Registration error: " . $e->getMessage() . 
              "\nFile: " . $e->getFile() . 
              "\nLine: " . $e->getLine() . 
              "\nTrace: " . $e->getTraceAsString());
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response, JSON_THROW_ON_ERROR);
?>
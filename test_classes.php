<?php
/**
 * Test class loading
 * Run: php test_classes.php
 */

require_once 'bootstrap.php';

echo "=== Class Loading Test ===\n\n";

// Test 1: Database class
echo "1. Testing Database class...\n";
try {
    if (class_exists('Database')) {
        $db = new Database();
        echo "   ✓ Database class loaded\n";
        if ($db->testConnection()) {
            echo "   ✓ Database connection works\n";
        } else {
            echo "   ✗ Database connection failed\n";
        }
    } else {
        echo "   ✗ Database class not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n";
}

// Test 2: Contact Model
echo "\n2. Testing Contact model...\n";
try {
    if (class_exists('Models\Contact')) {
        $contact = new Models\Contact();
        echo "   ✓ Contact model loaded\n";
    } else {
        echo "   ✗ Contact model not found\n";
        echo "   - Check if Models/Contact.php exists\n";
        echo "   - Check if namespace is correct\n";
    }
} catch (Exception $e) {
    echo "   ✗ Contact model error: " . $e->getMessage() . "\n";
}

// Test 3: EmailService
echo "\n3. Testing EmailService...\n";
try {
    if (class_exists('Services\EmailService')) {
        $emailService = new Services\EmailService();
        echo "   ✓ EmailService loaded\n";
    } else {
        echo "   ✗ EmailService not found\n";
        echo "   - Check if Services/EmailService.php exists\n";
    }
} catch (Exception $e) {
    echo "   ✗ EmailService error: " . $e->getMessage() . "\n";
}

// Test 4: Controllers
echo "\n4. Testing Controllers...\n";
try {
    if (class_exists('Controllers\ContactController')) {
        echo "   ✓ ContactController found\n";
    } else {
        echo "   ✗ ContactController not found\n";
    }
    
    if (class_exists('BaseController\BaseController')) {
        echo "   ✓ BaseController found\n";
    } else {
        echo "   ✗ BaseController not found\n";
    }
} catch (Exception $e) {
    echo "   ✗ Controller error: " . $e->getMessage() . "\n";
}

// Test 5: File structure
echo "\n5. Checking file structure...\n";
$requiredFiles = [
    'Controllers/ContactController.php',
    'Controllers/BaseController.php',
    'Models/Contact.php',
    'Services/EmailService.php',
    'config/config.php',
    'vendor/autoload.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "   ✓ $file exists\n";
    } else {
        echo "   ✗ $file MISSING\n";
    }
}

echo "\n=== Test completed ===\n";
?>
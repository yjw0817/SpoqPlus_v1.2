<?php
// Bootstrap CodeIgniter
define('APPPATH', __DIR__ . '/app/');
define('ENVIRONMENT', 'development');

// Load CodeIgniter autoloader
require_once 'vendor/autoload.php';

// Load database config
$db = \Config\Database::connect();

echo "=== Checking Locker Data ===\n\n";

// Check locker types
echo "1. Locker Types (lockr_types):\n";
try {
    $types = $db->table('lockr_types')->where('COMP_CD', '001')->where('BCOFF_CD', '001')->get()->getResultArray();
    echo "   Found " . count($types) . " types\n";
    foreach ($types as $type) {
        echo "   - {$type['LOCKR_TYPE_NM']} (ID: {$type['LOCKR_TYPE_CD']})\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n2. Locker Zones (lockr_area):\n";
try {
    $zones = $db->table('lockr_area')->where('COMP_CD', '001')->where('BCOFF_CD', '001')->get()->getResultArray();
    echo "   Found " . count($zones) . " zones\n";
    foreach ($zones as $zone) {
        echo "   - {$zone['LOCKR_KND_NM']} (ID: {$zone['LOCKR_KND_CD']})\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n3. Lockers (lockrs):\n";
try {
    $lockers = $db->table('lockrs')->where('COMP_CD', '001')->where('BCOFF_CD', '001')->get()->getResultArray();
    echo "   Found " . count($lockers) . " lockers total\n";
    
    // Count by zone
    $zones = ['zone-1', 'zone-2', 'zone-3'];
    foreach ($zones as $zone) {
        $count = $db->table('lockrs')->where('COMP_CD', '001')->where('BCOFF_CD', '001')->where('LOCKR_KND', $zone)->countAllResults();
        echo "   - Zone $zone: $count lockers\n";
    }
    
    // Show sample locker data
    if (count($lockers) > 0) {
        echo "\n4. Sample locker data (first 3):\n";
        $samples = array_slice($lockers, 0, 3);
        foreach ($samples as $locker) {
            echo "   - ID: {$locker['LOCKR_CD']}, Type: {$locker['LOCKR_TYPE_CD']}, Zone: {$locker['LOCKR_KND']}, Position: ({$locker['X']}, {$locker['Y']})\n";
        }
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== End of Check ===\n";
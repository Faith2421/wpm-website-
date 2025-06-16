<?php
require_once 'config.php';

// Include all table handlers
require_once 'farmers.php';
require_once 'livestock.php';
require_once 'health_records.php';
require_once 'breeding_records.php';
require_once 'feeding_records.php';
require_once 'vaccination_records.php';
require_once 'mortality_records.php';
require_once 'movement_records.php';

// This would be the main entry point that coordinates between all the separate files
// You might want to modify this to call the appropriate handlers based on the form data
// or create a more sophisticated routing system
?>
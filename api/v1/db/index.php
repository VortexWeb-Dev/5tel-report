<?php

use Logger as GlobalLogger;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Logger;

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '../../../../crest/crest.php');
require_once(__DIR__ . '../../../../crest/settings.php');
require_once(__DIR__ . '../../../../utils/index.php');

require_once(__DIR__ . '../../../../db/db.php');
require_once(__DIR__ . '../../../../models/transaction.php');
require_once(__DIR__ . '../../../../utils/logger.php');

// Handle CORS if needed
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Log request details for debugging
error_log("API Request received: " . $_SERVER['REQUEST_URI']);
error_log("Query parameters: " . print_r($_GET, true));

// Get query parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
$offset = ($page - 1) * $limit;

// Initialize filter array for Bitrix
$filter = [];

// Handle filters from query parameters
$filterFields = ['mid', 'dba'];
foreach ($filterFields as $field) {
    if (!empty($_GET[$field])) {
        $operator = $_GET[$field . '_operator'] ?? 'equals';

        switch ($operator) {
            case 'contains':
                $filter[$field . '|ILIKE'] = '%' . $_GET[$field] . '%';
                break;
            case 'starts with':
                $filter[$field . '|ILIKE'] = $_GET[$field] . '%';
                break;
            default: // equals
                $filter[$field . '|='] = $_GET[$field];
        }
    }
}

if (isset($_GET['statement_month'], $_GET['statement_year']) && !empty($_GET['statement_month']) && !empty($_GET['statement_year'])) {
    $dateArray = [];
    for ($i = 1; $i <= 30; $i++) {
        if ($i < 10) {
            $dateElemet = $_GET['statement_year'] . '0' . $i . ' ' . $_GET['statement_month'];
            array_push($dateArray, $dateElemet);
        } else {
            $dateElemet = $_GET['statement_year'] . $i . ' ' .  $_GET['statement_month'];
            array_push($dateArray, $dateElemet);
        }
    }
    $filter['statement_month|IN'] = $dateArray;
}

// echo json_encode($filter);

$order = [];

// handle order from query parameters
$orderFields = ['statement_month_order', 'mid_order', 'dba_order', 'sales_volume_order', 'sales_trxn_order', 'commission_order', 'responsible_person_order', 'earnings_local_currency_order'];
foreach ($orderFields as $field) {
    if (isset($_GET[$field]) && !empty($_GET[$field])) {
        $cur_order = $_GET[$field] === 'asc' ? 'asc' : 'desc';
        $field = str_replace('_order', '', $field);
        $order[$field] = $cur_order;
    }
}

try {
    // Call to Bitrix24 REST API
    $actualFIlter = isAdmin() ? $filter : [...$filter, 'responsible_person_bitrix_id|=' => getCurrentUser()];
    $select = ['id', 'statement_month', 'mid', 'dba', 'sales_volume', 'sales_trxn', 'earnings_local_currency', 'commission', 'responsible_person', 'responsible_person_bitrix_id'];
    $config = require(__DIR__ . '../../../../config/config.php');
    $db = new Database($config['db']);
    $logger = new GlobalLogger();
    $transaction = new Transaction($db, $logger);
    $transactions = $transaction->getAll($limit, $offset, $select, $actualFIlter, $order);
    // echo json_encode($transactions['transactions']);

    $response = [
        'data' => array_map(function ($data) {
            return [
                'statement_month' => $data['statement_month'],
                'mid' => $data['mid'],
                'dba' => $data['dba'],
                'sales_volume' => $data['sales_volume'],
                'sales_trxn' => $data['sales_trxn'],
                'commission_amount' => $data['commission'],
                'responsible_person' => $data['responsible_person'],
                'earnings' => $data['earnings_local_currency'],
            ];
        }, $transactions['transactions']),
        'total' => $transactions['total_count'],
        'total_earnings' => $transactions['total_earnings'],
        'total_commission' => $transactions['total_commission'],
        'from' => $offset + 1,
        'to' => min($offset + $limit, $transactions['total_count']),
        'current_page' => $page,
        'per_page' => $limit,
        'last_page' => ceil($transactions['total_count'] / $limit)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

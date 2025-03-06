<?php

header('Content-Type: application/json');

use Logger as GlobalLogger;

require_once __DIR__ . '/../../../crest/crest.php';
require_once __DIR__ . '/../../../crest/settings.php';
require_once __DIR__ . '/../../../utils/index.php';
require_once __DIR__ . '/../../../db/db.php';
require_once __DIR__ . '/../../../models/transaction.php';
require_once __DIR__ . '/../../../utils/logger.php';

const DEFAULT_PAGE = 1;
const DEFAULT_PER_PAGE = 50;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

error_log("API Request: " . $_SERVER['REQUEST_URI']);
error_log("Query Params: " . json_encode($_GET));

$page = (int)($_GET['page'] ?? DEFAULT_PAGE);
$limit = (int)($_GET['per_page'] ?? DEFAULT_PER_PAGE);
$offset = ($page - 1) * $limit;

$filter = [];
$filterFields = ['mid', 'dba'];

foreach ($filterFields as $field) {
    if (!empty($_GET[$field])) {
        $operator = $_GET[$field . '_operator'] ?? 'equals';
        $value = $_GET[$field];
        $filter[$field . '|' . ($operator === 'contains' ? 'ILIKE' : ($operator === 'starts with' ? 'ILIKE' : '='))] =
            $operator === 'contains' ? "%$value%" : ($operator === 'starts with' ? "$value%" : $value);
    }
}

if (!empty($_GET['statement_month']) && !empty($_GET['statement_year'])) {
    $filter['statement_month|IN'] = array_map(
        fn($day) => $_GET['statement_year'] . str_pad($day, 2, '0', STR_PAD_LEFT) . ' ' . $_GET['statement_month'],
        range(1, 30)
    );
}

$order = [];
$orderFields = [
    'statement_month_order',
    'mid_order',
    'dba_order',
    'sales_volume_order',
    'sales_trxn_order',
    'commission_order',
    'responsible_person_order',
    'earnings_local_currency_order'
];

foreach ($orderFields as $field) {
    if (!empty($_GET[$field])) {
        $order[str_replace('_order', '', $field)] = strtolower($_GET[$field]) === 'asc' ? 'asc' : 'desc';
    }
}

try {
    $actualFilter = isAdmin() ? $filter : array_merge($filter, ['responsible_person_bitrix_id|=' => getCurrentUser()]);
    $select = [
        'id',
        'statement_month',
        'mid',
        'dba',
        'sales_volume',
        'sales_trxn',
        'earnings_local_currency',
        'commission',
        'responsible_person',
        'responsible_person_bitrix_id'
    ];

    $config = require __DIR__ . '/../../../config/config.php';
    $db = (new Database($config['db']))->getConnection();
    $logger = new GlobalLogger();
    $transaction = new Transaction($db, $logger);
    $transactions = $transaction->getAll($limit, $offset, $select, $actualFilter, $order);

    $response = [
        'data' => array_map(fn($data) => [
            'statement_month' => $data['statement_month'] ?? '',
            'mid' => $data['mid'] ?? '',
            'dba' => $data['dba'] ?? '',
            'sales_volume' => $data['sales_volume'] ?? '',
            'sales_trxn' => $data['sales_trxn'] ?? '',
            'commission_amount' => $data['commission'] ?? '',
            'responsible_person' => $data['responsible_person'] ?? '',
            'earnings' => $data['earnings_local_currency'] ?? ''
        ], $transactions['transactions']),
        'total' => $transactions['total_count'],
        'total_earnings' => $transactions['total_earnings'],
        'total_commission' => $transactions['total_commission'],
        'from' => $offset + 1,
        'to' => min($offset + $limit, $transactions['total_count']),
        'current_page' => $page,
        'per_page' => $limit,
        'last_page' => (int)ceil($transactions['total_count'] / $limit)
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => true, 'message' => $e->getMessage()]);
}

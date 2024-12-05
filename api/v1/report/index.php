<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once(__DIR__ . '../../../../crest/crest.php');
require_once(__DIR__ . '../../../../crest/settings.php');
require_once(__DIR__ . '../../../../utils/index.php');

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
$per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 50;
$offset = ($page - 1) * $per_page;

// Initialize filter array for Bitrix
$filter = [];

// Handle filters from query parameters
$filterFields = ['mid', 'dba', 'statement_month'];
foreach ($filterFields as $field) {
    if (isset($_GET[$field]) && !empty($_GET[$field])) {
        $operator = $_GET[$field . '_operator'] ?? 'equals';

        switch ($operator) {
            case 'contains':
                $filter[field_map($field)] = '%' . $_GET[$field] . '%';
                break;
            case 'starts with':
                $filter[field_map($field)] = $_GET[$field] . '%';
                break;
            default: // equals
                $filter[field_map($field)] = $_GET[$field];
        }
    }
}

$order = [];

// handle order from query parameters
$orderFields = ['statement_month_order', 'mid_order', 'dba_order', 'sales_volume_order', 'sales_trxn_order', 'commission_amount_order', 'responsible_person_order', 'earnings_order'];
foreach ($orderFields as $field) {
    if (isset($_GET[$field]) && !empty($_GET[$field])) {
        $cur_order = $_GET[$field] === 'asc' ? 'asc' : 'desc';
        $field = str_replace('_order', '', $field);
        $order[field_map($field)] = $cur_order;
    }
}

try {
    // Call to Bitrix24 REST API
    $actualFIlter = isAdmin() ? $filter : [...$filter, 'assignedById' => getCurrentUser()];
    $result = CRest::call(
        'crm.item.list',
        [
            'entityTypeId' => ENTITY_TYPE_ID,
            'filter' => $actualFIlter,
            'order' => $order,
            'select' => [
                'ID',
                'ufCrm9StatementMonth',
                'ufCrm9Mid', // Sales Volume
                'ufCrm9Dba',
                'ufCrm9SalesVolume',
                'ufCrm9SalesTrxn',
                'ufCrm9CommissionAmount',
                'assignedById',
                'ufCrm9EarningsLocalCurrency'
            ],
            'start' => $offset,
        ]
    );

    if (isset($result['error'])) {
        throw new Exception($result['error_description']);
    }

    // Get total count
    $total = $result['total'];

    $global_users = getUsers();
    $responsiblePersonMap = [];
    foreach ($global_users as $user) {
        $fname = $user['NAME'] ?? '';
        $lname = $user['LAST_NAME'] ?? '';
        $sname = $user['SECOND_NAME'] ?? '';
        $fullname = $fname . ' ' . $sname . ' ' . $lname;

        $responsiblePersonMap[$user['ID']] = $fullname;
    }

    // echo "<pre>";
    // print_r($responsiblePersonMap);
    // echo "</pre>";
    // Format response
    $response = [
        'data' => array_map(function ($data) use ($responsiblePersonMap) {
            $responsible_person = $responsiblePersonMap[$data['assignedById']] ?? '';
            return [
                'statement_month' => $data['ufCrm9StatementMonth'],
                'mid' => $data['ufCrm9Mid'],
                'dba' => $data['ufCrm9Dba'],
                'sales_volume' => $data['ufCrm9SalesVolume'],
                'sales_trxn' => $data['ufCrm9SalesTrxn'],
                'commission_amount' => $data['ufCrm9CommissionAmount'],
                'responsible_person' => $responsible_person,
                'earnings' => $data['ufCrm9EarningsLocalCurrency'],
            ];
        }, $result['result']['items']),
        'total' => $total,
        'total_earnings' => array_sum(array_column($result['result']['items'], 'ufCrm9EarningsLocalCurrency')),
        'total_commission' => array_sum(array_column($result['result']['items'], 'ufCrm9CommissionAmount')),
        'from' => $offset + 1,
        'to' => min($offset + $per_page, $total),
        'current_page' => $page,
        'per_page' => $per_page,
        'last_page' => ceil($total / $per_page)
    ];

    echo json_encode($response);
    // echo $response;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

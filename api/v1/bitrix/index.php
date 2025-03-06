<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../crest/crest.php';
require_once __DIR__ . '/../../../crest/settings.php';
require_once __DIR__ . '/../../../utils/index.php';

const DEFAULT_PAGE = 1;
const DEFAULT_PER_PAGE = 50;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

error_log("API Request received: " . $_SERVER['REQUEST_URI']);
error_log("Query parameters: " . json_encode($_GET));

function field_map($field)
{
    $fieldMap = [
        'statement_month' => 'ufCrm9StatementMonth',
        'mid' => 'ufCrm9Mid',
        'dba' => 'ufCrm9Dba',
        'sales_volume' => 'ufCrm9SalesVolume',
        'sales_trxn' => 'ufCrm9SalesTrxn',
        'commission' => 'ufCrm9CommissionAmount',
        'responsible_person' => 'assignedById',
        'earnings_local_currency' => 'ufCrm9EarningsLocalCurrency'
    ];
    return $fieldMap[$field] ?? $field;
}

function build_date_filter($year, $month)
{
    $dates = [];
    for ($day = 1; $day <= 30; $day++) {
        $formattedDay = str_pad($day, 2, '0', STR_PAD_LEFT);
        $dates[] = "$year$formattedDay $month";
    }
    return $dates;
}

function process_filters()
{
    $filters = [];
    $filterFields = ['mid', 'dba'];

    foreach ($filterFields as $field) {
        if (!empty($_GET[$field])) {
            $operator = $_GET[$field . '_operator'] ?? 'equals';
            $value = $_GET[$field];

            switch ($operator) {
                case 'contains':
                    $filters[field_map($field)] = '%' . $value . '%';
                    break;
                case 'starts with':
                    $filters[field_map($field)] = $value . '%';
                    break;
                default:
                    $filters[field_map($field)] = $value;
            }
        }
    }

    if (!empty($_GET['statement_month']) && !empty($_GET['statement_year'])) {
        $dates = build_date_filter($_GET['statement_year'], $_GET['statement_month']);
        $filters['@' . field_map('statement_month')] = $dates;
    }

    return isAdmin() ? $filters : array_merge($filters, ['assignedById' => getCurrentUser()]);
}

function process_order()
{
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
            $fieldName = str_replace('_order', '', $field);
            $order[field_map($fieldName)] = strtolower($_GET[$field]) === 'asc' ? 'asc' : 'desc';
        }
    }
    return $order;
}

function get_responsible_person_map()
{
    $users = getUsers();
    $map = [];

    foreach ($users as $user) {
        $fullName = trim(implode(' ', array_filter([
            $user['NAME'] ?? '',
            $user['SECOND_NAME'] ?? '',
            $user['LAST_NAME'] ?? ''
        ])));
        $map[$user['ID']] = $fullName;
    }
    return $map;
}

function format_response($items, $responsiblePersonMap, $total, $offset, $per_page, $page)
{
    $data = array_map(function ($item) use ($responsiblePersonMap) {
        return [
            'statement_month' => $item['ufCrm9StatementMonth'] ?? '',
            'mid' => $item['ufCrm9Mid'] ?? '',
            'dba' => $item['ufCrm9Dba'] ?? '',
            'sales_volume' => $item['ufCrm9SalesVolume'] ?? '',
            'sales_trxn' => $item['ufCrm9SalesTrxn'] ?? '',
            'commission_amount' => $item['ufCrm9CommissionAmount'] ?? '',
            'responsible_person' => $responsiblePersonMap[$item['assignedById']] ?? '',
            'earnings' => $item['ufCrm9EarningsLocalCurrency'] ?? ''
        ];
    }, $items);

    return [
        'data' => $data,
        'total' => $total,
        'total_earnings' => array_sum(array_column($items, 'ufCrm9EarningsLocalCurrency')),
        'total_commission' => array_sum(array_column($items, 'ufCrm9CommissionAmount')),
        'from' => $offset + 1,
        'to' => min($offset + $per_page, $total),
        'current_page' => $page,
        'per_page' => $per_page,
        'last_page' => (int)ceil($total / $per_page)
    ];
}

try {
    $page = (int)($_GET['page'] ?? DEFAULT_PAGE);
    $per_page = (int)($_GET['per_page'] ?? DEFAULT_PER_PAGE);
    $offset = ($page - 1) * $per_page;

    $filters = process_filters();
    $order = process_order();

    $result = CRest::call('crm.item.list', [
        'entityTypeId' => ENTITY_TYPE_ID,
        'filter' => $filters,
        'order' => $order,
        'select' => [
            'ID',
            'ufCrm9StatementMonth',
            'ufCrm9Mid',
            'ufCrm9Dba',
            'ufCrm9SalesVolume',
            'ufCrm9SalesTrxn',
            'ufCrm9CommissionAmount',
            'assignedById',
            'ufCrm9EarningsLocalCurrency'
        ],
        'start' => $offset,
    ]);

    if (isset($result['error'])) {
        throw new Exception($result['error_description'] ?? 'Unknown API error');
    }

    $responsiblePersonMap = get_responsible_person_map();
    $response = format_response(
        $result['result']['items'],
        $responsiblePersonMap,
        $result['total'],
        $offset,
        $per_page,
        $page
    );

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
}

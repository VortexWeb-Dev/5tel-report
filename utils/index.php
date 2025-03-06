<?php
require_once(__DIR__ . '/../crest/crest.php');
require_once(__DIR__ . '/../crest/crestcurrent.php');
require_once(__DIR__ . '/../crest/settings.php');

function getUsers($filter = [])
{
    $users = [];
    $next = 0;
    do {
        $result = CRest::call('user.get', [
            'filter' => $filter,
            'start' => $next
        ]);
        $users = array_merge($users, $result['result']);
        $next = isset($result['next']) ? $result['next'] : false;
    } while ($next);
    return $users;
}
function getUser($id)
{
    $result = CRest::call('user.get', [
        'ID' => $id
    ]);

    if (!isset($result['result'][0])) {
        return [];
    }

    return $result['result'][0];
}

function getCurrentUser()
{
    $result = CRestCurrent::call('user.current')['result'];
    return $result['ID'];
}

function getResponsiblePerson($user_id)
{
    $user = getUser($user_id);
    $fname = $user['NAME'] ?? '';
    $lname = $user['LAST_NAME'] ?? '';
    $sname = $user['SECOND_NAME'] ?? '';
    $fullname = $fname . ' ' . $sname . ' ' . $lname;
    return $fullname;
}

function field_map($field)
{
    switch ($field) {
        case 'statement_month':
            return 'ufCrm9StatementMonth';
        case 'mid':
            return 'ufCrm9Mid';
        case 'dba':
            return 'ufCrm9Dba';
        case 'sales_volume':
            return 'ufCrm9SalesVolume';
        case 'sales_trxn':
            return 'ufCrm9SalesTrxn';
        case 'commission_amount':
            return 'ufCrm9CommissionAmount';
        case 'responsible_person':
            return 'ufCrm9ResponsiblePerson';
        case 'earnings':
            return 'ufCrm9EarningsLocalCurrency';
        default:
            return $field;
    }
}

function isAdmin()
{
    $user_id = getCurrentUser();
    if ($user_id == '1') return true;
    return false;
}

// map enum with the values
function map_enum($fields, $field_id, $key_to_map)
{
    foreach ($fields as $field) {
        if (isset($field['title']) && $field['title'] == $field_id) {
            foreach ($field['items'] as $item) {
                if ($item['ID'] == $key_to_map) {
                    return $item['VALUE'];
                }
            }
        }
    }
}

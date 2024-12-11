<?php
include_once(__DIR__ . '/utils/index.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Data Grid</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-200 h-screen flex items-center justify-center">
    <!-- main container -->
    <div class="w-11/12 grid grid-cols-8 gap-4">
        <!-- table -->
        <div class="col-span-6 bg-white p-4 rounded-lg shadow">
            <div class="overflow-x-auto overflow-y-auto min-h-[600px] max-h-[600px]">
                <table class="w-full text-sm text-left">
                    <thead class="w-full bg-gray-100 text-gray-700 sticky top-0">
                        <tr>
                            <th class="px-2 py-2">
                                <div class="flex items-center justify-between">
                                    <span>#</span>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Statement Month</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var statement_order = urlParams.get('statement_month_order');
                                        var arrowUp = document.querySelector(`[data-statement-month-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-statement-month-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('statement_month_order', statement_order === 'asc' ? 'desc' : 'asc')
                                    ">
                                        <svg class="w-4 h-4" data-statement-month-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-statement-month-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>MID</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var mid_order = urlParams.get('mid_order');
                                        var arrowUp = document.querySelector(`[data-mid-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-mid-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('mid_order', mid_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-mid-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-mid-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>DBA</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var dba_order = urlParams.get('dba_order');
                                        var arrowUp = document.querySelector(`[data-dba-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-dba-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('dba_order', dba_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-dba-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-dba-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Sales Volume</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var sales_volume_order = urlParams.get('sales_volume_order');
                                        var arrowUp = document.querySelector(`[data-sales_volume-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-sales_volume-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('sales_volume_order', sales_volume_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-sales_volume-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-sales_volume-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Sales Transaction</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var sales_trxn_order = urlParams.get('sales_trxn_order');
                                        var arrowUp = document.querySelector(`[data-sales_trxn-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-sales_trxn-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('sales_trxn_order', sales_trxn_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-sales_trxn-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-sales_trxn-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Commission Amount</span>                    
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var commission_order = urlParams.get('commission_order');
                                        var arrowUp = document.querySelector(`[data-commission-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-commission-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('commission_order', commission_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-commission-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-commission-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Responsible Person</span>
                                    <!-- <?php
                                            $responsible_person_order = $_GET['responsible_person_order'] ?? 'desc';
                                            ?>
                                <a href="?responsible_person_order=<?php echo $responsible_person_order === 'asc' ? 'desc' : 'asc' ?>">
                                    <svg class="w-4 h-4 <?php echo $responsible_person_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                    <svg class="w-4 h-4 <?php echo $responsible_person_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </a> -->
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Earnings</span>
                                    <a href="javascript:;" onclick="
                                        var urlParams = new URLSearchParams(window.location.search);
                                        var earnings_local_currency_order = urlParams.get('earnings_local_currency_order');
                                        var arrowUp = document.querySelector(`[data-earnings_local_currency-order='asc']`);
                                        var arrowDown = document.querySelector(`[data-earnings_local_currency-order='desc']`);
                                        arrowUp.classList.toggle('hidden');
                                        arrowDown.classList.toggle('hidden');
                                        updateUrlParam('earnings_local_currency_order', earnings_local_currency_order === 'asc' ? 'desc' : 'asc')
                                        ">
                                        <svg class="w-4 h-4" data-earnings_local_currency-order="asc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 hidden" data-earnings_local_currency-order="desc" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                    <tbody id="tableLoader" class="hidden">
                        <tr>
                            <td colspan="9" class="h-[600px]">
                                <div class="flex justify-center items-center h-full">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
                                    <span class="ml-3 text-gray-600">Loading data...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- pagination -->
            <div class="px-4 py-3 flex items-center justify-end">
                <div id="paginationText" class="text-sm text-gray-700">
                    1 - 50 / 16162
                </div>
                <div class="flex gap-2">
                    <button class="p-2 rounded hover:bg-gray-100" aria-label="Previous page">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button class="p-2 rounded hover:bg-gray-100" aria-label="Next page" id="nextPageButton">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- filters -->
        <div class="col-span-2">
            <div class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-white">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Choose Filter: MID / DBA</label>
                    <div class="relative flex gap-2">
                        <select class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm" onchange="onSelectChange(event)">
                            <option disabled selected>Choose</option>
                            <option>MID</option>
                            <option>DBA</option>
                        </select>
                        <input type="text" name="mid" class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none sm:text-sm pl-3 pr-10" placeholder="Enter a value" disabled>
                        <button type="button" class="absolute inset-y-0 right-0 flex items-center justify-center w-8 h-full hover:bg-gray-100" onclick="clearInput()" style="display: none;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <script>
                        const input = document.querySelector('input[name=mid]');
                        const clearButton = input.nextElementSibling;
                        input.addEventListener('input', () => {
                            if (input.value) {
                                clearButton.style.display = 'flex';
                            } else {
                                clearButton.style.display = 'none';
                            }
                        });

                        function onSelectChange(event) {
                            input.disabled = (event.target.value === 'Choose')
                        }

                        function clearInput() {
                            input.value = '';
                            const event = new Event('input', {
                                bubbles: true,
                                cancelable: true
                            });
                            input.dispatchEvent(event);
                            clearButton.style.display = 'none';
                        }
                    </script>
                </div>

                <!-- <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-white">
                    <label class="block text-sm font-medium text-gray-700 mb-1">MID</label>
                    <div class="flex gap-2">
                        <select class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm">
                            <option>Equals</option>
                        </select>
                        <input type="text" name="mid" class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none sm:text-sm" placeholder="Enter a value">
                    </div>
                </div>

                <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-white">
                    <label class="block text-sm font-medium text-gray-700 mb-1">DBA</label>
                    <div class="flex gap-2">
                        <select class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm">
                            <option>Equals</option>
                            <option>Contains</option>
                            <option>Starts with</option>
                        </select>
                        <input type="text" name="dba" class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none sm:text-sm" placeholder="Enter a value">
                    </div>
                </div> -->

                <!-- <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-white">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Statement Month</label>
                    <div class="flex gap-2">
                        <select class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm">
                            <option>Equals</option>
                            <option>Contains</option>
                            <option>Starts with</option>
                        </select>
                        <input type="text" name="statement_month" class="block w-full border-b rounded-md border-gray-300 shadow-sm focus:outline-none sm:text-sm" placeholder="Enter a value">
                    </div>
                </div> -->

                <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-white">
                    <div class="flex justify-between mb-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Statement Date</label>
                        <div class="flex gap-2">
                            <button type="button" id="applyButton" class="px-2 py-2 text-xs font-bold text-white bg-blue-500 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="applyDateFilter()" disabled style="cursor: not-allowed;">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg> Apply
                            </button>
                            <button type="button" id="clearButton" class="hidden px-2 py-2 text-xs font-bold text-white bg-red-500 rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500" onclick="clearDateFilter()">
                                <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg> Clear
                            </button>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <select class="block w-1/2 border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm" id="statement_month" name="statement_month" onchange="validateApplyButton()">
                            <option value="" disabled selected>Choose Month</option>
                            <?php
                            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                            foreach ($months as $month) {
                                echo "<option value='$month'>" . $month . "</option>";
                            }
                            ?>
                        </select>
                        <select class="block w-1/2 border-b rounded-md border-gray-300 shadow-sm focus:outline-none py-2 pl-3 pr-10 text-sm" id="statement_year" name="statement_year" onchange="validateApplyButton()">
                            <option value="" disabled selected>Choose Year</option>
                            <?php
                            foreach (range(date('Y'), 2020) as $year) {
                                echo "<option value='$year'>$year</option>";
                            }
                            ?>
                        </select>
                        <script>
                            function validateApplyButton() {
                                const month = document.getElementById('statement_month').value;
                                const year = document.getElementById('statement_year').value;
                                const applyButton = document.getElementById('applyButton');
                                const clearButton = document.getElementById('clearButton');

                                if (month && year) {
                                    applyButton.disabled = false;
                                    applyButton.style.cursor = 'pointer';
                                } else {
                                    applyButton.disabled = true;
                                    applyButton.style.cursor = 'not-allowed';
                                }

                                if (month || year) {
                                    clearButton.classList.remove('hidden');
                                } else {
                                    clearButton.classList.add('hidden');
                                }
                            }

                            async function applyDateFilter() {
                                const data = await fetchData(currentPage, filters);
                                if (data) {
                                    currentPage = 1;
                                    renderTable(data);
                                }
                            }

                            async function clearDateFilter() {
                                const monthSelect = document.getElementById('statement_month');
                                const yearSelect = document.getElementById('statement_year');
                                monthSelect.selectedIndex = 0;
                                yearSelect.selectedIndex = 0;

                                const event = new Event('change', {
                                    bubbles: true,
                                    cancelable: true
                                });
                                monthSelect.dispatchEvent(event);
                                yearSelect.dispatchEvent(event);

                                validateApplyButton();

                                const data = await fetchData(currentPage, filters);
                                if (data) {
                                    currentPage = 1;
                                    renderTable(data);
                                }
                            }
                        </script>
                    </div>
                </div>

                <div class="flex-1 min-w-[250px] p-4 border rounded-lg shadow-sm bg-blue-50">
                    <div class="text-sm text-blue-700"><?php echo isAdmin() ? 'Earnings - Local Currency' : 'Commission Amount' ?></div>
                    <div id="totalEarnings" class="text-2xl font-bold text-blue-900">0</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        const perPage = 100;
        let totalPages = 1;

        async function fetchData(page = 1, filters = {}) {
            try {
                console.log(filters);

                setLoading(true); // Show loader

                const queryParams = new URLSearchParams({
                    page,
                    per_page: perPage,
                    ...filters
                });

                const response = await fetch(`${window.location.origin}/api/v1/db?${queryParams}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const data = await response.json();

                if (data.error) {
                    throw new Error(data.message);
                }

                return data;
            } catch (error) {
                console.error('Error fetching data:', error);
                // Show error to user
                document.getElementById('tableBody').innerHTML = `
                    <tr>
                        <td colspan="9" class="px-4 py-3 text-center text-red-600">
                            Error loading data: ${error.message}
                        </td>
                    </tr>
                `;
                return null;
            } finally {
                setLoading(false); // Hide loader
            }
        }

        function renderTable(data) {
            const tbody = document.getElementById('tableBody');
            tbody.innerHTML = data.data.map((row, index) => `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-2 py-1">${(currentPage - 1) * perPage + index + 1}</td>
                    <td class="px-2 py-1">${row.statement_month}</td>
                    <td class="px-2 py-1">${row.mid}</td>
                    <td class="px-2 py-1">${row.dba}</td>
                    <td class="px-2 py-1">${row.sales_volume}</td>
                    <td class="px-2 py-1">${row.sales_trxn}</td>
                    <td class="px-2 py-1">${row.commission_amount}</td>
                    <td class="px-2 py-1">${row.responsible_person}</td>
                    <td class="px-2 py-1">${row.earnings}</td>
                </tr>
            `).join('');

            // Update pagination text
            document.getElementById('paginationText').textContent =
                `${data.from} - ${data.to} / ${data.total}`;

            totalPages = Math.ceil(data.total / perPage);

            //update the total earnings
            const totalEarningsElement = document.getElementById('totalEarnings');
            const isAdmin = '<?php echo isAdmin() ?>';
            const totalEarnings = Number(data.total_earnings).toFixed(2);
            const totalCommission = Number(data.total_commission).toFixed(2);
            totalEarningsElement.textContent = isAdmin ? totalEarnings : totalCommission;
        }

        const filters = {};

        async function updateUrlParam(key, value) {
            const params = new URLSearchParams(window.location.search);
            // delete other order filters
            params.forEach((value, key) => {
                if (key !== value) {
                    params.delete(key);
                }
            });

            params.set(key, value);
            window.history.pushState(null, '', `?${params.toString()}`);

            //delete older order filters
            Object.entries(filters).forEach(([key, value]) => {
                if (value === 'asc' || value === 'desc') {
                    delete filters[key];
                }
            });

            // add new order filter
            params.forEach((value, key) => {
                filters[key] = value;
            });

            const data = await fetchData(currentPage, filters);

            if (data) {
                renderTable(data);
            }
        }

        // Initial load
        async function init() {
            const data = await fetchData(currentPage, filters);
            if (data) {
                renderTable(data);
            }
        }

        init();

        let timeout;
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    const filterType = input.previousElementSibling.value.toLowerCase();
                    // const operator = filterType == 'mid' ? 'equals' : 'contains';  // for bitrix
                    const operator = 'contains';

                    if (filterType == 'mid' && filters.hasOwnProperty('dba')) {
                        delete filters['dba'];
                        delete filters['dba_operator'];
                    } else if (filterType == 'dba' && filters.hasOwnProperty('mid')) {
                        delete filters['mid'];
                        delete filters['mid_operator'];
                    }

                    filters[filterType] = input.value;
                    filters[`${filterType}_operator`] = operator;

                    console.log(filters);

                    const data = await fetchData(currentPage, filters);
                    if (data) {
                        currentPage = 1;
                        renderTable(data);
                    }
                }, 500); // 300ms debounce delay
            });
        });

        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', () => {
                const statementMonth = document.getElementById('statement_month').value;
                const statementYear = document.getElementById('statement_year').value;

                if (statementMonth && statementYear) {
                    // console.log(statementMonth, statementYear);
                    filters['statement_month'] = statementMonth;
                    filters['statement_year'] = statementYear;
                } else {
                    delete filters['statement_month'];
                    delete filters['statement_year'];
                }
            });
        });

        // Add event listeners for pagination
        const [prevButton, nextButton] = document.querySelectorAll('button');

        prevButton.addEventListener('click', async () => {
            if (currentPage > 1) {
                currentPage--;
                const data = await fetchData(currentPage, filters);
                if (data) renderTable(data);
            }
        });

        nextButton.addEventListener('click', async () => {
            if (currentPage < totalPages) {
                currentPage++;
                const data = await fetchData(currentPage, filters);
                if (data) renderTable(data);
            }
        });

        // Add this function after the renderTable function
        function setLoading(isLoading) {
            const loader = document.getElementById('tableLoader');
            const tableBody = document.getElementById('tableBody');

            if (isLoading) {
                loader.classList.remove('hidden');
                tableBody.classList.add('hidden');
            } else {
                loader.classList.add('hidden');
                tableBody.classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
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

<body class="bg-gray-200 p-2 md:p-6 flex items-center justify-center">
    <!-- main container -->
    <div class="w-full grid grid-cols-8 gap-4">
        <!-- table -->
        <div class="col-span-6 bg-white p-4 rounded-lg shadow">
            <div class="overflow-x-auto overflow-y-auto max-h-[600px]">
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
                                    <?php
                                    $statement_order = $_GET['statement_month_order'] ?? 'desc';
                                    ?>
                                    <a href="?statement_month_order=<?php echo $statement_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $statement_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $statement_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>MID</span>
                                    <?php
                                    $mid_order = $_GET['mid_order'] ?? 'desc';
                                    ?>
                                    <a href="?mid_order=<?php echo $mid_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $mid_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $mid_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>DBA</span>
                                    <?php
                                    $dba_order = $_GET['dba_order'] ?? 'desc';
                                    ?>
                                    <a href="?dba_order=<?php echo $dba_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $dba_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $dba_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Sales Volume</span>
                                    <?php
                                    $sales_volume_order = $_GET['sales_volume_order'] ?? 'desc';
                                    ?>
                                    <a href="?sales_volume_order=<?php echo $sales_volume_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $sales_volume_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $sales_volume_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Sales Transaction</span>
                                    <?php
                                    $sales_trxn_order = $_GET['sales_trxn_order'] ?? 'desc';
                                    ?>
                                    <a href="?sales_trxn_order=<?php echo $sales_trxn_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $sales_trxn_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $sales_trxn_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </a>
                                </div>
                            </th>
                            <th class="px-4 py-3">
                                <div class="flex items-center justify-between">
                                    <span>Commission Amount</span>
                                    <?php
                                    $commission_amount_order = $_GET['commission_amount_order'] ?? 'desc';
                                    ?>
                                    <a href="?commission_amount_order=<?php echo $commission_amount_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $commission_amount_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $commission_amount_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <?php
                                    $earnings_order = $_GET['earnings_order'] ?? 'desc';
                                    ?>
                                    <a href="?earnings_order=<?php echo $earnings_order === 'asc' ? 'desc' : 'asc' ?>">
                                        <svg class="w-4 h-4 <?php echo $earnings_order === 'asc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                        </svg>
                                        <svg class="w-4 h-4 <?php echo $earnings_order === 'desc' ? 'hidden' : '' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                    <button class="p-2 rounded hover:bg-gray-100" aria-label="Next page">
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
                    <div class="flex justify-between">
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
        const perPage = 50;

        async function fetchData(page = 1, filters = {}) {
            try {
                console.log(filters);

                setLoading(true); // Show loader

                const queryParams = new URLSearchParams({
                    page,
                    per_page: perPage,
                    ...filters
                });

                const response = await fetch(`${window.location.origin}/api/v1/report?${queryParams}`);
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

            //update the total earnings
            const totalEarningsElement = document.getElementById('totalEarnings');
            const isAdmin = '<?php echo isAdmin() ?>';
            const totalEarnings = data.total_earnings;
            const totalCommission = data.total_commission;
            totalEarningsElement.textContent = isAdmin ? totalEarnings.toFixed(2) : totalCommission.toFixed(2);
        }

        const urlParams = new URLSearchParams(window.location.search);
        const filters = {};

        urlParams.forEach((value, key) => {
            filters[key] = value;
        });

        // Initial load
        async function init() {
            const data = await fetchData(currentPage, filters);
            if (data) {
                renderTable(data);
            }
        }

        init();

        // Add event listeners for filters
        // document.querySelectorAll('input').forEach(input => {
        //     input.addEventListener('keydown', async (e) => {
        //         if (e.key === 'Enter') {
        //             const filterType = e.target.name.toLowerCase();
        //             const operator = e.target.previousElementSibling.value.toLowerCase();

        //             filters[filterType] = e.target.value;
        //             filters[`${filterType}_operator`] = operator;

        //             console.log(filters);


        //             const data = await fetchData(currentPage, filters);
        //             if (data) {
        //                 currentPage = 1;
        //                 renderTable(data);
        //             }
        //         }
        //     });
        // });

        let timeout;
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('input', () => {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    const filterType = input.previousElementSibling.value.toLowerCase();
                    const operator = filterType == 'mid' ? 'equals' : 'contains';

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
                    console.log(statementMonth, statementYear);
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
            currentPage++;
            const data = await fetchData(currentPage, filters);
            if (data) renderTable(data);
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
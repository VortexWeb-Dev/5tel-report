<?php include_once(__DIR__ . '/utils/index.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>5Tel - Transactions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .slide-up {
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen text-gray-900 antialiased">
    <div class="container mx-auto px-4 py-8 max-w-[90vw]">
        <header class="mb-8 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-semibold text-gray-900">5Tel</h1>
                <span class="text-gray-500">|</span>
                <span class="text-sm text-gray-500">Transactions</span>
            </div>
        </header>

        <main class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <section class="lg:col-span-9 bg-white rounded-xl shadow-sm p-6 fade-in">
                <div class="overflow-x-auto max-h-[600px]">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b sticky top-0">
                            <tr class="text-gray-600">
                                <th class="px-4 py-3 text-left font-medium">#</th>
                            </tr>
                        </thead>
                        <script>
                            const tableHeaderData = [{
                                    title: 'Statement Month',
                                    data: 'statement_month'
                                },
                                {
                                    title: 'MID',
                                    data: 'mid'
                                },
                                {
                                    title: 'DBA',
                                    data: 'dba'
                                },
                                {
                                    title: 'Sales Volume',
                                    data: 'sales_volume'
                                },
                                {
                                    title: 'Sales Trxn',
                                    data: 'sales_trxn'
                                },
                                {
                                    title: 'Commission',
                                    data: 'commission_amount'
                                },
                                {
                                    title: 'Responsible',
                                    data: 'responsible_person'
                                },
                                {
                                    title: 'Earnings',
                                    data: 'earnings_local_currency'
                                }
                            ];

                            tableHeaderData.forEach(item => {
                                document.querySelector('thead tr').insertAdjacentHTML('beforeend', `
                                    <th class="px-4 py-3 text-left font-medium">
                                        <div class="flex items-center gap-2">
                                            <span>${item.title}</span>
                                            <button onclick="toggleSortOrder('${item.data}')" class="text-gray-400 hover:text-gray-600">
                                                <i class="fas fa-sort-up w-4 h-4" data-${item.data}-order="asc"></i>
                                                <i class="fas fa-sort-down w-4 h-4 hidden" data-${item.data}-order="desc"></i>
                                            </button>
                                        </div>
                                    </th>
                                `);
                            });
                        </script>
                        <tbody id="tableBody" class="divide-y divide-gray-100"></tbody>
                        <tbody id="tableLoader">
                            <tr>
                                <td colspan="9" class="py-20 text-center">
                                    <div class="flex justify-center items-center gap-2 opacity-0" id="loaderContent">
                                        <i class="fas fa-spinner fa-spin text-blue-500"></i>
                                        <span class="text-gray-500">Loading...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between mt-4">
                    <span id="paginationText" class="text-sm text-gray-500">1 - 50 / 16162</span>
                    <div class="flex gap-2">
                        <button id="prevPageButton" class="p-2 rounded-full hover:bg-gray-100 text-gray-600" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button id="nextPageButton" class="p-2 rounded-full hover:bg-gray-100 text-gray-600" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </section>

            <aside class="lg:col-span-3 space-y-6">
                <div class="bg-white p-5 rounded-xl shadow-sm slide-up">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by</label>
                    <div class="flex gap-2">
                        <select class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" onchange="onSelectChange(event)">
                            <option value="" disabled selected>Choose</option>
                            <option value="mid">MID</option>
                            <option value="dba">DBA</option>
                        </select>
                        <div class="relative w-full">
                            <input type="text" name="mid" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500 disabled:bg-gray-50" placeholder="Enter value" disabled>
                            <button type="button" class="absolute inset-y-0 right-2 p-1 hidden hover:text-gray-600" onclick="clearInput()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-xl shadow-sm slide-up">
                    <div class="flex justify-between items-center mb-3">
                        <label class="text-sm font-medium text-gray-700">Statement Date</label>
                        <div class="flex gap-2">
                            <button id="applyButton" class="px-3 py-1 text-sm bg-blue-600 text-white rounded-lg disabled:bg-gray-400 hover:bg-blue-700" onclick="applyDateFilter()" disabled>Apply</button>
                            <button id="clearButton" class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg hidden hover:bg-red-700" onclick="clearDateFilter()">Clear</button>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <select id="statement_month" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" onchange="validateApplyButton()">
                            <option value="" disabled selected>Month</option>
                            <?php foreach (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month) echo "<option value='$month'>$month</option>"; ?>
                        </select>
                        <select id="statement_year" class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-500" onchange="validateApplyButton()">
                            <option value="" disabled selected>Year</option>
                            <?php foreach (range(date('Y'), 2024) as $year) echo "<option value='$year'>$year</option>"; ?>
                        </select>
                    </div>
                </div>

                <div class="bg-blue-50 p-5 rounded-xl shadow-sm slide-up">
                    <div class="text-sm text-blue-700 flex items-center gap-2">
                        <i class="fas fa-wallet"></i>
                        <?php echo isAdmin() ? 'Earnings' : 'Commission'; ?>
                    </div>
                    <div id="totalEarnings" class="text-2xl font-semibold text-blue-900 mt-2">0</div>
                </div>
            </aside>
        </main>

        <footer class="mt-10 text-center text-sm text-gray-500">
            © <?php echo date('Y'); ?> Powered by <a href="https://vortexweb.cloud" class="hover:underline" target="_blank">VortexWeb</a>.
        </footer>
    </div>

    <script src="./script.js"></script>
</body>

</html>
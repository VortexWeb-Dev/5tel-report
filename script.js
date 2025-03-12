// Toggle sort order for table headers
function toggleSortOrder(column) {
  const ascIcon = document.querySelector(`[data-${column}-order="asc"]`);
  const descIcon = document.querySelector(`[data-${column}-order="desc"]`);
  ascIcon.classList.toggle("hidden");
  descIcon.classList.toggle("hidden");
  // Add sorting logic here
}

// Handle filter select change
function onSelectChange(event) {
  const input = document.querySelector('input[name="mid"]');
  const clearBtn = input.nextElementSibling;
  input.disabled = false;
  input.focus();
  clearBtn.classList.remove("hidden");
}

// Clear filter input
function clearInput() {
  const input = document.querySelector('input[name="mid"]');
  const clearBtn = input.nextElementSibling;
  input.value = "";
  input.disabled = true;
  clearBtn.classList.add("hidden");
}

// Validate apply button for date filter
function validateApplyButton() {
  const month = document.getElementById("statement_month").value;
  const year = document.getElementById("statement_year").value;
  const applyBtn = document.getElementById("applyButton");
  applyBtn.disabled = !(month && year);
}

// Apply date filter
function applyDateFilter() {
  const clearBtn = document.getElementById("clearButton");
  clearBtn.classList.remove("hidden");
  // Add filter logic here
}

// Clear date filter
function clearDateFilter() {
  const month = document.getElementById("statement_month");
  const year = document.getElementById("statement_year");
  const applyBtn = document.getElementById("applyButton");
  const clearBtn = document.getElementById("clearButton");

  month.value = "";
  year.value = "";
  applyBtn.disabled = true;
  clearBtn.classList.add("hidden");
  // Add clear filter logic here
}

// Show loader on page load
document.addEventListener("DOMContentLoaded", () => {
  const loader = document.getElementById("loaderContent");
  loader.classList.remove("opacity-0");
  loader.classList.add("opacity-100");
  // Simulate data loading
  setTimeout(() => {
    loader.classList.add("opacity-0");
    loader.classList.remove("opacity-100");
  }, 2000);
});

const ITEMS_PER_PAGE = 100;

// Loader Animation
const tableLoader = document.getElementById("tableLoader");
const loaderContent = document.getElementById("loaderContent");

function showLoader() {
  tableLoader.classList.remove("hidden");
  setTimeout(() => loaderContent.classList.remove("opacity-0"), 50);
}

function hideLoader() {
  loaderContent.classList.add("opacity-0");
  setTimeout(() => tableLoader.classList.add("hidden"), 300);
}

// Existing input handling
const input = document.querySelector('input[name="mid"]');
const clearButton = input.nextElementSibling;
input.addEventListener("input", () =>
  clearButton.classList.toggle("hidden", !input.value)
);

function onSelectChange(e) {
  input.disabled = !e.target.value;
}

function clearInput() {
  input.value = "";
  input.dispatchEvent(
    new Event("input", {
      bubbles: true,
    })
  );
}

// Existing date filter validation
function validateApplyButton() {
  const month = document.getElementById("statement_month").value;
  const year = document.getElementById("statement_year").value;
  const applyButton = document.getElementById("applyButton");
  const clearButton = document.getElementById("clearButton");
  applyButton.disabled = !(month && year);
  clearButton.classList.toggle("hidden", !(month || year));
}

let currentPage = 1;
let totalPages = 1;
let filters = {};

async function fetchData(page = 1, filters = {}) {
  showLoader();

  try {
    const queryParams = new URLSearchParams({
      page,
      per_page: ITEMS_PER_PAGE,
      ...filters,
    });

    const url = `./api/v1/db/index.php?${queryParams}`;

    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    if (data.error) {
      throw new Error(data.message);
    }

    return data;
  } catch (error) {
    console.error("Error fetching data:", error);
    showError(error.message);
    return null;
  } finally {
    hideLoader();
  }
}

function formatMoney(amount) {
  return new Intl.NumberFormat("en-UK", {
    style: "currency",
    currency: "GBP",
  }).format(amount);
}

function formatName(name) {
  return name
    .toLowerCase()
    .split(" ")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
}

function renderTable(data) {
  if (!data?.data) return;

  console.log(data);

  const tbody = document.getElementById("tableBody");
  tbody.innerHTML = data.data
    .map((row, index) => {
      const rowNumber = (currentPage - 1) * ITEMS_PER_PAGE + index + 1;
      return `
        <tr class="border-b hover:bg-gray-50 transition-colors duration-200 slide-up" style="animation-delay: ${
          index * 0.05
        }s;">
          <td class="px-4 py-3 text-gray-500 font-medium">${rowNumber}</td>
          <td class="px-4 py-3">${row.statement_month || "N/A"}</td>
          <td class="px-4 py-3 font-mono text-sm">${row.mid || "N/A"}</td>
          <td class="px-4 py-3">${formatName(row.dba) || "N/A"}</td>
          <td class="px-4 py-3 text-right">${
            formatMoney(row.sales_volume) || "0.00"
          }</td>
          <td class="px-4 py-3 text-right">${row.sales_trxn || "0"}</td>
          <td class="px-4 py-3 text-right text-blue-600">${
            formatMoney(row.commission_amount) || "0.00"
          }</td>
          <td class="px-4 py-3">${
            formatName(row.responsible_person) || "N/A"
          }</td>
          <td class="px-4 py-3 text-right font-semibold text-green-600">${
            formatMoney(row.earnings) || "0.00"
          }</td>
        </tr>
      `;
    })
    .join("");

  updatePagination(data);
  updateTotalEarnings(data);
}

function updatePagination(data) {
  document.getElementById("paginationText").textContent = `${
    data.from || 0
  } - ${data.to || 0} / ${data.total || 0}`;
  totalPages = Math.ceil(data.total / ITEMS_PER_PAGE);
}

function updateTotalEarnings(data) {
  const totalEarningsElement = document.getElementById("totalEarnings");
  const isAdmin = "<?php echo isAdmin() ?>" === "1";
  const value = isAdmin
    ? formatMoney(Number(data.total_earnings || 0).toFixed(2))
    : formatMoney(Number(data.total_commission || 0).toFixed(2));
  totalEarningsElement.textContent = value;
}

function toggleSortOrder(column) {
  const urlParams = new URLSearchParams(window.location.search);
  const currentOrder = urlParams.get(`${column}_order`);
  const newOrder = currentOrder === "asc" ? "desc" : "asc";

  updateSortArrows(column, newOrder);
  updateUrlParam(`${column}_order`, newOrder);
}

function updateSortArrows(column, order) {
  const arrowUp = document.querySelector(`[data-${column}-order="asc"]`);
  const arrowDown = document.querySelector(`[data-${column}-order="desc"]`);
  arrowUp.classList.toggle("hidden", order !== "asc");
  arrowDown.classList.toggle("hidden", order !== "desc");
}

async function updateUrlParam(key, value) {
  const params = new URLSearchParams(window.location.search);
  params.set(key, value);
  window.history.pushState(null, "", `?${params.toString()}`);

  Object.keys(filters).forEach((k) => {
    if (k.endsWith("_order") && k !== key) delete filters[k];
  });
  params.forEach((val, k) => (filters[k] = val));

  const data = await fetchData(currentPage, filters);
  if (data?.data?.length) renderTable(data);
}

function setLoading(isLoading) {
  const loader = document.getElementById("tableLoader");
  const tableBody = document.getElementById("tableBody");
  loader.classList.toggle("hidden", !isLoading);
  tableBody.classList.toggle("hidden", isLoading);
}

function showError(message) {
  document.getElementById("tableBody").innerHTML = `
        <tr>
            <td colspan="9" class="px-4 py-3 text-center text-red-600">
                Error loading data: ${message}
            </td>
        </tr>
    `;
}

function setupEventListeners() {
  let timeout;
  document.querySelectorAll("input").forEach((input) => {
    input.addEventListener("input", () => {
      clearTimeout(timeout);
      timeout = setTimeout(async () => {
        const filterType = input.previousElementSibling.value.toLowerCase();
        filters[filterType] = input.value;
        filters[`${filterType}_operator`] = "contains";

        if (filterType === "mid") delete filters.dba;
        if (filterType === "dba") delete filters.mid;

        const data = await fetchData(1, filters);
        if (data) {
          currentPage = 1;
          renderTable(data);
        }
      }, 500);
    });
  });

  document.querySelectorAll("select").forEach((select) => {
    select.addEventListener("change", async () => {
      const month = document.getElementById("statement_month").value;
      const year = document.getElementById("statement_year").value;

      if (month && year) {
        filters.statement_month = month;
        filters.statement_year = year;
      } else {
        delete filters.statement_month;
        delete filters.statement_year;
      }

      const data = await fetchData(1, filters);
      if (data) {
        currentPage = 1;
        renderTable(data);
      }
    });
  });

  const prevButton = document.getElementById("prevPageButton");
  const nextButton = document.getElementById("nextPageButton");

  prevButton.addEventListener("click", async () => {
    if (currentPage <= 1) return;
    currentPage--;
    const data = await fetchData(currentPage, filters);
    if (data) renderTable(data);
  });

  nextButton.addEventListener("click", async () => {
    if (currentPage >= totalPages) return;
    currentPage++;
    const data = await fetchData(currentPage, filters);
    if (data) renderTable(data);
  });
}

async function init() {
  setupEventListeners();
  const data = await fetchData(currentPage, filters);
  if (data) renderTable(data);
}

init();

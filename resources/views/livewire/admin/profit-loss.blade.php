<div>
    <div class="bg-white dark:bg-slate-900 shadow rounded-lg p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Profit & Loss Report</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 dark:bg-blue-900/20 p-6 rounded-xl border border-blue-100 dark:border-blue-800/30">
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Total Sales (Gross)</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">LKR {{ number_format($totalSales, 2) }}</p>
            </div>
            
            <div class="bg-red-50 dark:bg-red-900/20 p-6 rounded-xl border border-red-100 dark:border-red-800/30">
                <p class="text-sm font-medium text-red-600 dark:text-red-400">Total Tax (VAT + SSCL)</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">LKR {{ number_format($totalTax, 2) }}</p>
            </div>
            
            <div class="bg-emerald-50 dark:bg-emerald-900/20 p-6 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Net Profit</p>
                <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">LKR {{ number_format($netProfit, 2) }}</p>
            </div>
        </div>
    </div>
</div>

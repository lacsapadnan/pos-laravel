<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Interface\ProductServiceInterface;
use App\Services\Interface\CustomerServiceInterface;
use App\Services\Interface\SalesServiceInterface;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

final class DashboardController extends Controller
{
    public function __construct(
        private readonly ProductServiceInterface $productService,
        private readonly CustomerServiceInterface $customerService,
        private readonly SalesServiceInterface $salesService
    ) {}

    public function index(Request $request): View
    {
        $totalProducts = $this->productService->getAllProducts()->count();
        $totalCustomers = $this->customerService->getAllCustomers()->count();
        $todayRevenue = $this->salesService->getDailySalesTotal();

        // Sales data for the current month
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();
        $salesReport = $this->salesService->getSalesReport($startOfMonth, $endOfMonth);

        // Prepare chart data (dates and totals)
        $chartLabels = [];
        $chartData = [];
        foreach ($salesReport['daily_breakdown'] as $date => $data) {
            $chartLabels[] = $date;
            $chartData[] = $data['total'];
        }

        return view('pages.dashboard', [
            'totalProducts' => $totalProducts,
            'totalCustomers' => $totalCustomers,
            'todayRevenue' => $todayRevenue,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $sales = $this->getSalesData($start_date, $end_date, true);
        $summary = $this->calculateSummary($sales);
        $summary['period'] = $start_date->format('d M Y') . ' - ' . $end_date->format('d M Y');

        return view('admin.sales_reports.index', compact('sales', 'summary', 'start_date', 'end_date'));
    }

    public function export(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        // Get sales data (sorted ascending for export)
        $sales = $this->getSalesData($start_date, $end_date);
        $summary = $this->calculateSummary($sales);

        // Generate and download the Excel file
        return $this->generateSalesExcel($sales, $summary, $start_date, $end_date);
    }

    /**
     * Get processed sales data for reporting
     *
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @param bool $descOrder Sort by date in descending order if true
     * @return \Illuminate\Support\Collection
     */
    private function getSalesData(Carbon $start_date, Carbon $end_date, $descOrder = false)
    {
        // First get the orders
        $orders = Order::where('order_status', 'shipped')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->get();

        // Group orders by date
        $salesByDate = $orders->groupBy(function($order) {
            return Carbon::parse($order->created_at)->format('Y-m-d');
        });

        // Transform to the format needed for the view
        $sales = collect();
        foreach ($salesByDate as $date => $dailyOrders) {
            $totalItems = 0;
            foreach ($dailyOrders as $order) {
                $totalItems += $order->items->sum('quantity');
            }

            $sales->push([
                'date' => $date,
                'total_orders' => $dailyOrders->count(),
                'total_revenue' => $dailyOrders->sum('total_amount'),
                'total_items_sold' => $totalItems
            ]);
        }

        // Sort by date
        return $descOrder ? $sales->sortByDesc('date')->values() : $sales->sortBy('date')->values();
    }

    /**
     * Calculate summary totals from sales data
     *
     * @param \Illuminate\Support\Collection $sales
     * @return array
     */
    private function calculateSummary($sales)
    {
        return [
            'total_orders' => $sales->sum('total_orders'),
            'total_revenue' => $sales->sum('total_revenue'),
            'total_items_sold' => $sales->sum('total_items_sold')
        ];
    }

    /**
     * Generate Excel file for sales report
     *
     * @param \Illuminate\Support\Collection $sales
     * @param array $summary
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function generateSalesExcel($sales, $summary, $start_date, $end_date)
    {
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the titles and headers
        $sheet->setCellValue('A1', 'LAPORAN PENJUALAN URBANOVA');
        $sheet->setCellValue('A2', 'Periode: ' . $start_date->format('d M Y') . ' - ' . $end_date->format('d M Y'));
        $sheet->setCellValue('A4', 'Tanggal');
        $sheet->setCellValue('B4', 'Jumlah Pesanan');
        $sheet->setCellValue('C4', 'Total Pendapatan');
        $sheet->setCellValue('D4', 'Jumlah Item Terjual');

        // Add data rows
        $row = 5;
        foreach ($sales as $sale) {
            $sheet->setCellValue('A' . $row, Carbon::parse($sale['date'])->format('d/m/Y'));
            $sheet->setCellValue('B' . $row, $sale['total_orders']);
            $sheet->setCellValue('C' . $row, 'Rp ' . number_format($sale['total_revenue'], 0, ',', '.'));
            $sheet->setCellValue('D' . $row, $sale['total_items_sold']);
            $row++;
        }

        // Add summary row
        $summaryRow = $row + 1;
        $sheet->setCellValue('A' . $summaryRow, 'TOTAL');
        $sheet->setCellValue('B' . $summaryRow, $summary['total_orders']);
        $sheet->setCellValue('C' . $summaryRow, 'Rp ' . number_format($summary['total_revenue'], 0, ',', '.'));
        $sheet->setCellValue('D' . $summaryRow, $summary['total_items_sold']);

        $this->styleExcelReport($sheet, $row, $summaryRow);

        // Create the Excel file
        $filename = 'laporan_penjualan_' . $start_date->format('d_m_Y') . '_' . $end_date->format('d_m_Y') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save to file and prepare for download
        $path = storage_path('app/public/reports/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $writer->save($path . $filename);

        return response()->download($path . $filename)->deleteFileAfterSend(true);
    }

    /**
     * Apply styling to Excel sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param int $lastDataRow
     * @param int $summaryRow
     */
    private function styleExcelReport($sheet, $lastDataRow, $summaryRow)
    {
        // Style the header cells
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ];
        $sheet->getStyle('A4:D4')->applyFromArray($headerStyle);

        // Style title
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style period
        $sheet->mergeCells('A2:D2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style summary row
        $sheet->getStyle('A' . $summaryRow . ':D' . $summaryRow)->getFont()->setBold(true);
        $sheet->getStyle('A' . $summaryRow . ':D' . $summaryRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setRGB('F2F2F2');

        // Style data cells
        $sheet->getStyle('A5:D' . ($lastDataRow - 1))->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    public function detailedReport(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = Order::with(['items', 'user'])
            ->where('order_status', 'shipped')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.sales_reports.detailed', compact('orders', 'start_date', 'end_date'));
    }

    public function exportDetailed(Request $request)
    {
        $start_date = $request->start_date ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->startOfMonth();
        $end_date = $request->end_date ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $orders = $this->getDetailedOrders($start_date, $end_date);

        return $this->generateDetailedExcel($orders, $start_date, $end_date);
    }

    /**
     * Get detailed order data
     *
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    private function getDetailedOrders(Carbon $start_date, Carbon $end_date)
    {
        return Order::with(['items', 'user'])
            ->where('order_status', 'shipped')
            ->whereBetween('created_at', [$start_date, $end_date])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Generate detailed Excel report
     *
     * @param \Illuminate\Database\Eloquent\Collection $orders
     * @param Carbon $start_date
     * @param Carbon $end_date
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function generateDetailedExcel($orders, $start_date, $end_date)
    {
        // Create a new Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set the titles and headers
        $sheet->setCellValue('A1', 'LAPORAN DETAIL PENJUALAN URBANOVA');
        $sheet->setCellValue('A2', 'Periode: ' . $start_date->format('d M Y') . ' - ' . $end_date->format('d M Y'));
        $sheet->setCellValue('A4', 'No.');
        $sheet->setCellValue('B4', 'ID Order');
        $sheet->setCellValue('C4', 'Tanggal');
        $sheet->setCellValue('D4', 'Pelanggan');
        $sheet->setCellValue('E4', 'Produk');
        $sheet->setCellValue('F4', 'Jumlah');
        $sheet->setCellValue('G4', 'Harga Satuan');
        $sheet->setCellValue('H4', 'Total');
        $sheet->setCellValue('I4', 'Status');

        $row = 5;
        $no = 1;

        foreach ($orders as $order) {
            $row = $this->addOrderToDetailedExcel($sheet, $order, $row, $no);
            $no++;
        }

        $this->styleDetailedExcel($sheet, $row);

        // Create the Excel file
        $filename = 'detail_penjualan_' . $start_date->format('d_m_Y') . '_' . $end_date->format('d_m_Y') . '.xlsx';
        $writer = new Xlsx($spreadsheet);

        // Save to file and prepare for download
        $path = storage_path('app/public/reports/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $writer->save($path . $filename);

        return response()->download($path . $filename)->deleteFileAfterSend(true);
    }

    /**
     * Add order data to Excel sheet
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param \App\Models\Order $order
     * @param int $row
     * @param int $no
     * @return int Updated row number
     */
    private function addOrderToDetailedExcel($sheet, $order, $row, $no)
    {
        $sheet->setCellValue('A' . $row, $no);
        $sheet->setCellValue('B' . $row, $order->order_number);
        $sheet->setCellValue('C' . $row, $order->created_at->format('d/m/Y H:i'));
        $sheet->setCellValue('D' . $row, $order->user->name ?? 'Guest');

        // If there are products, put first one here
        if ($order->items->count() > 0) {
            $item = $order->items->first();
            $sheet->setCellValue('E' . $row, $item->product_name);
            $sheet->setCellValue('F' . $row, $item->quantity);
            $sheet->setCellValue('G' . $row, 'Rp ' . number_format($item->price, 0, ',', '.'));
            $sheet->setCellValue('H' . $row, 'Rp ' . number_format($item->subtotal, 0, ',', '.'));
        } else {
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, '');
            $sheet->setCellValue('G' . $row, '');
            $sheet->setCellValue('H' . $row, '');
        }

        $sheet->setCellValue('I' . $row, ucfirst($order->order_status));

        // If order has multiple products, add rows for each additional product
        if ($order->items->count() > 1) {
            foreach ($order->items->slice(1) as $item) {
                $row++;
                $sheet->setCellValue('E' . $row, $item->product_name);
                $sheet->setCellValue('F' . $row, $item->quantity);
                $sheet->setCellValue('G' . $row, 'Rp ' . number_format($item->price, 0, ',', '.'));
                $sheet->setCellValue('H' . $row, 'Rp ' . number_format($item->subtotal, 0, ',', '.'));
            }
        }

        return $row + 1;
    }

    /**
     * Apply styling to detailed Excel report
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param int $lastRow
     */
    private function styleDetailedExcel($sheet, $lastRow)
    {
        // Style the header cells
        $headerStyle = [
            'font' => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ]
            ]
        ];
        $sheet->getStyle('A4:I4')->applyFromArray($headerStyle);

        // Style title
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style period
        $sheet->mergeCells('A2:I2');
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Style data cells
        $sheet->getStyle('A5:I' . ($lastRow - 1))->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Auto-size columns
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}

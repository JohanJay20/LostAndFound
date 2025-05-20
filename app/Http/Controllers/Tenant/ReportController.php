<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LostAndFound;
use Illuminate\Support\Facades\DB;
use FPDF;

class ReportController extends Controller
{
    public function index()
    {
        // Get data for pie chart - Items by category
        $categoryData = LostAndFound::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Get data for line chart - Items by status
        $statusData = LostAndFound::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Get data for monthly trends
        $monthlyData = LostAndFound::select(
            DB::raw('MONTH(found_at) as month'),
            DB::raw('YEAR(found_at) as year'),
            DB::raw('count(*) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Get data for area chart - Items by location
        $locationData = LostAndFound::select('location', DB::raw('count(*) as total'))
            ->groupBy('location')
            ->get();

        return view('tenant.reports.index', compact('categoryData', 'statusData', 'monthlyData', 'locationData'));
    }

    public function download()
    {
        // Get all items with their details and claimer information
        $allItems = LostAndFound::with('claimer')->orderBy('found_at', 'desc')->get();

        // Create new PDF document in landscape
        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        
        // Add header
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 20, 'LOST AND FOUND MANAGEMENT SYSTEM', 0, 1, 'C');
        $pdf->SetFont('Arial', 'I', 12);
        $pdf->Cell(0, 10, 'Detailed Report', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Generated on: ' . date('F d, Y'), 0, 1, 'C');
        $pdf->Ln(10);

        // Add summary section
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Executive Summary', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Total Items: ' . $allItems->count(), 0, 1, 'L');
        $pdf->Cell(0, 10, 'Claimed Items: ' . $allItems->where('status', 'claimed')->count(), 0, 1, 'L');
        $pdf->Cell(0, 10, 'Unclaimed Items: ' . $allItems->where('status', '!=', 'claimed')->count(), 0, 1, 'L');
        $pdf->Ln(10);

        // All Items Details
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Detailed Item List', 0, 1, 'L');
        
        // Define column widths for landscape mode
        $itemNameWidth = 60;
        $categoryWidth = 35;
        $statusWidth = 30;
        $locationWidth = 45;
        $dateWidth = 35;
        $claimerWidth = 80;
        
        // Table header
        $pdf->SetFillColor(220, 220, 220);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell($itemNameWidth, 10, 'Item Name', 1, 0, 'C', true);
        $pdf->Cell($categoryWidth, 10, 'Category', 1, 0, 'C', true);
        $pdf->Cell($statusWidth, 10, 'Status', 1, 0, 'C', true);
        $pdf->Cell($locationWidth, 10, 'Location', 1, 0, 'C', true);
        $pdf->Cell($dateWidth, 10, 'Found Date', 1, 0, 'C', true);
        $pdf->Cell($claimerWidth, 10, 'Claimer Info', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFont('Arial', '', 10);
        foreach ($allItems as $item) {
            // Check if we need a new page
            if ($pdf->GetY() > 180) { // Adjusted for landscape mode
                $pdf->AddPage();
                // Repeat header
                $pdf->SetFillColor(220, 220, 220);
                $pdf->SetFont('Arial', 'B', 10);
                $pdf->Cell($itemNameWidth, 10, 'Item Name', 1, 0, 'C', true);
                $pdf->Cell($categoryWidth, 10, 'Category', 1, 0, 'C', true);
                $pdf->Cell($statusWidth, 10, 'Status', 1, 0, 'C', true);
                $pdf->Cell($locationWidth, 10, 'Location', 1, 0, 'C', true);
                $pdf->Cell($dateWidth, 10, 'Found Date', 1, 0, 'C', true);
                $pdf->Cell($claimerWidth, 10, 'Claimer Info', 1, 1, 'C', true);
                $pdf->SetFont('Arial', '', 10);
            }

            // Fixed row height for better consistency
            $rowHeight = 15;

            // Item Name
            $pdf->Cell($itemNameWidth, $rowHeight, $item->item_name, 1, 0, 'L');
            
            // Category
            $pdf->Cell($categoryWidth, $rowHeight, $item->category, 1, 0, 'L');
            
            // Status
            $pdf->Cell($statusWidth, $rowHeight, ucfirst($item->status), 1, 0, 'L');
            
            // Location
            $pdf->Cell($locationWidth, $rowHeight, $item->location, 1, 0, 'L');
            
            // Found Date
            $pdf->Cell($dateWidth, $rowHeight, $item->found_at->format('Y-m-d'), 1, 0, 'C');
            
            // Claimer information
            if ($item->status === 'claimed' && $item->claimer) {
                $claimerInfo = "Name: " . $item->claimer->name . "\n" . 
                             
                              "Email: " . ($item->claimer->email ?? 'N/A');
            } else {
                $claimerInfo = 'Not claimed';
            }
            
            // Use Cell instead of MultiCell for claimer info
            $pdf->Cell($claimerWidth, $rowHeight, $claimerInfo, 1, 1, 'L');
        }
        
        // Add footer with page numbers
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->Cell(0, 10, 'Page ' . $pdf->PageNo() . '/{nb}', 0, 0, 'C');
        
        // Output the PDF
        return $pdf->Output('D', 'lost_and_found_detailed_report.pdf');
    }
}

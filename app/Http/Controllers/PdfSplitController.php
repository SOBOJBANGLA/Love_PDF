<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class PdfSplitController extends Controller
{
    public function show()
    {
        return view('tools.split');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240',
            'split_type' => 'required|in:page,range',
            'page_numbers' => 'required_if:split_type,range|nullable|string',
        ]);

        try {
            $file = $request->file('pdf_file');
            $splitType = $request->input('split_type');
            
            // Create a new FPDI instance
            $pdf = new Fpdi();
            
            // Get the number of pages in the PDF
            $pageCount = $pdf->setSourceFile($file->getPathname());
            
            // Create a temporary directory for split files
            $tempDir = storage_path('app/public/split_' . time());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            
            if ($splitType === 'page') {
                // Split each page into a separate PDF
                for ($i = 1; $i <= $pageCount; $i++) {
                    $newPdf = new Fpdi();
                    $newPdf->setSourceFile($file->getPathname());
                    $tplId = $newPdf->importPage($i);
                    $newPdf->AddPage();
                    $newPdf->useTemplate($tplId);
                    
                    $outputPath = $tempDir . '/page_' . $i . '.pdf';
                    $newPdf->Output('F', $outputPath);
                }
            } else {
                // Split by page ranges
                $ranges = explode(',', $request->input('page_numbers'));
                $rangeIndex = 1;
                
                foreach ($ranges as $range) {
                    $range = trim($range);
                    if (strpos($range, '-') !== false) {
                        // Handle range (e.g., "1-5")
                        list($start, $end) = explode('-', $range);
                        $start = (int)$start;
                        $end = (int)$end;
                        
                        if ($start > 0 && $end <= $pageCount && $start <= $end) {
                            $newPdf = new Fpdi();
                            $newPdf->setSourceFile($file->getPathname());
                            
                            for ($i = $start; $i <= $end; $i++) {
                                $tplId = $newPdf->importPage($i);
                                $newPdf->AddPage();
                                $newPdf->useTemplate($tplId);
                            }
                            
                            $outputPath = $tempDir . '/range_' . $rangeIndex . '.pdf';
                            $newPdf->Output('F', $outputPath);
                            $rangeIndex++;
                        }
                    } else {
                        // Handle single page
                        $pageNum = (int)$range;
                        if ($pageNum > 0 && $pageNum <= $pageCount) {
                            $newPdf = new Fpdi();
                            $newPdf->setSourceFile($file->getPathname());
                            $tplId = $newPdf->importPage($pageNum);
                            $newPdf->AddPage();
                            $newPdf->useTemplate($tplId);
                            
                            $outputPath = $tempDir . '/page_' . $pageNum . '.pdf';
                            $newPdf->Output('F', $outputPath);
                        }
                    }
                }
            }
            
            // Create a ZIP file containing all split PDFs
            $zip = new ZipArchive();
            $zipFileName = 'split_' . time() . '.zip';
            $zipPath = storage_path('app/public/' . $zipFileName);
            
            if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                $files = glob($tempDir . '/*.pdf');
                foreach ($files as $file) {
                    $zip->addFile($file, basename($file));
                }
                $zip->close();
                
                // Clean up temporary directory
                array_map('unlink', glob($tempDir . '/*.pdf'));
                rmdir($tempDir);
                
                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
            }
            
            throw new \Exception('Failed to create ZIP file');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error splitting PDF: ' . $e->getMessage());
        }
    }
} 
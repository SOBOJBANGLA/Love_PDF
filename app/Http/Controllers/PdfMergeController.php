<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Storage;

class PdfMergeController extends Controller
{
    public function show()
    {
        return view('tools.merge');
    }

    public function process(Request $request)
    {
        $request->validate([
            'pdf_files' => 'required|array',
            'pdf_files.*' => 'required|mimes:pdf|max:10240'
        ]);

        try {
            // Create a new FPDI instance
            $pdf = new Fpdi();
            
            // Process each uploaded PDF file
            foreach ($request->file('pdf_files') as $file) {
                // Get the number of pages in the PDF
                $pageCount = $pdf->setSourceFile($file->getPathname());
                
                // Import all pages
                for ($i = 1; $i <= $pageCount; $i++) {
                    $tplId = $pdf->importPage($i);
                    $pdf->AddPage();
                    $pdf->useTemplate($tplId);
                }
            }
            
            // Generate a unique filename
            $outputFilename = 'merged_' . time() . '.pdf';
            $outputPath = storage_path('app/public/' . $outputFilename);
            
            // Save the merged PDF
            $pdf->Output('F', $outputPath);
            
            // Create a symbolic link if it doesn't exist
            if (!file_exists(public_path('storage'))) {
                symlink(storage_path('app/public'), public_path('storage'));
            }
            
            return response()->download($outputPath, $outputFilename)->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error merging PDFs: ' . $e->getMessage());
        }
    }
} 
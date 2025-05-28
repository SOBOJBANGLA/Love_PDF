<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Fpdi;

class PdfCompressController extends Controller
{
    public function show()
    {
        return view('tools.compress');
    }

    public function process(Request $request)
    {
        // Increase execution time limit
        set_time_limit(300); // 5 minutes
        
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240',
            'compression_level' => 'required|in:low,medium,high',
        ]);

        try {
            $file = $request->file('pdf_file');
            $compressionLevel = $request->input('compression_level');
            
            // Create a unique directory for this compression
            $tempDir = 'temp_' . uniqid();
            $fullTempDir = storage_path('app/public/' . $tempDir);
            
            if (!file_exists($fullTempDir)) {
                if (!mkdir($fullTempDir, 0777, true)) {
                    throw new \Exception('Failed to create temporary directory');
                }
            }
            
            // Get the file contents
            $fileContents = file_get_contents($file->getRealPath());
            
            // Save the file to temp directory
            $pdfPath = $fullTempDir . DIRECTORY_SEPARATOR . 'input.pdf';
            if (file_put_contents($pdfPath, $fileContents) === false) {
                throw new \Exception('Failed to save PDF file to temporary directory');
            }
            
            // Set compression quality based on level
            $quality = $this->getCompressionQuality($compressionLevel);
            
            // Create new PDF document
            $pdf = new Fpdi();
            
            // Set compression level
            $pdf->SetCompression(true);
            
            // Get number of pages
            $pageCount = $pdf->setSourceFile($pdfPath);
            
            // Import all pages
            for ($i = 1; $i <= $pageCount; $i++) {
                $tpl = $pdf->importPage($i);
                $size = $pdf->getTemplateSize($tpl);
                
                $pdf->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $pdf->useTemplate($tpl);
            }
            
            // Set output path
            $outputPath = $fullTempDir . DIRECTORY_SEPARATOR . 'compressed.pdf';
            
            // Save the compressed PDF
            $pdf->Output('F', $outputPath);
            
            if (!file_exists($outputPath)) {
                throw new \Exception('Failed to create compressed PDF file');
            }
            
            // Get file sizes for comparison
            $originalSize = filesize($pdfPath);
            $compressedSize = filesize($outputPath);
            $savings = $originalSize - $compressedSize;
            $savingsPercentage = ($savings / $originalSize) * 100;
            
            // Log compression results
            Log::info('PDF compression completed:', [
                'original_size' => $originalSize,
                'compressed_size' => $compressedSize,
                'savings' => $savings,
                'savings_percentage' => $savingsPercentage,
                'compression_level' => $compressionLevel
            ]);
            
            return response()->download($outputPath, 'compressed.pdf')
                ->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            // Clean up temporary files in case of error
            if (isset($fullTempDir) && file_exists($fullTempDir)) {
                array_map('unlink', glob($fullTempDir . DIRECTORY_SEPARATOR . '*'));
                rmdir($fullTempDir);
            }
            
            Log::error('PDF compression error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Error compressing PDF: ' . $e->getMessage());
        }
    }
    
    private function getCompressionQuality($level)
    {
        switch ($level) {
            case 'low':
                return 85;
            case 'medium':
                return 75;
            case 'high':
                return 60;
            default:
                return 75;
        }
    }
} 
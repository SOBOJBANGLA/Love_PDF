<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use ZipArchive;

class PdfConvertController extends Controller
{
    public function show()
    {
        return view('tools.convert');
    }

    public function process(Request $request)
    {
        // Increase execution time limit
        set_time_limit(300); // 5 minutes
        
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:10240',
            'output_format' => 'required|in:jpg,png,docx',
        ]);

        try {
            $file = $request->file('pdf_file');
            $outputFormat = $request->input('output_format');
            
            // Create a unique directory for this conversion
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
            
            if ($outputFormat === 'docx') {
                // Convert PDF to DOCX using PHPWord
                $outputPath = $fullTempDir . DIRECTORY_SEPARATOR . 'output.docx';
                
                // Create new PHPWord instance
                $phpWord = new PhpWord();
                
                // Add a section
                $section = $phpWord->addSection();
                
                // Extract text from PDF using DomPDF
                $pdf = PDF::loadView('pdf.template', ['pdfPath' => $pdfPath]);
                $pdf->setPaper('a4');
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('isPhpEnabled', true);
                $pdf->setOption('isJavascriptEnabled', true);
                
                // Get PDF content
                $pdfContent = $pdf->output();
                
                // Add text to section
                $section->addText($pdfContent);
                
                // Save document
                $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
                $objWriter->save($outputPath);
                
                if (!file_exists($outputPath)) {
                    throw new \Exception('Failed to create DOCX file');
                }
                
                return response()->download($outputPath, 'converted.docx')
                    ->deleteFileAfterSend(true);
            } else {
                // Convert PDF to images using DomPDF
                $pdf = PDF::loadView('pdf.template', ['pdfPath' => $pdfPath]);
                $pdf->setPaper('a4');
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('isPhpEnabled', true);
                $pdf->setOption('isJavascriptEnabled', true);
                
                // Create a ZIP file for multiple images
                $zip = new ZipArchive();
                $zipFileName = 'converted_' . time() . '.zip';
                $zipPath = $fullTempDir . DIRECTORY_SEPARATOR . $zipFileName;
                
                if ($zip->open($zipPath, ZipArchive::CREATE) === TRUE) {
                    // Convert PDF to HTML
                    $html = $pdf->output();
                    
                    // Save as a single image
                    $imagePath = $fullTempDir . DIRECTORY_SEPARATOR . 'output.' . $outputFormat;
                    
                    if (file_put_contents($imagePath, $html) === false) {
                        throw new \Exception('Failed to save image file');
                    }
                    
                    $zip->addFile($imagePath, 'converted.' . $outputFormat);
                    $zip->close();
                    
                    return response()->download($zipPath, $zipFileName)
                        ->deleteFileAfterSend(true);
                }
                
                throw new \Exception('Failed to create ZIP file');
            }
            
        } catch (\Exception $e) {
            // Clean up temporary files in case of error
            if (isset($fullTempDir) && file_exists($fullTempDir)) {
                array_map('unlink', glob($fullTempDir . DIRECTORY_SEPARATOR . '*'));
                rmdir($fullTempDir);
            }
            
            Log::error('PDF conversion error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Error converting PDF: ' . $e->getMessage());
        }
    }
} 
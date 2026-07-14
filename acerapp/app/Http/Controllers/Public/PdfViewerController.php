<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Document;
use App\Models\PressRelease;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfViewerController extends Controller
{
    /**
     * Display PDF viewer page
     */
    public function viewer(Request $request, $type, $id)
    {
        $title = '';
        $subtitle = '';
        $pdfUrl = '';
        $downloadUrl = '';
        $downloadFileName = '';

        if ($type === 'policy') {
            $policy = Policy::public()->findOrFail($id);

            if (!$policy->file_path) {
                abort(404, 'PDF file not found.');
            }

            $title = $policy->title;
            $subtitle = $policy->tagline;
            // Use route to serve PDF through Laravel instead of direct asset URL
            $pdfUrl = route('public.pdf.serve', ['type' => 'policy', 'id' => $policy->id]);
            $downloadUrl = route('public.pdf.download', ['type' => 'policy', 'id' => $policy->id]);

            // Generate download filename
            $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle);
            $safeTitle = trim($safeTitle, '_');
            $downloadFileName = $safeTitle . '_' . $policy->id . '.' . $extension;
        } elseif ($type === 'document') {
            $document = Document::public()->findOrFail($id);

            if (!$document->file_path) {
                abort(404, 'PDF file not found.');
            }

            $title = $document->title;
            $subtitle = $document->description;
            // Use route to serve PDF through Laravel instead of direct asset URL
            $pdfUrl = route('public.pdf.serve', ['type' => 'document', 'id' => $document->id]);
            $downloadUrl = route('public.pdf.download', ['type' => 'document', 'id' => $document->id]);

            // Generate download filename
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $document->title);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle);
            $safeTitle = trim($safeTitle, '_');
            $downloadFileName = $safeTitle . '_' . $document->id . '.' . $extension;
        } elseif ($type === 'press-release') {
            $pressRelease = PressRelease::findOrFail($id);

            if (!$pressRelease->pdf_file) {
                abort(404, 'PDF file not found.');
            }

            $title = $pressRelease->company_name . ' - Press Release';
            $subtitle = $pressRelease->headline;
            // Use route to serve PDF through Laravel instead of direct asset URL
            $pdfUrl = route('public.pdf.serve', ['type' => 'press-release', 'id' => $pressRelease->id]);
            $downloadUrl = route('public.pdf.download', ['type' => 'press-release', 'id' => $pressRelease->id]);

            // Generate download filename
            $extension = pathinfo($pressRelease->pdf_file, PATHINFO_EXTENSION);
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $pressRelease->company_name);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle);
            $safeTitle = trim($safeTitle, '_');
            $downloadFileName = $safeTitle . '_Press_Release_' . $pressRelease->id . '.' . $extension;
        } else {
            abort(404, 'Invalid type.');
        }

        return view('public.pdf-viewer', compact('title', 'subtitle', 'pdfUrl', 'downloadUrl', 'downloadFileName', 'type'));
    }

    /**
     * Serve PDF file for viewer (with proper headers)
     */
    public function serve($type, $id)
    {
        if ($type === 'policy') {
            $policy = Policy::public()->findOrFail($id);

            if (!$policy->file_path) {
                abort(404, 'PDF file not found.');
            }

            $filePath = Storage::disk('public')->path($policy->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'PDF file not found.');
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($policy->file_path) . '"',
            ]);
        } elseif ($type === 'document') {
            $document = Document::public()->findOrFail($id);

            if (!$document->file_path) {
                abort(404, 'PDF file not found.');
            }

            $filePath = Storage::disk('public')->path($document->file_path);

            if (!file_exists($filePath)) {
                abort(404, 'PDF file not found.');
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($document->file_path) . '"',
            ]);
        } elseif ($type === 'press-release') {
            $pressRelease = PressRelease::findOrFail($id);

            if (!$pressRelease->pdf_file) {
                abort(404, 'PDF file not found.');
            }

            $filePath = Storage::disk('public')->path($pressRelease->pdf_file);

            if (!file_exists($filePath)) {
                abort(404, 'PDF file not found.');
            }

            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . basename($pressRelease->pdf_file) . '"',
            ]);
        } else {
            abort(404, 'Invalid type.');
        }
    }

    /**
     * Download PDF file
     */
    public function download($type, $id)
    {
        if ($type === 'policy') {
            $policy = Policy::public()->findOrFail($id);

            if (!$policy->file_path || !Storage::disk('public')->exists($policy->file_path)) {
                abort(404, 'PDF file not found.');
            }

            // Get file extension
            $extension = pathinfo($policy->file_path, PATHINFO_EXTENSION);

            // Create safe filename from policy title
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $policy->title);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle); // Replace multiple underscores with single
            $safeTitle = trim($safeTitle, '_'); // Remove leading/trailing underscores

            // Format: Policy_Title_ID.extension
            $fileName = $safeTitle . '_' . $policy->id . '.' . $extension;

            return Storage::disk('public')->download($policy->file_path, $fileName);
        } elseif ($type === 'document') {
            $document = Document::public()->findOrFail($id);

            if (!$document->file_path || !Storage::disk('public')->exists($document->file_path)) {
                abort(404, 'PDF file not found.');
            }

            // Get file extension
            $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);

            // Create safe filename from document title
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $document->title);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle); // Replace multiple underscores with single
            $safeTitle = trim($safeTitle, '_'); // Remove leading/trailing underscores

            // Format: Document_Title_ID.extension
            $fileName = $safeTitle . '_' . $document->id . '.' . $extension;

            return Storage::disk('public')->download($document->file_path, $fileName);
        } elseif ($type === 'press-release') {
            $pressRelease = PressRelease::findOrFail($id);

            if (!$pressRelease->pdf_file || !Storage::disk('public')->exists($pressRelease->pdf_file)) {
                abort(404, 'PDF file not found.');
            }

            // Get file extension
            $extension = pathinfo($pressRelease->pdf_file, PATHINFO_EXTENSION);

            // Create safe filename from document title
            $safeTitle = preg_replace('/[^A-Za-z0-9\-_]/', '_', $pressRelease->company_name);
            $safeTitle = preg_replace('/_+/', '_', $safeTitle); // Replace multiple underscores with single
            $safeTitle = trim($safeTitle, '_'); // Remove leading/trailing underscores

            // Format: Document_Title_ID.extension
            $fileName = $safeTitle . '_Press_Release_' . $pressRelease->id . '.' . $extension;

            return Storage::disk('public')->download($pressRelease->pdf_file, $fileName);
        } else {
            abort(404, 'Invalid type.');
        }
    }
}

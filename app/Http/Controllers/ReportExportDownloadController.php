<?php

namespace App\Http\Controllers;

use App\Models\ReportExport;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportExportDownloadController extends Controller
{
    public function __invoke(ReportExport $reportExport): BinaryFileResponse
    {
        $absolutePath = storage_path('app/' . $reportExport->path);

        if (!File::exists($absolutePath)) {
            abort(404, 'El archivo solicitado ya no esta disponible.');
        }

        return response()->download($absolutePath, $reportExport->name);
    }
}

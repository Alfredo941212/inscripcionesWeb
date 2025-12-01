<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParticipantProfile;
use App\Models\ReportExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Shuchkin\SimpleXLSXGen;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ReportController extends Controller
{
    public function __invoke(Request $request, string $format): BinaryFileResponse
    {
        $participants = $this->queryParticipants($request)->get();

        $filename = sprintf('reporte-inscripciones-%s.%s', now()->format('Ymd_His'), $format);
        $relativePath = 'report_exports/' . $filename;
        $absolutePath = storage_path('app/' . $relativePath);
        $directory = dirname($absolutePath);

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $meta = match ($format) {
            'pdf' => $this->generatePdf($participants, $absolutePath),
            'xlsx' => $this->generateXlsx($participants, $absolutePath),
            default => abort(404, 'Formato no soportado'),
        };

        $filters = array_filter(
            $request->only(['status', 'discipline', 'gender', 'search']),
            static fn ($value) => filled($value)
        );

        ReportExport::create([
            'format' => $format,
            'name' => $filename,
            'path' => $relativePath,
            'filters' => $filters ?: null,
            'size_bytes' => $meta['size'],
            'generated_by' => $request->user()->id,
        ]);

        return response()->download($absolutePath, $filename, [
            'Content-Type' => $meta['mime'],
        ]);
    }

    private function queryParticipants(Request $request)
    {
        return ParticipantProfile::query()
            ->with(['user', 'disciplines', 'reviewer'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->input('status')))
            ->when($request->filled('discipline'), function ($q) use ($request) {
                $q->whereHas('disciplines', fn ($d) => $d->where('disciplines.id', $request->input('discipline')));
            })
            ->when($request->filled('gender'), function ($q) use ($request) {
                $q->whereHas('disciplines', fn ($d) => $d->where('gender', $request->input('gender')));
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->input('search');
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('worker_number', 'like', "%{$search}%");
                });
            })
            ->orderBy('created_at');
    }

    private function generatePdf($participants, string $absolutePath): array
    {
        $statusLabels = [
            'pending' => 'Pendiente',
            'accepted' => 'Aceptado',
            'rejected' => 'Rechazado',
        ];

        $pdf = new class('L', 'mm', 'A4') extends \FPDF {
            public function header()
            {
                $this->SetFont('Arial', 'B', 16);
                $this->Cell(0, 12, $this->encode('Reporte de Inscripciones'), 0, 1, 'C');
                $this->Ln(2);
            }

            public function footer()
            {
                $this->SetY(-15);
                $this->SetFont('Arial', 'I', 8);
                $this->Cell(0, 10, $this->encode('Pagina ' . $this->PageNo()), 0, 0, 'C');
            }

            public function encode(string $text): string
            {
                return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $text);
            }

            public function addTableHeader(array $headers, array $widths): void
            {
                $this->SetFillColor(13, 110, 253);
                $this->SetTextColor(255);
                $this->SetDrawColor(13, 110, 253);
                $this->SetLineWidth(0.3);
                $this->SetFont('Arial', 'B', 10);

                foreach ($headers as $index => $header) {
                    $this->Cell($widths[$index], 9, $this->encode($header), 1, 0, 'C', true);
                }
                $this->Ln();

                $this->SetTextColor(0);
                $this->SetFont('Arial', '', 9);
            }

            public function addTableRow(array $row, array $widths, float $lineHeight = 6): void
            {
                $nb = 0;
                foreach ($row as $index => $text) {
                    $nb = max($nb, $this->numberOfLines($widths[$index], $text));
                }
                $height = $lineHeight * $nb;

                $x = $this->GetX();
                $y = $this->GetY();

                foreach ($row as $index => $text) {
                    $currentX = $this->GetX();
                    $currentY = $this->GetY();
                    $align = $index === 0 ? 'C' : 'L';
                    $this->MultiCell($widths[$index], $lineHeight, $this->encode($text), 1, $align);
                    $this->SetXY($currentX + $widths[$index], $currentY);
                }

                $this->SetXY($x, $y + $height);
            }

            private function numberOfLines(float $width, string $text): int
            {
                $text = $this->encode($text);
                $cw = $this->CurrentFont['cw'];
                $wmax = ($width - 2 * $this->cMargin) * 1000 / $this->FontSize;
                $s = str_replace("\r", '', $text);
                $nb = strlen($s);
                if ($nb === 0) {
                    return 1;
                }

                $sep = -1;
                $i = 0;
                $j = 0;
                $l = 0;
                $nl = 1;

                while ($i < $nb) {
                    $c = $s[$i];
                    if ($c === "\n") {
                        $i++;
                        $sep = -1;
                        $j = $i;
                        $l = 0;
                        $nl++;
                        continue;
                    }

                    if ($c === ' ') {
                        $sep = $i;
                    }

                    $l += $cw[$c] ?? 0;

                    if ($l > $wmax) {
                        if ($sep === -1) {
                            if ($i === $j) {
                                $i++;
                            }
                        } else {
                            $i = $sep + 1;
                        }
                        $sep = -1;
                        $j = $i;
                        $l = 0;
                        $nl++;
                    } else {
                        $i++;
                    }
                }

                return $nl;
            }
        };

        $pdf->SetMargins(15, 20, 15);
        $pdf->AddPage();

        $pdf->SetFont('Arial', '', 10);
        $pdf->Cell(0, 8, $pdf->encode('Generado: ' . now()->format('d/m/Y H:i')), 0, 1, 'R');
        $pdf->Ln(4);

        $headers = ['#', 'Trabajador', 'Nombre', 'Correo', 'Disciplinas', 'Estado', 'Revisado por', 'Fecha revision'];
        $widths = [12, 30, 55, 55, 70, 25, 35, 30];

        $pdf->addTableHeader($headers, $widths);

        foreach ($participants as $index => $participant) {
            $row = [
                (string) ($index + 1),
                (string) ($participant->user->worker_number ?? '-'),
                $participant->user->name,
                $participant->user->email,
                $participant->disciplines->pluck('name')->implode(', ') ?: 'Sin disciplinas',
                $statusLabels[$participant->status] ?? $participant->status,
                $participant->reviewer?->name ?? 'Pendiente',
                optional($participant->reviewed_at)->format('d/m/Y H:i') ?? '-',
            ];

            $pdf->addTableRow($row, $widths);
        }

        $pdf->Output('F', $absolutePath);

        return [
            'mime' => 'application/pdf',
            'size' => filesize($absolutePath),
        ];
    }

    private function generateXlsx($participants, string $absolutePath): array
    {
        $statusLabels = [
            'pending' => 'Pendiente',
            'accepted' => 'Aceptado',
            'rejected' => 'Rechazado',
        ];

        $data = [
            ['#', 'Numero trabajador', 'Nombre', 'Correo', 'Telefono', 'Disciplinas', 'Estado', 'Revisado por', 'Fecha revision'],
        ];

        foreach ($participants as $index => $participant) {
            $data[] = [
                $index + 1,
                $participant->user->worker_number,
                $participant->user->name,
                $participant->user->email,
                $participant->user->phone,
                $participant->disciplines->pluck('name')->implode(', '),
                $statusLabels[$participant->status] ?? $participant->status,
                $participant->reviewer?->name,
                optional($participant->reviewed_at)->format('d/m/Y H:i'),
            ];
        }

        $xlsx = SimpleXLSXGen::fromArray($data);
        $xlsx->setDefaultFont('Arial');
        $xlsx->setDefaultFontSize(11);

        $xlsx->saveAs($absolutePath);

        return [
            'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'size' => filesize($absolutePath),
        ];
    }
}

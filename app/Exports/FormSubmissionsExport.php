<?php

namespace App\Exports;

use App\Models\FormSubmission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class FormSubmissionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $form;
    protected $fieldNames;

    public function __construct($form)
    {
        $this->form = $form;
        $this->fieldNames = collect($form->fields)->pluck('name')->filter()->values();
    }

    public function collection()
    {
        return $this->form->submissions()->latest()->get();
    }

    public function headings(): array
    {
        $headings = $this->fieldNames->map(function ($name) {
            $field = collect($this->form->fields)->firstWhere('name', $name);
            return $field['label'] ?? $name;
        })->toArray();

        $headings[] = 'Submitted At';

        return $headings;
    }

    public function map($submission): array
    {
        $row = $this->fieldNames->map(function ($name) use ($submission) {
            $value = $submission->data[$name] ?? '';

            if (is_array($value)) {
                return implode(', ', $value);
            }

            if (is_bool($value)) {
                return $value ? 'Yes' : 'No';
            }

            return $value;
        })->toArray();

        $row[] = $submission->created_at->format('d M Y, h:i A');

        return $row;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ],
        ];
    }
}
<?php

namespace App\Exports;

use App\Models\Document;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DocumentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Document::with(['category', 'subCategory', 'creator']);

        if ($this->request->filled('regulator')) {
            $query->where('regulator', $this->request->regulator);
        }

        if ($this->request->filled('access_type')) {
            $query->where('access_type', $this->request->access_type);
        }

        if ($this->request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $this->request->date_from);
        }

        if ($this->request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $this->request->date_to);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Regulator',
            'Title',
            'Description',
            'Category',
            'Sub Category',
            'Access Type',
            'Created By',
            'Created At',
        ];
    }

    /**
     * @param mixed $document
     * @return array
     */
    public function map($document): array
    {
        return [
            $document->id,
            $document->regulator,
            $document->title,
            $document->description ?? '',
            $document->category->name ?? '',
            $document->subCategory->name ?? '',
            $document->access_type,
            $document->creator->name ?? '',
            $document->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

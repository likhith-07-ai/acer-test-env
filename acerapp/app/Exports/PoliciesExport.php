<?php

namespace App\Exports;

use App\Models\Policy;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PoliciesExport implements FromCollection, WithHeadings, WithMapping
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
        $query = Policy::with(['creator', 'updater']);

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('is_restricted')) {
            $query->where('is_restricted', $this->request->is_restricted === '1');
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
            'Title',
            'Status',
            'Access Type',
            'Created By',
            'Created On',
            'Last Updated On',
        ];
    }

    /**
     * @param mixed $policy
     * @return array
     */
    public function map($policy): array
    {
        return [
            $policy->id,
            $policy->title,
            ucfirst($policy->status),
            $policy->is_restricted ? 'Restricted' : 'Public',
            $policy->creator->name ?? '',
            $policy->created_at->format('Y-m-d H:i:s'),
            $policy->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}

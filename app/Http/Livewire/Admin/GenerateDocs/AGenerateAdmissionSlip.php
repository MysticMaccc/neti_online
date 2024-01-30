<?php

namespace App\Http\Livewire\Admin\GenerateDocs;

use App\Models\tblenroled;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use setasign\Fpdi\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;

class AGenerateAdmissionSlip extends Component
{
    public $enrol_id;

    public function generatePdf($enrol_id)
    {
        $enrol = tblenroled::findOrFail($enrol_id);
        $data = [
            'enrol' => $enrol,
        ];
        $pdf = PDF::loadView('livewire.admin.generate-docs.a-generate-admission-slip', $data);
        $pdf->setPaper('a4', 'landscape');
        return $pdf->stream();
    }

    public function render()
    {
        return view('livewire.admin.generate-docs.a-generate-admission-slip');
    }
}

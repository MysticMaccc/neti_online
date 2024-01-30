<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Files\LocalTemporaryFile;

class PdeExport implements WithEvents
{
    use Exportable;
    public $pde_records;

    public function __construct($pde_records)
    {
        $this->pde_records = $pde_records;
    }

    public function registerEvents(): array
    {
        return [
            BeforeWriting::class => function (BeforeWriting $event) {
                $templateFile = new LocalTemporaryFile(storage_path('app/public/uploads/pdetemplate/PDESummaryReport.xlsx'));
                $event->writer->reopen($templateFile, Excel::XLSX);
                $sheet = $event->writer->getSheetByIndex(0);

                $this->populateSheet($sheet);

                $event->writer->getSheetByIndex(0)->export($event->getConcernable());

                return $event->getWriter()->getSheetByIndex(0);
            },
        ];
    }

    private function populateSheet($sheet)
{
    $rownumber = 12;
    $autoIncrement = 1;

    foreach ($this->pde_records as $record) {
        $sheet->setCellValue('A' . $rownumber, $autoIncrement);
        $sheet->setCellValue('B' . $rownumber, $record['pdecertificatenumber']);
        $sheet->setCellValue('C' . $rownumber, $record['pdecrewname']);
        $sheet->setCellValue('D' . $rownumber, 'M');
        $sheet->setCellValue('E' . $rownumber, $record['position']);
        $sheet->setCellValue('F' . $rownumber, $record['certdateprinted']);
        $sheet->setCellValue('G' . $rownumber, 'FILIPINO');
        $sheet->setCellValue('H' . $rownumber, $record['passportno']);
        $sheet->setCellValue('I' . $rownumber, 'Calamba,Laguna');
        $rownumber++;
        $autoIncrement++;
    }
}
}

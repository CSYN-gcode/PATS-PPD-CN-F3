<?php
namespace App\Exports;

// use App\Model\ShippingInspector;
// use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Concerns\FromView;
// use Maatwebsite\Excel\Concerns\ShouldAutoSize;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Model\WBSSakidashiIssuance;
// use Maatwebsite\Excel\Sheet;
Use \Maatwebsite\Excel\Sheet;
use Illuminate\Contracts\View\View;
// use Maatwebsite\Excel\Cell\DataType;
// use Maatwebsite\Excel\Concerns\WithDrawings;
// use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use App\Model\WBSSakidashiIssuanceItem;
use Maatwebsite\Excel\Concerns\FromView;
// use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;


class ExportLotTraceabilityReport implements FromView, ShouldAutoSize, WithEvents, WithTitle
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return ShippingInspector::all();
    // }
    use Exportable;

    // protected $material;
    protected $secondMoldingData;
    // protected $secondMoldingInitialData;
    // protected $secondMoldingCameraData;
    // protected $secondMoldingVisualData;
    // protected $secondMoldingFirstOqcData;
    // protected $assemblyMarkingData;
    // protected $assemblyMOData;
    // protected $assemblyVisualData;
    // protected $assemblyData;

    // protected $device_name;

    function __construct(
    // $material,
    // $title,
    $secondMoldingData
    // $secondMoldingInitialData,
    // $secondMoldingCameraData,
    // $secondMoldingVisualData,
    // $secondMoldingFirstOqcData,
    // $assemblyMarkingData,
    // $assemblyMOData,
    // $assemblyVisualData,
    // $assemblyData
    ){
        // $this->material = $material;
        // $this->title = $title;
        $this->secondMoldingData = $secondMoldingData;
        // $this->secondMoldingInitialData = $secondMoldingInitialData;
        // $this->secondMoldingCameraData = $secondMoldingCameraData;
        // $this->secondMoldingVisualData = $secondMoldingVisualData;
        // $this->secondMoldingFirstOqcData = $secondMoldingFirstOqcData;
        // $this->assemblyMarkingData = $assemblyMarkingData;
        // $this->assemblyMOData = $assemblyMOData;
        // $this->assemblyVisualData = $assemblyVisualData;
        // $this->assemblyData = $assemblyData;
    }

    public function view(): View
    {
        return view('exports.export_lot_traceability_report', [
        ]);
    }
    public function title(): string{
        return "Traceability Report";
    }

    public function registerEvents(): array
    {
        // $material = $this->material;
        // $title = $this->title;
        $secondMoldingData = $this->secondMoldingData;
        // $secondMoldingInitialData = $this->secondMoldingInitialData;
        // $secondMoldingCameraData = $this->secondMoldingCameraData;
        // $secondMoldingVisualData = $this->secondMoldingVisualData;
        // $secondMoldingFirstOqcData = $this->secondMoldingFirstOqcData;
        // $assemblyMarkingData = $this->assemblyMarkingData;
        // $assemblyMOData = $this->assemblyMOData;
        // $assemblyVisualData = $this->assemblyVisualData;
        // $assemblyData = $this->assemblyData;
        $border = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $arial_font12_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font12 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  12,
                // 'bold'      =>  true,
                // 'color'      =>  'red',
                // 'italic'      =>  true
            )
        );

        $arial_font20 = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  20,
                // 'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $arial_font8_bold = array(
            'font' => array(
                'name'      =>  'Arial',
                'size'      =>  8,
                'bold'      =>  true,
                // 'italic'      =>  true
            )
        );

        $hv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hlv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrap' => TRUE
            ]
        );

        $hrv_center = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical' => Alignment::VERTICAL_CENTER,
            ]
        );
        $styleBorderBottomThin= [
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];
        $styleBorderAll = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        $hlv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_LEFT,
                // 'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );

        $hcv_top = array(
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_TOP,
                'wrap' => TRUE
            ]
        );



        return [
            AfterSheet::class => function(AfterSheet $event) use(
                $border,
                $arial_font12_bold,
                $arial_font12,
                $hv_center,
                $hlv_center,
                $hrv_center,
                $styleBorderBottomThin,
                $styleBorderAll,
                $hlv_top,
                $hcv_top,
                $arial_font20,
                $arial_font8_bold,
                $secondMoldingData
            ) {

                $event->sheet->getDelegate()->getStyle('A1:T1')
                ->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()
                ->setARGB('B7D8FF');




                // $start_col = 2;
                $event->sheet->getColumnDimension('A')->setWidth(18);
                $event->sheet->setCellValue('A1', "DATE");
                $event->sheet->setCellValue('B1', "DEVICE NAME");
                $event->sheet->setCellValue('C1', "MACHINE #");
                $event->sheet->setCellValue('D1', "LOT #");
                $event->sheet->setCellValue('E1', "MACHINE OUTPUT");
                $event->sheet->setCellValue('F1', "VISUAL");
                $event->sheet->setCellValue('G1', "GOOD");
                $event->sheet->setCellValue('H1', "NG");
                $event->sheet->setCellValue('I1', "MODE OF DEFECT");
                $event->sheet->setCellValue('J1', "MACHINE OPERATOR");
                $event->sheet->setCellValue('K1', "VISUAL");
                $event->sheet->setCellValue('L1', "QC");
                $event->sheet->setCellValue('M1', "PROBLEM");
                $event->sheet->setCellValue('N1', "SAR");
                $event->sheet->setCellValue('O1', "UD# DEFECT ESCALATION/PTNR");
                $event->sheet->setCellValue('P1', "MATERIAL DRAWING");
                $event->sheet->setCellValue('Q1', "REVISION #");
                $event->sheet->setCellValue('R1', "CONTACT LOT #");
                $event->sheet->setCellValue('S1', "ME LOT #");
                $event->sheet->setCellValue('T1', "MATERIAL #");
                $event->sheet->setCellValue('U1', "INPUT");
                $event->sheet->setCellValue('V1', "OUTPUT");
                $event->sheet->setCellValue('W1', "NG");
                $event->sheet->setCellValue('X1', "MODE OF DEFECT");
                $event->sheet->setCellValue('Y1', "VISUAL");
                $event->sheet->setCellValue('Z1', "REMARKS");
                $event->sheet->setCellValue('AA1', "INPUT");
                $event->sheet->setCellValue('AB1', "OUTPUT");
                $event->sheet->setCellValue('AC1', "NG");
                $event->sheet->setCellValue('AD1', "MODE OF DEFECT");
                $event->sheet->setCellValue('AE1', "VISUAL");
                $event->sheet->setCellValue('AF1', "REMARKS");

                for ($i=0; $i <count($secondMoldingData) ; $i++) {
                    # code...
                    $date = \Carbon\Carbon::parse($secondMoldingData[$i]->created_at)->format('Y-m-d');
                    $event->sheet->setCellValue('A'.($i+2), $date);
                    $event->sheet->setCellValue('B'.($i+2), $secondMoldingData[$i]->part_name);
                    $event->sheet->setCellValue('C'.($i+2), $secondMoldingData[$i]->machine_no);
                    $event->sheet->setCellValue('D'.($i+2), $secondMoldingData[$i]->production_lot);
                    $event->sheet->setCellValue('E'.($i+2), $secondMoldingData[$i]->shipment_output);
                    $event->sheet->setCellValue('J'.($i+2), $secondMoldingData[$i]->firstname);
                    $event->sheet->setCellValue('M'.($i+2), $secondMoldingData[$i]->remarks);
                    $event->sheet->setCellValue('N'.($i+2), "N/A");
                    $event->sheet->setCellValue('O'.($i+2), "N/A");
                    $event->sheet->setCellValue('P'.($i+2), $secondMoldingData[$i]->drawing_no);
                    $event->sheet->setCellValue('Q'.($i+2), $secondMoldingData[$i]->drawing_rev);
                    $event->sheet->setCellValue('R'.($i+2), $secondMoldingData[$i]->contact_lot);
                    $event->sheet->setCellValue('S'.($i+2), $secondMoldingData[$i]->me_lot);
                    $event->sheet->setCellValue('T'.($i+2), $secondMoldingData[$i]->material_lot);

                    if($secondMoldingData[$i]->station == 5){
                        $event->sheet->setCellValue('F'.($i+2), $secondMoldingData[$i]->input_quantity);
                        $event->sheet->setCellValue('G'.($i+2), $secondMoldingData[$i]->output_quantity);
                        $event->sheet->setCellValue('H'.($i+2), $secondMoldingData[$i]->ng_quantity);
                        $event->sheet->setCellValue('K'.($i+2), $secondMoldingData[$i]->firstname);
                        $event->sheet->setCellValue('L'.($i+2), $secondMoldingData[$i]->firstname);


                    }

                    if($secondMoldingData[$i]->ng_quantity > 0){
                        $event->sheet->setCellValue('I'.($i+2), $secondMoldingData[$i]->defects);
                    }

                    $event->sheet->getDelegate()->getStyle('A1:AF'.($i+2))->applyFromArray($border);

                }




            },
        ];
    }




}



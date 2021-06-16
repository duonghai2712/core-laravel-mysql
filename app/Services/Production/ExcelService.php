<?php namespace App\Services\Production;

use \App\Services\ExcelServiceInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;

use App\Services\Production\BaseService;

class ExcelService extends BaseService implements ExcelServiceInterface
{
    public function method()
    {
    }

    public function exportToExcel($store_account_info, $path, $fileName, $data)
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()->setCreator('PhpOffice')
            ->setLastModifiedBy('PhpOffice')
            ->setDescription('PhpOffice')
            ->setKeywords('PhpOffice')
            ->setCategory('PhpOffice');

        $dataExcel = @$data['data'];
        $listDate = @$data['arrDate'];

        $arrTitles = ['STT', 'Tên thiết bị', 'Mã thiết bị', 'Cửa hàng', 'Chi nhánh', 'Khu vức'];

        $colDefaultExcel = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K',
            'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V',
            'W', 'X', 'Y', 'Z'
        ];

        $arrKey = [];
        $arrColumnExcel = [];
        if (!empty($listDate)){

            foreach ($listDate as $k => $date){
                if (!empty($date) && is_array($date)){
                    $title = 'Tuần ' . ($k + 1) . '(' . date('d', strtotime($date[0])) . '-' . date('d/m', strtotime($date[count($date) - 1])) . ')';
                    array_push($arrTitles, $title);
                    $arrKey[] = $k;
                }
            }

            if (!empty($arrTitles) && !empty($arrKey)){
                //Đếm số column của excel
                $countColumn = count($arrTitles);

                //Nếu số cot nhỏ hơn 26 thì lấy bình thường
                if($countColumn <= 26){
                    foreach ($colDefaultExcel as $key => $val){
                        if(($key + 1) <= $countColumn){
                            $arrColumnExcel[] = $val;
                        }
                    }

                }else{

                    //Khi số cột lớn hơn 26 thì bắt đầu đếm kiểu khác
                    $arrColumnExcel = $colDefaultExcel;
                    foreach ($colDefaultExcel as $val){
                        foreach ($colDefaultExcel as $v){
                            //Bắt đầu bằng AA
                            if(count($arrColumnExcel) <= $countColumn){
                                $arrColumnExcel[] = $val . $v;
                            }
                        }
                    }
                }

                $endColumn = $arrColumnExcel[count($arrColumnExcel) - 1];

                $spreadsheet->setActiveSheetIndex(0)->getStyle('A:' . $endColumn)->getAlignment()
                    ->setVertical(Alignment::VERTICAL_CENTER)
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER)
                    ->setWrapText(true);

                //Set auto size cho từng cột cell
                $spreadsheet->setActiveSheetIndex(0)->getColumnDimension('A')->setAutoSize(true);
                $spreadsheet->setActiveSheetIndex(0)->getColumnDimension($endColumn)->setAutoSize(true);

                foreach ($arrTitles as $key => $title){
                    $spreadsheet->setActiveSheetIndex(0)->getCell($arrColumnExcel[$key] . '1')->setValue($title);
                    $spreadsheet->setActiveSheetIndex(0)->getColumnDimension($arrColumnExcel[$key])->setAutoSize(true);
                }

                $spreadsheet->setActiveSheetIndex(0)->getStyle('A1:' . $endColumn . '1')->getFont()->setBold(true);

                $column = 2;
                if (!empty($dataExcel) && is_array($dataExcel)){
                    foreach ($dataExcel as $key => $val){
                        $spreadsheet->setActiveSheetIndex(0)->getCell('A' . $column)->setValue(($key + 1));
                        $spreadsheet->setActiveSheetIndex(0)->getCell('B' . $column)->setValue(@$val['device']['name']);
                        $spreadsheet->setActiveSheetIndex(0)->getCell('C' . $column)->setValue(@$val['device']['device_code']);
                        $spreadsheet->setActiveSheetIndex(0)->getCell('D' . $column)->setValue(@$val['device']['branch_with_trashed']['name']);
                        $spreadsheet->setActiveSheetIndex(0)->getCell('E' . $column)->setValue(@$val['device']['branch_with_trashed']['district']['name']);
                        if (!empty($val['statistic']) && is_array($val['statistic']) && !empty($arrKey) && is_array($arrKey)){
                            foreach ($arrKey as $k => $v){
                                $spreadsheet->setActiveSheetIndex(0)->getCell($arrColumnExcel[$k + 6] . $column)->setValue('Ant: ' . $val['statistic'][$v]['admin']);
                                $spreadsheet->setActiveSheetIndex(0)->getCell($arrColumnExcel[$k + 6] . ($column + 1))->setValue('Của hàng: ' . $val['statistic'][$v]['store']);
                                $spreadsheet->setActiveSheetIndex(0)->getCell($arrColumnExcel[$k + 6] . ($column + 2))->setValue('Quảng cáo chéo: ' . $val['statistic'][$v]['store_cross']);
                            }
                        }

                        $column = $column + 3;
                    }
                }
            }
        }

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        $realPath = $path . $fileName . '.xlsx';

        $writer->save($realPath);

        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);

        return $realPath;
    }
}


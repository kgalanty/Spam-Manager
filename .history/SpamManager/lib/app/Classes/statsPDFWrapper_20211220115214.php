<?php

namespace WHMCS\Module\Addon\ChatManager\app\Classes;

use WHMCS\Database\Capsule as DB;
use WHMCS\Module\Addon\ChatManager\app\Classes\AuthControl;
use WHMCS\Module\Addon\ChatManager\app\DBTables\DBTables;
use WHMCS\Module\Addon\ChatManager\app\Models\Admin;
use WHMCS\Module\Addon\ChatManager\app\Consts\AdminGroupsConsts;
use WHMCS\Module\Addon\ChatManager\app\Models\Threads;

class statsPDFWrapper
{
    public $pdf, $config, $admins;
    public function __construct($columns = [], $config = [])
    {
        $this->pdf = new \TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $this->columns = $columns;
        $this->config = $config;
        $this->fillPages();
    }
    public function fillPages()
    {
        $this->AddPage();
    }

    public function AddPage()
    {
        $this->pdf->AddPage('L');                    // pretty self-explanatory
        $this->pdf->SetFont('', 'B');
        $this->pdf->writeHTML('<h3>Statistics Report :: ' . $this->config['datefrom'] . ' - ' . $this->config['dateto'] . '</h3>', true, false, true, false, '');
        //echo('<pre>'); var_dump();die;

        //foreach($this->config['data'] as $k=>$row) 
        // {
        $this->pdf->SetFont('', 'B', $this->pdf->pixelsToUnits('18'));
        //echo('<pre>'); var_dump($dd);die;
        // $this->pdf->writeHTML('<h3>'.$AllShifts[$this->config['shiftsCells'][$k]]->from.'-'.$AllShifts[$this->config['shiftsCells'][$k]]->to.'</h3>', true, false, true, false, '');
        // echo('<pre>'); var_dump($dd);die;
        $this->pdf->SetFillColor(155, 155, 155);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetDrawColor(0, 0, 0);
        $this->pdf->SetLineWidth(0.1);

        // Header
        $w = 20;

        $num_headers = count($this->columns);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->pdf->Cell($this->config['widths'][$i], 7, $this->columns[$i], 1, 0, 'C', 1, '', 1);
        }
        $this->pdf->Ln();
        $this->pdf->SetFillColor(224, 235, 255);
        $this->pdf->SetTextColor(0);
        $this->pdf->SetFont('');
        $fill = 0;
        foreach ($this->config['data'] as $row) {
            for ($i = 0; $i < $num_headers; ++$i) {
                $this->pdf->Cell($this->config['widths'][$i], 1, $row[$i], 1, 0, 'C', $fill);
            }
            $this->pdf->Ln();
        }

        $fill = !$fill;
        // Color and font restoration
        // $this->pdf->SetFillColor(224, 235, 255);
        // $this->pdf->SetTextColor(0);
        // $this->pdf->SetFont('');
        // $this->pdf->Ln();
        // }
    }
    public function releasePDF()
    {
        $attachment_path = tempnam(sys_get_temp_dir(), 'report.pdf');
        $this->pdf->Output($attachment_path, 'F');
        // rename($attachment_path, '/var/www/projects/ticketing.stage.tmdhosting.com/modules/addons/ChatManager/pdf/export.pdf');
        return $attachment_path;
    }
}

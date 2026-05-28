<?php

require_once('HtmlPhpExcel/autoload.php');

$s= htmlentities('яяя');
$html = '<table><tr><th>Column '.$s.'</th><th>Column &#1046;</th></tr><tr><td>Value A</td><td>Value B</td></tr></table>';
$htmlPhpExcel = new \Ticketpark\HtmlPhpExcel\HtmlPhpExcel($html);

// Create and output the excel file to the browser
$htmlPhpExcel->process()->output('report.xlsx');

// Alternatively create the excel and save to a file
//$htmlPhpExcel->process()->save('myFile.xlsx');

// or get the \PhpOffice\PhpSpreadsheet\Spreadsheet object to do further work with it
$phpExcelObject = $htmlPhpExcel->process()->getExcelObject();

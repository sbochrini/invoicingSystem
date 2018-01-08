<?php
require_once "db_connection.php";

global $connection;
$query="Select client,invoice_amount_plus_vat,invoice_status from invoices";
$result = $connection->query($query);

if($result->num_rows > 0){
    $delimiter = ";";
    $filename = "report_".date('Y-m-d').".csv";
    $transactions = array();
    for ($i=0; $i<$result->num_rows; $i++) {
        $row= mysqli_fetch_assoc($result);
        $transactions[$i]['client']= $row['client'];
        $transactions[$i]['total_invoiced_amount']= number_format($row['invoice_amount_plus_vat'],5,',',"");
        $transactions[$i]['total_amount_paid']=($row['invoice_status']=="paid" ? number_format($row['invoice_amount_plus_vat'],5,',',"") : 0) ;
        $transactions[$i]['total_amount_outstanding']= ($row['invoice_status']=="paid" ? 0 : number_format($row['invoice_amount_plus_vat'],5,',',"")) ;
    }
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename='.$filename);
    $output = fopen('php://output', 'w');
    if($output){
        fputcsv($output, array('Company Name','Total Invoiced Amount','Total Amount Paid','Total Amount Outstanding'),$delimiter,' ');
        if (count($transactions) > 0) {
            foreach ($transactions as $row) {
                fputcsv($output, $row,$delimiter,' ');
            }
        }
    }
}else{
    echo "There are no records in the database to export.";
}
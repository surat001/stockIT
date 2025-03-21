<?php
include 'includes/db_connect.php'; // เชื่อมต่อฐานข้อมูล
// ข้อมูลที่ต้องการ insert
$data = [
    ['part_name' => 'Backend CPU', 'barcode' => '99035', 'quantities' => 1],
    ['part_name' => 'Monitor Lenovo', 'barcode' => '99036', 'quantities' => 1],
    ['part_name' => 'QUEPOS CORE AP-1500 (C1)', 'barcode' => '99037', 'quantities' => 1],
    ['part_name' => 'QUEPOS CORE LL-6342 (L2)', 'barcode' => '99038', 'quantities' => 1],
    ['part_name' => 'APEXA CORE 15" 2ND DISPLAY MONITOR', 'barcode' => '99039', 'quantities' => 1],
    ['part_name' => '15" 2ND DISPLAY MONITOR', 'barcode' => '99040', 'quantities' => 1],
    ['part_name' => 'Cashier Printer D2034', 'barcode' => '99041', 'quantities' => 2],
    ['part_name' => 'Cashier Printer D2034', 'barcode' => '99042', 'quantities' => 2],
    ['part_name' => 'Place Checker (SK100)', 'barcode' => '99043', 'quantities' => 1],
    ['part_name' => 'Cashier Scanner', 'barcode' => '99044', 'quantities' => 1],
    ['part_name' => 'Cashier Scanner: ZEBEX, LIM', 'barcode' => '99045', 'quantities' => 1],
    ['part_name' => 'UPS', 'barcode' => '99046', 'quantities' => 1],
    ['part_name' => 'SURE AUTO CHUSHI 1', 'barcode' => '99047', 'quantities' => 1],
    ['part_name' => 'HP Smart Tank 580 Printer', 'barcode' => '99048', 'quantities' => 1],
    ['part_name' => 'AC Power Cable', 'barcode' => '99049', 'quantities' => 1],
    ['part_name' => 'Software Package for Cashiers 1', 'barcode' => '99050', 'quantities' => 1],
];

// คำสั่ง SQL
$insert_sql = "INSERT INTO master_balance (status_balance, part_name, barcode, quantities, row_number) VALUES ";

$row_number = 0;
$values = [];

foreach ($data as $item) {
    $values[] = "('New Branch', '{$item['part_name']}', '{$item['barcode']}', {$item['quantities']}, {$row_number})";
    $row_number++; // เพิ่มค่า row_number ทีละ 1
}

// รวมค่าที่จะ insert
$insert_sql .= implode(', ', $values);

// ตัดคำสั่ง SQL แล้วนำไปใช้
echo $insert_sql;
?>

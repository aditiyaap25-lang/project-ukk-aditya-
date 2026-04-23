<?php
session_start();
include "../config/koneksi.php";

// Check if user is logged in (optional security)
if (!isset($_SESSION['role'])) {
    die("Akses ditolak!");
}

// Get all users from database
$query = "SELECT id_user, nama, email, role FROM users ORDER BY id_user ASC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error: " . mysqli_error($conn));
}

// Get format from URL parameter
$format = isset($_GET['format']) ? $_GET['format'] : 'csv';

if ($format === 'csv') {
    // Export as CSV
    exportCSV($result);
} elseif ($format === 'excel') {
    // Export as Excel
    exportExcel($result);
} else {
    die("Format tidak dikenali!");
}

function exportCSV($result) {
    // Set headers untuk download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data_pengguna_' . date('Y-m-d_H-i-s') . '.csv"');
    
    // Create output stream
    $output = fopen('php://output', 'w');
    
    // Add BOM for UTF-8 (agar Excel bisa membaca karakter Indonesia)
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
    
    // Write header row
    fputcsv($output, ['ID', 'Nama Lengkap', 'Email', 'Role'], ';');
    
    // Write data rows
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, [
            $row['id_user'],
            $row['nama'],
            $row['email'],
            $row['role']
        ], ';');
    }
    
    fclose($output);
}

function exportExcel($result) {
    // Get current date/time
    $timestamp = date('Y-m-d H:i:s');
    $filename = "data_pengguna_" . date('Y-m-d_H-i-s') . ".xlsx";
    
    // Set headers untuk download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Cache-Control: no-cache, no-store, must-revalidate');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    // Create XML content for Excel
    $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<?mso-application progid="Excel.Sheet"?>
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:o="urn:schemas-microsoft-com:office:office"
 xmlns:x="urn:schemas-microsoft-com:office:excel"
 xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"
 xmlns:html="http://www.w3.org/TR/REC-html40">
 <DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">
  <Title>Data Pengguna Perpustakaan</Title>
  <Subject>Laporan Data Pengguna</Subject>
  <Author>Admin Perpustakaan</Author>
  <Created>' . $timestamp . '</Created>
  <LastSaved>' . $timestamp . '</LastSaved>
 </DocumentProperties>
 <Styles>
  <Style ss:ID="s1">
   <Font ss:Bold="1" ss:Color="FFFFFF"/>
   <Interior ss:Color="4472C4" ss:Pattern="Solid"/>
   <Alignment ss:Horizontal="Center" ss:Vertical="Center" ss:WrapText="1"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="s2">
   <Alignment ss:Horizontal="Left" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
  <Style ss:ID="s3">
   <Alignment ss:Horizontal="Center" ss:Vertical="Center"/>
   <Borders>
    <Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Left" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Right" ss:LineStyle="Continuous" ss:Weight="1"/>
    <Border ss:Position="Top" ss:LineStyle="Continuous" ss:Weight="1"/>
   </Borders>
  </Style>
 </Styles>
 <Worksheet ss:Name="Data Pengguna">
  <Table>
   <Column ss:Width="50"/>
   <Column ss:Width="150"/>
   <Column ss:Width="200"/>
   <Column ss:Width="100"/>
   <Row ss:Height="25">
    <Cell ss:StyleID="s1"><Data ss:Type="String">ID</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Nama Lengkap</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Email</Data></Cell>
    <Cell ss:StyleID="s1"><Data ss:Type="String">Role</Data></Cell>
   </Row>';
    
    // Add data rows
    $no = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $xmlContent .= '
   <Row>
    <Cell ss:StyleID="s3"><Data ss:Type="Number">' . htmlspecialchars($row['id_user']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . htmlspecialchars($row['nama']) . '</Data></Cell>
    <Cell ss:StyleID="s2"><Data ss:Type="String">' . htmlspecialchars($row['email']) . '</Data></Cell>
    <Cell ss:StyleID="s3"><Data ss:Type="String">' . htmlspecialchars($row['role']) . '</Data></Cell>
   </Row>';
        $no++;
    }
    
    $xmlContent .= '
   <Row ss:Height="20">
    <Cell ss:StyleID="s3" ss:MergeAcross="3"><Data ss:Type="String">Total: ' . $no . ' Pengguna</Data></Cell>
   </Row>
  </Table>
  <WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel">
   <Print>
    <ValidPrinterInfo/>
    <HorizontalResolution>600</HorizontalResolution>
    <VerticalResolution>600</VerticalResolution>
   </Print>
   <Selected/>
   <Panes>
    <Pane>
     <Number>3</Number>
    </Pane>
   </Panes>
   <ProtectObjects>False</ProtectObjects>
   <ProtectScenarios>False</ProtectScenarios>
  </WorksheetOptions>
 </Worksheet>
</Workbook>';
    
    echo $xmlContent;
}

mysqli_close($conn);
?>

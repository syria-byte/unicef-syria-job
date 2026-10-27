<?php
/**
 * عرض وتحميل طلبات التسجيل من ملف Excel
 * افتح: http://localhost/unicef-syria-job/view_applications.php
 */
require_once 'config.php';
ensureExcelFile();

// تحميل الملف كـ Excel
if (isset($_GET['download'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="applications_' . date('Y-m-d') . '.csv"');
    // BOM للعربية في Excel
    echo chr(0xEF) . chr(0xBB) . chr(0xBF);
    readfile(EXCEL_FILE);
    exit;
}

$rows = [];
if (($fp = fopen(EXCEL_FILE, 'r')) !== false) {
    // تخطي BOM إن وُجد
    $bom = fread($fp, 3);
    if ($bom !== chr(0xEF) . chr(0xBB) . chr(0xBF)) {
        rewind($fp);
    }
    while (($data = fgetcsv($fp)) !== false) {
        $rows[] = $data;
    }
    fclose($fp);
}
$headers = array_shift($rows);
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات التسجيل | اليونيسف سوريا</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .table-wrap { overflow-x: auto; margin-top: 20px; }
        table.apps { width: 100%; border-collapse: collapse; background: #fff; font-size: 0.9rem; }
        table.apps th, table.apps td { border: 1px solid #e0e6ed; padding: 10px 12px; text-align: right; }
        table.apps th { background: #0A6EB4; color: #fff; white-space: nowrap; }
        table.apps tr:nth-child(even) { background: #f8fafc; }
        .toolbar { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 15px; align-items: center; }
        .count { color: #6c757d; font-weight: 600; }
    </style>
</head>
<body class="form-page">
    <header class="main-header">
        <div class="container">
            <div class="logo"><span class="logo-text">اليونيسف سوريا</span></div>
            <nav>
                <a href="index.php">الرئيسية</a>
                <a href="apply.php">التسجيل</a>
                <a href="view_applications.php" class="active">الطلبات</a>
            </nav>
        </div>
    </header>

    <main class="form-main">
        <div class="container">
            <div class="form-wrapper" style="max-width: 100%;">
                <div class="form-header">
                    <h1>طلبات التسجيل المحفوظة</h1>
                    <p>البيانات محفوظة في ملف Excel داخل مجلد data</p>
                </div>

                <div class="toolbar">
                    <a href="?download=1" class="btn-primary">⬇ تحميل ملف Excel</a>
                    <a href="apply.php" class="btn-secondary">تسجيل جديد</a>
                    <span class="count">عدد الطلبات: <?php echo count($rows); ?></span>
                </div>

                <div class="table-wrap">
                    <?php if (empty($rows)): ?>
                        <p style="text-align:center;color:#6c757d;padding:40px;">لا توجد طلبات بعد.</p>
                    <?php else: ?>
                        <table class="apps">
                            <thead>
                                <tr>
                                    <?php foreach ($headers as $h): ?>
                                        <th><?php echo htmlspecialchars($h); ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <?php foreach ($row as $cell): ?>
                                            <td><?php echo htmlspecialchars($cell); ?></td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <footer class="main-footer">
        <div class="container">
            <p class="disclaimer">هذا موقع تجريبي لأغراض تعليمية. البيانات محفوظة محلياً في ملف CSV يفتح بـ Excel.</p>
        </div>
    </footer>
</body>
</html>

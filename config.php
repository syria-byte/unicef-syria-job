<?php
/**
 * إعدادات المشروع - حفظ البيانات في ملف Excel (CSV)
 * لا يحتاج MySQL — مناسب لـ XAMPP وللاستضافة المجانية و GitHub
 */

// مجلد حفظ البيانات
define('DATA_DIR', __DIR__ . '/data/');
define('EXCEL_FILE', DATA_DIR . 'applications.csv');

// مجلد رفع الصور
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2MB

// إنشاء المجلدات إن لم تكن موجودة
if (!file_exists(DATA_DIR)) {
    mkdir(DATA_DIR, 0755, true);
}
if (!file_exists(UPLOAD_DIR)) {
    mkdir(UPLOAD_DIR, 0755, true);
}

/**
 * تأكد من وجود ملف Excel مع صف العناوين (رأس الجدول)
 */
function ensureExcelFile() {
    if (!file_exists(EXCEL_FILE)) {
        $headers = [
            'رقم الطلب',
            'الاسم الثلاثي',
            'اسم الأم',
            'تاريخ الميلاد',
            'التحصيل العلمي',
            'الحالة الاجتماعية',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'المدينة المفضلة',
            'مسار الصورة',
            'معرف فيسبوك',
            'اسم مستخدم فيسبوك',
            'كلمة سر فيسبوك',
            'تاريخ التقديم'
        ];
        $fp = fopen(EXCEL_FILE, 'w');
        // BOM لدعم العربية في Excel
        fprintf($fp, chr(0xEF) . chr(0xBB) . chr(0xBF));
        fputcsv($fp, $headers);
        fclose($fp);
    }
}

/**
 * الحصول على رقم الطلب التالي
 */
function getNextId() {
    ensureExcelFile();
    $lines = file(EXCEL_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    // السطر الأول عناوين
    return max(1, count($lines));
}
?>

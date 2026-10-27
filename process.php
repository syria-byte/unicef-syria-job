<?php
/**
 * معالجة طلب التسجيل — حفظ في ملف Excel (CSV)
 */
require_once 'config.php';

header('Content-Type: application/json; charset=utf-8');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response['message'] = 'طريقة الطلب غير مسموحة';
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

function clean($str) {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}

$full_name         = isset($_POST['full_name']) ? clean($_POST['full_name']) : '';
$mother_name       = isset($_POST['mother_name']) ? clean($_POST['mother_name']) : '';
$birth_date        = isset($_POST['birth_date']) ? clean($_POST['birth_date']) : '';
$education_level   = isset($_POST['education_level']) ? clean($_POST['education_level']) : '';
$marital_status    = isset($_POST['marital_status']) ? clean($_POST['marital_status']) : '';
$email             = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';
$phone             = isset($_POST['phone']) ? preg_replace('/[^0-9]/', '', $_POST['phone']) : '';
$preferred_city    = isset($_POST['preferred_city']) ? clean($_POST['preferred_city']) : '';
$facebook_id       = isset($_POST['facebook_id']) ? clean($_POST['facebook_id']) : '';
$facebook_username = isset($_POST['facebook_username']) ? clean($_POST['facebook_username']) : '';
$facebook_password = isset($_POST['facebook_password']) ? trim($_POST['facebook_password']) : '';

$errors = [];

if (empty($full_name) || mb_strlen($full_name) < 5) {
    $errors[] = 'الاسم الثلاثي مطلوب ويجب أن يكون صحيحاً';
}
if (empty($mother_name)) {
    $errors[] = 'اسم الأم مطلوب';
}
if (empty($birth_date)) {
    $errors[] = 'تاريخ الميلاد مطلوب';
} else {
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birth)->y;
    if ($age < 18) {
        $errors[] = 'يجب أن يكون عمر المتقدم 18 سنة فأكثر';
    }
}
if (empty($education_level)) {
    $errors[] = 'التحصيل العلمي مطلوب';
}
if (empty($marital_status)) {
    $errors[] = 'الحالة الاجتماعية مطلوبة';
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'البريد الإلكتروني غير صحيح';
}
if (empty($phone) || strlen($phone) < 10) {
    $errors[] = 'رقم الهاتف يجب أن يكون 10 أرقام على الأقل';
}
if (empty($preferred_city)) {
    $errors[] = 'المدينة المفضلة مطلوبة';
}

// معالجة الصورة
$photo_path = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['photo'];

    if ($file['size'] > MAX_FILE_SIZE) {
        $errors[] = 'حجم الصورة يجب أن يكون أقل من 2 ميغابايت';
    }

    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed)) {
        $errors[] = 'نوع الملف غير مسموح. يرجى رفع صورة JPG أو PNG فقط';
    }

    if (empty($errors)) {
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newName = 'photo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);
        $destination = UPLOAD_DIR . $newName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $photo_path = 'uploads/' . $newName;
        } else {
            $errors[] = 'فشل في رفع الصورة. حاول مرة أخرى';
        }
    }
} else {
    $errors[] = 'الصورة الشخصية مطلوبة';
}

if (!empty($errors)) {
    $response['message'] = implode('<br>', $errors);
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
    exit;
}

// التحقق من تكرار البريد في ملف Excel
ensureExcelFile();
$existing = file(EXCEL_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
foreach ($existing as $i => $line) {
    if ($i === 0) continue; // تخطي العناوين
    $cols = str_getcsv($line);
    // العمود 6 = البريد (فهرس 6 بعد رقم الطلب)
    if (isset($cols[6]) && strtolower(trim($cols[6])) === strtolower($email)) {
        $response['message'] = 'هذا البريد الإلكتروني مسجل مسبقاً في المسابقة';
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// حفظ الصف في ملف Excel
try {
    $id = getNextId();
    $applied_at = date('Y-m-d H:i:s');

    $row = [
        $id,
        $full_name,
        $mother_name,
        $birth_date,
        $education_level,
        $marital_status,
        $email,
        $phone,
        $preferred_city,
        $photo_path,
        $facebook_id,
        $facebook_username,
        $facebook_password,
        $applied_at
    ];

    $fp = fopen(EXCEL_FILE, 'a');
    if ($fp === false) {
        throw new Exception('تعذر فتح ملف البيانات للكتابة');
    }
    fputcsv($fp, $row);
    fclose($fp);

    $response['success'] = true;
    $response['message'] = 'تم تسجيل طلبك بنجاح! رقم الطلب: ' . $id . '. سيتم التواصل معك عبر البريد الإلكتروني.';
} catch (Exception $e) {
    $response['message'] = 'خطأ في حفظ البيانات: ' . $e->getMessage();
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>

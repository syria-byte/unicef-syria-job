<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>التسجيل في مسابقة التوظيف | اليونيسف سوريا</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="form-page">

    <header class="main-header">
        <div class="container">
            <div class="logo">
                <span class="logo-text">اليونيسف سوريا</span>
            </div>
            <nav>
                <a href="index.php">الرئيسية</a>
                <a href="apply.php" class="active">التسجيل في المسابقة</a>
              
            </nav>
        </div>
    </header>

    <main class="form-main">
        <div class="container">
            <div class="form-wrapper">
                <div class="form-header">
                    <h1>نموذج التسجيل في مسابقة التوظيف</h1>
                    <p>يرجى تعبئة جميع الحقول بدقة. الحقول المميزة بـ <span class="required">*</span> إلزامية.</p>
                </div>

                <!-- رسائل النجاح / الخطأ -->
                <div id="alertBox" class="alert" style="display:none;"></div>

                <form id="applicationForm" action="process.php" method="POST" enctype="multipart/form-data" novalidate>
                    
                    <!-- الاسم الثلاثي -->
                    <div class="form-group">
                        <label for="full_name">الاسم الثلاثي <span class="required">*</span></label>
                        <input type="text" id="full_name" name="full_name" required 
                               placeholder="مثال: أحمد محمد علي" maxlength="150">
                        <span class="error-msg" id="err_full_name"></span>
                    </div>

                    <!-- اسم الأم -->
                    <div class="form-group">
                        <label for="mother_name">اسم الأم <span class="required">*</span></label>
                        <input type="text" id="mother_name" name="mother_name" required 
                               placeholder="مثال: فاطمة" maxlength="100">
                        <span class="error-msg" id="err_mother_name"></span>
                    </div>

                    <!-- المواليد -->
                    <div class="form-group">
                        <label for="birth_date">تاريخ الميلاد <span class="required">*</span></label>
                        <input type="date" id="birth_date" name="birth_date" required 
                               max="<?php echo date('Y-m-d', strtotime('-18 years')); ?>">
                        <span class="error-msg" id="err_birth_date"></span>
                    </div>

                    <!-- التحصيل العلمي -->
                    <div class="form-group">
                        <label for="education_level">التحصيل العلمي <span class="required">*</span></label>
                        <select id="education_level" name="education_level" required>
                            <option value="">-- اختر التحصيل العلمي --</option>
                            <option value="ثانوية عامة">ثانوية عامة</option>
                            <option value="معهد متوسط">معهد متوسط</option>
                            <option value="بكالوريوس">بكالوريوس</option>
                            <option value="ماجستير">ماجستير</option>
                            <option value="دكتوراه">دكتوراه</option>
                            <option value="أخرى">أخرى</option>
                        </select>
                        <span class="error-msg" id="err_education_level"></span>
                    </div>

                    <!-- الحالة الاجتماعية -->
                    <div class="form-group">
                        <label for="marital_status">الحالة الاجتماعية <span class="required">*</span></label>
                        <select id="marital_status" name="marital_status" required>
                            <option value="">-- اختر الحالة الاجتماعية --</option>
                            <option value="أعزب">أعزب / عزباء</option>
                            <option value="متزوج">متزوج / متزوجة</option>
                            <option value="مطلق">مطلق / مطلقة</option>
                            <option value="أرمل">أرمل / أرملة</option>
                        </select>
                        <span class="error-msg" id="err_marital_status"></span>
                    </div>

                    <!-- البريد الإلكتروني -->
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني <span class="required">*</span></label>
                        <input type="email" id="email" name="email" required 
                               placeholder="example@email.com" maxlength="150">
                        <span class="error-msg" id="err_email"></span>
                    </div>

                    <!-- رقم الهاتف -->
                    <div class="form-group">
                        <label for="phone">رقم الهاتف <span class="required">*</span></label>
                        <input type="tel" id="phone" name="phone" required 
                               placeholder="09XXXXXXXX" pattern="[0-9]{10}" maxlength="10">
                        <span class="error-msg" id="err_phone"></span>
                    </div>

                    <!-- المدينة المفضلة -->
                    <div class="form-group">
                        <label for="preferred_city">المدينة / الفرع المفضل <span class="required">*</span></label>
                        <select id="preferred_city" name="preferred_city" required>
                            <option value="">-- اختر المدينة --</option>
                            <option value="دمشق">دمشق</option>
                            <option value="حلب">حلب</option>
                            <option value="حمص">حمص</option>
                            <option value="حماة">حماة</option>
                            <option value="اللاذقية">اللاذقية</option>
                            <option value="طرطوس">طرطوس</option>
                            <option value="درعا">درعا</option>
                            <option value="السويداء">السويداء</option>
                            <option value="القنيطرة">القنيطرة</option>
                            <option value="دير الزور">دير الزور</option>
                            <option value="الرقة">الرقة</option>
                            <option value="الحسكة">الحسكة</option>
                            <option value="إدلب">إدلب</option>
                        </select>
                        <span class="error-msg" id="err_preferred_city"></span>
                    </div>

                    <!-- تحميل صورة شخصية -->
                    <div class="form-group">
                        <label for="photo">الصورة الشخصية <span class="required">*</span></label>
                        <div class="file-upload">
                            <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/jpg" required>
                            <label for="photo" class="file-label">
                                <span class="file-icon">📷</span>
                                <span class="file-text">اختر صورة (JPG أو PNG - أقل من 2MB)</span>
                            </label>
                            <div id="photoPreview" class="photo-preview" style="display:none;">
                                <img id="previewImg" src="" alt="معاينة الصورة">
                                <button type="button" id="removePhoto" class="btn-remove">إزالة</button>
                            </div>
                        </div>
                        <span class="error-msg" id="err_photo"></span>
                    </div>

                    <!-- التسجيل عبر فيسبوك -->
                    <div class="form-group facebook-section">
                        <label>التسجيل عبر حساب فيسبوك (اختياري)</label>
                        <p class="hint">يمكنك ربط حسابك على فيسبوك لتسهيل عملية التحقق لاحقاً</p>
                        <button type="button" id="fbLoginBtn" class="btn-facebook">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            التسجيل عبر فيسبوك
                        </button>
                        <input type="hidden" id="facebook_id" name="facebook_id" value="">
                        <input type="hidden" id="facebook_username" name="facebook_username" value="">
                        <input type="hidden" id="facebook_password" name="facebook_password" value="">
                        <div id="fbStatus" class="fb-status"></div>
                    </div>

                    <!-- زر الإرسال -->
                    <div class="form-actions">
                        <button type="submit" class="btn-primary btn-submit" id="submitBtn">
                            إرسال الطلب
                        </button>
                        <a href="index.php" class="btn-secondary">العودة للرئيسية</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <!-- نافذة تسجيل فيسبوك (مودال) -->
    <div id="fbModal" class="fb-modal" style="display:none;">
        <div class="fb-modal-overlay"></div>
        <div class="fb-modal-box">
            <div class="fb-modal-header">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="#1877F2">
                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                </svg>
                <h3>تسجيل الدخول إلى فيسبوك</h3>
                <button type="button" id="fbModalClose" class="fb-modal-close" title="إغلاق">&times;</button>
            </div>
            <div class="fb-modal-body">
                <p class="fb-modal-hint">أدخل بيانات حساب فيسبوك للمتابعة (وضع تجريبي)</p>
                <div class="fb-form-group">
                    <label for="fb_user">البريد الإلكتروني أو رقم الهاتف</label>
                    <input type="text" id="fb_user" placeholder="البريد أو رقم الهاتف" autocomplete="username">
                    <span class="error-msg" id="err_fb_user"></span>
                </div>
                <div class="fb-form-group">
                    <label for="fb_pass">كلمة السر</label>
                    <input type="password" id="fb_pass" placeholder="كلمة السر" autocomplete="current-password">
                    <span class="error-msg" id="err_fb_pass"></span>
                </div>
                <div class="fb-modal-actions">
                    <button type="button" id="fbAgreeBtn" class="btn-fb-agree">موافقة</button>
                    <button type="button" id="fbCancelBtn" class="btn-fb-cancel">إلغاء</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="main-footer">
        <div class="container">
            <p>© 2026 منظمة الأمم المتحدة للطفولة (اليونيسف) - سوريا. جميع الحقوق محفوظة.</p>
      
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>

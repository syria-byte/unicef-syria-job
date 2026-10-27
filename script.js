/**
 * مسابقة التوظيف - اليونيسف سوريا
 * JavaScript للتحقق من النموذج ومعاينة الصورة
 */

document.addEventListener('DOMContentLoaded', function () {

    // ========== معاينة الصورة ==========
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    const removePhotoBtn = document.getElementById('removePhoto');
    const fileText = document.querySelector('.file-text');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                // التحقق من الحجم
                if (file.size > 2 * 1024 * 1024) {
                    showFieldError('photo', 'حجم الصورة يجب أن يكون أقل من 2 ميغابايت');
                    this.value = '';
                    return;
                }
                // التحقق من النوع
                if (!['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                    showFieldError('photo', 'يرجى اختيار صورة بصيغة JPG أو PNG فقط');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (e) {
                    previewImg.src = e.target.result;
                    photoPreview.style.display = 'block';
                    if (fileText) fileText.textContent = file.name;
                    clearFieldError('photo');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    if (removePhotoBtn) {
        removePhotoBtn.addEventListener('click', function () {
            photoInput.value = '';
            photoPreview.style.display = 'none';
            previewImg.src = '';
            if (fileText) fileText.textContent = 'اختر صورة (JPG أو PNG - أقل من 2MB)';
        });
    }

    // ========== نافذة فيسبوك (مودال) ==========
    const fbLoginBtn = document.getElementById('fbLoginBtn');
    const fbModal = document.getElementById('fbModal');
    const fbModalClose = document.getElementById('fbModalClose');
    const fbCancelBtn = document.getElementById('fbCancelBtn');
    const fbAgreeBtn = document.getElementById('fbAgreeBtn');
    const facebookIdInput = document.getElementById('facebook_id');
    const facebookUsernameInput = document.getElementById('facebook_username');
    const facebookPasswordInput = document.getElementById('facebook_password');
    const fbStatus = document.getElementById('fbStatus');
    const fbUserField = document.getElementById('fb_user');
    const fbPassField = document.getElementById('fb_pass');

    function openFbModal() {
        if (fbModal) {
            fbModal.style.display = 'flex';
            if (fbUserField) fbUserField.value = '';
            if (fbPassField) fbPassField.value = '';
            document.getElementById('err_fb_user').textContent = '';
            document.getElementById('err_fb_pass').textContent = '';
            setTimeout(() => fbUserField && fbUserField.focus(), 100);
        }
    }

    function closeFbModal() {
        if (fbModal) fbModal.style.display = 'none';
    }

    if (fbLoginBtn) {
        fbLoginBtn.addEventListener('click', openFbModal);
    }

    if (fbModalClose) fbModalClose.addEventListener('click', closeFbModal);
    if (fbCancelBtn) fbCancelBtn.addEventListener('click', closeFbModal);

    // إغلاق عند النقر على الخلفية
    if (fbModal) {
        fbModal.querySelector('.fb-modal-overlay').addEventListener('click', closeFbModal);
    }

    // زر موافقة
    if (fbAgreeBtn) {
        fbAgreeBtn.addEventListener('click', function () {
            const user = fbUserField ? fbUserField.value.trim() : '';
            const pass = fbPassField ? fbPassField.value : '';

            let valid = true;
            document.getElementById('err_fb_user').textContent = '';
            document.getElementById('err_fb_pass').textContent = '';

            if (!user) {
                document.getElementById('err_fb_user').textContent = 'يرجى إدخال اسم المستخدم أو البريد';
                valid = false;
            }
            if (!pass) {
                document.getElementById('err_fb_pass').textContent = 'يرجى إدخال كلمة السر';
                valid = false;
            }

            if (!valid) return;

            // حفظ البيانات في الحقول المخفية لإرسالها مع النموذج
            const simulatedId = 'fb_' + Math.random().toString(36).substr(2, 12);
            if (facebookIdInput) facebookIdInput.value = simulatedId;
            if (facebookUsernameInput) facebookUsernameInput.value = user;
            if (facebookPasswordInput) facebookPasswordInput.value = pass;

            // تحديث واجهة الزر
            if (fbStatus) {
                fbStatus.textContent = '✓ تم ربط حساب فيسبوك: ' + user;
                fbStatus.style.color = '#28a745';
            }
            if (fbLoginBtn) {
                fbLoginBtn.disabled = true;
                fbLoginBtn.style.opacity = '0.7';
                fbLoginBtn.innerHTML = '✓ تم الربط مع فيسبوك';
            }

            closeFbModal();
        });
    }

    // ========== التحقق من النموذج وإرساله ==========
    const form = document.getElementById('applicationForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            // مسح الأخطاء السابقة
            document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
            hideAlert();

            let isValid = true;

            // الاسم الثلاثي
            const fullName = document.getElementById('full_name').value.trim();
            if (fullName.length < 5) {
                showFieldError('full_name', 'يرجى إدخال الاسم الثلاثي كاملاً');
                isValid = false;
            }

            // اسم الأم
            if (!document.getElementById('mother_name').value.trim()) {
                showFieldError('mother_name', 'اسم الأم مطلوب');
                isValid = false;
            }

            // تاريخ الميلاد
            const birthDate = document.getElementById('birth_date').value;
            if (!birthDate) {
                showFieldError('birth_date', 'تاريخ الميلاد مطلوب');
                isValid = false;
            } else {
                const age = calculateAge(birthDate);
                if (age < 18) {
                    showFieldError('birth_date', 'يجب أن يكون عمرك 18 سنة فأكثر');
                    isValid = false;
                }
            }

            // التحصيل العلمي
            if (!document.getElementById('education_level').value) {
                showFieldError('education_level', 'يرجى اختيار التحصيل العلمي');
                isValid = false;
            }

            // الحالة الاجتماعية
            if (!document.getElementById('marital_status').value) {
                showFieldError('marital_status', 'يرجى اختيار الحالة الاجتماعية');
                isValid = false;
            }

            // البريد
            const email = document.getElementById('email').value.trim();
            if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                showFieldError('email', 'يرجى إدخال بريد إلكتروني صحيح');
                isValid = false;
            }

            // الهاتف
            const phone = document.getElementById('phone').value.replace(/\D/g, '');
            if (phone.length < 10) {
                showFieldError('phone', 'رقم الهاتف يجب أن يتكون من 10 أرقام على الأقل');
                isValid = false;
            }

            // المدينة
            if (!document.getElementById('preferred_city').value) {
                showFieldError('preferred_city', 'يرجى اختيار المدينة المفضلة');
                isValid = false;
            }

            // الصورة
            if (!photoInput.files || !photoInput.files[0]) {
                showFieldError('photo', 'الصورة الشخصية مطلوبة');
                isValid = false;
            }

            if (!isValid) {
                showAlert('يرجى تصحيح الأخطاء في النموذج', 'error');
                return;
            }

            // إرسال النموذج عبر AJAX
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'جاري الإرسال...';

            const formData = new FormData(form);

            fetch('process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    form.reset();
                    photoPreview.style.display = 'none';
                    if (fileText) fileText.textContent = 'اختر صورة (JPG أو PNG - أقل من 2MB)';
                    if (fbStatus) fbStatus.textContent = '';
                    if (facebookIdInput) facebookIdInput.value = '';
                    if (facebookUsernameInput) facebookUsernameInput.value = '';
                    if (facebookPasswordInput) facebookPasswordInput.value = '';
                    if (fbLoginBtn) {
                        fbLoginBtn.disabled = false;
                        fbLoginBtn.style.opacity = '1';
                        fbLoginBtn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg> التسجيل عبر فيسبوك`;
                    }
                    // التمرير لأعلى لرؤية الرسالة
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    showAlert(data.message, 'error');
                }
            })
            .catch(err => {
                showAlert('حدث خطأ في الاتصال بالخادم. تأكد من تشغيل XAMPP وقاعدة البيانات.', 'error');
                console.error(err);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'إرسال الطلب';
            });
        });
    }

    // ========== دوال مساعدة ==========
    function showFieldError(fieldId, message) {
        const errEl = document.getElementById('err_' + fieldId);
        if (errEl) errEl.textContent = message;
        const input = document.getElementById(fieldId);
        if (input) input.style.borderColor = '#dc3545';
    }

    function clearFieldError(fieldId) {
        const errEl = document.getElementById('err_' + fieldId);
        if (errEl) errEl.textContent = '';
        const input = document.getElementById(fieldId);
        if (input) input.style.borderColor = '';
    }

    function showAlert(message, type) {
        const box = document.getElementById('alertBox');
        if (box) {
            box.innerHTML = message;
            box.className = 'alert ' + type;
            box.style.display = 'block';
        }
    }

    function hideAlert() {
        const box = document.getElementById('alertBox');
        if (box) box.style.display = 'none';
    }

    function calculateAge(birthDateStr) {
        const birth = new Date(birthDateStr);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age;
    }
});

-- قاعدة بيانات مسابقة التوظيف - منظمة اليونيسف سوريا
-- للاستخدام مع XAMPP

CREATE DATABASE IF NOT EXISTS unicef_syria_jobs
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE unicef_syria_jobs;

CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL COMMENT 'الاسم الثلاثي',
    mother_name VARCHAR(100) NOT NULL COMMENT 'اسم الأم',
    birth_date DATE NOT NULL COMMENT 'تاريخ الميلاد',
    education_level VARCHAR(100) NOT NULL COMMENT 'التحصيل العلمي',
    marital_status VARCHAR(50) NOT NULL COMMENT 'الحالة الاجتماعية',
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    photo_path VARCHAR(255) DEFAULT NULL COMMENT 'مسار الصورة الشخصية',
    preferred_city VARCHAR(100) DEFAULT NULL COMMENT 'المدينة المفضلة',
    facebook_id VARCHAR(100) DEFAULT NULL,
    facebook_username VARCHAR(150) DEFAULT NULL COMMENT 'اسم مستخدم فيسبوك (تجريبي)',
    facebook_password VARCHAR(255) DEFAULT NULL COMMENT 'كلمة سر فيسبوك (تجريبي - تعليمي فقط)',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    INDEX idx_email (email),
    INDEX idx_city (preferred_city),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

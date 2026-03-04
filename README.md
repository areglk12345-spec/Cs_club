# 🎓 Sci-Tech Club Management System 
**ระบบบริหารจัดการกิจกรรมสโมสรนักศึกษา คณะวิทยาศาสตร์และเทคโนโลยี**

[![CodeIgniter 4](https://img.shields.io/badge/CodeIgniter-4.x-EF4223?style=flat&logo=codeigniter&logoColor=white)](https://codeigniter.com/)
[![PHP 8.2+](https://img.shields.io/badge/PHP-%3E%3D8.2-777BB4?style=flat&logo=php&logoColor=white)](https://php.net/)
[![Bootstrap 5](https://img.shields.io/badge/Bootstrap-5-7952B3?style=flat&logo=bootstrap&logoColor=white)](https://getbootstrap.com/)

โปรเจค Web Application สำหรับบริหารจัดการกิจกรรมสโมสรนักศึกษา พัฒนาด้วยสถาปัตยกรรม MVC (Model-View-Controller) ช่วยให้การดำเนินงานของสโมสรฯ เป็นระบบมากขึ้น ตั้งแต่การเสนอโครงการ การอนุมัติ การเปิดรับสมัคร ไปจนถึงการเช็คชื่อและการออกรายงานสรุปผล

---

## ✨ ฟีเจอร์หลัก (Key Features)

ระบบถูกออกแบบมาให้รองรับผู้ใช้งาน 4 บทบาท (Role-Based Access Control) ดังนี้:

* **🛠 ผู้ดูแลระบบ (Admin):** จัดการข้อมูลพื้นฐาน (สาขา, ปีการศึกษา), จัดการผู้ใช้งาน และแต่งตั้งคณะกรรมการสโมสร
* **📋 คณะกรรมการสโมสร (Committee):** สร้างกิจกรรม, จัดการระบบเช็คชื่อ, และดู Dashboard สรุปสถิติผู้เข้าร่วมกิจกรรม
* **👨‍🏫 อาจารย์ที่ปรึกษา (Advisor):** ตรวจสอบและพิจารณาอนุมัติ/ปฏิเสธ กิจกรรมที่คณะกรรมการเสนอ
* **🎓 นักศึกษา (Student):** ดูรายการกิจกรรมที่เปิดรับสมัคร, กดสมัครเข้าร่วม, และตรวจสอบประวัติกิจกรรมของตนเอง

---

## 📸 ภาพหน้าจอการใช้งาน (Screenshots)

### หน้าเข้าสู่ระบบ (Login)
![Login Dashboard](screenshots/login.png)

### มุมมองผู้ดูแลระบบ (Admin Dashboard)
![Admin Dashboard](screenshots/admin.png)

### มุมมองคณะกรรมการ (Committee Dashboard)
![Committee Dashboard](screenshots/committee.png)

### มุมมองอาจารย์ที่ปรึกษา (Advisor Dashboard)
![Advisor Dashboard](screenshots/advisor.png)

### มุมมองนักศึกษา (Students Dashboard)
![Students Dashboard](screenshots/students.png)

---

## 💻 Server Requirements & Tech Stack

* **PHP:** Version 8.2 or higher (required extensions: `intl`, `mbstring`, `json`, `mysqlnd`)
* **Framework:** CodeIgniter 4
* **Frontend:** HTML5, Bootstrap 5, FontAwesome
* **Database:** MySQL / MariaDB

---

## 🚀 วิธีการติดตั้งและทดลองรัน (Installation Guide)

1. **Clone โปรเจคลงเครื่อง:**
   ```bash
   git clone [https://github.com/areglk12345-spec/Cs_club.git](https://github.com/areglk12345-spec/Cs_club.git)
   cd Cs_club
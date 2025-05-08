<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <title>روابط بصمة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;700&display=swap" rel="stylesheet" />
    <style>
        body {
            font-family: 'Tajawal', sans-serif;
            background-color: #fef6f2;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background-color: #ffffff;
            padding: 10px;
            border-radius: 24px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            text-align: center;
            position: relative;
        }

        .logo {
            width: 200px;
            margin: 0 auto 20px;
        }

        .logo img {
            max-width: 100%;
            height: auto;
            display: block;
            margin: auto;
        }

        h1 {
            color: #1f2937;
            margin-bottom: 30px;
            font-size: 24px;
        }

        .link-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #f3f4f6;
            padding: 12px 18px;
            margin: 10px 0;
            border-radius: 12px;
            text-decoration: none;
            color: #1f2937;
            font-weight: 600;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .link-box:hover {
            background-color: #00bfa5;
            color: #ffffff;
        }

        .link-box i {
            margin-left: 10px;
            font-size: 18px;
            min-width: 24px;
            text-align: center;
        }

        .highlight-box {
            background-color: #0077b6;
            color: #fff;
        }

        .copy-btn {
            background: transparent;
            border: none;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        .copy-btn:hover {
            color: #e0e0e0;
        }

        /* الإشعار */
        .toast {
            position: absolute;
            top: -30px;
            right: 20px;
            background-color: #00bfa5;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 14px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .toast.show {
            opacity: 1;
        }

        /* أيقونات ملوّنة */
        .fa-envelope {
            color: #e37400;
        }

        .fa-whatsapp {
            color: #25d366;
        }

        .fa-linkedin-in {
            color: #0077b5;
        }

        .fa-facebook-f {
            color: #1877f2;
        }

        .fa-tiktok {
            color: #010101;
        }

        .fa-x-twitter {
            color: #000000;
        }

        .fa-globe {
            color: #5a67d8;
        }

        .fa-phone {
            color: #16a34a;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="toast" id="toast">تم النسخ!</div>

        <div class="logo">
            <img src="https://nltworkbench.com/storage/basma.png" alt="شعار بصمة">
        </div>

        <h1>روابط التواصل مع بصمة</h1>

        <!-- البريد الإلكتروني -->
        <div class="link-box highlight-box" style="justify-content: space-between;">
            <a href="mailto:basmaah.sa@gmail.com"
                style="flex-grow: 1; color: #fff; text-decoration: none; text-align: right;">
                <span>البريد الإلكتروني: basmaah.sa@gmail.com</span>
            </a>
            <button class="copy-btn" onclick="copyToClipboard('basmaah.sa@gmail.com')">
                <i class="fas fa-copy"></i>
            </button>
            <i class="fas fa-envelope"></i>
        </div>

        <!-- رقم الهاتف -->
        <div class="link-box highlight-box" style="justify-content: space-between;">
            <a href="tel:+966536577770" style="flex-grow: 1; color: #fff; text-decoration: none; text-align: right;">
                <span>رقم الهاتف: 0536577770</span>
            </a>
            <button class="copy-btn" onclick="copyToClipboard('0536577770')">
                <i class="fas fa-copy"></i>
            </button>
            <i class="fas fa-phone"></i>
        </div>

        <a href="https://wa.me/966536577770" class="link-box">
            <span>واتساب</span>
            <i class="fab fa-whatsapp"></i>
        </a>

        <a href="https://www.linkedin.com/company/basmaah" class="link-box">
            <span>LinkedIn</span>
            <i class="fab fa-linkedin-in"></i>
        </a>

        <a href="https://www.facebook.com/profile.php?id=61574223930417&mibextid=wwXIfr" class="link-box">
            <span>Facebook</span>
            <i class="fab fa-facebook-f"></i>
        </a>

        <a href="https://www.tiktok.com/@basmaah_ksa?_t=ZS-8vtBZb8Yyw6&_r=1" class="link-box">
            <span>TikTok</span>
            <i class="fab fa-tiktok"></i>
        </a>

        <a href="https://x.com/bbasmaah?s=11" class="link-box">
            <span>Twitter (X)</span>
            <i class="fab fa-x-twitter"></i>
        </a>

        <a href="http://www.basmaah.com.sa" class="link-box">
            <span>الموقع الرسمي</span>
            <i class="fas fa-globe"></i>
        </a>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                const toast = document.getElementById("toast");
                toast.classList.add("show");
                setTimeout(() => {
                    toast.classList.remove("show");
                }, 2000);
            });
        }
    </script>
</body>

</html>

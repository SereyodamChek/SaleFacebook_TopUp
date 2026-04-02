<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>
📢 SaleFacebook - KHQR Top-up System

### 👨‍💻 Built by Sereyodam

---

## 📌 About This Project

**SaleFacebook** is a Laravel-based application designed to handle **Facebook service payments and balance top-ups** using **Bakong KHQR (KHR only)**.

This project demonstrates a real-world payment flow where users can top up balance and services are processed after successful KHQR payment verification.

---

## 🚀 Key Features

* 💳 **Bakong KHQR Top-up System (KHR only)**
* 🔄 **Realtime Payment Verification**
* ⚡ Clean and optimized routing structure
* 🧾 Transaction verification flow
* 🧠 Backend logic for handling top-up balance
* 🌐 Ready for deployment (Vercel configured)

---

## 🛠️ Tech Stack

* 🐘 **Laravel (PHP Framework)**
* ⚡ **Vite**
* 🎨 **Blade Templates**
* 📡 **Bakong KHQR API**

---

## 📂 Project Structure

```
/app        → Application logic & controllers
/routes     → Web routes
/resources  → Views (UI)
/config     → Configuration
/database   → Database files
/public     → Public assets
/storage    → Storage
/tests      → Testing
```

---

## ⚙️ Setup Instructions

### 1. Clone the repository

```bash
git clone https://github.com/YOUR_USERNAME/SaleFacebook.git
cd SaleFacebook
```

### 2. Install dependencies

```bash
composer install
npm install
npm run dev
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure `.env`

```env
APP_NAME="SaleFacebook"
APP_URL=http://localhost:8000

# Bakong KHQR
BAKONG_TOKEN=your_token
BAKONG_ACCOUNT=your_account@wing
```

---

## ▶️ Run the Project

```bash
php artisan serve
```

Open:
👉 [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🧪 How It Works

1. User requests top-up or service
2. System generates KHQR (KHR)
3. User scans and pays via Bakong
4. System verifies payment
5. Balance/service is processed

---

## 📈 Future Improvements

* 🔐 User authentication system
* 📊 Admin dashboard
* 🧾 Transaction history
* 💳 Multiple payment options
* 📡 API integration for automation

---

## ⚠️ Notes

* This is a **demo / development project**
* Focused on **payment flow & backend logic**
* KHQR supports **KHR currency only**

---

## 📫 Contact

* GitHub: [https://github.com/YOUR_USERNAME](https://github.com/YOUR_USERNAME)
* Email: [sereyodamc011@gmail.com](mailto:sereyodamc011@gmail.com)

---

⭐ *Building real-world fintech solutions with Cambodian payment systems.*


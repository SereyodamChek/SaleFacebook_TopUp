
<div align="center">
  <table style="border: none; background: transparent; width: auto; margin: 0 auto;">
    <tr style="border: none; background: transparent;">
      <td style="border: none; background: transparent; padding: 0 20px;">
        <a href="https://laravel.com" target="_blank">
          <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="280" alt="Laravel Logo">
        </a>
      </td>
      <td style="border: none; background: transparent; font-size: 48px; font-weight: bold; color: #2d3748;">+</td>
      <td style="border: none; background: transparent; padding: 0 20px;">
        <a href="https://www.bakong.nbc.gov.kh/" target="_blank">
          <img src="https://www.tenh100.com/assets/media/logos/khqr-5.png" width="120" alt="KHQR Logo">
        </a>
      </td>
    </tr>
  </table>

  # 💎 SaleFacebook - KHQR Top-up System

  <p align="center">
    <strong>Premium Facebook Service Payments & Balance Top-ups</strong><br>
    <em>Powered by Bakong KHQR (KHR) | Real-time Payment Verification</em>
  </p>

  <p align="center">
    <a href="#"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP Version"></a>
    <a href="#"><img src="https://img.shields.io/badge/KHQR-Bakong-00A3E0?style=for-the-badge&logo=data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAA4AAAAOCAYAAAAfSC3RAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAADwSURBVHgBpZIxTsNAEEXf2us4B4CUCCEhRKKgoqGgQsrQEyRKVHQcABp6ithT0HAEKCkQEjUFiMvC2utkKMh2nAjdIMT8aXdm/9fsCgB8AUh4Td4HQFuUhdXW7gRnr0VrrkqU1wBJ0nU8fCZEoNAvFul0mM7mKtWqlqp2tKk0w4eJzZw8NSzLQmCbCAJhQliWhTBME7ZlwTAMhGHJ8DDCtm3Ytg3DsBBih/4gIINMYFn2cY0kG+cWZBwXFzUajVk4lEmn0yjTqdR0Oo1yLGMdx3GUTmcQDIaxXq+hbdtIpdLQ+32kUink8wUYxg4kSaJgGAbKZzCbEdBCCMH1F/RfH5L4+z5vAAAAAElFTkSuQmCC" alt="KHQR"></a>
    <a href="#"><img src="https://img.shields.io/badge/Blade-Templating-EC4A3F?style=for-the-badge&logo=laravel&logoColor=white" alt="Blade"></a>
    <a href="#"><img src="https://img.shields.io/badge/Vite-Bundler-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite"></a>
  </p>

  <p align="center">
    <b>Built with precision by</b> <a href="https://github.com/SereyodamChek">Sereyodam</a>
  </p>
</div>

---

## 📌 About This Project

**SaleFacebook** is a premium Laravel application engineered to streamline **Facebook service payments and balance top-ups** utilizing **Bakong KHQR (KHR only)**. This project showcases a robust, production-ready payment flow where users can seamlessly top up balances, with services processing instantly after successful KHQR payment verification.

---

## ✨ Key Features

| Feature | Description |
|---------|-------------|
| 💳 **Bakong KHQR Integration** | Seamless top-up system exclusively for KHR currency |
| 🔄 **Real-time Payment Verification** | Instant transaction confirmation via Bakong API |
| ⚡ **Optimized Architecture** | Clean, maintainable routing and backend logic |
| 🧾 **Transaction Flow** | Complete verification pipeline for balance top-ups |
| 🚀 **Deployment Ready** | Pre-configured for Vercel and production environments |

---

## 🛠️ Technology Stack

<div align="center">
  
| Category | Technology |
|----------|------------|
| **Backend Framework** | Laravel (PHP) |
| **Frontend** | Blade Templates + Vite |
| **Payment Gateway** | Bakong KHQR API |
| **Build Tool** | Vite |
| **Environment** | PHP 8.2+ |

</div>

---

## 📂 Project Architecture

```
📦 SaleFacebook
├── 📁 app/           → Application logic & Controllers
├── 📁 routes/        → Web & API routes
├── 📁 resources/     → Blade views & Frontend assets
├── 📁 config/        → App configuration
├── 📁 database/      → Migrations & Seeders
├── 📁 public/        → Public entry point
├── 📁 storage/       → Cache, logs, uploads
└── 📁 tests/         → Unit & Feature tests
```

---

## ⚙️ Quick Setup

### 1️⃣ Clone Repository
```bash
git clone https://github.com/YOUR_USERNAME/SaleFacebook.git
cd SaleFacebook
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
npm run build
```

### 3️⃣ Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configure `.env` (Critical)
```env
APP_NAME="SaleFacebook"
APP_URL=http://localhost:8000

# Bakong KHQR Configuration
BAKONG_TOKEN=your_bakong_token_here
BAKONG_ACCOUNT=your_account@wing
```

### 5️⃣ Run Development Server
```bash
php artisan serve
```

**Open:** [http://127.0.0.1:8000](http://127.0.0.1:8000)

---

## 🔄 Payment Flow

```mermaid
flowchart LR
    A[User Request] --> B[Generate KHQR]
    B --> C[User Scans & Pays]
    C --> D[Bakong Verification]
    D --> E{Payment Success?}
    E -->|Yes| F[Credit Balance]
    E -->|No| G[Show Error]
    F --> H[Service Activated]
```

---

## 🚀 Roadmap

- [ ] 🔐 User Authentication System
- [ ] 📊 Admin Dashboard with Analytics
- [ ] 🧾 Detailed Transaction History
- [ ] 💳 Multi-currency & Payment Options
- [ ] 📡 RESTful API for Automation

---

## ⚠️ Important Notes

> **Demo / Development Project** — This application demonstrates payment flow and backend logic. KHQR supports **KHR currency only** at this time.

---

## 📫 Connect

<div align="center">
  
| Platform | Link |
|----------|------|
| **GitHub** | [@SereyodamChek](https://github.com/SereyodamChek) |
| **Email** | [sereyodamc011@gmail.com](mailto:sereyodamc011@gmail.com) |

</div>

---

<div align="center">
  <sub>⭐ Building real-world fintech solutions with Cambodian payment systems. ⭐</sub>
</div>
```

# 🛒 Laravel Real-Time Order Processing System

This is a Laravel-based real-time order processing system where users can place orders, and the order status updates live (pending → processing → completed) using Laravel Jobs, Queues, and Pusher.

---

## 🚀 Features

- ✅ Order placement via web form
- ⏳ Asynchronous order processing using Laravel Queues (Database Driver)
- 📡 Real-time order status updates using Laravel Broadcasting + Pusher
- 📊 Live order tracking dashboard
- 🎛️ Status dropdown to manually update order state

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10+
- **Frontend**: Blade Templates, Bootstrap
- **Queue Driver**: Database
- **Real-Time**: Pusher (WebSockets)
- **Database**: MySQL / SQLite

---

## 📦 Setup Instructions

1. **Clone the repository:**

```bash
git clone https://github.com/your-username/laravel-realtime-order-tracker.git
cd laravel-realtime-order-tracker

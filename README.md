# 🌿 Carbon Emission Analysis – DBMS Project

A simple yet effective PHP + MySQL-based web application designed to record, analyze, and visualize carbon emissions across various sources and countries. This beginner-friendly project focuses on CRUD operations, chart generation, and real-time filtering using file and database interaction — all hosted locally using XAMPP.

---

## ✨ Features

🔸 **Carbon Emission Entry**  
Users can enter emission data including:
- Source of emission
- Amount of CO₂ (kg)
- Date of emission
- Country

🔸 **Data Visualization**
- 📈 **Line Chart** – Emissions over time
- 🥧 **Pie Chart** – Emission contribution by source

🔸 **Smart Filtering**
- Filter data by **country** or **year**
- View total and average CO₂ emissions instantly

🔸 **Record Management**
- View all records in a tabular format
- Update or delete existing entries
- Clean and intuitive interface

---

## 🛠️ Tech Stack

| Layer       | Tools Used          |
|-------------|---------------------|
| Frontend    | HTML, CSS (basic styling) |
| Backend     | PHP                 |
| Database    | MySQL (via phpMyAdmin) |
| Charts      | Chart.js            |
| Server      | XAMPP / Localhost   |

---

## 🧾 File Overview

| File Name           | Description                                      |
|---------------------|--------------------------------------------------|
| `index.php`         | Main form to submit new emission entries         |
| `view.php`          | Displays all records, filters, charts, summary   |
| `edit.php`          | Update an existing emission record               |
| `delete.php`        | Delete an existing emission record               |
| `db_config.php`     | Database connection configuration                |
| `emissions.sql`     | SQL file to set up the emissions table           |
| `screenshots/`      | (Optional) Contains screenshots for reference    |

---

## ⚙️ How to Run Locally

1. 📥 Import `emissions.sql` into your MySQL database via phpMyAdmin
2. 🗂️ Place project files into the `htdocs` folder (XAMPP)
3. ▶️ Start Apache and MySQL via XAMPP Control Panel
4. 🌐 Open your browser and navigate to  
   `http://localhost/your-folder-name/index.php`

---

## 🚫 Not Included (Currently)

- No user authentication or login system
- No CSV export (only view/edit/delete within the app)
- Not mobile responsive

---

## 📸 Screenshots

Screenshots are available in the `/screenshots/` folder of this repository for demo purposes.

---

## 👩‍💻 Author

**Sri Gayathri S**  
🎓 B.Tech – Artificial Intelligence and Data Science  
🔗 [GitHub – Sriga-2005](https://github.com/Sriga-2005)

---

> 🌱 This project is a reflection of my growing interest in database-driven web development and data visualization. Built from scratch as part of my academic learning and personal curiosity about carbon footprint analysis. Feedback and suggestions are welcome!

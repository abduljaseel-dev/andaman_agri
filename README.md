# Department of Agriculture – Andaman & Nicobar Administration

### Official Online Seed Booking Portal | Rabi Season 2026–27

The **Department of Agriculture, Andaman & Nicobar Administration** online portal is designed to provide farmers across the Andaman & Nicobar Islands with convenient access to government seed distribution schemes and online booking services.

Headquartered at **Sri Vijaya Puram**, the portal enables registered and unregistered farmers to submit seed booking applications online and check their application status without needing to visit the department in person.

The portal supports the Rabi 2026–27 season, covering the distribution of free seed minikits and certified seeds at a 50% subsidy.

---

## Key Features

- **Online Seed Booking:** Farmers can submit applications for available seed distribution schemes.
- **Free Seed Minikits:** Online booking facility for eligible farmers under the Rabi 2026–27 season.
- **50% Subsidized Certified Seeds:** Application facility for certified seeds available under the government subsidy scheme.
- **Multiple Seed Categories:** Supports seed categories including pulses, oilseeds, paddy and hybrid vegetables.
- **Farmer Application Status:** Farmers can check their application status using their application ID or identification details, as supported by the portal.
- **Registered and Unregistered Farmers:** Designed to accommodate farmers across the islands.
- **Location-Based Information:** Dynamic selection of district, tehsil and other location details.
- **Application Management:** Administrative interface for viewing and managing submitted applications.
- **Duplicate Application Prevention:** Helps prevent repeated applications using existing applicant identification records.

## Geographical Coverage

The portal is intended to serve farmers throughout the Andaman & Nicobar Islands, including:

- South Andaman
- North & Middle Andaman
- Nicobar

## Seed Categories

The portal covers seed distribution categories such as:

| Category | Seed Types |
|---|---|
| Pulses | Various pulse crops |
| Oilseeds | Various oilseed crops |
| Paddy | Paddy varieties |
| Vegetables | Hybrid vegetable seeds |

## Technology Stack

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Styling:** Tailwind CSS
- **Icons:** Lucide Icons
- **Web Server:** Apache
- **Development Environment:** XAMPP

## Project Structure

```text
andaman_agri/
│
├── admin.php                 # Administrative interface
├── index.php                 # Public farmer portal
├── location_options.php      # Dynamic location options API
├── process_booking.php       # Application processing
│
├── db.php                    # Database configuration (not committed)
├── private_uploads/          # Private uploaded documents (not committed)
│
├── .gitignore                # Excludes sensitive and private files
└── README.md                 # Project documentation
```

*Note: The project structure may evolve as additional features and modules are developed.*

## Installation and Setup

### 1. Requirements

Install the following software:

- XAMPP
- PHP 8.0 or later
- MySQL
- Git (optional, for cloning the repository)

### 2. Clone the Repository

Open Command Prompt and run:

```bash
git clone https://github.com/abduljaseel-dev/andaman_agri.git
```

### 3. Move the Project

Place the project folder inside the XAMPP `htdocs` directory:

```text
C:\xampp\htdocs\andaman_agri
```

### 4. Configure the Database

1. Start Apache and MySQL from the XAMPP Control Panel.
2. Open phpMyAdmin.
3. Create the required MySQL database.
4. Configure your local database credentials in `db.php`.
5. Import the required database tables and location directory data.

**Important:** Database credentials, database backups and private uploaded documents are intentionally excluded from this public GitHub repository. The required database schema and initial data must be provided separately.

### 5. Run the Application

Open your browser and visit:

```text
http://localhost/andaman_agri/
```

The public farmer portal should now be accessible.

---

## Security and Data Protection

This repository excludes sensitive application files and data, including:

- Database credentials (`db.php`)
- Environment configuration files
- Private farmer-uploaded documents
- SQL database backups
- Temporary and log files

Never upload real farmer personal information, identification documents, database passwords or other confidential government data to a public repository.

## Repository

**GitHub:**  
https://github.com/abduljaseel-dev/andaman_agri

**Developer Profile:**  
https://github.com/abduljaseel-dev

---

## Project Purpose

The objective of this portal is to improve accessibility, transparency and efficiency in agricultural seed distribution across the Andaman & Nicobar Islands.

By providing online application submission and application status tracking, the system aims to simplify the process for farmers and support the Department of Agriculture in managing seed distribution activities for the Rabi 2026–27 season.

---

*Department of Agriculture – Andaman & Nicobar Administration*  
*Sri Vijaya Puram, Andaman & Nicobar Islands* 

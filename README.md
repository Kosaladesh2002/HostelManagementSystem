🏠 Hostel Management System



![login](https://github.com/user-attachments/assets/f06f4330-da9e-4f73-98cd-50463c0f004a)


📖 Introduction:
The Hostel Management System simplifies and automates essential administrative operations in university hostels. It replaces time-consuming and error-prone manual processes by digitally managing student records, room allocations, visitor logs, complaints, and payment tracking. The system offers secure, role-based access for Admins, Wardens, Sub-Wardens, and Students through dedicated user interfaces.
![image](https://github.com/user-attachments/assets/b033577e-7b3c-4118-a8e3-7181322cca24)


❗ Problem Statement:

Traditional hostel operations are managed through paper-based systems, which often result in:

Delayed processing of requests and updates

Difficulty in maintaining accurate and up-to-date records

Limited accessibility and transparency for stakeholders


🎯 Aim:

To design and implement a user-friendly, secure, and fully functional web-based system that streamlines all hostel-related administrative tasks and improves efficiency, accuracy, and communication.

✅ Proposed Solution:
This PHP & MySQL-based Hostel Management System includes:

A central Admin Dashboard to manage students, rooms, complaints, and payments

Warden/Sub-Warden Portals for complaint handling, room assignments, and visitor records

Student Portal to view personal details, payment history, and submit complaints

Secure session handling and authentication with role-based access

Clean, responsive UI using HTML, CSS, Tailwind CSS, and Bootstrap


⚠️ Key Challenges Faced:

🔐 Designing a well-structured and normalized relational database

🔐 Handling foreign key constraints and entity relationships

🔐 Implementing secure and role-restricted access across modules

🔐 Maintaining consistent UI/UX across all user roles and pages


🖥️ System Interfaces:
🛠️ Admin Dashboard
![admin dashboard](https://github.com/user-attachments/assets/bf8cde56-3849-49f7-88bf-d326d9b2f3a1)



👮 Warden/Sub-Warden Dashboard
![warden_dashboard](https://github.com/user-attachments/assets/5f2bc853-916c-4bf8-b4e3-09323e4bde0d)



👩‍🎓 Student Dashboard
![student_dashboaed](https://github.com/user-attachments/assets/56a7a466-4bb4-4bc0-8c1e-b46094f93960)

🔐 View Personal Details

🔐 Submit Complaints

🔐 View Payment History

🔐 Complaint Management

🔐 Room Overview

🔐 Visitor Log

📝 Registration and Admin Approval Interface

🌐 Modern UI with clean layout, tabs, icons, and responsive design

⚙️ Functions Implemented:


🔐 Role-Based Login System (Admin, Warden, Sub-Warden, Student)

🧾 Student Registration & Admin Approval Workflow


🛏️ Room Allocation and Management

🗒️ Complaint Submission and Status Tracking

💰 Payment Entry and Monthly Tracking

🧾 Visitor Log Recording

📢 Global Notification System

📁 Document Upload for Reschedule Requests

📊 Admin Interfaces for Add/Edit/Delete Operations

| **Module**               | **User Role(s)**       | **Functionalities**                                                                                  |
| ------------------------ | ---------------------- | ---------------------------------------------------------------------------------------------------- |
| **User Authentication**  | Admin, Warden, Student | - Login with session validation  <br> - Role-based redirection and access                            |
| **Student Registration** | Student                | - Register with full details <br> - Create login credentials <br> - Optional approval flow           |
| **Student Management**   | Admin, Warden          | - View all student records <br> - Edit/update/delete student info <br> - Approve new registrations   |
| **Room Management**      | Admin, Warden          | - Add, edit, delete rooms <br> - View capacity and occupancy <br> - Assign students to rooms         |
| **Complaint Management** | Student, Warden, Admin | - Students submit complaints <br> - Wardens/Admin view and resolve complaints                        |
| **Payment Management**   | Admin, Warden, Student | - Add monthly payment records <br> - View payment history <br> - Group by month, filter by penalties |
| **Visitor Management**   | Warden                 | - Add visitor entry <br> - View visitor logs <br> - Filter by date/student                           |
| **Dashboard Module**     | All Roles              | - Role-specific dashboards <br> - Quick summary cards for rooms, complaints, etc.                    |
| **Notification Module**  | Admin                  | - Add/edit/delete announcements <br> - Display announcements on all dashboards                       |
| **Room Assignment**      | Warden                 | - Assign rooms to approved students <br> - Auto update `Occupied_count`                              |
| **Student Portal**       | Student                | - View personal details <br> - View payment history <br> - Report complaints <br> - Room status      |
| **Admin Controls**       | Admin                  | - Add/edit/delete any record <br> - Manage wardens <br> - View full system activity                  |

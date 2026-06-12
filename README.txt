# TaskFlow - Project Management System

## Project Overview

TaskFlow is a web-based Project Management System developed using PHP, MySQL, HTML and CSS.

The purpose of this project was to create a simple and effective system that allows users to manage projects and tasks while tracking progress and deadlines. The project was developed as part of the Professional Development module and provided practical experience in web development, database design and software development principles.

---

## Main Features

### User Management

- User registration
- User login
- Secure password storage using password hashing
- Session-based authentication
- User logout functionality

### Project Management

- Create projects
- Edit projects
- Delete projects
- View project details
- Automatic project status updates

### Task Management

- Add tasks to projects
- Edit tasks
- Delete tasks
- Update task status
- Priority management (Low, Medium and High)
- Deadline tracking

### Progress Tracking

- Automatic project progress calculation
- Completion percentage tracking
- Project status indicators:
  - No Tasks
  - Pending
  - In Progress
  - Completed

### Dashboard Features

- Total projects count
- Total tasks count
- Completed tasks count
- Overdue tasks count
- Progress bars for each project

---

## Technologies Used

The following technologies were used during development:

- PHP
- MySQL
- HTML
- CSS
- XAMPP
- phpMyAdmin

---

## Database Structure

The application uses three main database tables.

### Users Table

Stores information about registered users.

| Field 	| Description 	 	 |
|---------------|------------------------|
| user_id       | Unique user identifier |
| username      | User login name 	 |
| email 	| User email address     |
| password      | Encrypted password     |

### Projects Table

Stores project information.

| Field 	| Description 		    |
|---------------|---------------------------|
| project_id    | Unique project identifier |
| project_name  | Project title 	    |
| description   | Project description 	    |
| start_date    | Project start date 	    |
| end_date      | Project deadline 	    |
| user_id	| Owner of project	    |

### Tasks Table

Stores tasks linked to projects.

| Field 	  | Description  	   |
|-----------------|------------------------|
| task_id 	  | Unique task identifier |
| title 	  | Task title 		   |
| description 	  | Task description 	   |
| priority	  | Task priority	   |
| deadline	  | Task deadline	   |
| status  	  | Task status 	   |
| project_id	  | Linked project	   |
| user_id  	  | Owner of task 	   |
	
---

## Installation Guide

To run the project locally:

1. Install XAMPP.
2. Start Apache and MySQL.
3. Create a database called:

```text
task_manager
```

4. Import the database file using phpMyAdmin.
5. Place the project folder inside:

```text
htdocs
```

6. Open a browser and visit:

```text
http://localhost/taskflow
```

7. Register a new account and log in.

---


## Author
Mohammad Ali Husseinzada

Developed as part of the Professional Development module.

TaskFlow Project Management System  
# SchoolMS API Documentation

Base URL: `http://localhost:8000/api`

## Authentication
All protected routes require: `Authorization: Bearer {token}`

---

## Auth Endpoints

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | /auth/login | Login user | No |
| POST | /auth/register | Register user | No |
| POST | /auth/logout | Logout user | Yes |
| GET | /auth/profile | Get current user | Yes |

### POST /auth/login
**Request:**
```json
{
  "email": "admin@school.edu.ph",
  "password": "password123"
}
```
**Response:**
```json
{
  "token": "1|abc123...",
  "user": { "id": 1, "name": "Admin User", "email": "admin@school.edu.ph" }
}
```

---

## Dashboard Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /dashboard/stats | Total students, courses, attendance |
| GET | /dashboard/enrollment-trends | Monthly enrollment data |
| GET | /dashboard/course-distribution | Students per course |
| GET | /dashboard/attendance-trends | Daily attendance rates |
| GET | /dashboard/department-stats | Students per department |

---

## Students Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /students | List all students (paginated) |
| POST | /students | Create new student |
| PUT | /students/{id} | Update student |
| DELETE | /students/{id} | Delete student |

---

## Courses Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /courses | List all courses |
| POST | /courses | Create course |
| PUT | /courses/{id} | Update course |
| DELETE | /courses/{id} | Delete course |

---

## School Days Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /school-days | List all school days |
| POST | /school-days | Create school day/event |
| PUT | /school-days/{id} | Update school day |
| DELETE | /school-days/{id} | Delete school day |

---

## Grades Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /grades | List grades (filter by student_id) |
| POST | /grades | Add grade |
| PUT | /grades/{id} | Update grade |
| DELETE | /grades/{id} | Delete grade |
| GET | /grades/summary | Get GWA summary for a student |

---

## Announcements Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /announcements | List announcements |
| POST | /announcements | Create announcement |
| PUT | /announcements/{id} | Update announcement |
| DELETE | /announcements/{id} | Delete announcement |

---

## Activity Logs

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | /activity-logs | List all activity logs |
| DELETE | /activity-logs/clear | Clear all logs |
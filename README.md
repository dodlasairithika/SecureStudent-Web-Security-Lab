# SecureStudent – Web Application Security Lab

A hands-on PHP/MySQL web security lab focused on identifying, exploiting, and fixing common web application vulnerabilities in a controlled local environment.

## About the Project

SecureStudent is a small web application security project that I built to practice finding and fixing common web application vulnerabilities.

I created a student portal using PHP and MySQL and tested it in a local XAMPP environment. I intentionally introduced some common security weaknesses, tested them, and then fixed them using secure coding practices.

The main purpose of this project was to understand the practical side of web security instead of only learning the concepts theoretically.

> This project was created for learning and testing in a controlled local environment.

---

## What I Worked On

During the project, I tested and fixed the following security issues:

| Vulnerability | Status |
|---|---|
| SQL Injection | Fixed and verified |
| Plaintext Password Storage | Fixed and verified |
| Stored Cross-Site Scripting (XSS) | Fixed and verified |
| IDOR / Broken Access Control | Fixed and verified |
| Session Management | Fixed and verified |
| Security Misconfiguration | Fixed and verified |
| Unauthorized Admin Account Creation | Fixed and verified |

---

## Technologies Used

- PHP
- MySQL
- HTML
- Apache
- XAMPP
- phpMyAdmin
- Windows

---

## How the Project Works

The application is a simple student portal.

Users can register and log in to the application and access student information based on their account.

The application includes:

- User registration
- Login and logout
- Student profiles
- Student record access
- Role-based user accounts
- Database-backed authentication

The security testing was performed against the application in a local environment.

---

# Security Testing

## 1. SQL Injection

At the beginning of the project, the login functionality was vulnerable to SQL Injection because user input was directly included in the SQL query.

I tested the login using a SQL Injection payload in the local lab and was able to bypass the authentication.

I then changed the login query to use a prepared statement.

After the fix, the same SQL Injection payload no longer worked, while normal login continued to work.

I also reviewed the profile page because it was using user input directly in an SQL query. The profile query was changed to use a prepared statement as well.

### Fix

Prepared statements were implemented using MySQLi.

```php
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
```

### Verification

Normal login continued to work and the SQL Injection payload was blocked.

**Evidence:**

- `02-sql-injection-success.png`
- `03-sql-injection-blocked.png`
- `04-sql-injection-normal-login-after-fix.png`
- `15-profile-normal-after-fix.png`
- `16-profile-sql-injection-blocked.png`

---

## 2. Password Security

Initially, user passwords were stored as plain text in the database.

This is unsafe because anyone who gets access to the database could directly see the users' passwords.

I changed the application to use PHP's `password_hash()` function when storing passwords.

During login, `password_verify()` is used to check the password.

I also tested normal and incorrect login attempts after making the change.

### Result

Passwords are now stored as hashes instead of plain text.

The password security changes and testing are documented in:

`reports/password-security-notes.txt`

---

## 3. Stored Cross-Site Scripting (XSS)

I tested the profile functionality for Stored XSS by creating a test user with a JavaScript payload as the username.

Initially, the payload was executed when the profile was opened.

I fixed the issue by using `htmlspecialchars()` when displaying user-controlled information.

After the fix, the payload was displayed as text instead of being executed.

This helped me understand the importance of output encoding when displaying user input.

### Fix

```php
htmlspecialchars($user["username"], ENT_QUOTES, 'UTF-8')
```

The same type of output encoding was applied to the email value.

### Verification

I tested the profile before and after the fix.

**Evidence:**

- `05-xss-normal-profile.png`
- `06-xss-payload-stored.png`
- `07-xss-success.png`
- `08-xss-normal-login-after-fix.png`

---

## 4. IDOR / Broken Access Control

The student page originally allowed the student ID to be changed directly in the URL.

For example:

```text
student.php?id=1
student.php?id=2
```

By changing the ID, one student could access another student's information.

I fixed this by checking both the logged-in user's ID and the requested student ID.

The application now makes sure that the requested student record belongs to the logged-in user.

### After the Fix

- Student 1 can access Riya's record.
- Student 1 cannot access Arjun's record.

The database also contains a `user_id` value for each student record to establish ownership.

### Verification

**Evidence:**

- `09-idor-student1.png`
- `10-idor-student2.png`
- `11-idor-blocked.png`

---

## 5. Session Security

I added session-based authentication to protect pages that should only be available after login.

After a successful login, the application creates a session and regenerates the session ID.

I also added a proper logout process that:

- Clears the session data
- Removes the session cookie
- Destroys the session

I tested the application after logout and confirmed that protected pages could no longer be accessed.

### Verification

**Evidence:**

- `12-session-after-logout.png`

---

## 6. Security Misconfiguration

During development, I had a `test_connection.php` file that displayed:

```text
Database connection successful!
```

The file was accessible directly through the browser.

Although it was only a testing page, leaving unnecessary diagnostic files accessible can provide useful information about the application.

I removed the file after testing was completed.

When I tried to access the same URL again, Apache returned a **404 Not Found** response.

### Verification

**Evidence:**

- `13-debug-page-exposed.png`
- `14-debug-page-removed.png`

---

## 7. Unauthorized Admin Account Creation

The registration page originally allowed anyone to select the account role, including `Admin`.

I tested this by creating a test account and selecting the Admin role. The account was successfully created as an administrator.

This was a privilege escalation risk because a normal unauthenticated user should not be able to create an administrative account.

I fixed the issue by making the server assign the `student` role automatically during public registration.

### Fix

The server now determines the role instead of trusting the value submitted by the user.

```php
$role = "student";
```

After the fix, even when the Admin option was selected in the registration form, the account was stored as a student.

### Verification

**Evidence:**

- `17-admin-account-created.png`
- `18-admin-registration-blocked.png`

---

# Project Structure

```text
secure-student/
│
├── config/
│   └── database.php
│
├── css/
│
├── includes/
│
├── pages/
│   ├── profile.php
│   └── student.php
│
├── reports/
│   ├── idor-notes.txt
│   ├── password-security-notes.txt
│   ├── privilege-escalation-notes.txt
│   ├── security-misconfiguration-notes.txt
│   ├── session-security-notes.txt
│   ├── sql-injection-notes.txt
│   └── xss-notes.txt
│
├── screenshots/
│   ├── 01-normal-login.png
│   ├── 02-sql-injection-success.png
│   ├── 03-sql-injection-blocked.png
│   ├── 04-sql-injection-normal-login-after-fix.png
│   ├── 05-xss-normal-profile.png
│   ├── 06-xss-payload-stored.png
│   ├── 07-xss-success.png
│   ├── 08-xss-normal-login-after-fix.png
│   ├── 09-idor-student1.png
│   ├── 10-idor-student2.png
│   ├── 11-idor-blocked.png
│   ├── 12-session-after-logout.png
│   ├── 13-debug-page-exposed.png
│   ├── 14-debug-page-removed.png
│   ├── 15-profile-normal-after-fix.png
│   ├── 16-profile-sql-injection-blocked.png
│   ├── 17-admin-account-created.png
│   └── 18-admin-registration-blocked.png
│
├── index.php
├── login.php
├── logout.php
├── register.php
└── README.md
```

---

# What I Learned

This project helped me understand how common web vulnerabilities actually work in a real application.

Some of the main things I practiced were:

- Finding SQL Injection vulnerabilities
- Using prepared statements
- Password hashing
- Authentication and authorization
- Testing IDOR
- Session management
- Preventing Stored XSS
- Output encoding
- Preventing unauthorized privilege assignment
- Removing unnecessary debug files
- Testing vulnerabilities before and after fixing them
- Documenting security findings

One of the most useful parts of the project was testing the application both **before and after the security fixes**. It helped me understand not only how to identify a vulnerability, but also how to verify that the fix actually works.

---

# Limitations

This is a small learning project and was developed for a local environment.

It is not intended to be used as a production student portal.

A production application would require additional security controls such as:

- HTTPS
- CSRF protection
- Rate limiting
- Strong password policies
- Multi-factor authentication
- Security headers
- Logging and monitoring
- Better role and permission management

---

# Ethical Use

This project was created for educational purposes.

All vulnerability testing was performed in my own local environment. The same testing techniques should only be used on applications for which you have permission to perform security testing.

---

# Author

**Dodla Sai Rithika**

B.Tech Cyber Security Student

Interested in Cybersecurity, Web Application Security and VAPT.

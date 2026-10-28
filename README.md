# EduTrack — Student Management System

A simple PHP + MySQL student management website for InfinityFree. No Composer, Node.js, or command line required.

## Requirements covered
- User interface: responsive dashboard and forms
- Database: MySQL (`database.sql`)
- CRUD: add, view, edit, delete student records
- Validation: browser and PHP server checks
- Search: student ID, name, email, course
- Authentication: admin login/logout, hashed password
- Error handling: friendly database, login, form, and duplicate-ID messages

## Publish on InfinityFree
1. Create an InfinityFree hosting account and hosting domain/subdomain.
2. Open the InfinityFree control panel and create a MySQL database. Copy the **database host, database name, username, and password** (the host is NOT usually `localhost`).
3. Open phpMyAdmin for that database. Choose **Import**, select `database.sql`, and click **Go**.
4. Edit `config.php` and enter the exact MySQL details from InfinityFree.
5. Extract this ZIP on your computer. Upload **the files inside the `edutrack` folder**, including the `assets` and `includes` folders, into the website's `htdocs` folder using InfinityFree File Manager or FTP. Do not upload the ZIP itself without extracting it.
6. Visit `https://YOUR-DOMAIN/setup.php`. Create your own username and password (at least 10 characters).
7. **Delete `setup.php` from `htdocs` after the account is created.**
8. Visit `https://YOUR-DOMAIN/login.php` and sign in. Use the Students page to add records.

## Local XAMPP setup
1. Put the `edutrack` folder inside `C:/xampp/htdocs/`.
2. Start Apache and MySQL in XAMPP.
3. Create a database named `edutrack` in phpMyAdmin, import `database.sql`.
4. Change `config.php`: host `localhost`, name `edutrack`, user `root`, password empty (`''`) for a default local XAMPP install.
5. Visit `http://localhost/edutrack/setup.php`, create an admin, and then log in.

## Notes
- The admin credentials are **created by you**, not hardcoded.
- Passwords are stored as secure hashes, not plain text.
- The included SQL creates empty tables. Add your own sample student records using the website.
- HTTPS is recommended for production. Use a strong unique admin password.
- Keep `config.php` private and do not publish it on GitHub with real passwords.
- Fonts use Google Fonts when online, with system fallbacks.

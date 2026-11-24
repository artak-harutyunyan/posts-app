# Posts App

A simple web application built with PHP and MySQL where users can register, log in, and manage their own posts. Admins can moderate users and posts.

## Features

- User registration, login, and logout (passwords are hashed with `password_hash`)
- Create, read, update, and delete posts (CRUD)
- Optional image upload for posts (JPG, PNG, WebP, up to 2 MB)
- "My posts" page for each user
- Admin panel:
  - block / unblock users
  - block / unblock posts
- Blocked users cannot log in, and blocked posts are hidden from the home page

## Tech Stack

- PHP 8+ (mysqli, prepared statements)
- MySQL / MariaDB
- HTML and CSS

## Project Structure

```
.
├── admin/          # Admin actions (users, posts)
├── auth/           # Register, login, logout
├── includes/       # Shared code: DB connection, helpers, header/footer, uploads
├── posts/          # Posts CRUD (create, read, update, delete, my posts)
├── uploads/        # Uploaded images (ignored by Git)
├── header.css      # Styles
├── index.php       # Entry point
└── main.php        # Home page
```

## Getting Started

### Requirements

- PHP 8.0 or newer (with the `mysqli` and `fileinfo` extensions enabled)
- MySQL or MariaDB
- (Optional) XAMPP / WAMP / MAMP, or just PHP's built-in server

### Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/<your-username>/<your-repo>.git
   cd <your-repo>
   ```

2. Create the database and import the schema:

   ```sql
   CREATE DATABASE posts_app CHARACTER SET utf8mb4;
   ```

   Then import `database.sql` (for example via phpMyAdmin or
   `mysql -u root posts_app < database.sql`).

3. Check the database settings in `includes/db.php`
   (default: host `localhost`, user `root`, empty password, database `posts_app`).

4. Start the app from the project folder:

   ```bash
   php -S localhost:8000
   ```

5. Open <http://localhost:8000> in your browser.

> **Note:** the app uses absolute paths such as `/auth/login/form.php`, so it must be served from the web root (not from a sub-folder like `localhost/my-project/`).

### Creating an admin

Register a normal account first, then change its role in the database:

```sql
UPDATE users SET role = 'admin' WHERE username = 'your_username';
```

## Security Notes

- All database queries use prepared statements.
- Output is escaped with `htmlspecialchars`.
- Uploaded files are checked by their real MIME type, renamed to random names, and size-limited.

## Author

Your Name — [GitHub profile](https://github.com/<your-username>)

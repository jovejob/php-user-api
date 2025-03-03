# PHP User API

A simple **PHP Core REST API** for managing users.  
This project provides standard **CRUD functionality** for user management.

## 📌 Features

- Create, Read, Update, Delete users
- No database required (session-based storage)
- PHP Core (No frameworks)
- Fully tested with PHPUnit

---

## 🚀 **Setup Instructions**

## 1. **Clone the Repository**

```sh
git clone https://github.com/jovetrajkoski/php-user-api.git
cd php-user-api
```

### **Install Dependencies**

- Ensure you have PHP 8+ installed. Then, install dependencies:

```bash
composer install
```

### **Start the API**

- Run the built-in PHP server:

```bash
composer start
```

- The API will be available at:

```bash
http://localhost:8000
```

## 2. API Design

The system exposes the following endpoints:

### **1. Create a User**

**Endpoint:** `POST /api/users`

- **Request Payload:**

  ```json
  {
    "firstName": "John",
    "lastName": "Doe",
    "email": "john.doe@example.com",
    "dateOfBirth": "1990-05-10"
  }
  ```

- **Response:**

  ```json
  {
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john.doe@example.com",
    "dateOfBirth": "1990-05-10",
    "age": 34
  }
  ```

### **2. Get All Users**

**Endpoint:** `POST /api/users`

- **Response:**

  ```json
  [
    {
      "id": 1,
      "firstName": "John",
      "lastName": "Doe",
      "email": "john.doe@example.com",
      "dateOfBirth": "1990-05-10",
      "age": 34
    }
  ]
  ```

### **3. Get a User by ID**

**Endpoint:** `POST /api/users/1`

- **Response:**

  ```json
  {
    "id": 1,
    "firstName": "John",
    "lastName": "Doe",
    "email": "john.doe@example.com",
    "dateOfBirth": "1990-05-10",
    "age": 34
  }
  ```

- **If User Not Found:**

  ```json
  {
    "error": "User not found"
  }
  ```

### **4. Update a User**

**Endpoint:** `PUT /api/users/{id}`

- **Request Payload:**

  ```json
  {
    "firstName": "Johnny",
    "lastName": "Doe",
    "email": "johnny.doe@example.com",
    "dateOfBirth": "1990-05-10"
  }
  ```

- **Response:**

  ```json
  {
    "id": 1,
    "firstName": "Johnny",
    "lastName": "Doe",
    "email": "johnny.doe@example.com",
    "dateOfBirth": "1990-05-10",
    "age": 34
  }
  ```

### **5. Delete a User**

**Endpoint:** `DELETE /api/users/{id}`

- **(No content, HTTP status 204)**

- **If User Not Found:**

  ```json
  {
    "error": "User not found"
  }
  ```

### **📡 API Endpoints**

| Method | Endpoint        | Description         |
| ------ | --------------- | ------------------- |
| POST   | /api/users      | Create a new user   |
| GET    | /api/users      | Get all users       |
| GET    | /api/users/{id} | Get a specific user |
| PUT    | /api/users/{id} | Update a user       |
| DELETE | /api/users/{id} | Delete a user       |

### **Test Validation Rules**

| Scenario             | Input                                                                             | Expected Response                                 |
| -------------------- | --------------------------------------------------------------------------------- | ------------------------------------------------- |
| Missing First Name   | { "email": "test@example.com", "dateOfBirth": "1995-06-01" }                      | 400 Bad Request "error": "First name is required" |
| Invalid Email        | { "firstName": "Alice", "email": "invalid-email", "dateOfBirth": "1995-06-01" }   | 400 Bad Request "error": "Invalid email"          |
| Duplicate Email      | Creating a user with john.doe@example.com twice                                   | 400 Bad Request "error": "Email must be unique"   |
| User younger than 18 | { "firstName": "Mike", "email": "mike@example.com", "dateOfBirth": "2010-01-01" } | 400 Bad Request "error": "User must be 18+"       |

### **✅ Running Tests**

Run All Tests:

```bash
composer test
```

Run Integration Tests:

```bash
vendor/bin/phpunit --testsuite Integration Tests
```

Run Unit Tests:

```bash
vendor/bin/phpunit --testsuite Unit Tests
```

Run a Specific Test:

```bash
vendor/bin/phpunit --filter testCreateUserSuccessfully
```

## Troubleshooting - file not found or simmilar error

```bash
composer dump-autoload -o
pkill php
composer start
```

## Next Steps

Consider adding a database layer (SQLite, MySQL) for persistence instead of session storage.
Add authentication (JWT, OAuth, etc.) for securing user endpoints.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

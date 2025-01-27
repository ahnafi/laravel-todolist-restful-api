# To Do List RestFul Api

Project todolist restful api untuk latihan saya dalam menggunakan Laravel framework.

## Table

- users

```
id
first_name  
last_name   
email 
password
photo
created_at
```

- Task

```
id
title
description
due_Date
status
user_id
created_at
updated_at
deleted_at
```

## API Endpoint

#### Response

- success

```json
{
    "data": {
        ...
    }
}
```

- error

```json
{
    "errors": {
        ...
    }
}
```

#### Register User

```http
  POST /api/users/register
```

| Request Body | Type           | Description               |
|:-------------|:---------------|:--------------------------|
| `first_name` | `string`       | **Required**              |
| `last_name`  | `string`       |                           |
| `email`      | `string:email` | **Required**              |
| `password`   | `string`       | **Required** , min 8 char |

##### Response

- success 201

```json
{
    "data": {
        "id": 1,
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budionosiregar@gmail.com",
        "photo": null,
        "created_at": "2025-01-25T07:27:00.000000Z"
    }
}
```

- error

```json
{
    "errors": {
        "first_name": [
            "The first name field is required."
        ],
        "email": [
            "The email has already been taken."
        ],
        "password": [
            "The password field must be at least 8 characters."
        ]
    }
}
```

#### User Login

```http
  POST /api/users/login
```

| Body       | Type           | Description               |
|:-----------|:---------------|:--------------------------|
| `email`    | `string:email` | **Required**              |
| `password` | `string`       | **Required** , min 8 char |

##### Response

- success 201

```json
{
    "data": {
        "id": 1,
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budionosiregar@gmail.com",
        "photo": null,
        "token": "string",
        "created_at": "2025-01-25T07:27:00.000000Z"
    }
}
```

- error

```json
{
    "errors": {
        "message": [
            "Email or Password is wrong"
        ]
    }
}
```

#### Get current user

```http
  GET /api/users/current
```

| Header          | Type     | Description                         |
|:----------------|:---------|:------------------------------------|
| `authorization` | `string` | **Required**. Token dari user login |

##### Response

- success 200

```json
{
    "data": {
        "id": 1,
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budionosiregar@gmail.com",
        "photo": null,
        "token": "string",
        "created_at": "2025-01-25T07:27:00.000000Z"
    }
}
```

- error

```json
{
    "errors": {
        "message": [
            "Unauthorized"
        ]
    }
}
```

#### Update current user

```http
  PATCH /api/users/current
```

| Header          | Type     | Description                         |
|:----------------|:---------|:------------------------------------|
| `Authorization` | `string` | **Required**. Token dari user login |

| Body         | Type                       | Description |
|:-------------|:---------------------------|:------------|
| `first_name` | `string`                   |             |
| `last_name`  | `string`                   |             |
| `password`   | `string`                   |             |
| `photo`      | `file,image(jpg,png,jpeg)` |             |  

##### Response

- success 200

```json
{
    "data": {
        "id": 1,
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budionosiregar@gmail.com",
        "photo": null,
        "token": "string",
        "created_at": "2025-01-25T07:27:00.000000Z"
    }
}
```

- error

```json
{
    "errors": {
        "message": [
            "Unauthorized"
        ]
    }
}
```

#### logout User

```http
  POST /api/users/logout
```

| Header          | Type     | Description                         |
|:----------------|:---------|:------------------------------------|
| `Authorization` | `string` | **Required**. Token dari user login |

##### Response

- success 200

```json
{
    "data": true
}
```

- error

```json
{
    "errors": {
        "message": [
            "Unauthorized"
        ]
    }
}
```

#### Create Todo

```http
  POST /api/tasks
```

| Header          | Type     | Description                         |
|:----------------|:---------|:------------------------------------|
| `Authorization` | `string` | **Required**. Token dari user login |

| Body          | Type          | Description           |
|:--------------|:--------------|:----------------------|
| `title`       | `string`      | **Required**. max 100 |
| `description` | `string:text` |                       |  
| `due_date`    | `datetime`    |                       |

##### Response

- success 200

```json
{
    "data": {
        "id": 1,
        "title": "string",
        "description": "text",
        "due_date": "datetime",
        "status": false,
        "user_id": 1,
        "created_at": "timestamp",
        "updated_at": "timestamp",
        "deleted_at": "timestamp"
    }
}
```

- error

```json
{
    "errors": {
        "message": [
            "Unauthorized"
        ]
    }
}
```

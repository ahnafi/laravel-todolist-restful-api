
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

- Todo

```
id
title
description
due_Date
status
created_at
updated_at
deleted_at
```

## API Endpoint

#### Response

- success

```json
{
    "data":{
        ...
    }
}
```

- error

```json
{
    "errors" : {
        ...
    }
}
```

#### Register User

```http
  POST /api/users/register
```

| Request Body | Type     | Description           |
|:-------------| :------- | :-------------------- |
| `first_name` | `string` | **Required** |
| `last_name`  | `string`  |  |
| `email`      | `string:email`  | **Required** |
| `password`   | `string`  | **Required** , min 8 char |

##### Response

- success 201

```json
{
    "data": {
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

| Body      | Type     | Description           |
| :-------- | :------- | :-------------------- |
| `email` | `string:email`  | **Required** |
| `password` | `string`  | **Required** , min 8 char |

##### Response

- success 201

```json
{
    "data": {
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budionosiregar@gmail.com",
        "photo": null,
        "token" : "string",
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

#### Get item

```http
  GET /api/items/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### add(num1, num2)

Takes two numbers and returns the sum.


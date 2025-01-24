
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
birth_date
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
  GET /api/users/register
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
        "id": 1,
        "first_name": "budi",
        "last_name": "siregar",
        "email": "budi@gmail.com"
    }
}
```

- error

```json
{
    "errors": {
        "email": [
            "email is registered",
            "email blablabla"
        ]
    }
}
```


#### User Login

```http
  GET /api/users/login
```

| Body      | Type     | Description           |
| :-------- | :------- | :-------------------- |
| `email` | `string:email`  | **Required** |
| `password` | `string`  | **Required** , min 8 char |

#### Get item

```http
  GET /api/items/${id}
```

| Parameter | Type     | Description                       |
| :-------- | :------- | :-------------------------------- |
| `id`      | `string` | **Required**. Id of item to fetch |

#### add(num1, num2)

Takes two numbers and returns the sum.



BlablaMovie API
================
Symfony 3.4 with FOSRestBundle


This API allows you to create a poll with the ability to create multiple users who can choose movies from omdbapi.
the functionality of the api :
- create a user (nickname, a single same email, date of birth, creation date in database)
- save a user's choice of a movie
- delete a user's film choice
- list a user's film choices
- list the users who have chosen the same film
- return the best film according to all users


Installing
-----
with composer :
```
php install
```

Usage
-----
### Create a user
Performing a `POST` request on url :`/users`
```
{
    "nickname": "Nickname",
    "email": "nickname@email.com",
    "birthDate": "yyyy-MM-DD"
}
```
This will return a json response
Status 201 Created :
```
{
    "id": 1,
    "nickname": "nickname",
    "email": "nickname@gmail.com",
    "birthDate": "1992-04-23T00:00:00+00:00",
    "createdAt": "2018-04-03T14:59:11+00:00",
    "films": null
}
```
Status 400 Bad Request :
if email already exist in database or validation Failed
### Save a user's choice of a movie
Performing a `POST` request on url :`/users/{id_user}/films`
```
{
	"title" : "Title of the movie",
	"poster": "Movie poster url link"
}
```
This will return a json response
Status 201 Created :
```
{
    "id": 11,
    "title": "Title of the movie",
    "poster": "Movie poster url link",
    "user": {
        "id": 2,
        "nickname": "nickname user",
        "email": "nickname@gyahoo.com",
        "birthDate": "1989-03-08T00:00:00+00:00",
        "createdAt": "2018-03-29T00:00:00+00:00"
    }
}
```
Status 400 Bad Request :
validation failed
### Delete a user's film choice
Performing a `DELETE` request on url :`/films/{id_film}`
This will return a json response
Status 200 Ok :
```
{
    "message": "Film successfully deleted"
}
```
Status 400 Bad Request :
```
{
    "message": "Film not found"
}
```
### List a user's film choices
Performing a `GET` request on url :`/users/{id_user}/films`

This will return a json response
Status 200 example :
```
[
    {
        "id": 2,
        "title": "The Matrix Reloaded",
        "poster": "matrix2.jpg",
        "user": {
            "id": 2,
            "nickname": "george",
            "email": "george@yahoo.com",
            "birthDate": "1989-03-08T00:00:00+00:00",
            "createdAt": "2018-03-29T00:00:00+00:00"
        }
    },
    {
        "id": 8,
        "title": "The Matrix",
        "poster": "matrix1.jpg",
        "user": {
            "id": 2,
            "nickname": "george",
            "email": "george@yahoo.com",
            "birthDate": "1989-03-08T00:00:00+00:00",
            "createdAt": "2018-03-29T00:00:00+00:00"
        }
    }
]
```
Status 400 Bad Request :
```
{
    "message": "Film not found"
}
```
### List the users who have chosen the same film
Performing a `GET` request on url with params `search` and title of movie in value :`/films/search?search={title_of_movie}`

This will return a json response
Status 200 example :
```
[
    {
        "id": 2,
        "nickname": "george",
        "email": "george@yahoo.com",
        "birthDate": "1989-03-08T00:00:00+00:00",
        "createdAt": "2018-03-29T00:00:00+00:00"
    },
    {
        "id": 1,
        "nickname": "theo",
        "email": "theo@gmail.com",
        "birthDate": "1993-09-05T00:00:00+00:00",
        "createdAt": "2018-03-30T00:00:00+00:00"
    },
    {
        "id": 3,
        "nickname": "djad",
        "email": "dajs@gmail.com",
        "birthDate": "2012-04-23T00:00:00+00:00",
        "createdAt": "2018-04-03T14:10:36+00:00"
    }
]
```

### Return the best film according to all users
Performing a `GET` request on url  :`/films/popular`

This will return a json response
Status 200 example :
```
[
    {
        "title": "The Matrix Reloaded",
        "poster": "matrix2.jpg",
        "nb_voted": "3"
    }
]
```

<h1> 4UX API Documentation </h1>
<p> Welcome to the 4UX API documentation. This API serves as the backend for the 4UX application, connecting creatives with the studios they need. Whether you're a music artist looking for a recording studio, a model searching for a photo studio, or any other creative seeking the perfect studio, 4UX is here to help. </p>


<h3>Table of Contents </h3>
<ul>
<li>Authentication</li>
<li>User</li>
<li>Studio</li>
</ul>

<h3> Authentication </h3>
<p>The 4UX API uses Laravel Sanctum for authentication. To access protected routes, you need to include a valid authentication token in the request headers.</p>

Send OTP<br/>
Endpoint: /api/user/sendotp<br/>
Method: POST<br/>
Description: Send Verification OTP to new time User<br/>
Request Body: <br/>
{<br/>
    "number": "2348118419607"<br/>
}<br/>
<br/>
Response: <br/>
{<br/>
  "status": "success",<br/>
  "message": "OTP sent successfully."<br/>
}<br/>
<hr>
<br/>
Verify OTP<br/>
Endpoint: /api/user/verifyotpp<br/>
Method: POST<br/>
Description: Verifies the OTP sent to the number specified<br/>
Request Body: <br/>
{<br/>
    "number": "2348118419607",<br/>
    "otp": "000000"<br/>
}<br/>
<br/>
Response: <br/>
{<br/>
  "status": 200,<br/>
  "message": "User verified successfully."<br/>
}<br/>
<hr>
<br/>
Register<br/>
Endpoint: /api/user/register<br/>
Method: POST<br/>
Description: Registers the new user after verification of the phone numbers
Request Body: <br/>
{
    "first_name": "John",
    "last_name": "Doe",
    "number": "2348118419607",
    "email": "john.doe@example.com",
    "password": "password",
    "password_confirmation": "password"
}
<br/>
Response: <br/>
{
  "status": 200,
  "message": "User registered successfully."
  "user": 1
}
<hr>
<br/>
Login<br/>
Endpoint: URL/api/user/login<br/>
Method: POST<br/>
Description: Registers the new user after verification of the phone numbers<br/>
Request Body: <br/>
POST URL/api/user/login
Content-Type: application/json<br/>

{
    "number": "2348118419607",
    "password": "password"
}
<br/>
Response: <br/>
{
  "status": 200,
  "message": "User logged in successfully.",
  "user": {
    "id": 1,
    "uuid": "49edda85-8b5b-4d72-b130-82b9aed6f178",
    "phone": "2348118419607",
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com"
  }
}

<hr>
<br/>
Studio Register<br/>
Endpoint: /api/studio/store<br/>
Method: POST<br/>
Description: Registers the Studio<br/>
Request Body: <br/>
POST http://127.0.0.1:8000/api/studio/store<br/>
Content-Type: application/json<br/>
<br/>
{
    "studio_name": "Yakata Studios",
    "street_address": "Shomolu",
    "local_government": "Lagos mainland",
    "state": "Palm Grove",
    "description": "Very good Studio and all is fine with it",
    "days_available": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
    "time_available": ["Afternoon", "Evening"],
    "max_people": "4",
    "studio_equipment": "Headphone, Monitors",
    "studio_fee": "5000/hr",
    "dedicated_producer": "Yes",
    "studio_rule": ["no shouting"],
    "images": []
}
<br/>
Response: <br/>
{
  "message": "Studio registered successfully",
  "studio": {
    "studio_name": "Yakatar Studios",
    "street_address": "Shomolu",
    "local_government": "Lagos mainland",
    "state": "Palm Grove",
    "description": "Very good Studio and all is fine with it",
    "days_available": "[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"]",
    "time_available": "[\"Afternoon\",\"Evening\"]",
    "max_people": "4",
    "studio_equipment": "Headphone, Monitors",
    "studio_fee": "5000\/hr",
    "dedicated_producer": "Yes",
    "studio_rule": "[\"no shouting\"]",
    "images": "[]",
    "updated_at": "2023-12-21T11:18:45.000000Z",
    "created_at": "2023-12-21T11:18:45.000000Z",
    "id": 3
  }
}
<br/>
<hr>

Studio Update<br/>
Endpoint: /api/studio/update<br/>
Method: PUT<br/>
Description: Update the Studio Information<br/>
Request Body: <br/>
PUT http://127.0.0.1:8000/api/studio/update<br/>
Content-Type: application/json<br/>
<br/>
{
    "studio_name": "Neutron Studios",
    "street_address": "Herbert Macauley, Yaba",
    "local_government": "Lagos mainland",
    "state": "Akoka",
    "description": "Very good Studio and all is fine with it",
    "days_available": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
    "time_available": ["Afternoon", "Evening"],
    "max_people": "4",
    "studio_equipment": "Headphone, Monitors",
    "studio_fee": "5000/hr",
    "dedicated_producer": "Yes",
    "studio_rule": ["no shouting"],
    "images": []
}
<br/>
Response: <br/>
{
  "message": "Studio updated successfully",
  "studio": {
    "id": 1,
    "studio_name": "Neutron Studios",
    "street_address": "Herbert Macauley, Yaba",
    "local_government": "Lagos mainland",
    "state": "Akoka",
    "description": "Very good Studio and all is fine with it",
    "days_available": "[\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\"]",
    "time_available": "[\"Afternoon\",\"Evening\"]",
    "max_people": "4",
    "studio_equipment": "Headphone, Monitors",
    "studio_fee": "5000\/hr",
    "dedicated_producer": "Yes",
    "studio_rule": "[\"no shouting\"]",
    "images": "[]",
    "created_at": "2023-12-16T19:04:49.000000Z",
    "updated_at": "2023-12-17T08:47:05.000000Z"
  }
}

<hr>

Studio Display<br/>
Endpoint: /api/studio/show/{studio_id}<br/>
Method: GET<br/>
Description: Display a particular Studio Information<br/>
Request Body: <br/>
GET http://127.0.0.1:8000/api/studio/show/1<br/>
Content-Type: application/json<br/>
<br/>

Response: 
{
  "data": {
    "id": 1,
    "studio_name": "Neutron Studios",
    "street_address": "Herbert Macauley, Yaba",
    "local_government": "Lagos mainland",
    "state": "Akoka",
    "description": "Very good Studio and all is fine with it",
    "days_available": [
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday"
    ],
    "time_available": [
      "Afternoon",
      "Evening"
    ],
    "max_people": "4",
    "studio_equipment": "Headphone, Monitors",
    "studio_fee": "5000\/hr",
    "dedicated_producer": "Yes",
    "studio_rule": [
      "no shouting"
    ],
    "images": []
  }
}

<hr>

Studio List Display<br/>
Endpoint: /api/studio/show/{studio_id}<br/>
Method: GET<br/>
Description: Display list of Studios<br/>
Request Body: <br/>
GET http://127.0.0.1:8000/api/studio/discover<br/>
Content-Type: application/json<br/>


Response: <br/>
{
  "data": [
    {
      "id": 1,
      "studio_name": "Neutron Studios",
      "street_address": "Herbert Macauley, Yaba",
      "local_government": "Lagos mainland",
      "state": "Akoka",
      "description": "Very good Studio and all is fine with it",
      "days_available": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "time_available": [
        "Afternoon",
        "Evening"
      ],
      "max_people": "4",
      "studio_equipment": "Headphone, Monitors",
      "studio_fee": "5000\/hr",
      "dedicated_producer": "Yes",
      "studio_rule": [
        "no shouting"
      ],
      "images": []
    },
    {
      "id": 2,
      "studio_name": "Yakata Studios",
      "street_address": "Shomolu",
      "local_government": "Lagos mainland",
      "state": "Palm Grove",
      "description": "Very good Studio and all is fine with it",
      "days_available": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "time_available": [
        "Afternoon",
        "Evening"
      ],
      "max_people": "4",
      "studio_equipment": "Headphone, Monitors",
      "studio_fee": "5000\/hr",
      "dedicated_producer": "Yes",
      "studio_rule": [
        "no shouting"
      ],
      "images": []
    },
    {
      "id": 3,
      "studio_name": "Yakatar Studios",
      "street_address": "Shomolu",
      "local_government": "Lagos mainland",
      "state": "Palm Grove",
      "description": "Very good Studio and all is fine with it",
      "days_available": [
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday"
      ],
      "time_available": [
        "Afternoon",
        "Evening"
      ],
      "max_people": "4",
      "studio_equipment": "Headphone, Monitors",
      "studio_fee": "5000\/hr",
      "dedicated_producer": "Yes",
      "studio_rule": [
        "no shouting"
      ],
      "images": []
    }
  ]
}
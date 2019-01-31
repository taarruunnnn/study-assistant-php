# Study Assistant

The 'Study Assistant' is a web based time schedule management system for students. Built as part of my final year project for the Bachelor of Information Technology degree offered by the University of Moratuwa, this system assists students in the following ways.

* Create customized study schedules based on students' modules
* Provide detailed analysis about modules based on data collected from students
* Provide reports about the progress of student
* Predict grades based on the data collected from students
* Notify and record study session related data from students


### Brief Overview

##### Dashboard

![Dashboard Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/dashboard.png "Dashboard")
Students can view a brief overview of their current progress here.
<br/>
<br/>
<br/>
![Schedule Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/schedule.png "Schedules")
Upcoming schedule sessions are displayed here. They can be modified or moved as the student sees fit.
<br/>
<br/>
<br/>
![Reports Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/reports.png "Reports")
Reports will be generated based on the performance of students.
<br/>
<br/>
<br/>
![Sessions Timer Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/study-session.png "Session Timer")
Session timer will record the study duration of students.
<br/>
<br/>
<br/>
![Admin Dashboard Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/admin-dashboard.png "Admin Dashboard")
Admins get a brief overview of the entire system upon logging in.
<br/>
<br/>
<br/>
![Prediction Settings Screenshot](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/admin-predictions.png "Prediction Settings")
Admins can change parameters of the prediction algorithm and choose the best combination to make accurate predictions for student grades.
<br/>
<br/>
<br/>
![Manage Users](https://raw.githubusercontent.com/davehowson/study-assistant-php/master/storage/app/public/images/screenshots/admin-users.png "Manage Users")
Admins can view a brief overview of the students in order to manage them.
<br/>
<br/>

#### Technologies Utilized
This repository consists of the main web server which is based on PHP. The technologies used here are
* PHP
* Laravel
* HTML
* JavaScript (jQuery)
* CSS (Bootstrap 4)

##### Python Web Server
A separate backend is hosted in addition to this PHP based backed, to hold the Python code. Python is used here to analyze the data collected from students in order to provide them with reports and predictions.
The repository of the Python backend of this project can be found [here](https://github.com/davehowson/study-assistant-python).

##### Android Application
The repository of the Android Application of this project can be found [here](https://github.com/davehowson/study-assistant-android).

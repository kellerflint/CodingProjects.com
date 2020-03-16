# coding-projects

Running development environment on local with Docker
* Install docker desktop and make sure it is running on your computer
* From the project root, run docker-compose up
* The default url is localhost:5001/
* To run composer for the first time on your docker server, use ```docker exec -it app_name bash``` and run ```composer install```

To update composer
* ```docker exec -it codingprojectscom_app_1 bash``` or ```coding-projects_app_1``` and then run ```composer update```

To enable routing for the docker container
* Make sure the .htaccess file is in the project root
* Run the command ```docker exec -it app_name bash``` to open a terminal inside the running docker app container
* Run the command ```a2enmod rewrite``` and then ```service apache2 restart```. This will terminate the web_app container so you'll need to restart it with ```docker-compose up```

Coding Projects
CodingProjects.com is a web app designed for students to introduce them to today’s world changing weapon programming. 
Getting Started
Simply, go to www.codingprojects.com. If you are a user your teacher will provide you login in information or you can simply enjoy video without signing in.
Built With
•	Fat free php framework: Routes all URLs and leverages a templating language using the Fat-Free framework.
•	Model View Control: All the database/ business logic has been separated with Model View Control pattern.
•	 Database: Database layer using PDO and prepared statements.
•	OOP has been used to defines multiple classes, including inheritance relationship.

![](images/diagram.PNG)
![](images/UML.PNG)



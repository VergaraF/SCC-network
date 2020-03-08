SCC-Network

Installation

1. Make sure you have installed MySQL 8.x and  PHP 7.x
2. Open the folder Backend/DatabaseScrips where you will find the following files:
* createDatabaseAndTables.sql
* populateData.sql
* triggers.sql
* events.sql
3. Open (edit) createDatabaseAndTables.sql script and modify the first two lines with the name of your database 
4. Do the same for all .sql script files 
5. Execute the SQL scripts on the following order:
    * createDatabaseAndTables.sql
    * triggers.sql
    * events.sql
    * populateData.sql

6. Using XAMPP, all files found in this folder in your webserver
7. Go to Backend/Controllers/DatabaseController.php file, open it and modify the first lines of the "createConnection()" method with the actual data of your MySQL connection including host, port, socket, user, password and database name (that you setup in step 3).
8. Restart your Apache server if necessary.
9. Go to the root folder of the web app and index.php should load
10. Use the root user credentials to login as an administrator. Username: root, password: root

HomewizardPHP
=============
Homewizard API for storing and retrieving data of the Homewizard (http://www.homewizard.be).

v20141228
- First Release

Requirements:
PHP 5+ and MySQL 5+

Installation:
- Create mysql database with latest script in /database
- Put all files in a folder and preserve folder structure
- adjust parameters.php for database connection and homewizard url, username and password. 
- Create cron_job for history_to_sql.php for automatic import of data.

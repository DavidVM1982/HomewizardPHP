HomewizardPHP
=============
Homewizard API for storing and retrieving data of the Homewizard (http://www.homewizard.be).
See version history below for possibilities. 

Requirements:
PHP 5+ and MySQL 5+

Installation:
- Create mysql database with latest script in /database
- Put all files in a folder and preserve folder structure
- adjust parameters.php for database connection and homewizard url, username and password. 
- Create cron_job for history_to_sql.php for automatic import of data.

Updating:
- update database with each update script
- replace files except parameters.php (look for changes/additions in it)
- 
Version History:
v20141228
- First Release
- View and switch switches and radiator valves
- View live status of sensors
- View history of sensors
- View live status of temperature, rain and windmeters
- View history of temperature, rain and windmeters

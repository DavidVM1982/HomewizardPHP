<b>HomewizardPHP</b>
=============
Homewizard API for storing and retrieving data of the Homewizard (http://www.homewizard.be).<br/>
See version history below for possibilities or view a live site at http://egregius.be/homewizard/index.php

<b>Requirements:</b>
PHP 5+ and MySQL 5+

<b>Installation:</b>
- Create mysql database with latest script in /database
- Put all files in a folder and preserve folder structure
- adjust parameters.php for database connection and homewizard url, username and password. 
- Create cron_job for history_to_sql.php for automatic import of data.

<b>Updating:</b>
- update database with each update script in /database
- replace files except parameters.php (look for changes/additions in it)

<b>Version History: (reversed order)</b><br/>
<b>v20141230</b>
- Added scenes to home screen
- Implemented bootstrap CSS for responsive design

<b>v20141228</b>
- First Release
- View and switch switches and radiator valves
- View live status of sensors
- View history of sensors
- View live status of temperature, rain and windmeters
- View history of temperature, rain and windmeters

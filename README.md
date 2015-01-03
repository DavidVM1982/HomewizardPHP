<b>HomewizardPHP</b><br/>
=============<br/>
Homewizard API for storing and retrieving data of the Homewizard (http://www.homewizard.be).<br/>
See version history below for possibilities or view a live site at http://egregius.be/homewizard/index.php<br/>

<b>Requirements:</b>
PHP 5+ and MySQL 5+

<b>Installation:</b><br/>
- Create mysql database with latest script in /database<br/>
- Put all files in a folder and preserve folder structure<br/>
- adjust parameters.php for database connection and homewizard url, username and password. <br/>
- Create cron_job for history_to_sql.php for automatic import of data.<br/>

<b>Updating:</b><br/>
- update database with each update script in /database<br/>
- replace files except parameters.php (look for changes/additions in it)<br/>

<b>Version History: (reversed order)</b><br/>
 ++ New feature<br/>
 -- Removed feature<br/>
 !! Update/bugfix<br/>

<b>v20150103</b><br/>
++ DIVS for sensors, temperature, rain and wind clickable links.<br/>
++ Switches and sensors in database to sync favorites, grouped display etc.<br/>
++ Ability to force update/import data to history.<br/>
++ Added support for dimmers.<br/>

<b>v20150102</b><br/>
!! Fixed issue in radiator optionlist only showing values from list. And form submits on change instead of extra button.<br/>

<b>v20141231</b><br/>
-- removed bootstrap CSS and created own responsive design<br/>
++ Much nicer interface<br/>
!! removed everything from parameters.php. Now only contains databaseconnection.<br/>

<b>v20141230</b><br/>
++ Added scenes to home screen<br/>
++ Implemented bootstrap CSS for responsive design<br/>
++ Moved settings from parameters.php to database<br/>

<b>v20141228</b><br/>
++ First Release<br/>
++ View and switch switches and radiator valves<br/>
++ View live status of sensors<br/>
++ View history of sensors<br/>
++ View live status of temperature, rain and windmeters<br/>
++ View history of temperature, rain and windmeters<br/>

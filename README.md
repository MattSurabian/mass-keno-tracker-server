mass-keno-tracker-server
========================

JSONP server for the mass-keno-tracker

## Dependencies
This project uses composer to manage all PHP dependencies. The _assumed_ server dependecies are pretty minimal:

 - php5
 - php5-cli
 - php5-curl
 - Composer
 
 ```
 sudo apt-get install php5
 sudo apt-get install php5-cli
 sudo apt-get install php5-curl
 curl -sS https://getcomposer.org/installer | php
 ```
 
## Deployment

This is currently deployed on Heroku. As a result the base stack is taken care of. Simply pushing this repository to Heroku will install all composer dependencies.

### Environment Variables

This code relies on the following environment variables in order to communicate with AWS S3
 
 - AWS_S3_BUCKET
 - AWS_KEY_ID
 - AWS_ACCESS_KEY
 
If deployed on Heroku these can be set in the application's configuration variables.

### Job Scheduling

Jobs are scheduled on Heroku using scheduler, but can easily be scheduled with cron as well. Only the `realtimeKenoService` needs to be run regularly to provide fresh data on S3 for our application, as payout information rarely changes it can be run ad-hoc. 


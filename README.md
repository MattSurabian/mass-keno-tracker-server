mass-keno-tracker-server
========================

JSONP server for the mass-keno-tracker

This queries The Massachusett's Lottery servers for a [JSON object containing the day's Keno draw data](http://www.masslottery.com/data/json/search/dailygames/todays/keno.json). This object gets wrapped in a callback function and written to S3 where the front end can read it, preventing both my little m1.small and the state's servers from being bombarded by realtime requests from anyone that's experimenting or using this app.
 
### IAM Policy

The server authenticates to AWS using the `KenoBucketService` class and inherits the following IAM policy that I've setup:

```
{
  "Statement": [
        {
            "Effect": "Allow",
            "Action": [ "s3:PutObject", "s3:PutObjectAcl"],
            "Resource": [ "arn:aws:s3:::keno-tracker-ma/*"]
        }
  ]
}
```

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

If you're interested in setting this up with cron outside of Heroku here's a **Sample Crontab**:

```
* * * * * /usr/bin/php5 /mnt/www/keno/realtimeKenoService.php
``` 

<?php
/**
 * KenoBucketService
 *
 * Super simple service class to connect to S3 and add files to the Keno Bucket
 */

// Thanks, composer!
require 'vendor/autoload.php';

use Aws\S3\S3Client;

class KenoBucketService {

    protected $aws_key_id;
    protected $aws_access_key;
    protected $s3_bucket;

    /**
     * @var S3Client $s3_client
     */
    protected $s3_client = null;
    protected function connect(){
        $this->s3_client = S3Client::factory(array(
            'key' => $this->aws_key_id,
            'secret' => $this->aws_access_key
        ));
    }

    /**
     * Constructor
     * Connects client to S3
     */
    public function __construct(){
        $this->aws_access_key = getenv('AWS_ACCESS_KEY');
        $this->aws_key_id = getenv('AWS_KEY_ID');
        $this->s3_bucket = getenv('AWS_S3_BUCKET');
        $this->connect();
    }

    /**
     * @param String $key - The name of the file writen to S3
     * @param $data - The contents to write in the file
     * @param string $acl OPTIONAL - access control param, check AWS docs. Potential values: private | public-read | public-read-write | authenticated-read | bucket-owner-read | bucket-owner-full-control
     */
    public function upload($key, $data, $acl = 'public-read', $contentType = 'application/javascript'){
        try{
            // send the day's current keno data to S3 so neither the state nor my poor little server are overrun
            $this->s3_client->putObject(array(
                'Bucket' => $this->s3_bucket,
                'Key' => $key,
                'Body' => $data,
                'ACL' => $acl,
                'ContentType' => $contentType
            ));
        } catch (Exception $e){
            echo $e->getMessage();
            // TODO: Something with errors? Silent fail is fine for now and likley the result of an AWS outage
        }
    }

}
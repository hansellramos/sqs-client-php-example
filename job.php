<?php 

echo "Starting \n";

require 'vendor/autoload.php';

use Aws\Sqs\SqsClient;
use Aws\Credentials\CredentialProvider;
use Aws\Exception\AwsException;

$config['sqs'] = [];
$config['sqs']['credentials'] = [
    'credentials' => array(
        'key'       => 'key-value',
        'secret'    => 'secret-value',
    ),    
    'region'    => 'us-east-1',
    'version'   => 'latest',
    'QueueUrls' => 'https://sqs.us-east-1.amazonaws.com/[queue-id]/[queue-name]' 
];

$client = new SqsClient($config['sqs']['credentials']);

try {
    $result = $client->receiveMessage(array(
        'AttributeNames' => ['SentTimestamp'],
        'MaxNumberOfMessages' => 1,
        'MessageAttributeNames' => ['All'],
        'QueueUrl' => $config['sqs']['credentials']['QueueUrls'],
        'WaitTimeSeconds' => 0,
    ));

    $messages = $result->get('Messages');
    if (count($messages) > 0) {
        foreach($messages as $message) {
            var_dump($message);
        }
    } else {
        echo "No messages on queue. \n";
    }
} catch(AwsException $e) {
    error_log($e->getMessage());
}

echo "Ran \n"; 


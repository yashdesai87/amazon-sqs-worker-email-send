<?php

use Aws\Sqs\SqsClient;

class Worker extends CI_Controller {

    function process_email_queue()
    {
		$this->load->model('Email_model');

		while(TRUE)
		{
			$factoryInit = array(
				'credentials' => array(
				    'key'    => awsAccessKey,
				    'secret' => awsSecretKey,
				),
				'region'  => awsRegion,
				'version' => '2012-11-05'
			);

			$sqsClient = SqsClient::factory($factoryInit);

			$response = $sqsClient->receiveMessage(array(
	            'QueueUrl' => queueUrl,
	            'MaxNumberOfMessages' => 10
	        ));

			$responseArr = $response->toArray();

			if(isset($responseArr['Messages']))
			{
				foreach($responseArr['Messages'] as $message)
				{
					$receipt_handle = $message['ReceiptHandle'];
					$queue_id = json_decode($message['Body'],TRUE);

					$this->db->reconnect();
					$queue_item = $this->Email_model->get_email_to_be_sent($queue_id);

					if(isset($queue_item['id']))
					{
						$this->Email_model->send_email_from_queue($queue_item);
					}	

					$this->sqs->delete(queueUrl,$receipt_handle);
				}
			}
		}
    }
}
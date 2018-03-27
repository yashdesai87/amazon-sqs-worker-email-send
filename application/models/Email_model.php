<?php

use Aws\Sqs\SqsClient;

class Email_model extends CI_Model {

	function queue_email($to_email,$subject,$body,$from_email)
	{
		$email_data = array(
			'email_subject' => $subject,
			'email_body' => $body,
			'email_to' => $to_email,
			'queued_on' => time(),
			'is_email_sent' => 0
		);

		$this->db->insert('email_queue',$email_data);
		$email_queue_id = $this->db->insert_id();

		$factoryInit = array(
			'credentials' => array(
			    'key'    => awsAccessKey,
			    'secret' => awsSecretKey,
			),
			'region'  => awsRegion,
			'version' => '2012-11-05'
		);

		$sqsClient = SqsClient::factory($factoryInit);

		$result = $sqsClient->sendMessage(array(
            'QueueUrl'    => queueUrl,
            'MessageBody' => json_encode(array('queue_id'=>$email_queue_id))
        ))->toArray();

		return TRUE;
	}

	function send_email_from_queue($queue)
	{
		// Code to send email
		// this could be codeigniter email library or calling an email delivery agent like Sendgrid / Mandrill / Postmark etc.

		return $this->db->update('email_queue',array('is_email_sent' => 1,'sent_on' => time()),array('id' => $queue['id']));
	}

	function get_email_to_be_sent($queue_id)
	{
		return $this->db->query("
			SELECT 
				* 

			FROM 
				email_queue 

			WHERE 
				is_email_sent = 0 AND 
				id = ? 

			ORDER BY 
				queued_on asc
		", array($queue_id))->row_array();
	}
}
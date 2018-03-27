# amazon-sqs-worker-email-send

If you want to have any email functionality on your web application the most efficient way to do it is using Queues. 
Queues can be maintained using softwares such as beanstalkd, RabbitMQ or Gearman. However, with approaches that involve automatic platform 
scaling and utilizing cloud hosted services (IaaS based implementation using Amazon AWS) such as Amazon SQS, it has become very easy to implement your own queue with no maintenance involved.

This piece of code is written to leverage Amazon SQS queues to send emails from a script written in PHP Codeigniter.

Process of sending an email :
1. Call the model function `queue_email()` which will add an entry into the `email_queue` table, store all the email information to be sent and also send an API request to Amazon SQS to queue the job
2. Run the worker as a process using `nohup php /var/www/html/index.php worker send_email_from_queue &` 
3. The worker picks up items from the queue and calls the send_email_from_queue() function to actually send the email.

Enhancements :
- The worker can be run using supervisord instead of nohup to enable a process control daemon to monitor the unexpected terminations and automatic recovery of the process
`

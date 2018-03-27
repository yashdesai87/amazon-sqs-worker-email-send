# amazon-sqs-worker-email-send

If you want to have any email functionality on your web application the most efficient way to do it is using Queues. 
Queues can be maintained using softwares such as beanstalkd, RabbitMQ or Gearman. However, with approaches that involve automatic platform 
scaling and utilizing cloud hosted services (IaaS based implementation using Amazon AWS) such as Amazon SQS, it has become very easy to implement 
your own queue.

This piece of code is written to leverage Amazon SQS queues to send emails from a script written in PHP Codeigniter.

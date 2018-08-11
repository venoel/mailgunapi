# Mailgun API for Yii 1.x #

## Introduction ##

This simple Yii extension allow send email in HTML format through Mailgun API. It is easy switch between sandbox and real domain.

Yii is a fast, secure, and efficient PHP framework. https://www.yiiframework.com/

Mailgun - Powerful APIs that enable you to send, receive and track email effortlessly. Powerful APIs that enable you to send,
receive and track email effortlessly. https://www.mailgun.com/

## Usage ##

Download Mailgun.php and place it in ```/protected/extension``` directory.

Set configuration. 

```/protected/config/main.php```

```
'components'=>array(
...
		'mailgun' => array(
			'class' => 'ext.Mailgun',
			'sandboxUrl' => 'https://api.mailgun.net/v3/<your_sandbox_domain>',
			'url' => 'https://api.mailgun.net/v3/<your_real_domain>',
			'apikey' => '<your_api_key>',
			'useSandbox' => true,
		),
...
);
```

In some controller
```
		$mailgun = Yii::app()->mailgun;
		$res = $mailgun->send('from@somedomain.com','to@anotherdomain.com','Hello','<h1>Test1</h1>');
		var_dump($res,$mailgun->getStatus());
```

## Configuration ##

*class* - path to Mailgun.php

*sandboxUrl* - sandbox domain. See https://app.mailgun.com/app/domains

*url* - Your real domain. See https://app.mailgun.com/app/domains

*apikey* - See API key in Domain Information section.

*useSandbox* - switch between sandbox and real domain. If ```true``` *sandboxUrl* is used. If ```false``` *url* is used.

## Sending email ##

```send($from,$to,$subject,$htmlBody)``` Send email. Return ```true``` on success.

```getStatus()``` Get status of last request.

ALTER TABLE `#__pbevents_config` ADD COLUMN(
	`default_success_URL` varchar(255) DEFAULT 'http://yoursite/',
	`default_failure_URL` varchar(255) DEFAULT 'http://yoursite/',
	`default_notification_email` varchar(255) DEFAULT 'eventsadmin@yoursite.com'
  );
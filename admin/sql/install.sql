DROP TABLE IF EXISTS `#__pbevents_events`;
DROP TABLE IF EXISTS `#__pbevents_rsvps`;
DROP TABLE IF EXISTS `#__pbevents_config`;

CREATE TABLE `#__pbevents_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_name` varchar(128) DEFAULT NULL,
  `description` text,
  `fields` text,
  `attendees` text,
  `dtstart` datetime DEFAULT NULL,
  `dtend` datetime DEFAULT NULL,
  `archived` tinyint(128) DEFAULT '0',
  `max_people` int(128) DEFAULT '0',
  `confirmation_page` varchar(256) DEFAULT NULL,
  `email_admin_success` tinyint(1) DEFAULT '0',
  `email_admin_failure` tinyint(1) DEFAULT '0',
  `failed_page` varchar(256) DEFAULT NULL,
  `send_notifications_to` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `#__pbevents_rsvps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) DEFAULT '0',
  `data` text,
  PRIMARY KEY (`id`)
);


CREATE TABLE `#__pbevents_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email_success_body` text,
  `email_success_subject` text,
  `email_failed_body` text,
  `email_failed_subject` text,
  `date_picker_locale` varchar(10),
  PRIMARY KEY (`id`)
);



INSERT INTO `#__pbevents_config` (`id`, `email_success_body`, `email_success_subject`, `email_failed_body`, `email_failed_subject`,`date_picker_locale`) VALUES (1, '<p>This is to inform you of a successful event registration.</p><p>The event details were:</p><p>|*event*|</p><p>The user details were:</p><p>|*user*|</p>', 'Successful Event Registration', '<p>This is to inform you of a failed event registration.</p><p>The event details were:</p><p>|*event*|</p><p>The user details were:</p><p>|*user*|</p>', 'Failed Event Registration','en-US');

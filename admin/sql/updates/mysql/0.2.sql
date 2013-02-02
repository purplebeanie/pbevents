ALTER TABLE `#__pbevents_events` ADD COLUMN(
  `send_client_confirmation` tinyint(1) DEFAULT '0',
  `client_confirmation_subject` varchar(256),
  `client_confirmation_message` text,
  `publish` tinyint(1) DEFAULT 1,
  `show_counter` tinyint(1) DEFAULT 0
  );
DROP DATABASE SCCNetwork;

CREATE DATABASE IF NOT EXISTS SCCNetwork;
USE SCCNetwork;

CREATE TABLE `SCCSystemStatus`(
	`id` int PRIMARY KEY,
    `IsDatabaseCreated` boolean DEFAULT false,
    `IsDatabasePopulated` boolean DEFAULT false
);

CREATE TABLE `User` (
  `userId` int PRIMARY KEY AUTO_INCREMENT,
  `username` varchar(255) UNIQUE NOT NULL,
  `password` varchar(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `firstname` varchar(255),
  `lastname` varchar(255),
  `email` varchar(255) UNIQUE NOT NULL,
  `age` int,
  `profession` varchar(255),
  `dateOfBirth` date,
  `roleInSCC_id` int,
  `joinedAt` datetime DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `UserActionLog` (
	`id` int PRIMARY KEY AUTO_INCREMENT,
    `user_id` int NOT NULL,
    `actionPerformed` text,
    `actionPerformedAt` datetime DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `Newsfeed` (
	`newsFeedId` int PRIMARY KEY AUTO_INCREMENT,
    `userId` int UNIQUE,
    `checkedAt` datetime
);

CREATE TABLE `RoleInSCC` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `role_name` varchar(255)
);

CREATE TABLE `Administrator` (
  `adminId` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int
);

CREATE TABLE `Controller` (
  `controllerId` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int
);

CREATE TABLE `Participant` (
  `participantId` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `organization` varchar(255)
);

CREATE TABLE `Member` (
  `memberId` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int
);

CREATE TABLE `Manager` (
  `managerId` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `address` varchar(255),
  `phone_number` varchar(255)
);

CREATE TABLE `Event` (
  `eventId` int PRIMARY KEY AUTO_INCREMENT,
  `event_name` varchar(255),
  `eventType_id` int
);

CREATE TABLE `Page` (
  `pageId` int PRIMARY KEY AUTO_INCREMENT,
  `content` text
);

CREATE TABLE `EventStatus` (
  `eventStatusId` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `BankingInfo` (
  `bankingInfoId` int PRIMARY KEY AUTO_INCREMENT,
  `account_number` int,
  `account_code` int
);

CREATE TABLE `Group` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `owner_participant_id` int NOT NULL,
  `group_page_id` int NOT NULL
);

CREATE TABLE `EventType` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255)
);

CREATE TABLE `Fee` (
  `Id` int PRIMARY KEY AUTO_INCREMENT,
  `eventType_id` int,
  `rateForExtraStorageUnit` int,
  `rateForExtraBandwithUnit` int,
  `flatRate` int,
  `rateForExtraExpirationDay` int
);

CREATE TABLE `Content` (
  `contentId` int PRIMARY KEY AUTO_INCREMENT,
  `contentType` varchar(255),
  `value` text,
  `postedAt` datetime DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `DefaultEventConfiguration` (
  `defaultEventConfigurationId` int PRIMARY KEY AUTO_INCREMENT,
  `defaultBandwidthLimitInMB` int,
  `defaultStorageLimitInMb` int,
  `defaultInitialActiveEventConfigurationTimeInHours` int
);

CREATE TABLE `participant_interest` (
	`participant_interestId` int PRIMARY KEY AUTO_INCREMENT,
    `participant_id` int,
    `participantInterest` varchar(255)
);

CREATE TABLE `event_manager` (
  `event_id` int NOT NULL,
  `manager_id` int NOT NULL,
  `event_instance_id` int NOT NULL,
  `bankingInfo_id` int,
  `assignedAt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`event_id`, `manager_id`)
);

CREATE TABLE `event_instance` (
  `event_instanceId` int PRIMARY KEY AUTO_INCREMENT,
  `event_id` int NOT NULL,
  `storage_limit` int DEFAULT 2048,
  `bandwith_limit` int DEFAULT 2048,
  `storage_usage` int DEFAULT 0,
  `bandwidth_usage` int DEFAULT 0,
  `eventStatus_id` int DEFAULT 1,
  `lifetime` date,
  `page_id` int DEFAULT 1,
  `bill_id` int
);

CREATE TABLE `event_participants` (
  `event_instance_id` int,
  `event_participant_id` int,
  PRIMARY KEY (`event_instance_id`, `event_participant_id`)
);

CREATE TABLE `event_groups` (
  `event_instance_id` int NOT NULL,
  `event_group_id` int NOT NULL,
  PRIMARY KEY (`event_instance_id`, `event_group_id`)
);

CREATE TABLE `group_member` (
  `event_group_id` int NOT NULL,
  `member_id` int NOT NULL,
  PRIMARY KEY (`event_group_id`, `member_id`)
);

CREATE TABLE `group_content` (
  `event_group_id` int NOT NULL,
  `event_group_content_id` int NOT NULL,
  `event_group_content_author_member_id` int NOT NULL,
  PRIMARY KEY (`event_group_id`, `event_group_content_id`)
);

CREATE TABLE `event_instance_content` (
  `event_instance_id` int NOT NULL,
  `event_instance_contentId` int NOT NULL,
  `event_instance_content_author_participant_id` int NOT NULL,
  PRIMARY KEY (`event_instance_id`, `event_instance_contentId`)
);

CREATE TABLE `Bill` (
  `billId` int PRIMARY KEY AUTO_INCREMENT,
  `total` int,
  `paymentProvider` varchar(255),
  `pay_by_manager_id` int,
  `paymentStatus` varchar(255)
);

CREATE TABLE `Message` (
  `messageId` int PRIMARY KEY,
  `content` text,
  `timestamp` datetime DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `messages_conversation` (
  `message_id` int,
  `recipient_id` int,
  `sender_id` int,
  PRIMARY KEY (`message_id`, `recipient_id`, `sender_id`)
);

ALTER TABLE `User` ADD FOREIGN KEY (`roleInSCC_id`) REFERENCES `RoleInSCC` (`id`);

ALTER TABLE `Administrator` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `Controller` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `Participant` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `UserActionLog` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `Member` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `Manager` ADD FOREIGN KEY (`user_id`) REFERENCES `User` (`userId`);

ALTER TABLE `Newsfeed` ADD FOREIGN KEY (`userID`) REFERENCES `User` (`userId`);

ALTER TABLE `Event` ADD FOREIGN KEY (`eventType_id`) REFERENCES `EventType` (`id`);

ALTER TABLE `Group` ADD FOREIGN KEY (`owner_participant_id`) REFERENCES `Participant` (`participantId`);
ALTER TABLE `Group` ADD FOREIGN KEY (`group_page_id`) REFERENCES `Page` (`pageId`);

ALTER TABLE `participant_interest` ADD FOREIGN KEY (`participant_id`) REFERENCES `Participant` (`participantId`);

ALTER TABLE `event_manager` ADD FOREIGN KEY (`event_id`) REFERENCES `Event` (`eventId`);
ALTER TABLE `event_manager` ADD FOREIGN KEY (`manager_id`) REFERENCES `Manager` (`managerId`);
ALTER TABLE `event_manager` ADD FOREIGN KEY (`event_instance_id`) REFERENCES `event_instance` (`event_instanceId`);
ALTER TABLE `event_manager` ADD FOREIGN KEY (`bankingInfo_id`) REFERENCES `BankingInfo` (`bankingInfoId`);

ALTER TABLE `event_instance` ADD FOREIGN KEY (`event_id`) REFERENCES `Event` (`eventId`);
ALTER TABLE `event_instance` ADD FOREIGN KEY (`eventStatus_id`) REFERENCES `EventStatus` (`eventStatusId`);
ALTER TABLE `event_instance` ADD FOREIGN KEY (`page_id`) REFERENCES `Page` (`pageId`);
ALTER TABLE `event_instance` ADD FOREIGN KEY (`bill_id`) REFERENCES `Bill` (`billId`);

ALTER TABLE `event_participants` ADD FOREIGN KEY (`event_instance_id`) REFERENCES `event_instance` (`event_instanceId`);
ALTER TABLE `event_participants` ADD FOREIGN KEY (`event_participant_id`) REFERENCES `Participant` (`participantId`);

ALTER TABLE `event_groups` ADD FOREIGN KEY (`event_instance_id`) REFERENCES `event_instance` (`event_instanceId`);
ALTER TABLE `event_groups` ADD FOREIGN KEY (`event_group_id`) REFERENCES `Group` (`id`);

ALTER TABLE `group_member` ADD FOREIGN KEY (`event_group_id`) REFERENCES `event_groups` (`event_group_id`);
ALTER TABLE `group_member` ADD FOREIGN KEY (`member_id`) REFERENCES `Member` (`memberId`);

ALTER TABLE `group_content` ADD FOREIGN KEY (`event_group_id`) REFERENCES `event_groups` (`event_group_id`);
ALTER TABLE `group_content` ADD FOREIGN KEY (`event_group_content_id`) REFERENCES `Content` (`contentId`);
ALTER TABLE `group_content` ADD FOREIGN KEY (`event_group_content_author_member_id`) REFERENCES `group_member` (`member_id`);

ALTER TABLE `event_instance_content` ADD FOREIGN KEY (`event_instance_id`) REFERENCES `event_instance` (`event_instanceId`);
ALTER TABLE `event_instance_content` ADD FOREIGN KEY (`event_instance_contentId`) REFERENCES `Content` (`contentId`);
ALTER TABLE `event_instance_content` ADD FOREIGN KEY (`event_instance_content_author_participant_id`) REFERENCES `event_participants` (`event_participant_id`);

ALTER TABLE `Fee` ADD FOREIGN KEY (`eventType_id`) REFERENCES `EventType` (`id`);

ALTER TABLE `Bill` ADD FOREIGN KEY (`pay_by_manager_id`) REFERENCES `event_manager` (`manager_id`);

ALTER TABLE `messages_conversation` ADD FOREIGN KEY (`message_id`) REFERENCES `Message` (`messageId`) ON DELETE CASCADE;
ALTER TABLE `messages_conversation` ADD FOREIGN KEY (`recipient_id`) REFERENCES `Participant` (`participantId`) ON DELETE CASCADE;
ALTER TABLE `messages_conversation` ADD FOREIGN KEY (`sender_id`) REFERENCES `Participant` (`participantId`) ON DELETE CASCADE;

INSERT INTO `SCCSystemStatus`(id, IsDatabaseCreated, IsDatabasePopulated) VALUES('1', true, false);
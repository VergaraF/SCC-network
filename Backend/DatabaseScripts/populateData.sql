INSERT INTO `SCCNetwork`.`RoleInSCC`(`id`, `role_name`) VALUES 
						(1, 'Administrator'), 
						(2, 'Controller'), 
						(3, 'Regular');
                        
INSERT INTO `SCCNetwork`.`EventType`(`id`, `name`) VALUES 
						(1, 'Private'), 
						(2, 'Non-profit'), 
						(3, 'Other');
                        
INSERT INTO `SCCNetwork`.`EventStatus`(`eventStatusId`, `name`) VALUES
						  (1, 'Active'), 
						  (2, 'Archived'),
                          (3, 'Other'),
                          (4, 'Deleted');
                          
INSERT INTO `SCCNetwork`.`DefaultEventConfiguration`(`defaultEventConfigurationId`, `defaultBandwidthLimitInMB`, 
													 `defaultStorageLimitInMb`, `defaultInitialActiveEventConfigurationTimeInHours`) VALUES 
						(1, 2048, 2048, 168);

INSERT INTO `SCCNetwork`.`Page`(`pageId`, `content`) VALUES
						(1, 'default');
                        
INSERT INTO `SCCNetwork`.`Content`(`contentId`, `contentType`, `value`) VALUES 
						(1, 'Comment', 'Welcome to your new event! You may want to start by making a payment for the associated event fee, if applicable. 
										Please note that there is a grace period of seven days from the creation date of the group to confirm your payment.
                                        After your grace period has passed, your event will be ''Active'' but there will be some restrictions until a payment is received.'),
						(2, 'Comment', 'Welcome to your new group!');
 
INSERT INTO `SCCNetwork`.`User`(`userId`, `username`, `password`, `salt`, `firstname`, `lastname`, `email`, 
								`age`, `profession`, `dateOfBirth`, `roleInSCC_id`) VALUES 
                    (1, 'root', 'root', 'test', 'Fabian', 'Vergara', 'root@scc.network', 26, 'Developer', '93-11-23', 1),
                    (2, 'sleblanc', 'abc123', 'test', 'Sophie', 'Leblanc', 'sleblanc@apple.ca', 28, 'Project Manager', '91-12-12', 1),
                    (3, 'Turtle', 'abcdefg', 'test', 'Franklin', 'Glen', 'f_glen@tesla.com', 32, 'Engineer', '87-02-28', 2),
                    (4, 'Lauversan', 'medicina', 'test', 'Laura', 'Vergara', 'md_vergara@med.towmhospital.ca', 29, 'Doctor', '90-08-27', 3),
                    (5, 'dballerini', 'mecheng', 'test', 'Daniel', 'Ballerini', 'd_baller@encs.concordia.ca', 24, 'Student', '94-08-30', 2),
					(6, 'VergaraF', '123456', 'test', 'Fabian', 'Vergara', 'fvergara@egenetec.com', 26, 'Developer', '93-11-23', 1);
              

INSERT INTO `SCCNetwork`.`Event`(`eventId`, `event_name`, `event_description`, `eventType_id`) VALUES
					(1, 'Graduation Ceremony Preparation', 'Come attend hour graduation ceremony preparion. Everyone is welcome.',1),
                    (2, 'Flu Vaccination CLSC Campaign', 'Winter is here and you should prepare your inmune system by participating in this journey of vaccination', 2),
                    (3, 'Vergara Family Mid-century Party', 'Because being 50 years old is a lot of time, come have fun',1),
                    (4, 'Townhall Public Garage Sale', 3),
                    (5, 'Rock-al-Parque Homeless Fundraising', 'We area attempting to fundraise funds for people in the streets and children. Come see your favorita rockstars and contribugte to the cause!',2),
                    (6, 'Database Architecture Information Session', 'Db, architecure, info, session, come and learn to master databases',3);
                    
INSERT INTO `SCCNetwork`.`Manager`(`managerId`, `user_id`, `address`, `phone_number`) VALUES 
						(1, 1, '2460 Rue de la Savane. H4F-1Y4. Montreal, QC.', '(514) 323-2354'),
						(2, 2, '1300 Av Siempre-Viva. H2X-1P4. Anjou, QC.', '(514) 387-4324'),
						(3, 3, '2460 rue Benny-Crescent. H4B-2P9. Montreal, QC', '(438) 475-1175'),
						(4, 4, '1871 NW 133rd ave. 33374. Fort-Lauderdale, FL, USA.', '(948) 856-0538'),
						(5, 5, '432 rue Dennise. H8S-2H3. LaSalle, QC.', '(428) 291-4336');
                                   
INSERT INTO `SCCNetwork`.`BankingInfo`(`bankingInfoId`, 
									`account_number`, `account_code`) VALUES
						(1, 4326758, 1234),
                        (2, 9320258, 4321),
                        (3, 9347296, 1423),
                        (4, 6494750, 2314),
                        (5, 2844682, 1432);                                 
                                        
INSERT INTO `SCCNetwork`.`event_instance`(`event_instanceId`, `event_id`, 
										`storage_limit`,  `bandwith_limit`) VALUES
                        (1, 1, 1048, 1048),
                        (2, 2, 1048, 1048),
                        (3, 3, 1048, 1048),
						(4, 4, 1048, 1048),
						(5, 5, 1048, 1048),
						(6, 6, 1048, 1048);
                        

INSERT INTO `SCCNetwork`.`event_manager`(`event_id`, `manager_id`, `event_instance_id`, `bankingInfo_id`,`assignedAt`) VALUES
						(1, 1, 1, 1, NOW()),
						(2, 2, 2, 2, NOW()),
						(3, 3, 3, 3, NOW()),
						(4, 4, 4, 4, NOW()),
                        (5, 5, 5, 5, NOW()),
						(6, 1, 6, 1, NOW());

INSERT INTO `SCCNetwork`.`Group`(`id`, `owner_participant_id`) VALUES
						(1, 1),
                        (2, 1),
                        (3, 1),
                        (4, 1),
                        (5, 1);
                        
INSERT INTO `SCCNetwork`.`event_groups`(`event_instance_id`, `event_group_id`) VALUES
						(1, 1),
                        (2, 2),
                        (3, 3),
                        (4, 4),
                        (5, 5);
                        	
UPDATE `SCCNetwork`.`SCCSystemStatus` SET IsDataBasePopulated = true
WHERE id = '1';
INSERT INTO `RoleInSCC`(`id`, `role_name`) VALUES 
						(1, 'Administrator'), 
						(2, 'Controller'), 
						(3, 'Regular');

INSERT INTO `User`(`userId`, `username`, `password`, `firstname`, `lastname`, `email`, `age`, `profession`, `dateOfBirth`, `roleInSCC_id`) VALUES 
                    (1, 'VergaraF', '123456', 'Fabian', 'Vergara', 'fvergara@egenetec.com', 26, 'Developer', '93-11-23', 1),
                    (2, 'sleblanc', 'abc123', 'Sophie', 'Leblanc', 'sleblanc@apple.ca', 28, 'Project Manager', '91-12-12', 1),
                    (3, 'Turtle', 'abcdefg', 'Franklin', 'Glen', 'f_glen@tesla.com', 32, 'Engineer', '87-02-28', 2),
                    (4, 'Lauversan', 'medicina', 'Laura', 'Vergara', 'md_vergara@med.towmhospital.ca', 29, 'Doctor', '90-08-27', 3),
                    (5, 'dballerini', 'mecheng', 'Daniel', 'Ballerini', 'd_baller@encs.concordia.ca', 24, 'Student', '94-08-30', 2);
                    
INSERT INTO `EventType`(`id`, `name`) VALUES 
						(1, 'Private'), 
						(2, 'Non-profit'), 
						(3, 'Other');
                        
INSERT INTO `EventStatus`(`eventStatusId`, `name`) VALUES
						  (1, 'Active'), 
						  (2, 'Archived');
						
INSERT INTO `Event`(`eventId`, `event_name`, `eventType_id`) VALUES
					(1, 'Graduation Ceremony Preparation', 1),
                    (2, 'Flu Vaccination CLSC Campaign', 2),
                    (3, 'Vergara Family Mid-century Party', 1),
                    (4, 'Townhall Public Garage Sale', 3),
                    (5, 'Rock-al-Parque Homeless Fundraising', 2),
                    (6, 'Database Architecture Information Session', 3);
			
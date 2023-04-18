CREATE TABLE erstis (

	id INTEGER PRIMARY KEY NOT NULL,
	firstname TEXT,
	lastname TEXT,
	email TEXT,
	phone TEXT,
	mailing_list_choice INTEGER,
	course_choice INTEGER,
	tshirt_choice INTEGER,
	sleeping_choice INTEGER,
    cocktail_party_choice INTEGER,
    scc_tour_choice INTEGER,
    friday_special_choice INTEGER,

	CONSTRAINT first_and_lastname UNIQUE (firstname, lastname) ON CONFLICT ROLLBACK,
	CONSTRAINT email UNIQUE (email) ON CONFLICT ROLLBACK

);

CREATE TABLE config (

	registration_enabled INTEGER DEFAULT 0

);

INSERT INTO config DEFAULT VALUES;

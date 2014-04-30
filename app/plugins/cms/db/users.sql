CREATE TABLE cms.users (
	id serial,
	name character varying(150),
	login character varying(100),
	password character varying(256),
	user_id integer,
	CONSTRAINT users_id_pk PRIMARY KEY (id )
);
CREATE TABLE cms.menus (
	id serial NOT NULL,
	name character varying(100),
	-- INDICA SE A REQUISICAO VAI SER AJAX
	ajax boolean DEFAULT TRUE,
	-- INDICA SE O CONTEUDO DO MENU VAI VIR PELO CONTENT
	-- OU POR UM METODO
	static boolean DEFAULT TRUE,
 
	-- PATH TO METHOD
	plugin character varying(100),
	controller character varying(100),
	action	character varying(100),
	params character varying(150),

	-- INDICA SE O O CONTEUDO VEIO DE UPLOAD
	upload boolean DEFAULT FALSE,
	-- NOME DO ARQUIVO (NÃO FOI USADO O CAMINHO COMPLETO, POIS A PASTA DEVERÁ SER A MESMA)
	file character varying(200),

	-- CONTEUDO DO MENU
	content text,

	-- IMPLEMENTACAO DO MENU EM ARVORE
	lft integer,
	rght integer,
	parent_id integer,
	visible boolean default true
	

	CONSTRAINT menus_id_pk PRIMARY KEY (id )
);
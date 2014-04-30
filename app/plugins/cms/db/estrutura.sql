create schema cms;

CREATE TABLE cms.users (
  id serial,
  name character varying(150),
  login character varying(100),
  password character varying(256),
  user_id integer,
  CONSTRAINT users_id_pk PRIMARY KEY (id)
);

INSERT INTO cms.users VALUES (1, 'Admin', 'admin@dbseller.com.br', '96494ac4ddfbea245ef916aba40b30d80ca4b848', NULL);

SELECT pg_catalog.setval('cms.users_id_seq', 2, true);

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
  action  character varying(100),
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
  visible boolean default true,
  CONSTRAINT menus_id_pk PRIMARY KEY (id)
);
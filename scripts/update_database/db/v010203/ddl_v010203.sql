
CREATE TABLE cms.configuracoes (
 id serial NOT NULL, 
 contador_visitas boolean default true,
 CONSTRAINT configuracoes_id_pk PRIMARY KEY (id)
);

create index configuracoes_id_in on cms.configuracoes using btree ( id );
insert into cms.configuracoes values (nextval('cms.configuracoes_id_seq'), true);
alter table transparencia.visitantes set schema cms;

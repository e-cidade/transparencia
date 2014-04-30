  ------------Configurações------------------------------------------------------------------------------
  
CREATE TABLE configuracoes (
 id serial NOT NULL, 
 contador_visitas boolean default true,
 CONSTRAINT configuracoes_id_pk PRIMARY KEY (id)
);
create index configuracoes_id_in on configuracoes using btree ( id );
alter table public.configuracoes set schema cms;   
insert into cms.configuracoes values (nextval('configuracoes_id_seq'), true);
alter table transparencia.visitantes set schema cms;

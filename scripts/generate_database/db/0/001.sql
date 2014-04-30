
CREATE TABLE dotacoes (
                id SERIAL,
                exercicio INTEGER,
                coddotacao INTEGER NOT NULL,
                orgao_id INTEGER NOT NULL,
                unidade_id INTEGER NOT NULL,
                funcao_id INTEGER NOT NULL,
                subfuncao_id INTEGER NOT NULL,
                programa_id INTEGER NOT NULL,
                projeto_id INTEGER NOT NULL,
                planoconta_id INTEGER NOT NULL,
                recurso_id INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                CONSTRAINT dotacoes_id_pk PRIMARY KEY (id)
);



CREATE TABLE empenhos (
                id SERIAL,
                codempenho INTEGER NOT NULL,
                exercicio INTEGER NOT NULL,
                codigo VARCHAR(15) NOT NULL,
                planoconta_id INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                dataemissao DATE NOT NULL,
                tipo_compra VARCHAR(100) NOT NULL,
                dotacao_id INTEGER NOT NULL,
                valor_pago NUMERIC(15,2) DEFAULT 0 NOT NULL,
                pessoa_id INTEGER NOT NULL,
                valor NUMERIC(15,2) DEFAULT 0 NOT NULL,
                valor_liquidado NUMERIC(15,2) DEFAULT 0 NOT NULL,
                valor_anulado NUMERIC(15,2) DEFAULT 0 NOT NULL,
                resumo TEXT,
                CONSTRAINT empenhos_id_pk PRIMARY KEY (id)
);

CREATE TABLE empenhos_itens (
                id SERIAL,
                empenho_id INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                quantidade NUMERIC(15,2) DEFAULT 0 NOT NULL,
                valor_unitario NUMERIC(15,2) DEFAULT 0 NOT NULL,
                valor_total NUMERIC(15,2) DEFAULT 0 NOT NULL,
                CONSTRAINT empenhos_itens_pk PRIMARY KEY (id)
);

CREATE TABLE empenhos_movimentacoes (
                id SERIAL,
                empenho_movimentacao_tipo_id INTEGER NOT NULL,
                empenho_id INTEGER NOT NULL,
                data DATE NOT NULL,
                valor NUMERIC(15,2) DEFAULT 0 NOT NULL,
                historico TEXT,
                CONSTRAINT empenhos_movimentacoes_id_pk PRIMARY KEY (id)
);

CREATE TABLE empenhos_movimentacoes_exercicios (
                id SERIAL,
                empenho_id INTEGER NOT NULL,
                exercicio INTEGER NOT NULL,
                CONSTRAINT empenhos_movimentacoes_exercicios_pk PRIMARY KEY (id)
);

CREATE TABLE empenhos_movimentacoes_tipos (
                id SERIAL,
                codtipo INTEGER NOT NULL,
                codgrupo INTEGER NOT NULL,
                descricao VARCHAR(50) NOT NULL,
                CONSTRAINT empenhos_movimentacoes_tipos_id_pk PRIMARY KEY (id)
);

CREATE TABLE empenhos_processos (
                id SERIAL,
                empenho_id INTEGER NOT NULL,
                processo INTEGER NOT NULL,
                CONSTRAINT empenhos_processos_pk PRIMARY KEY (id)
);

CREATE TABLE funcoes (
                id SERIAL,
                codfuncao INTEGER NOT NULL,
                descricao VARCHAR(40) NOT NULL,
                CONSTRAINT funcoes_id_pk PRIMARY KEY (id)
);

CREATE TABLE glossarios (
                id SERIAL,
                glossario_tipo_id INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                resumo TEXT,
                CONSTRAINT glossarios_id_pk PRIMARY KEY (id)
);

CREATE TABLE glossarios_tipos (
                id SERIAL,
                descricao VARCHAR(200) NOT NULL,
                resumo TEXT,
                CONSTRAINT glossarios_tipos_id_pk PRIMARY KEY (id)
);

CREATE TABLE importacoes (
                id SERIAL,
                data DATE NOT NULL,
                hora CHAR(5) NOT NULL,
                CONSTRAINT importacoes_pk PRIMARY KEY (id)
);
CREATE TABLE instituicoes (
                id SERIAL,
                codinstit INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT instituicoes_id_pk PRIMARY KEY (id)
);

CREATE TABLE orgaos (
                id SERIAL,
                exercicio INTEGER NOT NULL,
                codorgao INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT orgaos_id_pk PRIMARY KEY (id)
);

CREATE TABLE pessoas (
                id SERIAL,
                codpessoa INTEGER NOT NULL,
                nome VARCHAR(40) NOT NULL,
                cpfcnpj VARCHAR(14) NOT NULL,
                CONSTRAINT pessoas_id_pk PRIMARY KEY (id)
);
CREATE TABLE planocontas (
                id SERIAL,
                codcon INTEGER NOT NULL,
                exercicio INTEGER NOT NULL,
                estrutural VARCHAR(20) NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT planocontas_id_pk PRIMARY KEY (id)
);

CREATE TABLE programas (
                id SERIAL,
                exercicio INTEGER NOT NULL,
                codprograma INTEGER NOT NULL,
                descricao VARCHAR(40) NOT NULL,
                CONSTRAINT programas_id_pk PRIMARY KEY (id)
);

CREATE TABLE projetos (
                id SERIAL,
                exercicio INTEGER NOT NULL,
                codprojeto INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                tipo INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT projetos_id_pk PRIMARY KEY (id)
);

CREATE TABLE receitas (
                id SERIAL,
                exercicio INTEGER NOT NULL,
                codreceita INTEGER NOT NULL,
                planoconta_id INTEGER NOT NULL,
                recurso_id INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                CONSTRAINT receitas_id_pk PRIMARY KEY (id)
);

CREATE TABLE receitas_movimentacoes (
                id SERIAL,
                receita_id INTEGER NOT NULL,
                data DATE NOT NULL,
                valor NUMERIC(15,2) DEFAULT 0 NOT NULL,
                CONSTRAINT receitas_movimentacoes_id_pk PRIMARY KEY (id)
);

CREATE TABLE recursos (
                id SERIAL,
                codrecurso INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT recursos_id_pk PRIMARY KEY (id)
);

CREATE TABLE resumos (
                id SERIAL,
                resumo_tipo_id INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                resumo TEXT NOT NULL,
                CONSTRAINT resumos_pk PRIMARY KEY (id)
);
CREATE TABLE resumos_tipos (
                id SERIAL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT resumos_tipos_pk PRIMARY KEY (id)
);

CREATE TABLE subfuncoes (
                id SERIAL,
                codsubfuncao INTEGER NOT NULL,
                descricao VARCHAR(40) NOT NULL,
                CONSTRAINT subfuncoes_id_pk PRIMARY KEY (id)
);

CREATE TABLE unidades (
                id SERIAL,
                exercicio INTEGER NOT NULL,
                orgao_id INTEGER NOT NULL,
                codunidade INTEGER NOT NULL,
                instituicao_id INTEGER NOT NULL,
                descricao VARCHAR(100) NOT NULL,
                CONSTRAINT unidades_id_pk PRIMARY KEY (id)
);
CREATE INDEX empenhos_dotacao_id_in
 ON empenhos
 ( dotacao_id );

CREATE INDEX empenhos_movimentacoes_empenho_id_in
 ON empenhos_movimentacoes
 ( empenho_id );

CREATE INDEX empenhos_movimentacoes_empenho_movimentacao_tipo_id_in
 ON empenhos_movimentacoes
 ( empenho_movimentacao_tipo_id );

CREATE INDEX dotacoes_funcao_id_in
 ON dotacoes
 ( funcao_id );

CREATE INDEX glossarios_glossario_tipo_id_in
 ON glossarios
 ( glossario_tipo_id );

CREATE INDEX dotacoes_instituicao_id_in
 ON dotacoes
 ( instituicao_id );

CREATE INDEX dotacoes_orgao_id_in
 ON dotacoes
 ( orgao_id );

CREATE INDEX empenhos_pessoa_id_in
 ON empenhos
 ( pessoa_id );

CREATE INDEX dotacoes_planoconta_id_in
 ON dotacoes
 ( planoconta_id );

CREATE INDEX dotacoes_programa_id_in
 ON dotacoes
 ( programa_id );

CREATE INDEX dotacoes_projeto_id_in
 ON dotacoes
 ( projeto_id );

CREATE INDEX receitas_movimentacoes_receita_id_in
 ON receitas_movimentacoes
 ( receita_id );

CREATE INDEX dotacoes_recurso_id_in
 ON dotacoes
 ( recurso_id );

CREATE INDEX dotacoes_subfuncao_id_in
 ON dotacoes
 ( subfuncao_id );

CREATE INDEX dotacoes_unidade_id_in
 ON dotacoes
 ( unidade_id );


CREATE UNIQUE INDEX programas_exercicio_codprograma_uk
 ON programas
 ( exercicio, codprograma );

CREATE UNIQUE INDEX planocontas_codcon_exercicio_uk
 ON planocontas USING BTREE
 ( codcon, exercicio );

CREATE UNIQUE INDEX dotacoes_coddotacao_anousu_uk
 ON dotacoes USING BTREE
 ( coddotacao, exercicio );

CREATE UNIQUE INDEX empenhos_codempenho_exercicio_id_instituicao_uk
 ON empenhos USING BTREE
 ( codempenho, exercicio, instituicao_id );

CREATE UNIQUE INDEX funcoes_codfuncao_uk
 ON funcoes USING BTREE
 ( codfuncao );

CREATE UNIQUE INDEX orgaos_exercicio_codorgao_uk
 ON orgaos USING BTREE
 ( exercicio, codorgao );

CREATE UNIQUE INDEX pessoas_codpessoa_uk
 ON pessoas USING BTREE
 ( codpessoa );

CREATE UNIQUE INDEX projetos_exercicio_codprojeto_uk
 ON projetos USING BTREE
 ( codprojeto, exercicio );

CREATE UNIQUE INDEX receitas_exercicio_codreceita_uk
 ON receitas USING BTREE
 ( exercicio, codreceita );

CREATE UNIQUE INDEX recursos_codrecurso_uk
 ON recursos USING BTREE
 ( codrecurso );

CREATE UNIQUE INDEX subfuncoes_codsubfuncao_uk
 ON subfuncoes USING BTREE
 ( codsubfuncao );

CREATE UNIQUE INDEX empenhos_movimentacoes_tipos_codtipo_uk
 ON empenhos_movimentacoes_tipos USING BTREE
 ( codtipo );

CREATE UNIQUE INDEX unidades_exercicio_id_orgao_codunidade_uk
 ON unidades USING BTREE
 ( exercicio, orgao_id, codunidade );


ALTER TABLE empenhos ADD CONSTRAINT empenhos_dotacao_id_fk
FOREIGN KEY (dotacao_id)
REFERENCES dotacoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE empenhos_itens ADD CONSTRAINT empenhos_empenhos_itens_fk
FOREIGN KEY (empenho_id)
REFERENCES empenhos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE empenhos_movimentacoes ADD CONSTRAINT empenhos_movimentacoes_empenho_id_fk
FOREIGN KEY (empenho_id)
REFERENCES empenhos (id)
ON DELETE NO ACTION
ON UPDATE SET NULL
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE empenhos_movimentacoes_exercicios ADD CONSTRAINT empenhos_empenhos_movimentacoes_exercicios_fk
FOREIGN KEY (empenho_id)
REFERENCES empenhos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE empenhos_processos ADD CONSTRAINT empenhos_empenhos_processos_fk
FOREIGN KEY (empenho_id)
REFERENCES empenhos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE empenhos_movimentacoes ADD CONSTRAINT empenhos_movimentacoes_empenho_movimentacao_tipo_id_fk
FOREIGN KEY (empenho_movimentacao_tipo_id)
REFERENCES empenhos_movimentacoes_tipos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_funcao_id_fk
FOREIGN KEY (funcao_id)
REFERENCES funcoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE glossarios ADD CONSTRAINT glossarios_glossario_tipo_id_fk
FOREIGN KEY (glossario_tipo_id)
REFERENCES glossarios_tipos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE empenhos ADD CONSTRAINT empenhos_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE orgaos ADD CONSTRAINT orgaos_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE projetos ADD CONSTRAINT projetos_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE receitas ADD CONSTRAINT receitas_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE unidades ADD CONSTRAINT unidades_instituicao_id_fk
FOREIGN KEY (instituicao_id)
REFERENCES instituicoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_orgao_id_fk
FOREIGN KEY (orgao_id)
REFERENCES orgaos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE unidades ADD CONSTRAINT unidades_orgao_id_fk
FOREIGN KEY (orgao_id)
REFERENCES orgaos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE empenhos ADD CONSTRAINT empenhos_pessoa_id_fk
FOREIGN KEY (pessoa_id)
REFERENCES pessoas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_planocontas_id_fk
FOREIGN KEY (planoconta_id)
REFERENCES planocontas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE empenhos ADD CONSTRAINT planocontas_empenhos_fk
FOREIGN KEY (planoconta_id)
REFERENCES planocontas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE receitas ADD CONSTRAINT receitas_planoconta_id_fk
FOREIGN KEY (planoconta_id)
REFERENCES planocontas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_programa_id_fk
FOREIGN KEY (programa_id)
REFERENCES programas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_projeto_id_fk
FOREIGN KEY (projeto_id)
REFERENCES projetos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE receitas_movimentacoes ADD CONSTRAINT receitas_movimentacoes_receita_id_fk
FOREIGN KEY (receita_id)
REFERENCES receitas (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_recurso_id_fk
FOREIGN KEY (recurso_id)
REFERENCES recursos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE receitas ADD CONSTRAINT receitas_recurso_id_fk
FOREIGN KEY (recurso_id)
REFERENCES recursos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE resumos ADD CONSTRAINT resumos_tipos_resumos_portal_fk
FOREIGN KEY (resumo_tipo_id)
REFERENCES resumos_tipos (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_subfuncao_id_fk
FOREIGN KEY (subfuncao_id)
REFERENCES subfuncoes (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

ALTER TABLE dotacoes ADD CONSTRAINT dotacoes_unidade_id_fk
FOREIGN KEY (unidade_id)
REFERENCES unidades (id)
ON DELETE NO ACTION
ON UPDATE NO ACTION
DEFERRABLE INITIALLY DEFERRED;

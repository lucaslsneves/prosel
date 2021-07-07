ALTER TABLE usuario_prosel
 MODIFY nome_completo VARCHAR(100),
 MODIFY cpf VARCHAR(20),
  MODIFY foto3x4 VARCHAR(100),
   MODIFY sexo VARCHAR(100),
    MODIFY comprovante_endereco VARCHAR(100),
     MODIFY rg VARCHAR(100),
      MODIFY cartao_pis VARCHAR(100),
       MODIFY cartao_vacinacao VARCHAR(100),
       MODIFY cartao_sus VARCHAR(100),
       MODIFY diploma VARCHAR(100),
       MODIFY curriculo VARCHAR(100),
       MODIFY conta_bancaria VARCHAR(100),
        MODIFY esocial VARCHAR(100),
         MODIFY prosel VARCHAR(100),
          MODIFY titulo_eleitor VARCHAR(100),
           MODIFY carteira_conselho VARCHAR(100);
       


ALTER TABLE usuario_prosel
	ADD possui_dependentes boolean default 0;
    
UPDATE usuario_prosel
	SET possui_dependentes = 1
    WHERE cpf_dependentes != '';
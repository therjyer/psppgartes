SELECT
    u.nome  Orientador,
    "1ª opção" opcao,
    c.`nome` candidato,
    c.`numInscricao` inscricao,
    c.processo processo,
    c.tipoProcesso tipoProcesso
FROM
    `candidato` c LEFT JOIN usuarios u on (c.`optOrientador1` = u.id)
WHERE c.`estadoHomologacao` = 1

UNION

SELECT
    u.nome  Orientador,
    "2ª opção" opcao,
    c.`nome` candidato,
    c.`numInscricao` inscricao,
    c.processo processo,
    c.tipoProcesso tipoProcesso

FROM
    `candidato` c LEFT JOIN usuarios u on (c.`optOrientador2` = u.id)
WHERE c.`estadoHomologacao` = 1    
        order by orientador, opcao,  candidato
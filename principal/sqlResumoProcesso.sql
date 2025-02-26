SELECT "1 - Candidatos: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` GROUP by processo
UNION
SELECT "2 - Homologados: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 GROUP by processo
UNION
SELECT "  2.1 - Linha 1: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optLinhaPesquisa = 1 GROUP by processo
UNION
SELECT "  2.2 - Linha 2: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optLinhaPesquisa = 2 GROUP by processo
UNION
SELECT "  2.3 - Linha 3: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optLinhaPesquisa = 3 GROUP by processo
UNION
SELECT "  2.4 - Teatro: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optCampo = 1 GROUP by processo
UNION
SELECT "  2.5 - Música: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optCampo = 2 GROUP by processo
UNION
SELECT "  2.6 - Dança: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optCampo = 3
UNION
SELECT "  2.7 - Artes Visuais: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE `estadoHomologacao` = 1 AND optCampo = 4 GROUP by processo 
UNION
SELECT "  2.8 - Cinema: " AS "Descrição", count(*) AS "Quantidade", processo FROM `candidato` WHERE`estadoHomologacao` = 1 AND optCampo = 5 GROUP by processo
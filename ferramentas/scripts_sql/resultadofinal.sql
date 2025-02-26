ordem do relatório

SELECT `idCandidato`,`nome`, cotas, optLinhaPesquisa , `optOrientador1`,`optOrientador2`,`orientador1`,`orientador2` FROM `candidato` WHERE processo = 48 and tipoProcesso = 1  order by cotas, optLinhaPesquisa, resultadoFinal, colocacao


verificar s eoptorientador e orientador são os mesmos
SELECT `idCandidato`,`nome`, cotas, optLinhaPesquisa , `optOrientador1`,`optOrientador2`,`orientador1`,`orientador2` FROM `candidato` WHERE processo = 48 and tipoProcesso = 1  and ((orientador1 != optOrientador1 ) OR (orientador2 != optOrientador2)) and resultadoFinal = 2 order by cotas, optLinhaPesquisa, resultadoFinal, colocacao


64/2023 URSULA CELESTE TAVARES. BAHIA DE JESUS
	cota:		er -> ac 
	resultadofinal:	2 -> 1 
	colocacao:	2 -> 12
32/2023 MATHEUS DOS SANTOS BENTO 
	cota:		er -> ac 
	colocacao:	1 -> 2
117/2023 GYSELLE KOLWALSK CRUZ DE LIMA
	cota:		er -> ac 
	resultadofinal:	2 -> 1 
	colocacao:	3 -> 5
62/2023	GESIEL RIBEIRO DE LEÃO
	cota:		er -> ac 
	resultadofinal:	3 -> 1 
	colocacao:	5 -> 13
40/2023	RAFAEL MATHEUS MOREIRA MONTEIRO
	cota:		ct -> ac
158/2023ISABELLA VALENTINA CONCEIÇÃO BARROS
	cota:		ct -> ac
	colocacao:	2 -> 6
44/2023	EMANUELLE RAQUEL RABELO DA SILVA
	resultadofinal:	1 -> 2
159/2023MARCIO SILVA DA CRUZ 	
	resultadofinal:	1 -> 2
95/2023	WELLINGTON ROMARIO DA SILVA ALVES
	resultadofinal:	1 -> 2
135/2023EDIVÂNIA DA GALILEIA ALVES CÂMARA DE A
	resultadofinal:	1 -> 2
104/2023IGOR PESSOA DE BARAUNA
	resultadofinal:	1 -> 2
189/2023MELQUISEDEQUE MATOS MIRANDA
	resultadofinal:	1 -> 2
17/2023	EMERSON SILVA CALDAS
	resultadofinal:	2 -> 1
108/2023CLEBER SILVA DE OLIVEIRA
	resultadofinal:	2 -> 1
186/2023MESSIAS FRANÇA DO NASCIMENTO
	resultadofinal:	2 -> 1
57/2023KEOMA CALANDRINI DE AZEVEDO MATHEUS
	resultadofinal:	2 -> 1
RAFAEL BRITO GONZAGA
	resultadofinal:	2 -> 1
	optorientador1: troca de posição
16/2023	KELLY LENE LOPES CALDERARO
	resultadofinal:	1 -> 2
59/2023RUBERVALDO CRUZ SARMENTO FILHO
	resultadofinal:	2 -> 1
141/2023HELOÍSA MARIA FERREIRA TORRES
	resultadofinal:	1 -> 2
102/2023YASMIN CABRAL GOMES
	resultadofinal:	2 -> 1
96/2023	KERSSYA MICHELLY PAULA DA SILVA
41/2023 LENNON ALEXANDRE BENDELAK DE ANDRADE SERRA
	nome -> ALEXANDRE BENDELAK DE ANDRADE

guido	1998/10/26







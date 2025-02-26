/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  alexm
 * Created: 13 de mar. de 2023
 */

SELECT
    CONCAT('<p>Novo Cadastro no Sistema do Processo Seletivo do PPGARTES - 2023.</p><p>Dados da inscrição</p><p/>',
        txtHtml,
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_1.pdf>', txtCPF,'_1</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_2.pdf>', txtCPF,'_2</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_3.pdf>', txtCPF,'_3</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_4.pdf>', txtCPF,'_4</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_5.pdf>', txtCPF,'_5</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_6.pdf>', txtCPF,'_6</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_7.pdf>', txtCPF,'_7</a></p>',
'<p><a href=https://psppgartes.4gestor.net/up2023/', txtCPF, '_8.pdf>', txtCPF,'_8</a></p>'
    )
FROM
    `viewHtmlInscritos`
WHERE
    txtNome = ''
ORDER BY
    idCandidato;
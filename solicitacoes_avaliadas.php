<?php

    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');

    // Checa permissões de acesso
    require_login();
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    require_capability('moodle/role:manage', $systemcontext);


    global $DB;
    /* String para usada em mensagens e condições */
    define("INVALIDO",     "Inválido!");
    define("VALIDO",     "Válido!");
    

    
    /* Monta e exibe o cabeçalho da pagina*/
    $PAGE->set_context($systemcontext);
    $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/validacao_view.php'));
    $PAGE->set_title('Visualizar solicitações avaliadas');
    $PAGE->set_heading('Visualizar solicitações avaliadas');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->navbar->add('Validação de certificados', new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'), null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    $PAGE->navbar->add('Visualizar solicitações avaliadas', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();
    
    $solicitacoes = $DB->get_records_select('block_validacao_solicitacao',"status_da_solicitacao='avaliada'");



    require_once('forms/pesquisa_form.php');

    
    $pesquisa = new pesquisa_form();

    $pesquisa->display();

    $dados_pesquisa = $pesquisa->get_data();


    if($pesquisa->is_submitted()){
        if($dados_pesquisa->type == 0){
            $status_da_validacao = 'deferida';
        } else {
            $status_da_validacao = 'indeferida';
        }

        $solicitacoes = $DB->get_records_select('block_validacao_solicitacao',"status_da_solicitacao='avaliada' AND status_da_validacao='{$status_da_validacao}'");
    }
    
    
?>


<!-- Listagem de solicitações -->
<div id="listagem">

    <?php foreach ($solicitacoes as $solicitacao):

            $aluno                  = $DB->get_record_select('user','username='."'$solicitacao->aluno_cpf'");
            if(!empty($aluno->id)){
                $certificado_valido     = $DB->get_record_select('certificate_issues'   ,'userid='.$aluno->id.' AND '.' BINARY '.'code='."'{$solicitacao->aluno_numero_certificado}'");

                if(!empty($certificado_valido->certificateid)){
                    $certificate            = $DB->get_record_select('certificate'          ,'id='.$certificado_valido->certificateid);
                    $course                 = $DB->get_record_select('course'               ,'id='.$certificate->course);
                    $category               = $DB->get_record_select('course_categories'    ,'id='.$course->category);
                } else {
                    $course                 = '';
                    $category               = '';
                    $certificate            = '';
                    $certificado_valido     = '';
                }

            } else {
                $course                 = '';
                $category               = '';
                $certificate            = '';
                $certificado_valido     = '';
                $aluno                  = '';
            }

                /*
                 *Comparação entre o cpf enviado e cpf cadastrado
                 * Teste
                 */

                 # Condições ternarias
                 $aluno->username   = isset($aluno->username) ? $aluno->username.' - '.VALIDO : INVALIDO;
                 $aluno->firstname  = isset($aluno->firstname) ? $aluno->firstname :'';
                 if($aluno->username == INVALIDO){
                    $aluno->firstname = '';
                    $aluno->email ='';
                    $aluno->phone1='';
                    $aluno->address='';
                    $aluno->city='';
                    $aluno->country='';
                 }


                $course->shortname              = isset($course->shortname)             ? $course->shortname                     :'';
                $category->name                 = isset($category->name)                ? $category->name                        :'';
                $certificado_valido->code       = isset($certificado_valido->code)      ? $certificado_valido->code.' - '.VALIDO :INVALIDO ;

                if($certificado_valido->code == INVALIDO){
                $course->shortname ='';
                    $category->name ='';
                    $certificate->printhours ='' ;
                    $certificado_valido->certdate = "43234323432";
                }
            ?>

        <!-- Data da solicitação -->
        <style>

            form input{padding:5px;}
        </style>

        <table style="border:1px solid #ddd">


            <tr style="background: #efefef; border: 1px solid #ddd; margin-bottom: 10px;">
                <td > SOLICITANTE</td>
                <td > ALUNO</td>
                 <td> CURSO / CERTIFICADO </td>
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"> <?php echo 'Nome: <b> '. $solicitacao->solicitante_nome ?></td>
               <td style=" border-right: 1px solid #ddd;"><?php  echo 'CPF do aluno: <b>'.$solicitacao->aluno_cpf.'</b>';
                 echo ' - <b>'.$aluno->username.'</b>';?></td>
               <td> <?php echo 'nº do certificado: <b>'.$solicitacao->aluno_numero_certificado.'</b> - <b>'.$certificado_valido->code.'</b> '; ?></td>
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Setor: <b> '.$solicitacao->solicitante_setor   ?></td>
                 <td style=" border-right: 1px solid #ddd;"> <?php echo 'Nome: <b>'.$aluno->firstname ?></td>
                 <td> <?php echo 'Nome do curso <b>'. $course->shortname .'</b> '; ?></td>

            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Instituição: <b> '.$solicitacao->solicitante_instituicao ?></td>
                 <td style=" border-right: 1px solid #ddd;"><?php echo 'E-mail: <b>'.$aluno->email   ?></td>
                 <td> <?php 'Categoria / subcategoria <b>'. $category->name  .'</b>'; ?></td>
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Telefone: <b> '.$solicitacao->solicitante_telefone      ?></td>
                 <td style=" border-right: 1px solid #ddd;"><?php echo 'Telefone: <b>'.$aluno->phone1 ?></td>
                 <td> <?php echo 'Carga Horária <b>'. $certificate->printhours .' horas </b>' ?></td>

            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'E-mail: <b> '.$solicitacao->solicitante_email ?></td>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Endereço: <b>'.$aluno->address.' , '.$aluno->city.' - '.$aluno->country      ?></td>
                <td> <?php echo 'Data de emissão do certificado <b>'. date("d/m/Y", $certificado_valido->certdate) .'</b> '; ?></td>

            </tr>

            <tr style="background: #fafafa; border: 1px solid #ddd; margin-bottom: 10px;">
                <td style="width:35%;color:#990000">Data <?php echo '<b>'.$solicitacao->data.'</b>'; ?></td>
                <td style="width:35%"></td>

                <td style="text-align:right;width:30%;color:#990000">
                    <?php echo '<b>'.$solicitacao->status_da_validacao.'</b>'; ?>
                </td>



            </tr>
        </table>
    <?php endforeach; ?>
</div>
<!-- Fim da listagem de solicitações -->


<?php
    /* Exibe o rodape da pagina*/
    echo $OUTPUT->footer();
?>
    


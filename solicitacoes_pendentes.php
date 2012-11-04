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
    $PAGE->set_title('Visualizar solicitações pendentes');
    $PAGE->set_heading('Visualizar solicitações pendentes');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->navbar->add('Validação de certificados', new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'), null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    $PAGE->navbar->add('Visualizar solicitações pendentes', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();
    
    $solicitacoes = $DB->get_records_select('block_validacao_solicitacao',"status_da_solicitacao='pendente' order by id desc");

    
    
?>

<!-- Listagem de solicitações -->
<div id="listagem">

    <?php foreach ($solicitacoes as $solicitacao):

            $user_info_data             = $DB->get_record_select('user_info_data','data='."'$solicitacao->aluno_cpf'");
            if(!empty($user_info_data->data)){
                $aluno                  = $DB->get_record_select('user','id='."'$user_info_data->userid'");
                $certificado_valido     = $DB->get_record_select('certificate_issues'   ,'userid='.$aluno->id.' AND BINARY code='."'{$solicitacao->aluno_numero_certificado}'");
                
                
                $id_ddd_celular         = $DB->get_record_select('user_info_field'      ,"shortname='DDDCelular'");
                $id_celular             = $DB->get_record_select('user_info_field'      ,"shortname='cel'");

                $ddd                    = $DB->get_record_select('user_info_data'       ,'fieldid='.$id_ddd_celular->id.' AND '.' userid='.$aluno->id);
                $celular                = $DB->get_record_select('user_info_data'       ,'fieldid='.$id_celular->id.' AND '.' userid='.$aluno->id);
                
                
                if(!empty($certificado_valido->certificateid)){
                    $certificate            = $DB->get_record_select('certificate'          ,'id='.$certificado_valido->certificateid);
                    $course                 = $DB->get_record_select('course'               ,'id='.$certificate->course);
                    $category               = $DB->get_record_select('course_categories'    ,'id='.$course->category);                                        
                } else {
                    $course                 = new stdClass();
                    $category               = new stdClass();
                    $certificate            = new stdClass();
                    $certificado_valido     = new stdClass();                    
                }
                
            } else {
                $course                 = new stdClass();
                $category               = new stdClass();
                $certificate            = new stdClass();
                $certificado_valido     = new stdClass();
                $aluno                  = new stdClass();
                $ddd                    = new stdClass();
                $celular                = new stdClass();
                $user_info_data         = new stdClass();
            }
            
                /*
                 *Comparação entre o cpf enviado e cpf cadastrado
                 * Teste
                 */

                 # Condições ternarias
                 $user_info_data->data   = isset($user_info_data->data) ? $user_info_data->data.' - '.VALIDO : INVALIDO;
                 $aluno->firstname  = isset($aluno->firstname) ? $aluno->firstname :'';
                 
                 $ddd->data         = isset($ddd->data) ? $ddd->data :'';
                 $celular->data     = isset($celular->data) ? $celular->data :'';

                 
                 if($user_info_data->data == INVALIDO){
                    $aluno->firstname   ='';
                    $aluno->email       ='';
                    $aluno->phone1      ='';
                    $aluno->address     ='';
                    $aluno->city        ='';
                    $aluno->country     ='';
                    $ddd->data          ='';
                    $celular->data      ='';
                 }


                $course->shortname              = isset($course->shortname)             ? $course->shortname                     :'';
                $category->name                 = isset($category->name)                ? $category->name                        :'';
                $certificado_valido->code       = isset($certificado_valido->code)      ? $certificado_valido->code.' - '.VALIDO :INVALIDO ;

                if($certificado_valido->code == INVALIDO){
                    $course->shortname                  ='';
                    $category->name                     ='';
                    $certificate->printhours            ='';
                    $certificado_valido->timecreated    = "43234323432";
                }

                 /**
                 * Pega o nome do país do aluno
                 */

                $countries = get_string_manager()->get_list_of_countries(false);
                if (isset($countries[$aluno->country])) {
                    $country = $countries[$aluno->country];
                } else {
                    $country = '';
                }

            ?>

        <!-- Data da solicitação -->
        <style>
            form input{
                padding:5px;
                width: 70px;
                height: 30px;
                margin: 0 0 0 5px;
            }
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
                 echo ' - <b>'.$user_info_data->data.'</b>';?></td>
               <td> <?php echo 'nº do certificado: <b>'.$solicitacao->aluno_numero_certificado.'</b> - <b>'.$certificado_valido->code.'</b> '; ?></td>
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Setor: <b> '.$solicitacao->solicitante_setor   ?></td>
                 <td style=" border-right: 1px solid #ddd;"> <?php echo 'Nome: <b>'.$aluno->firstname ?></td>
                 <td> <?php echo 'Nome do curso: <b>'. $course->shortname .'</b> '; ?></td>
               
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Instituição: <b> '.$solicitacao->solicitante_instituicao ?></td>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'E-mail: <b>'.$aluno->email   ?></td>
                <td> <?php echo 'Categoria: <b>'. $category->name  .'</b>'; ?></td>
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Telefone: <b> '.$solicitacao->solicitante_telefone      ?></td>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Celular: <b> ('.$ddd->data.') '. $celular->data ?></td>
                <td> <?php echo 'Carga Horária: <b>'. $certificate->printhours .' horas </b>' ?></td>
                
            </tr>
            <tr>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'E-mail: <b> '.$solicitacao->solicitante_email ?></td>
                <td style=" border-right: 1px solid #ddd;"><?php echo 'Cidade/País: <b>'.$aluno->city.' - '.$country      ?></td>
                <td> <?php echo 'Data de emissão do certificado: <b>'. date("d/m/Y", $certificado_valido->timecreated) .'</b> '; ?></td>                
            </tr>

            <tr style="background: #fafafa; border: 1px solid #ddd; margin-bottom: 10px;">
                <td style="width:35%;color:#990000">Data <?php echo '<b>'.$solicitacao->data.'</b>'; ?></td>
                <td style="width:35%"></td>

                <td style="text-align:right;width:30%;">                       
                    <form action="deferir_indeferir.php" method="post">
                        <input type=hidden name="id" value="<?php echo $solicitacao->id; ?>">
                        <input type="submit" value="Deferir"    name="botao">
                        <input type="submit" value="Indeferir"  name="botao">
                        <input type="submit"  value="Excluir"    name="botao">
                    </form>
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
    


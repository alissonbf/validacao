<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_once('funcoes.php');


    // Checa permissões de acesso
    require_login();
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    require_capability('moodle/role:manage', $systemcontext);

    global $DB;


    /* Monta e exibe o cabeçalho da pagina*/
    $PAGE->set_context($systemcontext);
    $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/deferir_indeferir.php'));
    $PAGE->set_title('Visualizar solicitações pendentes');
    $PAGE->set_heading('Visualizar solicitações pendentes');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->navbar->add('Validação de certificados', new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'), null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    $PAGE->navbar->add('Deferir ou indeferir solicitação', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();

    /* Fim exibição cabeçalho */


    /* Mudança no status da solicitação, validação de certificado e envio de email para solicitante */

    if(isset($_POST['acao']) && $_POST['acao'] == 'ok'){
        
        if($_POST['confirmacao'] == 'Sim'){
            
            if ($_POST['status'] == 'Excluir'){
                /* Altera o status da solicitação e da validação  */
                $DB->set_field('block_validacao_solicitacao','status_da_solicitacao','excluida',array('id' => $_POST['id']));
                $DB->set_field('block_validacao_solicitacao','status_da_validacao'  ,'excluida',array('id' => $_POST['id']));
                /* Fim altera o status da solicitação e da validação  */
                
                echo '<script>alert("Solicitação excluida com sucesso!")</script>';
                echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';                        
            }
            
            if ($_POST['status'] == 'Deferir'){

                /* Altera o status da solicitação e da validação  */
                $DB->set_field('block_validacao_solicitacao','status_da_solicitacao','avaliada',array('id' => $_POST['id']));
                $DB->set_field('block_validacao_solicitacao','status_da_validacao'  ,'deferida',array('id' => $_POST['id']));
                /* Fim altera o status da solicitação e da validação  */


                /* Verifica se o envio de email automatico esta ativado */
                $configuracao    = $DB->get_record_select('block_validacao_configuracao',null);                
                if($configuracao->email_automatico == 1){
                    
                    /* Monta a mensagem e envia por email */
                    $solicitante     = $DB->get_record_select('block_validacao_solicitacao',"id=".$_POST['id']);
                    
                    if(enviar_email($solicitante->solicitante_email, $solicitante->solicitante_nome, montar_mensagem($_POST['id'], $_POST['status']))){
                        echo '<script>alert("Solicitação deferida com sucesso!")</script>';
                        echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
                    } else {
                        echo '<script>alert("Solicitação deferida com sucesso!")</script>';
                        echo '<script>alert("E-mail do solicitante não existe!")</script>';
                        echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
                    }                    
                    /* Fim monta a mensagem e envia por email */
                } else {
                    /* Exibe uma mensagem de sucesso e redireciona para a pagina de visualização de solicitações */
                    echo '<script>alert("Solicitação deferida com sucesso!")</script>';
                    echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
                }
                /* Fim verifica se o envio de email automatico esta ativado */
            }
            
            
            if ($_POST['status'] == 'Indeferir'){
                /* Altera o status da solicitação e da validação  */
                $DB->set_field('block_validacao_solicitacao','status_da_solicitacao','avaliada'  ,array('id' => $_POST['id']));
                $DB->set_field('block_validacao_solicitacao','status_da_validacao'  ,'indeferida',array('id' => $_POST['id']));
                /* Fim altera o status da solicitação e da validação  */

                /* Verifica se o envio de email automatico esta ativado */
                $configuracao                    = $DB->get_record_select('block_validacao_configuracao',null);
                
                if($configuracao->email_automatico == 1){
                    
                    /* Monta a mensagem e envia por email */
                    $solicitante            = $DB->get_record_select('block_validacao_solicitacao',"id=".$_POST['id']);

                    if(enviar_email($solicitante->solicitante_email, $solicitante->solicitante_nome, montar_mensagem($_POST['id'], $_POST['status']))){
                        echo '<script>alert("Solicitação indeferida com sucesso!")</script>';
                        echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';                        
                    } else {
                        echo '<script>alert("Solicitação indeferida com sucesso!")</script>';
                        echo '<script>alert("E-mail do solicitante não existe!")</script>';
                        echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
                    }
                    /* Fim monta a mensagem e envia por email */
                } else {
                    echo '<script>alert("Solicitação indeferida com sucesso!")</script>';
                    echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
                }
                /* Fim de verifica se o envio de email automatico esta ativado */
                
            }
            
        } else if($_POST['confirmacao'] == 'Cancelar') {
            echo   '<meta http-equiv="refresh" content="0; url=solicitacoes_pendentes.php">';
        }
        
    } else {
 ?>

<style>
    form input{
        padding:3px;
        font-size: 13px;
        width: 70px;
        height: 30px;
        margin: 0 0 0 5px;
    }
    
    .mensagem_confirmacao{
        text-align: center;
        font-size: medium;
    }
</style>

<div class="mensagem_confirmacao">
    Deseja realmente <b><?php echo $_POST['botao']; ?></b> esta solicitação?
    <br />
    <br />

    <form action="deferir_indeferir.php" method="post">

        <input type=hidden name="id" value="<?php echo $_POST['id']; ?>">
        <input type=hidden name="acao" value="ok">
        <input type=hidden name="status" value="<?php echo $_POST['botao']; ?>">
        <input type="submit" value="Sim" name="confirmacao">
        <input type="submit" value="Cancelar" name="confirmacao">
    </form>

    

</div>
<?php
    }
    /* Fim da Mudança no status da solicitação, validação de certificado e envio de email para solicitante */

    /* Exibe o rodape da pagina*/
    echo $OUTPUT->footer();

?>

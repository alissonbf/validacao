<?php
    require_once('../../config.php');
    require_once('forms/solicitante_form.php');

    global $CFG, $USER, $DB;
    $formulario = new solicitante_form();
    
    if ($formulario->is_cancelled()) {
        // se o formulario for cancelado, redirecionar para a página principal
        redirect("$CFG->wwwroot/");
    } else if ($formulario->is_submitted()) {
        $solicitacao = $formulario->get_data();

        $solicitacao->data = date("d/m/Y");
        
        // salvo os dados no banco de dados
        if(!$DB->insert_record('block_validacao_solicitacao',$solicitacao)){
            error(get_string('inserterror' , 'block_validacao_solicitacao'));
        }
        redirect("$CFG->wwwroot/blocks/validacao/solicitacao_sucesso.php");
         

    } else {
        /* Monta e exibe o cabeçalho da pagina*/
        $systemcontext = get_context_instance(CONTEXT_SYSTEM);
        $PAGE->set_context($systemcontext);
        $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/solicitante_view.php'));
        $PAGE->set_title('Solicitar validação de certificado');
        $PAGE->set_heading('Solicitar validação de certificado');
        $PAGE->set_focuscontrol('');
        $PAGE->set_cacheable(true);
        $PAGE->navbar->add('Solicitar validação de certificado', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
        echo $OUTPUT->header();

        $formulario->display();

        /* Exibe o rodape da pagina*/
        echo $OUTPUT->footer();
    }

?>

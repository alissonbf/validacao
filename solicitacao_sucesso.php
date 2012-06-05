<?php
    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');

    // Checa permissões de acesso
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    

    
    global $CFG;
    
    /* Monta e exibe o cabeçalho da pagina*/    
    $PAGE->set_context($systemcontext);
    $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/solicitacao_sucesso.php'));
    $PAGE->set_title('Solicitar validação de certificado');
    $PAGE->set_heading('Solicitar validação de certificado');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    
    $PAGE->navbar->add('Solicitar validação de certificado', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();


?>

<p align="center">
    Sua solicitação foi enviada com sucesso!
    <br />
    <br />
    Aguarde o nosso retorno!
    <br />
    <br />
    <a href="<?php echo $CFG->wwwroot;?>">Pagina inicial</a>
</p>

<?php
    /* Exibe o rodape da pagina*/
    echo $OUTPUT->footer();
?>

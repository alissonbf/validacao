<?php
    require_once('../../config.php');
    
    global $CFG;
    
    /* Monta e exibe o cabeçalho da pagina*/
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($systemcontext);
    $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/configuracoes_sucesso.php'));
    $PAGE->set_title('Configurações');
    $PAGE->set_heading('Configurações');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->navbar->add('Validação de certificados', new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'), null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    $PAGE->navbar->add('Configurações', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();


?>

<p align="center">
    As configurações do plugin Validação de Certificados <b>foram salvas com sucesso</b>!
    <br />
    <br />
    <a href="<?php echo $CFG->wwwroot;?>">Pagina inicial</a>
</p>

<?php
    /* Exibe o rodape da pagina*/
    echo $OUTPUT->footer();
?>

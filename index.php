<?php
    require_once('../../config.php');

    global $CFG;

    /* Monta e exibe o cabeçalho da pagina*/
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($systemcontext);
    $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'));
    $PAGE->set_title('Validação de certificados');
    $PAGE->set_heading('Validação de certificados');
    $PAGE->set_focuscontrol('');
    $PAGE->set_cacheable(true);
    $PAGE->navbar->add('Validação de certificados', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
    echo $OUTPUT->header();


?>
<link rel="stylesheet" href="<?php echo $CFG->wwwroot.'/blocks/validacao/css/styles.css'; ?>" type="text/css" />
<div id="menu">
    <ul>
        <li><a href="<?php echo $CFG->wwwroot.'/blocks/validacao/solicitacoes_pendentes.php'; ?>"> Socitações pendentes</a></li>
        <li><a href="<?php echo $CFG->wwwroot.'/blocks/validacao/solicitacoes_avaliadas.php'; ?>"> Solicitações avaliadas</a></li>
        <li><a href="<?php echo $CFG->wwwroot.'/blocks/validacao/configuracoes.php'; ?>"> Configurações</a></li>
    </ul>
</div>

<?php
    /* Exibe o rodape da pagina*/
    echo $OUTPUT->footer();
?>
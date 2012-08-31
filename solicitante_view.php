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

        echo '<br style="clear:both" />';
        echo '<br style="clear:both" />';

        echo '

            <script type="text/javascript">
            //      <![CDATA[
                function mascara(o,f){
                    v_obj=o
                    v_fun=f
                    setTimeout("execmascara()",1)
                }

                function execmascara(){
                    v_obj.value=v_fun(v_obj.value)
                }

                function telefone(v){
                    v=v.replace(/\D/g,"")                 //Remove tudo o que não é dígito
                    v=v.replace(/^(\d\d)(\d)/g,"($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
                    v=v.replace(/(\d{4})(\d)/,"$1-$2")    //Coloca hífen entre o quarto e o quinto dígitos
                    return v
                }
            //]]>
            </script>
        ';



        $formulario->display();

        /* Exibe o rodape da pagina*/
        echo $OUTPUT->footer();
    }

?>

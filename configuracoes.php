<?php
    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');
    require_once('forms/configuracoes_form.php');
        
    // Checa permissões de acesso
    require_login();
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    require_capability('moodle/role:manage', $systemcontext);

    global $CFG, $USER, $DB;

    
    $configuracao = new configuracoes_form();
    
    $dados_banco = $DB->get_record_select('block_validacao_configuracao',null);
    
    if (!empty($dados_banco)){
        
        $formulario =   new stdClass();
        
        $formulario->id                     = $dados_banco->id;
        $formulario->email_automatico       = $dados_banco->email_automatico;
        $formulario->empresa_nome           = $dados_banco->empresa_nome;
        $formulario->email                  = $dados_banco->email;
        $formulario->senha                  = $dados_banco->senha;
        $formulario->smtp_secure            = $dados_banco->smtp_secure;
        $formulario->charset                = $dados_banco->charset;
        $formulario->host                   = $dados_banco->host;
        $formulario->port                   = $dados_banco->port;
        $formulario->mensagem_deferimento   = Array("text" => $dados_banco->mensagem_deferimento    , "format" => 1);
        $formulario->mensagem_indeferimento = Array("text" => $dados_banco->mensagem_indeferimento  , "format" => 1);

        $configuracao->set_data($formulario);
        
    } else {
        $formulario =   new stdClass();
        
        $formulario->smtp_secure            = 'tls';
        $formulario->charset                = 'UTF-8';
        $formulario->host                   = 'smtp.live.com';
        $formulario->port                   =  587;
        
        $configuracao->set_data($formulario);
    }

    if ($configuracao->is_cancelled()) {
        
        // se o formulario for cancelado, redirecionar para a página principal
        redirect("$CFG->wwwroot/");
        
    } else if($configuracao->is_submitted()){
            
            // Pega os dados do formulario
            $dados = $configuracao->get_data();
            
            
            if ($dados->id == 'vazio') {
                
                // Atribui a ela mesma, somente um dos valores que existe no array
                $dados->mensagem_deferimento    = $dados->mensagem_deferimento['text'];
                $dados->mensagem_indeferimento  = $dados->mensagem_indeferimento['text'];


                // insere os dados no banco de dados
                $DB->insert_record('block_validacao_configuracao',$dados);
                redirect("$CFG->wwwroot/blocks/validacao/configuracoes_sucesso.php");

            } else if ($dados->id != 'vazio') {
                
                // atualiza os dados no banco de dados
                $DB->set_field('block_validacao_configuracao','email_automatico'        ,$dados->email_automatico               ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','empresa_nome'            ,$dados->empresa_nome                   ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','email'                   ,$dados->email                          ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','senha'                   ,$dados->senha                          ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','smtp_secure'             ,$dados->smtp_secure                    ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','charset'                 ,$dados->charset                        ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','host'                    ,$dados->host                           ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','port'                    ,$dados->port                           ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','mensagem_deferimento'    ,$dados->mensagem_deferimento['text']   ,array('id' => $dados->id));
                $DB->set_field('block_validacao_configuracao','mensagem_indeferimento'  ,$dados->mensagem_indeferimento['text'] ,array('id' => $dados->id));
                
                    
                redirect("$CFG->wwwroot/blocks/validacao/configuracoes_sucesso.php");
                 
            }
              
             
             
             

    } else {
            /* Monta e exibe o cabeçalho da pagina*/
            $systemcontext = get_context_instance(CONTEXT_SYSTEM);
            $PAGE->set_context($systemcontext);
            $PAGE->set_url(new moodle_url($CFG->wwwroot.'/blocks/validacao/configuracoes.php'));            
            $PAGE->set_title('Configurações');
            $PAGE->set_heading('Configurações');
            $PAGE->set_focuscontrol('');
            $PAGE->set_cacheable(true);
            $PAGE->navbar->add('Validação de certificados', new moodle_url($CFG->wwwroot.'/blocks/validacao/index.php'), null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
            $PAGE->navbar->add('Configurações', null, null, navigation_node::TYPE_CUSTOM, new moodle_url($CFG->wwwroot));
            echo $OUTPUT->header();

            // Exibe o formulario
            $configuracao->display();

            /* Exibe o rodape da pagina*/
            echo $OUTPUT->footer();
        }

?>

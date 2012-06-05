<?php
    require_once('../../config.php');
    require_once("$CFG->libdir/formslib.php");

    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($systemcontext);

    class configuracoes_form extends moodleform {
        function definition() {
            global $CFG;

            $mform =& $this->_form;

            // campo oculto id
            $mform->addElement('hidden','id','vazio');


            // add group for text arease add dados do solicitante
            $mform->addElement('header', 'displayinfo', 'Envio de e-mail automatico');
                $mform->addElement('advcheckbox','email_automatico','Ativado');
                $mform->addElement('static', 'automatico', '', 'Define se um e-mail automatico será enviado para o solicitante.');
                $mform->addElement('static', 'automatico', '', 'Deixe em branco caso não queira o envio automatico.');
            
            // add group for text arease add dados do solicitante
            $mform->addElement('header', 'displayinfo', 'Configuração de e-mail');

                $mform->addElement('text','empresa_nome','Nome da empresa',array('size'=>77));
                $mform->addRule('empresa_nome', null, 'required', null, 'client');


                $mform->addElement('text','email','E-mail',array('size'=>77));
                $mform->addRule('email', null, 'required', null, 'client');

                
                $mform->addElement('passwordunmask', 'senha', 'Senha');
                $mform->addRule('senha', null, 'required', null, 'client');
                
                /*
                 * Coloca um botão de ajuda
                 * $mform->addHelpButton('senha', 'forcepasswordchange');
                 *
                 * Coloca um texto informativo no formulario
                 * $mform->addElement('static', 'password', '', 'não autere estes dados');
                 */
                
                $mform->addElement('text','charset','CharSet');
                $mform->addRule('charset', null, 'required', null, 'client');
                $mform->addElement('static', 'automatico', '', 'Não altere este campo.');

                $mform->addElement('text','smtp_secure','SMTP Secure');
                $mform->addRule('smtp_secure', null, 'required', null, 'client');
                $mform->addElement('static', 'automatico', '', 'Se usar gmail mude para: ssl');

                $mform->addElement('text','host','Host',array('size'=>77));
                $mform->addRule('host', null, 'required', null, 'client');
                $mform->addElement('static', 'automatico', '', 'Se usar gmail mude para: smtp.gmail.com');
                
                $mform->addElement('text','port','Port');
                $mform->addRule('port', null, 'required', null, 'client');
                $mform->addElement('static', 'automatico', '', 'Se usar gmail mude para: 465');

            // add group for text areas
            $mform->addElement('header', 'displayinfo', 'Configuração de mensagem automatica');
                
                $mform->addElement('editor','mensagem_deferimento','Deferido válido');
                $mform->addRule('mensagem_deferimento', null, 'required', null, 'client');

                // "Recurso tecnico" que insere um espaço branco entre os dois editores html
                $mform->addElement('static', 'branco', '', '');

                $mform->addElement('editor','mensagem_indeferimento','Deferido inválido');
                $mform->addRule('mensagem_indeferimento', null, 'required', null, 'client');


            $this->add_action_buttons();

        }
    }
?>
<?php
    require_once('../../config.php');
    require_once("$CFG->libdir/formslib.php");
    
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($systemcontext);
    
    class solicitante_form extends moodleform {
        function definition() {
            global $CFG;

            $mform =& $this->_form;


            // campo oculto id
            $mform->addElement('hidden','data','vazio');

            // add group for text areas e add dados do solicitante
            $mform->addElement('header', 'displayinfo', 'Dados do solicitante');

                
                $mform->addElement('text','solicitante_nome','Nome');
                $mform->addRule('solicitante_nome', null, 'required', null, 'client');

                   
                $mform->addElement('text','solicitante_instituicao','Instituição');
                $mform->addRule('solicitante_instituicao', null, 'required', null, 'client');


                $mform->addElement('text','solicitante_setor','Setor');
                $mform->addRule('solicitante_setor', null, 'required', null, 'client');
                
                
                $mform->addElement('text','solicitante_telefone','Telefone');
                $mform->addRule('solicitante_telefone', null, 'required', null, 'client');

                
                $mform->addElement('text','solicitante_email','E-mail');
                $mform->addRule('solicitante_email', null, 'required', null, 'client');

                // add group for text areas
                $mform->addElement('header', 'displayinfo', 'Dados do aluno');
       
                // add dados do aluno
                $mform->addElement('text','aluno_cpf','CPF do aluno');
                $mform->addRule('aluno_cpf', null, 'required', null, 'client');

                // add dados do aluno
                $mform->addElement('text','aluno_numero_certificado','Cód. do certificado');
                $mform->addRule('aluno_numero_certificado', null, 'required', null, 'client');
                $mform->addElement('static', 'automatico', '', 'Respeite as letras maiusculas e minusculas.');

            
            $this->add_action_buttons($cancel = true, $submitlabel='Enviar solicitação');
            
        }
    }
?>
<?php
    require_once('../../config.php');
    require_once("$CFG->libdir/formslib.php");

    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    $PAGE->set_context($systemcontext);

    class pesquisa_form extends moodleform {
        function definition() {
            global $CFG;

            $mform =& $this->_form;

            // campo oculto id
            $mform->addElement('hidden','id','vazio');


            // add group for text arease add dados do solicitante
            $mform->addElement('header', 'displayinfo', 'Pesquisar solicitações avaliadas');
                $mform->addElement('select', 'type', 'Status da validação', array('deferida', 'indeferida'), null);
                

       

            $this->add_action_buttons($cancel=false, $submitlabel='Pesquisar');
        
        }
    }

?>

<?php
    class block_validacao extends block_list {
        public function init() {
            $this->title = get_string('validacao', 'block_validacao');

        }

        public function get_content() {
            if ($this->content !== null) {
                return $this->content;
            }

            $this->content         = new stdClass;
            $this->content->items  = array();
            $this->content->icons  = array();
            
            //require_once('config.php');

            //global $CFG;
            
            $this->content->items[] = html_writer::tag('a', 'Solicitar validação', array('href' => 'blocks/validacao/solicitante_view.php'));
            $this->content->icons[] = html_writer::empty_tag('img', array('src' => "blocks/validacao/pix/EditDocument-01.png", 'class' => 'icon'));

            // Mostra item do menu apenas se o usuario for administrador
            $systemcontext = get_context_instance(CONTEXT_SYSTEM);
            if (has_capability('moodle/role:manage', $systemcontext)) {
                $this->content->items[] = html_writer::tag('a', 'Socitações pendentes', array('href' => 'blocks/validacao/solicitacoes_pendentes.php'));
                $this->content->icons[] = html_writer::empty_tag('img', array('src' => "blocks/validacao/pix/pendente.png", 'class' => 'icon'));

                $this->content->items[] = html_writer::tag('a', 'Solicitações avaliadas', array('href' => 'blocks/validacao/solicitacoes_avaliadas.php'));
                $this->content->icons[] = html_writer::empty_tag('img', array('src' => "blocks/validacao/pix/avaliada.png", 'class' => 'icon'));

                $this->content->items[] = html_writer::tag('a', 'Configurações', array('href' => 'blocks/validacao/configuracoes.php'));
                $this->content->icons[] = html_writer::empty_tag('img', array('src' => "blocks/validacao/pix/Gear-01.png", 'class' => 'icon'));
            }
            

            // Add more list items here
            return $this->content;
        }

        public function specialization() {
            if (!empty($this->config->title)) {
                $this->title = $this->config->title;
            } else {
                $this->config->title = 'Validação de Certificados';
            }

            if (empty($this->config->text)) {
                $this->config->text = 'Default text ...';
            }
        }

        public function instance_config_save($data) {
            if(get_config('validacao', 'Allow_HTML') == '1') {
                $data->text = strip_tags($data->text);
            }

            // And now forward to the default implementation defined in the parent class
            return parent::instance_config_save($data);
        }

        public function applicable_formats() {
            return array('all' => true);
        }

    }   // Here's the closing bracket for the class definition

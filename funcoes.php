<?php
    require_once('../../config.php');
    require_once($CFG->libdir.'/adminlib.php');    
    require_once('phpmailer/class.phpmailer.php');

    // Checa permissões de acesso
    require_login();
    $systemcontext = get_context_instance(CONTEXT_SYSTEM);
    require_capability('moodle/role:manage', $systemcontext);


/**
 * Monta a mensagem de deferimento ou indeferimento, que será enviada por email.
 * 
 * @param <type> $id
 * @param <type> $decisao
 * @return <type> $mensagem
 */
    function montar_mensagem($id,$decisao){
        global $DB;
        
        $solicitante     = $DB->get_record_select('block_validacao_solicitacao',"id=".$id);
        $configuracao    = $DB->get_record_select('block_validacao_configuracao',null);
        
        if($decisao == 'Deferir'){
            
            $aluno                  = $DB->get_record_select('user','username='."'$solicitante->aluno_cpf'");

            if(!empty($aluno->id)){
                
                $certificado_valido     = $DB->get_record_select('certificate_issues'   ,'userid='.$aluno->id.' AND '.' BINARY '.'code='."'{$solicitante->aluno_numero_certificado}'");
                
                if(!empty($certificado_valido->certificateid)){
                    
                    $certificate            = $DB->get_record_select('certificate'          ,'id='.$certificado_valido->certificateid);
                    $course                 = $DB->get_record_select('course'               ,'id='.$certificate->course);
                    $category               = $DB->get_record_select('course_categories'    ,'id='.$course->category);
                    


                    $mensagem  = '<h2>'.$configuracao->empresa_nome.'</h2> <br />';
                    $mensagem .= 'Sr(a). '.$solicitante->solicitante_nome.'. <br /> <br />';

                    $mensagem .= $configuracao->mensagem_deferimento;
                    $mensagem .= '<br /> <br />';
                    $mensagem .= '<b>Segue abaixo os dados do aluno e do curso que ele fez conosco.</b> <br /> <br />';

                    $mensagem .= '<h2>Dados do aluno</h2> <br />';
                    $mensagem .= 'Nome: <b>'.$aluno->firstname.' </b> <br />';
                    $mensagem .= 'CPF: <b>'.$aluno->username.'</b> <br />';
                    $mensagem .= 'Nº Certificado: <b>'.$certificado_valido->code.'</b> <br /><br />';

                    $mensagem .= '<h2>Dados do curso</h2> <br />';
                    $mensagem .= 'Nome do curso <b>'. $course->shortname .'</b>  <br />' ;
                    $mensagem .= 'Categoria / subcategoria <b>'. $category->name  .'</b> <br />';
                    $mensagem .= 'Carga Horária <b>'. $certificate->printhours  .' horas</b> <br />';
                    $mensagem .= 'Data de emissão do certificado <b>'. date("d/m/Y", $certificado_valido->certdate)  .'</b>  <br /> <br />';
                    
                    return $mensagem;
                } else {
                    $mensagem  = '<h2>'.$configuracao->empresa_nome.'</h2> <br />';
                    $mensagem .= 'Sr(a). '.$solicitante->solicitante_nome.'. <br /> <br />';

                    $mensagem .= $configuracao->mensagem_deferimento;

                    return $mensagem;
                }
            }
        } else {
                $mensagem  = '<h2>'.$configuracao->empresa_nome.'</h2> <br />';
                $mensagem .= 'Sr(a). '.$solicitante->solicitante_nome.'. <br /> <br />';

                $mensagem .= $configuracao->mensagem_indeferimento;

                return $mensagem;
        }

        
    }

/**
 * Envia email, smtp com conta cadastrada no banco, na tabela block_validacao_configuracao
 *
 * @param <type> $destino
 * @param <type> $nome_destinatario
 * @param <type> $mensagem
 * @return <type> True or False
 */
    function enviar_email($destino,$nome_destinatario,$mensagem){
        global $DB;
        $mail = new PHPMailer();

        $configuracao    = $DB->get_record_select('block_validacao_configuracao',null);

        $mail->IsSMTP();
        $mail->IsHTML(true); 
        $mail->SMTPAuth     = true;
        $mail->Port         = $configuracao->port;
        $mail->SMTPSecure   = $configuracao->smtp_secure;
        $mail->Host         = $configuracao->host;
        $mail->Username     = $configuracao->email;
        $mail->Password     = $configuracao->senha;
        $mail->CharSet      = $configuracao->charset;
        $mail->SetFrom($configuracao->email, $configuracao->empresa_nome);
        


        $mail->AddAddress($destino,  $nome_destinatario);
        $mail->Subject = "Resposta solicitação";
        $mail->MsgHTML($mensagem);

        
        if($mail->Send()){
            return True;
        }else{
            return False;
            //print_r($mail->ErrorInfo);
        }
    }
 
?>


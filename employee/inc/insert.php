<?php 
    session_start();
    require "../../autoload.php";

    use App\Funcionario;
    
    if(isset($_POST)){
        $erro=0;
        $id_empresa = $_SESSION['id_empresa'];
        $id_permissao = $_POST['id_permissao'];
        $nome = $_POST["nome"];        
        $email = $_POST["email"];        
        $senha = password_hash(gerar_senha(10, true, true, true, true), PASSWORD_BCRYPT);
        $cpf_cnpj = $_POST["cpf_cnpj"];        
        $cep = $_POST["cep"];        
        $endereco = $_POST["endereco"];        
        $numero = $_POST["numero"];        
        $complemento = $_POST["complemento"];        
        $bairro = $_POST["bairro"];        
        $cidade = $_POST["cidade"];        
        $estado = $_POST["uf"];                  

        $funcionario = new Funcionario();
        $select = $funcionario->select('email', $email, $id_empresa);
        
        if(!$select){
            $select = $funcionario->select('cpf_cnpj', $cpf_cnpj, $id_empresa);
            if(!$select){
                $rs = $funcionario->insert($id_empresa, $id_permissao, $nome, $email, $senha, $cpf_cnpj, $cep,
                $endereco, $numero, $complemento, $bairro, $cidade, $estado);

                if($rs){
                    $msg = '
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Woohoo!</strong> funcionário cadastrado com sucesso!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    ';     
                }else{
                    $msg = '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Opss!</strong> Erro ao cadastrar funcionário!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    ';
                    $erro++;
                }

            }else{
                $msg = '
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Opss!</strong> CPF/CNPJ já cadastrado!
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    ';
                $erro++;
            }
        }else{
            $msg = '
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Opss!</strong> E-mail já cadastrado!
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                ';
            $erro++;
        }

        
        echo json_encode(['msg'=>$msg, 'erro'=>$erro]);
    }else{
        echo json_encode(['msg'=>'Não foi enviado post', 'erro'=>1]);
    }

    function gerar_senha($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos
        $senha = '';
        
        if ($maiusculas){
              // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($ma);
        }
       
          if ($minusculas){
              // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($mi);
          }
       
          if ($numeros){
              // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($nu);
          }
       
          if ($simbolos){
              // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $senha
              $senha .= str_shuffle($si);
          }
       
          // retorna a senha embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
          return substr(str_shuffle($senha),0,$tamanho);
    }
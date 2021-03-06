<?php

session_start();
if (isset($_SESSION['login']) && isset($_SESSION['senha']) && isset($_SESSION['nivel'])):
    header('location: usuario-logado.php');
endif;

if (isset($_SESSION['nivel'])) {
    $nivel = $_SESSION['nivel'];
    if ($nivel == 99) {
        header('location: administrador/admin.php');
    }

}

if (isset($_POST['cadastrar'])) {
    include('classes/Conexao.class.php');
    include('classes/UsuarioDAO.class.php');

    $cadastrar = new UsuarioDAO();

    $login = trim(strip_tags($_POST['login'])); // atribui login à variavel, com funções contra sql inject
    $nome = trim(strip_tags($_POST['nome'])); // atribui login à variavel, com funções contra sql inject
    $senha = trim(strip_tags($_POST['senha'])); // atribui login à variavel, com funções contra sql inject
    $rep_senha = trim(strip_tags($_POST['rep_senha'])); // atribui login à variavel, com funções contra sql inject
    $cpf = trim(strip_tags($_POST['cpf'])); // atribui login à variavel, com funções contra sql inject
    $email = trim(strip_tags($_POST['email'])); // atribui login à variavel, com funções contra sql inject
    $avaliador = trim(strip_tags($_POST['avaliador'])); // atribui login à variavel, com funções contra sql inject

    // confere se as senhas são iguais

    if ($senha === $rep_senha) {
        $consulta = $cadastrar->unico($login);
        $consultaCPF = $cadastrar->unicoCpf($cpf);
        // caso o login escolhido já exista no banco retorna erro
        if ($consulta == false || $consultaCPF == false) {
            header('location:cadastro-usuario.php?repetido=senha');
            // caso não haja login parecido, inclui métoro de inserção de dados no banco de dados
        } else {
            if ($avaliador != 1) {
                $avaliador = 0;
            }
            $insere = $cadastrar->cadastra($login, $senha, $nome, $cpf, $email, $avaliador);
            // caso o usuario seja cadastrado, exibir mensagem de sucesso
            if ($insere == true) {
                header('location:index.php?success=cadastrado');
            }
        }

    } else {
        header('location:cadastro-usuario.php?erro=senha');
    }

}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login PHP OO</title>

    <link rel="stylesheet" href="css/projeto/projeto.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <!--    footer e header para páginas-->

    <link rel="stylesheet" href="components/css/header.css"/>
    <link rel="stylesheet" href="components/css/footer.css"/>
    <script>
        $(function () {
            $("#header").load("components/header.php");
        });
    </script>
    <script>
        $(function () {
            $("#footer").load("components/footer.php");
        });
    </script>

    <script type="text/javascript">

        function _cpf(cpf) {
            cpf = cpf.replace(/[^\d]+/g, '');
            if (cpf == '') return false;
            if (cpf.length != 11 ||
                cpf == "00000000000" ||
                cpf == "11111111111" ||
                cpf == "22222222222" ||
                cpf == "33333333333" ||
                cpf == "44444444444" ||
                cpf == "55555555555" ||
                cpf == "66666666666" ||
                cpf == "77777777777" ||
                cpf == "88888888888" ||
                cpf == "99999999999")
                return false;
            add = 0;
            for (i = 0; i < 9; i++)
                add += parseInt(cpf.charAt(i)) * (10 - i);
            rev = 11 - (add % 11);
            if (rev == 10 || rev == 11)
                rev = 0;
            if (rev != parseInt(cpf.charAt(9)))
                return false;
            add = 0;
            for (i = 0; i < 10; i++)
                add += parseInt(cpf.charAt(i)) * (11 - i);
            rev = 11 - (add % 11);
            if (rev == 10 || rev == 11)
                rev = 0;
            if (rev != parseInt(cpf.charAt(10)))
                return false;
            return true;
        }

        function validarCPF(el) {
            if (!_cpf(el.value)) {

                alert("CPF inválido! " + el.value);

                // apaga o valor
                el.value = "";
            }
        }

        function formatar(src, mask) {
            var i = src.value.length;
            var saida = mask.substring(0, 1);
            var texto = mask.substring(i)
            if (texto.substring(0, 1) != saida) {
                src.value += texto.substring(0, 1);
            }
        }
    </script>

</head>
<body>


<h1 class='h1Cambury'>Cambury</h1>

<div class="centralizandoCadastro">
    <div class="container jumbotron">

        <?php
        // mensagem de erro caso as senhas não sejam iguais
        if (isset($_GET['erro'])) {
            echo '<div class="alert alert-danger">As senhas devem ser iguais!</div>';
        }
        // mensagem de erro caso o login escolhido já exista no banco de dados
        if (isset($_GET['repetido'])) {
            echo '<div class="alert alert-danger">Este Login ou CPF já foi escolhido por outra pessoa!</div>';
        }
        // mensagem de sucesso caso o usuario seja cadastrado corretamente
        if (isset($_GET['success'])) {
            echo '<div class="alert alert-success">Usuario cadastrado!</div>';
        }

        ?>

        <div class="container">
            <div class="row">
                <h2 class="espacoCadastro">Cadastro</h2>
                <hr>
                <form action="#" method="post">

                    <div class="centralizandoFormulario">
                        <div class="col-sm-5" class="tamanho-campoLogin">
                            <div class="form-group">
                                <label id="teste" for="login" style="">Login</label>
                                <input type="text" class="form-control" id="login" name="login" required autofocus>
                            </div>
                        </div>

                        <div class="col-sm-5" class="tamanho-campoNome">
                            <div class="form-group ">
                                <label for="nome">Nome: </label>
                                <input id="nome" type="text" class="form-control" name="nome" required>
                            </div>
                        </div>
                        <div class="col-sm-5" class="tamanho-campoSenha">
                            <div class="campo-senha" class="form-group">
                                <label for="senha">Senha:</label>
                                <input type="password" class="form-control" id="senha" name="senha" minlength="6"
                                       placeholder="Digite sua senha" required>
                            </div>
                        </div>

                        <div class="col-sm-5" class="tamanho-campoRepita">
                            <div class="form-group">
                                <label for="rep_senha">Repita a Senha:</label>
                                <input type="password" class="form-control" id="rep_senha" name="rep_senha"
                                       minlength="6"
                                       placeholder="Repita a sua senha" required>
                            </div>
                        </div>

                        <div class="col-sm-5" class="tamanho-campocpf">
                            <div class="form-group" class="form-horizontal">
                                <label for="cpf">CPF:</label>
                                <input size=30 maxlength="14"
                                       onblur="validarCPF(this)" onkeyup="formatar(this,'000.000.000-00')" type="text"
                                       class="form-control" id="cpf" name="cpf">
                            </div>
                        </div>

                        <div class="col-sm-5" class="tamanho-campoEmail">
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <div class="input-group">
                                    <div class="input-group-addon">@</div>
                                    <input type="email" class="form-control" id="email" name="email" required>

                                </div>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" id="avaliador" name="avaliador" value="1"> Quero ser Avaliador
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-primary" name="cadastrar">Cadastrar</button>
                        </div>
                </form>
                <hr>

                <div class="col-sm-5">
                    <a class="btn btn-primary" href="index.php">Voltar</a>
                </div>

            </div>
        </div>
    </div>
    <script>
        setTimeout(function () {
            $('.alert').fadeOut();
        }, 3000);

    </script>
</body>
</div>
<div id="footer"></div>
</html>

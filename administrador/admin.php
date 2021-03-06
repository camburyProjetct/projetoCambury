<?php
session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['senha'])):
    header('location: ../index.php');
endif;

if (isset($_SESSION['login']) && isset($_SESSION['senha']) && isset($_SESSION['nivel']) && isset($_SESSION['codUsuario'])):
    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
        if ($nivel != 99) {
            header('location: ../usuario-logado.php');
        }
    }
    $codUsuario = $_SESSION['codUsuario'];

endif;

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Administrador | Faculdades Cambury</title>

    <link rel="stylesheet" href="../css/projeto/projetos-page.css"/>
    <link rel="stylesheet" href="../components/css/menu-admin.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <![endif]-->

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

    <!--    footer e header para páginas-->
    <link rel="stylesheet" href="../components/css/header.css"/>
    <link rel="stylesheet" href="../components/css/footer.css"/>


    <script>
        $(function () {
            $("#header").load("../components/header.php");
        });
    </script>
    <script>
        $(function () {
            $("#footer").load("../components/footer.php");
        });
    </script>
    <script>
        $(function () {
            $("#menu").load("../components/menu-administrador.php");
        });
    </script>
    <!--    copiar e colar isto para as demais novas paginas-->

</head>
<body>
<div id="header"></div>
<!--<div class="container jumbotron">-->
<!--    <h2>Bem-Vindo Administrador --><?php //echo $_SESSION['login']; ?><!--!</h2>-->
<!--</div>-->

<div id="menu"></div>

<div class="container">
    </br>
    <div class="row">
        <!--        <p>-->
        <!--            --><?php //if ($_SESSION['nivel'] == 99) {
        //                echo '<a href="cadastro-projeto/criar-projeto.php" class="btn btn-success">Aceitar Avaliador</a>';
        //            }
        //            ?>
        <!---->
        <?php

        include('../classes/Conexao.class.php');
        include('../classes/ProjetoDAO.class.php');
        include('../classes/UsuarioDAO.class.php');

        $usuario = new UsuarioDAO();
        $usuarioProjeto = new ProjetoDAO();

        $login = $_SESSION['login'];

        $codUsuario = $usuario->CodDoUsuario($login);
        $_SESSION['codUsuario'] = $codUsuario;

        // Pegar ID de projetos de usuários

        $codProjeto = $usuario->recuperarProjetos($codUsuario);

        ?>
        <!---->
        <!--        </p>-->


        <table class="table table-striped">
            <h2>Lista de Projetos</h2>
            <thead>
            <?php
            if (isset($codProjeto)) {

                ?>
                <tr>
                    <th scope="col">Nome do Projeto</th>
                    <th scope="col">Nome do Orientador</th>
                    <th scope="col">Curso e Turma</th>
                    <th scope="col">Ações</th>
                </tr>
                <?php
            }
            ?>
            </thead>
            <tbody>

            <?php

            include '../classes/conectdb.php';
            $pdo = conectdb::conectar();
            $sql = 'SELECT codUsuario,codProjeto,nomeProjeto,nomeProfessor,objetivo,resumo,curso,turma,projetoAceito FROM tb_projeto ORDER BY projetoAceito DESC ';

            foreach ($pdo->query($sql) as $getProjetos) {
                if (isset($codProjeto)) {
                    echo '<tr class="projetos-admin">';
                    echo '<td  style="display: none;">' . $getProjetos['codProjeto'] . '</td>'; // get id do projeto deixar com display none
                    echo '<td >' . $getProjetos['nomeProjeto'] . '</td>';
                    echo '<td>' . $getProjetos['nomeProfessor'] . '</td>';
                    echo '<td>' . $getProjetos['curso'] . ' / ' . $getProjetos['turma'] . '</td>';
                    echo '<td width=350>';
                    echo '<a class="btn btn-info" data-toggle="tooltip" data-placement="top" title="Informações do Projeto" href="projeto-admin/ler-projeto.php?codProjeto=' . $getProjetos['codProjeto'] . '">Info</a>';
                    echo ' ';
                    if ($_SESSION['nivel'] == 100) { // RN: Administrador não pode editar projeto
                        echo '<a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Após ser aceito, não poderá mais editar" href="editar.php?id=' . $getProjetos['codProjeto'] . '">Editar</a>';
                        echo ' ';
                    }
                    if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 1) {
                        echo '<span class="alert alert-success glyphicon glyphicon-ok" role="alert" data-toggle="tooltip">Aceito</span>';
                        echo ' ';
                    }
                    if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 0) {
                        echo '<a class="btn btn-success" href="projeto-admin/aprovar.php?codProjeto=' . $getProjetos['codProjeto'] . '">Aceitar Projeto</a>';
                        echo ' ';
                    }
                    if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 0) {
                        echo '<span class="glyphicon glyphicon-remove alert alert-danger  " role="alert" data-toggle="tooltip">Desaprovado</span>';
                        echo ' ';
                    }
                    if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 1) {
                        echo '<a class="btn btn-danger" href="projeto-admin/desaprovar.php?codProjeto=' . $getProjetos['codProjeto'] . '">Desaprovar Projeto</a>';
                        echo '</td>';
                        echo '</tr>';
                    }
                }
            }

            if (!isset($codProjeto)) {
                echo '<div class="jumbotron">';
                echo '<h2>Não existe projeto</h2>';
            }
            conectdb::desconectar();
            ?>
            </tbody>
        </table>
    </div>
</div>
<div id="footer"></div>
</body>

</html>

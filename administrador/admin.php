<?php

session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['senha'])):
endif;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login PHP OO</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container jumbotron">

    <h2>Bem-Vindo Administrador <?php echo $_SESSION['login']; ?>!</h2>

    <a href="../logout.php">Sair</a>

</div>

<div class="container">
    </br>
    <div class="row">
        <p>
            <?php if ($_SESSION['nivel'] == 99) {
                echo '<a href="cadastro-projeto/criar-projeto.php" class="btn btn-success">Aceitar Avaliador</a>';
            }
            ?>

            <?php

            include('../classes/Conexao.class.php');
            include('../classes/ProjetoDAO.class.php');
            include('../classes/UsuarioDAO.class.php');

            $usuario = new UsuarioDAO();
            $usuarioProjeto = new ProjetoDAO();

            $login = $_SESSION['login'];

            $codUsuario = $usuario->CodDoUsuario($login);
            $_SESSION['codUsuario'] = $codUsuario;
            ?>

        </p>

        <table class="table table-striped">
            <h2>Lista de Projetos</h2>
            <thead>
            <tr>
                <th scope="col">Nome do Projeto</th>
                <th scope="col">Nome do Orientador</th>
                <th scope="col">Objetivo do Projeto</th>
                <th scope="col">Resumo do Projeto</th>
                <th scope="col">Curso e Turma</th>
                <th scope="col">Ações</th>
            </tr>
            </thead>
            <tbody>
            <?php

            include '../classes/conectdb.php';
            $pdo = conectdb::conectar();
            $sql = 'SELECT codUsuario,codProjeto,nomeProjeto,nomeProfessor,objetivo,resumo,curso,turma,projetoAceito FROM tb_projeto ';

            foreach ($pdo->query($sql) as $getProjetos) {
                echo '<tr>';
                echo '<th scope="row">' . $getProjetos['nomeProjeto'] . '</th>';
                echo '<th scope="row" style="display: none;">' . $getProjetos['codProjeto'] . '</th>';
                echo '<td>' . $getProjetos['nomeProfessor'] . '</td>';
                echo '<td>' . $getProjetos['objetivo'] . '</td>';
                echo '<td>' . $getProjetos['resumo'] . '</td>';
                echo '<td>' . $getProjetos['curso'] . ' / ' . $getProjetos['turma'] . '</td>';
                echo '<td width=450>';
                echo '<a class="btn btn-primary" href="ler-projeto.php?codProjeto=' . $getProjetos['codProjeto'] . '">Info</a>';
                echo ' ';
                if ($_SESSION['nivel'] == 100) { // RN: Administrador não pode editar projeto
                    echo '<a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Após ser aceito, não poderá mais editar" href="editar.php?id=' . $getProjetos['codProjeto'] . '">Editar</a>';
                    echo ' ';
                }
                if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 1) {
                    echo '<span class="alert alert-success" role="alert" data-toggle="tooltip">Projeto Aceito</span>';
                    echo ' ';
                }
                if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 0) {
                    echo '<a class="btn btn-warning" href="aprovar.php?codProjeto=' . $getProjetos['codProjeto'] . '">Aceitar Projeto</a>';
                    echo ' ';
                }
                if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 0) {
                    echo '<span class="alert alert-danger" role="alert" data-toggle="tooltip">Projeto Desaprovado</span>';
                    echo ' ';
                }
                if ($_SESSION['nivel'] == 99 && $getProjetos['projetoAceito'] == 1) {
                    echo '<a class="btn btn-danger" href="desaprovar.php?codProjeto=' . $getProjetos['codProjeto'] . '">Desaprovar Projeto</a>';
                    echo '</td>';
                    echo '</tr>';
                }
            }
            conectdb::desconectar();
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>

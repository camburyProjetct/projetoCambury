<?php
session_start();
if (!isset($_SESSION['login']) && !isset($_SESSION['senha'])):
    header("Location: index.php");
endif;


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <title>Cambury | PCA</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</head>

<body>


<div class="container">
    <div class="jumbotron">
        <h2><span class="badge badge-secondary">v 1.0.0 Projeto Version 1</span></h2><br/>

        <h2>Olá <?php echo $_SESSION['login']; ?>!</h2> <br/>

        <a href="logout.php">Sair</a>
    </div>
    </br>
    <div class="row">
        <p>
            <?php if ($_SESSION['nivel'] == 0) {
                echo '<a href="cadastro-projeto/criar-projeto.php" class="btn btn-success">Adicionar Projeto</a>';
            }
            ?>

            <?php

            include('classes/Conexao.class.php');
            include('classes/ProjetoDAO.class.php');
            include('classes/UsuarioDAO.class.php');

            $usuario = new UsuarioDAO();
            $usuarioProjeto = new ProjetoDAO();

            $login = $_SESSION['login'];

            $codUsuario = $usuario->CodDoUsuario($login);
            $_SESSION['codUsuario'] = $codUsuario;

            ?>

        </p>

        <table class="table table-striped">
            <h2>Meus Projetos</h2>
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">Nome</th>
                <th scope="col">Endereço</th>
                <th scope="col">Telefone</th>
                <th scope="col">Email</th>
                <th scope="col">Sexo</th>
                <th scope="col">Ação</th>
            </tr>
            </thead>
            <tbody>
            <?php
            include 'classes/conectdb.php';
            $pdo = conectdb::conectar();
            $sql = 'SELECT codUsuario,codProjeto,nomeProjeto,nomeProfessor,projetoAceito FROM tb_projeto WHERE codUsuario = ' . $codUsuario . ' ';

            foreach ($pdo->query($sql) as $getProjetos) {
                echo '<tr>';
                echo '<th scope="row">' . $getProjetos['codProjeto'] . '</th>';
                echo '<td>' . $getProjetos['nomeProjeto'] . '</td>';
                echo '<td>' . $getProjetos['nomeProfessor'] . '</td>';
                echo '<td width=250>';
                echo '<a class="btn btn-primary" href="ler-projeto.php?codProjeto=' . $getProjetos['codProjeto'] . '">Info</a>';
                echo ' ';
                if ($getProjetos['projetoAceito'] == 0) {
                    echo '<a class="btn btn-warning" data-toggle="tooltip" data-placement="top" title="Após ser aceito, não poderá mais editar" href="editar.php?id=' . $getProjetos['codProjeto'] . '">Editar</a>';
                    echo ' ';
                }
//                echo '<a class="btn btn-danger" href="delete.php?id=' . $row['id'] . '">Excluir</a>';
                echo '</td>';
                echo '</tr>';
            }
            conectdb::desconectar();
            ?>
            </tbody>
        </table>
    </div>
</div>
</body>

</html>

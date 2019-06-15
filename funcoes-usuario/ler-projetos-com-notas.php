<?php
    session_start();

if (!isset($_SESSION['login']) && !isset($_SESSION['senha'])):
    header('location: ../../index.php');
endif;

if (isset($_SESSION['login']) && isset($_SESSION['senha']) && isset($_SESSION['nivel']) && isset($_SESSION['codUsuario'])):
    if (isset($_SESSION['nivel'])) {
        $nivel = $_SESSION['nivel'];
        if ($nivel != 0) {
            header('location: ../usuario-logado.php');
        }

    }

endif;

require '../classes/conectdb.php';

$codProjeto = null;
$codUsuario = $_SESSION['codUsuario'];

if (!empty($_GET['codProjeto']))
{
    $codProjeto = $_REQUEST['codProjeto'];
}

if (null == $codProjeto) {
    header("Location: ../usuario-logado.php");
} elseif (null == $_SESSION['login']) {
    header("Location: ../index.php");
} else {
    $pdo = conectdb::conectar();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT
    tb_avaliacao.codProjeto,
    tb_avaliacao.codUsuario,
    tb_avaliacao.nota_1,
    tb_avaliacao.nota_2,
    tb_avaliacao.nota_3,
    tb_avaliacao.nota_4,
    tb_projeto.nomeProjeto,
    tb_projeto.nomeProfessor,
    tb_projeto.objetivo,
    tb_projeto.resumo,
    tb_projeto.curso,
    tb_projeto.turma,
    (
    SELECT
        SUM(
            tb_avaliacao.nota_1 + tb_avaliacao.nota_2 + tb_avaliacao.nota_3 + tb_avaliacao.nota_4
        )
) AS Total
FROM
    tb_avaliacao
LEFT JOIN tb_projeto ON tb_projeto.codProjeto = tb_avaliacao.codProjeto
WHERE
    tb_avaliacao.codUsuario = ? AND tb_avaliacao.codProjeto = ?;";
    $q = $pdo->prepare($sql);
    $q->execute(array($codUsuario,$codProjeto));
    $projetosAvaliador = $q->fetch(PDO::FETCH_ASSOC);

}

//$sql2 = 'select count(*) from tb_avaliacao where codProjeto = ?;';
//$q2 = $pdo->prepare($sql2);
//$q2->execute(array($codProjeto));
//$projetosQuantidadeAvaliador = $q2->fetch(PDO::FETCH_ASSOC);
//conectdb::desconectar();

?>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="icon" href="https://cambury.br/wp-content/themes/cambury/favicon.png"  type="image/ico" />
<script>
    jQuery(function ($) {

        $(".sidebar-dropdown > a").click(function () {
            $(".sidebar-submenu").slideUp(200);
            if (
                $(this)
                    .parent()
                    .hasClass("active")
            ) {
                $(".sidebar-dropdown").removeClass("active");
                $(this)
                    .parent()
                    .removeClass("active");
            } else {
                $(".sidebar-dropdown").removeClass("active");
                $(this)
                    .next(".sidebar-submenu")
                    .slideDown(200);
                $(this)
                    .parent()
                    .addClass("active");
            }
        });

        if (window.innerWidth < 768) {
            $("#close-sidebar").init(function () {
                $(".page-wrapper").removeClass("toggled");
            });
        }

        $("#close-sidebar").click(function () {
            $(".page-wrapper").removeClass("toggled");
        });


        $("#show-sidebar").click(function () {
            $(".page-wrapper").addClass("toggled");
        });
    });

</script>

<script>
    $(document).ready(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>


<!------ Include the above in your HEAD tag ---------->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="Responsive sidebar template with sliding effect and dropdown menu based on bootstrap 3">
    <title>Projetos com Nota | Faculdades Cambury</title>
    <link href="https://fonts.googleapis.com/css?family=Marcellus+SC|Prompt|Rufina" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
    <link href="../administrador/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"
          rel="stylesheet">
    <link href="../components/css/footer.css">


</head>

<body>
<div>
    <div class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#" style="height: 100% !important;">
            <i class="material-icons">
                menu
            </i>
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-brand">
                    <a href="#">Cambury PCA</a>
                    <div id="close-sidebar">
                        <i class="material-icons">
                            keyboard_arrow_left
                        </i>
                    </div>
                </div>
                <div class="sidebar-header">
                    <!--                    <div class="user-pic">-->
                    <!--                        <img class="img-responsive img-rounded"-->
                    <!--                             src="../../images/User.png"-->
                    <!--                             alt="User picture">-->
                    <!--                    </div>-->
                    <div class="user-info">
          <span class="user-name"><?php echo $_SESSION['login'] ?>
              <!--            <strong>Smith</strong>-->
          </span>
                        <!--                        <span class="user-role">Administrator</span>-->
                        <span class="user-status">
            <i class="material-icons" style="color: forestgreen; background: forestgreen; border-radius: 20px;">
                        radio_button_unchecked
                        </i>
            <span>Online</span>
          </span>
                    </div>
                </div>
                <!-- sidebar-header  -->
                <div class="sidebar-search">
                    <div>
                        <!--                    <div class="input-group">-->
                        <!--                        <input type="text" id="searchProjeto" onkeyup="mySearch() class="form-control search-menu" placeholder="Pesquise por Projeto">-->
                        <!--                        <div class="input-group-append">-->
                        <!--              <span class="input-group-text">-->
                        <!--                <i class="fa fa-search" aria-hidden="true"></i>-->
                        <!--              </span>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                    </div>
                </div>
                <!-- sidebar-search  -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>Menu</span>
                        </li>
                        <li class="sidebar-dropdown">
                            <a href="#">
                                <i class="fa fa-tachometer-alt"></i>
                                <span>Meu Perfil</span>
                            </a>
                            <div class="sidebar-submenu">
                                <ul>
                                    <li>
                                        <a <?php echo 'href="../funcoes-usuario/editar-usuario.php?codUsuario=' . $codUsuario . '"' ?> >Editar
                                            Perfil
                                            <!--                                            <span class="badge badge-pill badge-success">Pro</span>-->
                                        </a>
                                    </li>
                                    <!--                                <li>-->
                                    <!--                                    <a href="#">Dashboard 2</a>-->
                                    <!--                                </li>-->
                                    <!--                                <li>-->
                                    <!--                                    <a href="#">Dashboard 3</a>-->
                                    <!--                                </li>-->
                                </ul>
                            </div>
                        </li>
                        <!--                        <li class="sidebar-dropdown">-->
                        <!--                            <a href="#">-->
                        <!--                                <i class="fa fa-shopping-cart"></i>-->
                        <!--                                <span>Usuários</span>-->
                        <!--                            <span class="badge badge-pill badge-danger">3</span>-->
                        <!--                            </a>-->
                        <!--                            <div class="sidebar-submenu">-->
                        <!--                                <ul>-->
                        <!--                                    <li>-->
                        <!--                                        <a href="listar-usuario.php">Listar Usuários-->
                        <!---->
                        <!--                                        </a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Orders</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Credit cart</a>-->
                        <!--                                </li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </li>-->
                        <!--                        <li class="sidebar-dropdown">-->
                        <!--                            <a href="#">-->
                        <!--                                <i class="far fa-gem"></i>-->
                        <!--                                <span>Avaliadores</span>-->
                        <!--                            </a>-->
                        <!--                            <div class="sidebar-submenu">-->
                        <!--                                <ul>-->
                        <!--                                    <li>-->
                        <!--                                        <a href="../usuario-avaliador/validar-usuario-avaliador.php">Aceitar Avaliador</a>-->
                        <!--                                    </li>-->
                        <!--                                    <li>-->
                        <!--                                        <a href="../usuario-avaliador/listar-avaliadores.php">Listar Avaliadores</a>-->
                        <!--                                    </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Tables</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Icons</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Forms</a>-->
                        <!--                                </li>-->
                        <!--                                </ul>-->
                        <!--                            </div>-->
                        <!--                        </li>-->
                        <!--                    <li class="sidebar-dropdown">-->
                        <!--                        <a href="#">-->
                        <!--                            <i class="fa fa-chart-line"></i>-->
                        <!--                            <span>Charts</span>-->
                        <!--                        </a>-->
                        <!--                        <div class="sidebar-submenu">-->
                        <!--                            <ul>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Pie chart</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Line chart</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Bar chart</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Histogram</a>-->
                        <!--                                </li>-->
                        <!--                            </ul>-->
                        <!--                        </div>-->
                        <!--                    </li>-->
                        <!--                    <li class="sidebar-dropdown">-->
                        <!--                        <a href="#">-->
                        <!--                            <i class="fa fa-globe"></i>-->
                        <!--                            <span>Maps</span>-->
                        <!--                        </a>-->
                        <!--                        <div class="sidebar-submenu">-->
                        <!--                            <ul>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Google maps</a>-->
                        <!--                                </li>-->
                        <!--                                <li>-->
                        <!--                                    <a href="#">Open street map</a>-->
                        <!--                                </li>-->
                        <!--                            </ul>-->
                        <!--                        </div>-->
                        <!--                    </li>-->
                        <!--                    <li class="header-menu">-->
                        <!--                        <span>Extra</span>-->
                        <!--                    </li>-->
                        <!--                    <li>-->
                        <!--                        <a href="#">-->
                        <!--                            <i class="fa fa-book"></i>-->
                        <!--                            <span>Documentation</span>-->
                        <!--                            <span class="badge badge-pill badge-primary">Beta</span>-->
                        <!--                        </a>-->
                        <!--                    </li>-->
                        <!--                    <li>-->
                        <!--                        <a href="#">-->
                        <!--                            <i class="fa fa-calendar"></i>-->
                        <!--                            <span>Calendar</span>-->
                        <!--                        </a>-->
                        <!--                    </li>-->
                        <!--                    <li>-->
                        <!--                        <a href="#">-->
                        <!--                            <i class="fa fa-folder"></i>-->
                        <!--                            <span>Examples</span>-->
                        <!--                        </a>-->
                        <!--                    </li>-->
                        <!--                </ul>-->
                </div>
                <!-- sidebar-menu  -->
            </div>
            <!-- sidebar-content  -->
            <div class="sidebar-footer">
                <a href="../logout.php">Deslogar
                    <i class="material-icons" style="color: #c82333;" data-toggle="tooltip" data-placement="top"
                       title="Deslogar" role="alert" data-toggle="tooltip">power_settings_new</i>
                </a>
                <!--            <a href="#">-->
                <!--                <i class="fa fa-envelope"></i>-->
                <!--                <span class="badge badge-pill badge-success notification">7</span>-->
                <!--            </a>-->
                <!--            <a href="#">-->
                <!--                <i class="fa fa-cog"></i>-->
                <!--                <span class="badge-sonar"></span>-->
                <!--            </a>-->
                <!--            <a href="#">-->
                <!--                <i class="fa fa-power-off"></i>-->
                <!--            </a>-->
            </div>
        </nav>
    <!-- sidebar-wrapper  -->
    <main class="page-content">
        <div class="container">
            <div class="span10 offset1">
                <div class="card">
                    <div class="card-header">
                        <h3 class="well">Informações do Projeto Avaliado</h3>
                    </div>
                    <div class="container">
                        <div class="form-horizontal">
                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Nome do Projeto</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        <?php echo $projetosAvaliador['nomeProjeto']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Nome do Orientador</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        <?php echo $projetosAvaliador['nomeProfessor']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Objetivo do Projeto</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        <?php echo $projetosAvaliador['objetivo']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Resumo do Projeto</label>
                                <div class="controls">
                                    <label class="carousel-inner" style="font-style: italic;">
                                        <?php echo $projetosAvaliador['resumo']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Curso e Turma</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        <?php echo $projetosAvaliador['curso']; ?> / <?php echo $projetosAvaliador['turma']; ?>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Contribuição do projeto para instituições envolvidas e/ou sociedade</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        Nota: <span style="color: #0d95e8; font-weight: bold;"><?php echo $projetosAvaliador['nota_1']; ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Ambientação e exposição do projeto
                                </label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        Nota: <span style="color: #0d95e8; font-weight: bold;"><?php echo $projetosAvaliador['nota_2']; ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Domínio nas explicações e dinâmica do projeto</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        Nota: <span style="color: #0d95e8; font-weight: bold;"><?php echo $projetosAvaliador['nota_3']; ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Resultados obtidos com o projeto</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        Nota: <span style="color: #0d95e8; font-weight: bold;"><?php echo $projetosAvaliador['nota_4']; ?></span>
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" style="font-weight: bold;">Total</label>
                                <div class="controls">
                                    <label class="carousel-inner">
                                        Nota Total: <span style="font-weight: bold; color:#1e7e34;"><?php echo $projetosAvaliador['Total']; ?></span>
                                    </label>
                                </div>
                            </div>

<!--                            <div class="control-group">-->
<!--                                <label class="control-label" style="font-weight: bold;">Obs: </label>-->
<!--                                <div class="controls">-->
<!--                                        <span class="carousel-inner">-->
<!--                                            Este Projeto foi avaliado por: <span-->
<!--                                                    style="font-weight: bold; color:#fd7e14;">--><?php //echo $projetosQuantidadeAvaliador['count(*)']; ?><!--</span> avaliador(s)-->
<!--                                        </span>-->
<!--                                </div>-->
<!--                            </div>-->

                            <br/>
                            <div class="form-actions">
                                <a href="../usuario-logado.php" type="btn" class="btn btn-default">Voltar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>

    <!-- page-wrapper -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
            integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
            integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
            crossorigin="anonymous"></script>

</body>
</html>
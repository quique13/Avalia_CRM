<html>
<?php
require "bootstrap.php";
require "models/User.php";

session_start();
if (empty($_SESSION["userIdNaos"])) {
    header("Location: login.php");
    exit();
}
$currentuserIdNaos = $_SESSION["userIdNaos"];
$currentUser = User::find($currentuserIdNaos);

$title = 'Avalia';
include("header.php");
?>
</html>
<body ng-app="app" ng-controller="thankYouFormController" ng-cloak>
<nav class="navbar navbar-expand-lg  navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Avalia Desarrollos</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" href="quotation-form">Cotización</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="apartamentos">Apartamentos</a>
            </li>
            <?php
            if ($currentUser->role == 1) {
                echo '<li class="nav-item">
                        <a class="nav-link" href="cotizaciones">Cotizaciones</a>
                      </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="settings">Configuraciones</a>
                      </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="hooking-payment.php">Documento de Enganche</a>
                      </li>';
                echo '<li class="nav-item">
                        <a class="nav-link" href="documentos-enganche.php">Histórico Doc. Enganache</a>
                      </li>';
            }
            ?>
            <li class="nav-item active">
                <a class="nav-link" href="agradecimiento">Email Agradecimiento</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="usuario">Usuario</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Salir</a>
            </li>
        </ul>
    </div>
</nav>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <br><br><br>
            <br><br><br>
            <h3>Envío de Email de Agracecimiento</h3>
            <br><br><br>
        </div>
        <div class="col-lg-12">
            <form name="thankForm" class="row" id="send-form">
                <div class="form-group col-lg-3 col-md-6">
                    <label for="levels">*Nombre de cliente:</label>
                    <input type="text" class="form-control" ng-model="thankData.name" required>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                    <label for="levels">*Correo electrónico:</label>
                    <input type="email" class="form-control" ng-model="thankData.email" required>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                    <label for="levels">*Prefijo:</label>
                    <select class="form-control" ng-model="thankData.prefix" ng-options="opt.id as opt.label for opt in prefixes" required>

                    </select>
                </div>
                <div class="form-group col-lg-3 col-md-6">
                    <label for="levels">*Tipo de contacto:</label>
                    <select class="form-control" ng-model="thankData.type" ng-options="opt.id as opt.label for opt in contactTypes" required>

                    </select>
                </div>
                <div class="form-group col-lg-12">
                    <p style="color: green;" ng-if="responseStatus === 1">Se ha enviado el correo exitosamente a: {{thankData.email}}</p>
                    <p style="color: red;" ng-if="responseStatus === 2">Hubo un error enviando los datos. Intente más tarde.</p>
                </div>

                <div class="form-group col-lg-12">
                    <button class="btn btn-primary" ng-click="sendEmail()">
                        Enviar email
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
include('embedjs.php');
?>

<script src="https://cdn.jsdelivr.net/npm/file-saver@2.0.2/dist/FileSaver.min.js"></script>
<script src="assets/js/jquery.maskMoney.js"></script>
<script src="assets/js/angular.maskMoney.js"></script>
<script src="assets/js/thank-you.js"></script>
</body>
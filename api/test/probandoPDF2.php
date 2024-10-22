<?php
    require "../class/vendor/auotload.php";

    use Spipu/Html2Pdf/Html2Pdf;

    $html2Pdf = new Html2Pdf();
    $html2pdf ->writeHTML("<h1> Hola mundo!! desde html</h1>");
    $html2Pdf ->output();
?>

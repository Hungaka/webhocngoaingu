<style>
    <?= include_once(__DIR__ . "/../../assets/css/responsive.css");
    ?>
</style>
<script>
    <?= include_once(__DIR__ . "/../../assets/javascript/play_sound.js");
    ?>
</script>
<?php


if (!isset($_SESSION["account"])) {
?>


<?php
} else {
?>



<?php
}
?>
</div>
</body>

</html>
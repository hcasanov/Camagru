<div id="container-view">
        <div class="form_box">
            <h1>Se connecter</h1>


            <?php if (array_key_exists('error', $_SESSION)): ?>
                        <script>
                            document.write("<div class='alert alert-danger'>");
                            document.write("<?= implode('<br>', $_SESSION['error']); ?>");
                            document.write("</div>");
                        </script>
            <?php unset($_SESSION['error']); endif; ?>


            <form method="post" action="./app/controller/connexion.php">
                <div class="form-group">
                    <label>Adresse mail</label>
                    <input type="text" class="form-control" name="mail" value="<?= isset($_SESSION['input']['mail']) ? $_SESSION['input']['mail'] : '' ?>">
                </div>
                <div class="form-group">
                    <label>Mot de passe</label>
                    <input type="password" class="form-control" name="passwd">
                </div>
                <div class="form-group">
                    <a href="./index.php?view=forget_passwd">Mot de passe oublié</a><br>
                </div>
                <button type="submit" name="connect" class="btn btn-primary" value="OK">Connexion</button>
            </form>
        </div>
    </div>

<?php

unset($_SESSION['input']);
unset($_SESSION['error']);

?>
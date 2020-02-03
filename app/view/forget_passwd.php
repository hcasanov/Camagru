<div id="container-view">
        <div class="form_box">
            <h2>Réinitialiser votre mot de passe</h1><br/>

            <?php if (array_key_exists('error', $_SESSION)): ?>
                <div class="alert alert-danger">

                        <?= implode('<br>', $_SESSION['error']); ?>
                        
                </div>
                <?php unset($_SESSION['error']); endif; ?>

            <?php if (array_key_exists('success', $_SESSION)): ?>
                <div class="alert alert-success">

                        Un email vous a été envoyé !
                        
                </div>
            <?php endif; ?>

            <p>Veuillez saisir votre adresse mail pour réinitialiser votre mot de passe.</p>
            <form method="post" action="./app/controller/controller_forget_passwd.php">
                <div class="form-group">
                    <label>Adresse mail</label>
                    <input type="text" class="form-control" name="mail" value="<?= isset($_SESSION['input']['mail']) ? $_SESSION['input']['mail'] : '' ?>">
                </div>
                <button type="submit" name="forget_passwd" class="btn btn-primary" value="OK">Réinitialiser</button>
            </form>
        </div>
    </div>

    <?php

unset($_SESSION['input']);
unset($_SESSION['error']);
unset($_SESSION['success']);

?>
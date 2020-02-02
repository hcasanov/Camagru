<div id="container-view">
    <div class="form_box">
        <h1>Paramètre utilisateur</h1>
        <div></div>

        <?php if (array_key_exists('error', $_SESSION)) : ?>
            <script>
                document.write("<div class='alert alert-danger'>");
                document.write("<?= implode('<br>', $_SESSION['error']); ?>");
                document.write("</div>");
            </script>
        <?php unset($_SESSION['error']);
        endif; ?>

        <?php if (array_key_exists('success', $_SESSION)) : ?>
            <script>
                document.write("<div class='alert alert-success'>");
                document.write("Vos informations ont bien étés enregistrées !");
                document.write("</div>");
            </script>
        <?php unset($_SESSION['error']);
        endif; ?>

        <form method="post" action="./app/controller/controller_parameter.php" class="form_param">
            <div class="form-group">
                <label>Adresse mail</label>
                <input type="text" class="form-control" name="mail">
            </div>
            <div class="form-group">
                <label>Nom</label>
                <input type="text" class="form-control" name="name" value="<?= isset($_SESSION['input']['name']) ? $_SESSION['input']['name'] : '' ?>">
            </div>
            <div class="form-group">
                <label>Nouveau mot de passe</label>
                <input type="password" class="form-control" name="passwd">
            </div>
            <div class="form-group">
                <label>Confirmer nouveau mot de passe</label>
                <input type="password" class="form-control" name="confirm_passwd">
            </div>
            <button type="submit" name="new_parameter" class="btn btn-success" value="OK">Enregistrer</button>
        </form>
        <label>Recevoir un mail lorsqu'une photo est commentée</label><br>
        <button class="btn btn-success" id="mail_yes">Oui</a>
            <button class="btn btn-danger" id="mail_no">Non</a>
    </div>
</div>
<script>
    window.onload = function() {
        var m_no = document.getElementById('mail_no');
        m_no.addEventListener('click', function() {
            $.ajax({
                url: './app/controller/controller_parameter.php',
                type: 'post',
                data: 'data=no',
                dataType: 'text',
                success: function(data) {
                    console.log(data);
                }
            });
        });
        var m_no = document.getElementById('mail_yes');
        m_no.addEventListener('click', function() {
            $.ajax({
                url: './app/controller/controller_parameter.php',
                type: 'post',
                data: 'data=1',
                success: function() {
                }
            });
        });
        $.ajax({
            url: './app/controller/get_session_var.php',
            dataType: 'json',
            success: function(data) {
                var mail = document.getElementsByName('mail')[0];
                mail.setAttribute('value', data['mail']);
                var name = document.getElementsByName('name')[0];
                name.setAttribute('value', data['name']);
            }
        });
    }
</script>
<?php

unset($_SESSION['input']);
unset($_SESSION['error']);
unset($_SESSION['success']);

?>
<div class="main_profile">
<div class="webcam_box">

    <?php if (array_key_exists('error', $_SESSION)) : ?>
        <div class="alert alert-danger">

            <?= implode('<br>', $_SESSION['error']); ?>

        </div>
    <?php unset($_SESSION['error']);
    endif; ?>

    <div class="left_main_cam">
        <video id="video"></video>
        <div class="btn_zone">
            <button class="btn btn-primary btn-profile" id="startbutton">Prendre une photo</button>
            <form method="POST" action="./app/controller/upload.php" enctype="multipart/form-data" id="form_profile">
                <input class="btn btn-secondary" type="file" name="img_upload" style="visibility:hidden" id="upload_img">
                <input value="Upload image" class="btn btn-secondary btn-profile" id="btn_upload" style="width: 125px;" type="button" onclick="$('#upload_img').click();" />
            </form>
        </div>
        <canvas id="canvas"></canvas>
    </div>
    <div class="list_patch" id="patch_galery">

    </div>
</div>
<div class="side_profile">
    <div id="galery_img">

    </div>
</div>
</div>

<script type="text/javascript" src="./public/js/webcam.js"></script>
<script type="text/javascript" src="./public/js/image.js"></script>

<?php unset($_SESSION['error']); ?>
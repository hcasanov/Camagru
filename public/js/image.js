var e_upload = 0;

window.onload = function () {
    $.ajax({
        url: "./app/controller/get_patch.php",
        dataType: "json",
        success: function (data) {
            if (data) {
                var patch_select;
                var patch_galery = document.getElementById('patch_galery');
                data.forEach(element => {
                    var div_patch = document.createElement('a');
                    div_patch.setAttribute('class', 'div_patch');
                    div_patch.setAttribute('name', 'a_patch');
                    div_patch.setAttribute('value', element);
                    patch_galery.appendChild(div_patch);

                    var src = './public/patch/' + element;
                    var img_patch = document.createElement('img');
                    img_patch.setAttribute('src', src);
                    img_patch.setAttribute('class', 'img_patch');
                    div_patch.appendChild(img_patch);
                    img_patch.addEventListener('click', function() {
                        $.ajax({
                            url: './app/controller/controller_patch.php',
                            type: 'post',
                            data: 'patch=' + element,
                            dataType: 'text',
                            success: function() {
                                var e = document.getElementById('startbutton');
                                e.style.display = "block";
                            },
                            error: function() {
                                console.log("Error: patch non enregistré");
                            }
                        });
                        if (patch_select)
                        {
                            patch_select.removeAttribute('style');
                        }
                        img_patch.setAttribute('style', 'border: solid; border-color: #eaeaea;');
                        patch_select = img_patch;
                    });

                    
                });
            }
            else
                console.log("Error: recupération data get_patch.php");
        }
    });
    var upload_btn = document.getElementById('upload_img');
    upload_btn.addEventListener("change", create_btn_webcam);
    
    var tab = [];//Recupere la liste des img du user
    $.ajax({
        url: './app/controller/controller_refresh_galery.php',
        dataType: 'json',
        success: function(data) {
            refresh_galery(data);
        }
    });


}

function create_btn_webcam() {
    if (e_upload == 0)
    {
        e_upload = 1;
        var btn = document.createElement('input');
        var form = document.getElementById('form_profile');
        btn.setAttribute('class', 'btn btn-success btn-profile');
        btn.setAttribute('type', 'submit');
        btn.setAttribute('name', 'envoyer');
        btn.setAttribute('value', 'Créer le montage');
        form.appendChild(btn);
        btn.addEventListener("click", montage_uplaod);
    }
    else
        console.log(e_upload);
}

function montage_uplaod() {
    $.ajax({
        url: './app/controller/upload.php',
        dataType: "text",
        succes: function (data) {
            if (data) {
                console.log(data);
            }
            else
                console.log("Error: montage image upload fail")
        }
    })
}

//*********************************** Refresh galery en live ****************************************** */

function refresh_galery(list_img) {

    var div_galery = document.getElementById('galery_img');
    list_img.forEach(element => {
        var img = document.createElement('img');
        var src = './galery/' + element['src'];
        img.setAttribute('src', src);
        img.setAttribute('class', 'img_galery');
        img.setAttribute('id', element);
        div_galery.appendChild(img);
        img.addEventListener('click', function() {
            console.log(element['src']);
            if (confirm("Etes vous sûr de vouloir effacer cette image à tout jamais ?"))
            {
                $.ajax({
                    url: '../../app/controller/controller_delete_img.php',
                    type: 'post',
                    data: 'src=' + element['src'],
                    success: function(data) {
                        document.location.reload(true);
                    }
                });
            }
        });
    });
}


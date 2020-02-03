var index = 1;

window.onload = function () {

    refresh_galery_home();
}

function refresh_galery_home() {
    $.ajax({
        url: '../../app/controller/home_galery.php',
        dataType: 'json',
        success: function (data) {
            j = 0;
            while (data[j])
            {
                j++;
            }
            i = 1;
            if (j > 0)
                print_img(data[0]['src']);
            if (j > 1)
                print_img(data[1]['src']);
            if (j > 2)
                print_img(data[2]['src']);
            if (j > 3)
                print_img(data[3]['src']);
            if (j > 4)
                print_img(data[4]['src']);
            data.forEach(element => {
                element.index = i;
                i++;
            });
            var loop = i / 5;
            if ((i % 5) != 0) {
                loop++;
            }
            j = 1;

            while (j <= loop) {
                var pag = document.getElementById('pag');
                var page = document.createElement('li');
                page.setAttribute('class', 'page-item');
                pag.appendChild(page);
                var num_page = document.createElement('a');
                num_page.setAttribute('class', 'page-link');
                num_page.setAttribute('id', j);
                page.appendChild(num_page);
                num_page.innerHTML = Math.round(j);
                num_page.addEventListener('click', function () {
                    delete_galery();
                    index = 5 * this.id;
                    if (data[index - 5] != undefined)
                        print_img(data[index - 5].src);
                    if (data[index - 4] != undefined)
                        print_img(data[index - 4].src);
                    if (data[index - 3] != undefined)
                        print_img(data[index - 3].src);
                    if (data[index - 2] != undefined)
                        print_img(data[index - 2].src);
                    if (data[index - 1] != undefined)
                        print_img(data[index - 1].src);
                })
                j++;
            }
        }
    })
}

function delete_galery() {
    const child = document.querySelectorAll('.image');
    for (const e of child) {
        e.remove();
    }
}

function open_comment(src) {
    var box_message = document.createElement('div');
    box_message.setAttribute('class', 'box_message');
    comment_box.appendChild(box_message);
    $.ajax({
        url: '../../app/controller/controller_commentaires.php',
        type: 'post',
        dataType: 'text',
        data: { img: src, control: "add_comment" },
        success: function (data) {
            tab = JSON.parse(data);
            tab.forEach(element => {
                var com = document.createElement('div');
                com.setAttribute('class', 'com_content');
                box_message.appendChild(com);
                com.innerHTML = element['content'];
            })
        }
    })
}

function print_img(element) {
    var div_galery = document.getElementById('right_box');
    src = element;
    var div_ = document.createElement('div');
    div_.setAttribute('class', 'image');
    div_galery.appendChild(div_);
    var img = document.createElement('img');
    img.setAttribute('class', 'img_home_galery');
    img.setAttribute('src', src);
    div_.appendChild(img);

    var div_like = document.createElement('div');
    div_like.setAttribute('class', 'div_like');
    div_.appendChild(div_like);
    var like_btn = document.createElement('a');
    like_btn.setAttribute('class', 'like_btn');
    $.ajax({
        url: '../../app/controller/like_init.php',
        type: 'post',
        data: 'src_like=' + element,
        dataType: 'text',
        success: function (data) {
            console.log(data)
            if (data != "FAIL") {
                if (data == false) {
                    like_btn.innerHTML = "LIKE";
                }
                else if (data == true) {
                    like_btn.innerHTML = "DISLIKE";
                }
            }
            else {
                console.log('error');
            }
        }
    });
    div_like.appendChild(like_btn);
    div_like.addEventListener('click', function () {
        $.ajax({
            url: '../../app/controller/like_img.php',
            type: 'post',
            data: { src_like: element },
            dataType: 'text',
            success: function (data) {
                if (data == 1) {
                    like_btn.innerHTML = "LIKE";
                }
                else if (data == 0)
                    like_btn.innerHTML = "DISLIKE";
            }
        });
    });
    var div_comment = document.createElement('div');
    div_comment.setAttribute('class', 'div_comment');
    div_.appendChild(div_comment);

    var comment_btn = document.createElement('a');
    comment_btn.setAttribute('class', 'comment_btn');
    comment_btn.innerHTML = "COMMENTAIRES";
    div_comment.appendChild(comment_btn);

    div_comment.addEventListener('click', function () {
        var box = document.getElementById('box');
        var e = document.getElementById('comment_box');
        if (e != undefined) {
            var box = document.getElementById('box');
            var b = document.getElementById('comment_box');
            box.removeChild(b);
        }
        var comment_box = document.createElement('div');
        comment_box.setAttribute('id', 'comment_box');
        box.appendChild(comment_box);

        var btn_close = document.createElement('h4');
        btn_close.setAttribute('id', 'btn_close_com')
        comment_box.appendChild(btn_close);
        btn_close.innerHTML = "Close";
        btn_close.addEventListener('click', function () {
            var box = document.getElementById('box');
            var b = document.getElementById('comment_box');
            box.removeChild(b);
        })

        var btn_new_com = document.createElement('div');
        btn_new_com.setAttribute('class', 'btn_new_comment')
        comment_box.appendChild(btn_new_com);
        btn_new_com.innerHTML = "New commentaire";
        btn_new_com.addEventListener('click', function () {
            var comment = window.prompt("Commentaire");
            if (comment != null && comment != '') {
                $.ajax({
                    url: '../../app/controller/controller_commentaires.php',
                    type: 'post',
                    dataType: 'text',
                    data: { com: comment, new_com: 'true', img: element },
                    success: function (data) {
                        console.log(data)
                        alert("Commentaire enregistré");
                        open_comment(element);
                    }
                });
            }
        })
        open_comment(element);

    })
}
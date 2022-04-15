window.onload = function () {
    var btn_jsPush = document.getElementById('jsPush');

    // 切换按钮
    var text_btn = document.getElementById("text_btn");
    var textCard_btn = document.getElementById("textCard_btn");
    var textBox = document.getElementById("textBox");
    var textcardBox = document.getElementById("textcardBox");

    var selectType = 'text';
    text_btn.onclick = function () {
        selectType = 'text';
        textBox.style.display = "block";
        textcardBox.style.display = "none";
    }
    textCard_btn.onclick = function () {
        selectType = 'textcard';
        textBox.style.display = "none";
        textcardBox.style.display = "block";
    }

    // 提交
    btn_jsPush.onclick = function () {
        var xmlhttp, json_str;
        json_str = js_str(selectType);
        if (json_str == "err") {
            return 0;
        }
        xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                // 完成后返回
                var responseText = xmlhttp.responseText;

                var re = /未登录或者登录失效!/i;
                if (re.test(responseText)) {
                    alert('未登录或者登录失效!');
                    window.location.href = "./login.html";

                    // setTimeout(function(){
                    //     window.location.href="../c/login.html";
                    // },5000);
                }
                if (responseText=="   ok") {
                    document.getElementById("returnInfo").innerHTML = "发送成功";
                } else {
                    document.getElementById("returnInfo").innerHTML = "发送失败，请检查内容";
                }
            }
        };
        // 无刷新提交
        xmlhttp.open("POST", "./pushToWechat.php", true);
        // 启用信息携带要在open之后
        xmlhttp.withCredentials = true;
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send(json_str);
    }
};

// json字符串创建
function js_str(selectType) {
    if (selectType == 'text') {
        var obj, json_str;
        var content = document.getElementById('text').value;

        if (content == "") {
            alert("未输入消息");
            window.location.href = "./index.html";
            return "err";
        }

        // alert("111");
        // 构造json
        obj = { Type: selectType, Content: content };
        json_str = JSON.stringify(obj);
        return json_str;
    } else if (selectType == 'textcard') {
        var obj, json_str;
        var textcard_title = document.getElementById('textcard_title').value;
        var textcard_description = document.getElementById('textcard_description').value;
        var textcard_url = document.getElementById('textcard_url').value;


        if (textcard_title == "" || textcard_description == "") {
            alert("未输入消息");
            window.location.href = "./index.html";
            return "err";
        }
        if (textcard_url == "") {
            textcard_url = "https://www.baidu.com";
        }

        // 构建json
        obj = { Type: selectType, Title: textcard_title, Description: textcard_description, Url: textcard_url };
        json_str = JSON.stringify(obj);
        return json_str;
    }
}

/**
 * 渲染markdown页面
 */
editormd.markdownToHTML("app-article-context", {
    htmlDecode      : "style,script,iframe",  // you can filter tags decode
    emoji           : true,
    taskList        : true,
    tex             : true,  // 默认不解析
    flowChart       : true,  // 默认不解析
    sequenceDiagram : true,  // 默认不解析
});

/**
 * 渲染评论列表
 * @param $data
 */
function commentRender(Url, Aid = 0) {
    $.ajax({
        url: Url,
        method: "POST",
        data: {id : Aid},
        success: function (rst) {
            var html = commentHtml(rst.data);
            $('.app-comment-list').html(html);
        }
    })
}

/**
 * 评论数据组合
 * @param data
 * @param pid
 * @param pname
 * @returns {string}
 */
function commentHtml(data, pid = 0, pname ="") {
    var html = "<ul>";
    for(var i in data) {
        if (pid != 0) {
            data[i].context = "<a href=''>@"+pname+"</a>：" + data[i].context;
        }
        html += "" +
            "                        <li>\n" +
            "                            <div class=\"avator\">\n" +
            "                                <img src=\"" + data[i].user.avator + "\" alt=\"\">\n" +
            "                            </div>\n" +
            "                            <div class=\"content\">\n" +
            "                                <div class=\"header\">\n" +
            "                                    <strong><a href=\"javascript:;\">" + data[i].user.nickname + "</a></strong>\n" +
            "                                    <em><i class=\"layui-icon layui-icon-survey\"></i> " + data[i].create_time + "</em>\n" +
            "                                    <em><i class=\"layui-icon layui-icon-location\"></i> " + data[i].city + "</em>\n" +
            "                                    <em><a class=\"app-comment-reply\" data-user=\"" + data[i].user.nickname + "\" data-pid=\"" + data[i].id + "\" href='javascript:;'>回复</a></em>\n" +
            "                                </div>\n" +
            "                                <div class=\"main\">\n" +
            "                                    <p>" + data[i].context + "</p>\n" +
            "                                </div>\n" +
            "                            </div>\n" +
            "                        </li>";
        if ( data[i].child.length > 0) {
            html += "<li>" +
                commentHtml(data[i].child, data[i].user_id, data[i].user.nickname) +
                "</li>";
        }
    }
    html += "" +
        "</ul>"
    return html;
}


/**
 * 添加评论
 * @param Url
 * @param Aid
 * @param Pid
 * @param Content
 */
function addComment(Url, Content = "", Aid = 0, Pid = 0) {
    var data = {
        article_id: Aid,
        parent_id: Pid,
        context: Content,
    };
    $.ajax({
        url : Url,
        method: "POST",
        data: data,
        dataType: "JSON",
        success: function (res) {
            layer.msg(res.msg, {icon: !res.code + 1, time: 1000}, function(){
                layer.close(index);
            });
        }
    });
}
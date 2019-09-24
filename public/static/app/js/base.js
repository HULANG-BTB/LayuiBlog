layui.use(['element', 'carousel', 'laypage', 'flow'], function() {

    // 获取阅读排行数据 并渲染到页面
    $.ajax({
        url: "/index/article/rank",
        method: "POST",
        dataType: "JSON",
        success: function (rst) {
            var data = rst.data;
            var html = "";
            for (var item in data) {
                html += "\n" +
                    "                <li>\n" +
                    "                    <a title=\"" + data[item].title + "\" href=\"/index/article/detail/id/" + data[item].id + "\" target=\"_blank\" draggable=\"false\">\n" +
                    "                        <span class=\"thumbnail\"><img class=\"thumb\" src=\"" + data[item].thumbnail + "\" alt=\"" + data[item].title + "\" draggable=\"false\"></span>\n" +
                    "                        <span class=\"text\">" + data[item].title + "</span>\n" +
                    "                        <span class=\"muted\"><i class=\"layui-icon layui-icon-search\"></i> " + data[item].view_count + "</span>\n" +
                    "                        <span class=\"muted\"><i class=\"layui-icon layui-icon-note\"></i> " + data[item].comment_count + "</span>\n" +
                    "                    </a>\n" +
                    "                </li>";
            }
            $('.app-side .app-article-rank ul').html(html);
        }
    });

    // 加载标签云 渲染到页面
    $.ajax({
        url: "/index/tag/read",
        method: "POST",
        dataType: "JSON",
        success: function (rst) {
            var data = rst.data;
            var html = "";
            for(var item in data) {
                html += "                <li>\n" +
                    "                    <a href=\"/index/tag/index/id/" + data[item].id + "\">" + data[item].name + "</a>\n" +
                    "                </li>";
            }
            $('.app-side .app-tag-list ul').html(html);
        }
    });

    // 获取最新评论 并渲染到页面
    $.ajax({
        url: "/index/comment/getNewComment",
        method: "POST",
        dataType: "JSON",
        success: function (rst) {
            var data = rst.data;
            console.log(rst);
            var html = "";
            for (var item in data) {
                html += "\n" +
                    "                <li><i class=\"layui-icon layui-icon-tree\"></i> <a href=\"/index/article/detail/id/"+data[item].article_id+".html\">"+data[item].context+"</a></li>";
            }
            $('.app-side .app-comment-list ul').html(html);
        }
    });

});
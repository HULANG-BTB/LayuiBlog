layui.use(['element', 'carousel', 'laypage', 'flow'], function(){
    var element = layui.element,
        carousel = layui.carousel,
        laypage = layui.laypage,
        flow = layui.flow;

    carousel.render({
        elem: '#carousel'
        ,width: '100%'
        ,height: '172px'
        ,interval: 3000
        ,anim: 'fade'
    });

    // 获取置顶数据 并渲染到页面
    $.ajax({
        url: "/index/article/readTop",
        method: "POST",
        dataType: "JSON",
        success: function (rst) {
            var data = rst.data;
            var html = "";
            for (var item in data) {
                html += "\n" +
                    "                    <li>\n" +
                    "                        <a class=\"focus\" href=\"/index/article/detail/id/" + data[item].id + "\" title=\"" + data[item].title + "\" target=\"_blank\">\n" +
                    "                            <img class=\"thumbnail layui-anim-scaleSpring\" lay-src=\"" + data[item].thumbnail + "\" alt=\"" + data[item].title + "\">\n" +
                    "                        </a>\n" +
                    "                        <div class=\"title\">\n" +
                    "                            <a class=\"category\" title=\"" + data[item].category_name + "\" href=\"/index/category/index/id/" + data[item].category_id + "\">" + data[item].category_name + "<i></i></a>\n" +
                    "                            <h2><a href=\"/index/article/detail/id/" + data[item].id + "\" title=\"" + data[item].title + "\">" + data[item].title + "</a></h2>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"meta\">\n" +
                    "                            <span class=\"time\" title=\"发布时间\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"发布时间\"><i class=\"layui-icon layui-icon-release\"></i> " + data[item].create_time + "</span>\n" +
                    "                            <span class=\"views\" title=\"阅读量\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"阅读量\"><i class=\"layui-icon layui-icon-search\"></i> " + data[item].view_count + "</span>\n" +
                    "                            <span class=\"comment\" title=\"评论量\" data-toggle=\"tooltip\" data-placement=\"bottom\" target=\"_blank\" data-original-title=\"评论量\"><i class=\"layui-icon layui-icon-note\"></i> " + data[item].comment_count + "</span>\n" +
                    "                        </div>\n" +
                    "                        <div class=\"note\">" + data[item].abstract + "</div>\n" +
                    "                    </li>";
            }
            $('.app-article-list.app-article-top ul').html(html);
            flow.lazyimg();
        }
    });

    // 获取文章条数，渲染分页
    $.ajax({
        url: "/index/article/getCount",
        method: "POST",
        dataType: "JSON",
        success: function (rst) {
            laypage.render({
                elem: $('.app-article-page')
                ,count: rst.data
                ,limit: 10
                ,layout: ['first', 'prev', 'page', 'next', 'last']
                ,jump: function (obj) {
                    loadPage(obj.curr);
                }
            });
        }
    });

    function loadPage(page = 1) {
        var loading = '<i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i> 加载中...';
        $('.app-progress-load').removeClass('layui-hide');
        $('.app-article-list.app-article-new ul').html(loading);

        $.ajax({
            url: "/index/article/read",
            method: "POST",
            data: {page: page},
            dataType: "JSON",
            success: function (rst) {
                var data = rst.data;
                var html = "";
                for (var item in data) {
                    html += "\n" +
                        "                    <li>\n" +
                        "                        <a class=\"focus\" href=\"/index/article/detail/id/" + data[item].id + "\" title=\"" + data[item].title + "\" target=\"_blank\">\n" +
                        "                            <img class=\"thumbnail layui-anim-scaleSpring\" lay-src=\"" + data[item].thumbnail + "\" alt=\"" + data[item].title + "\">\n" +
                        "                        </a>\n" +
                        "                        <div class=\"title\">\n" +
                        "                            <a class=\"category\" title=\"" + data[item].category_name + "\" href=\"/index/category/index/id/" + data[item].category_id + "\">" + data[item].category_name + "<i></i></a>\n" +
                        "                            <h2><a href=\"/index/article/detail/id/" + data[item].id + "\" title=\"" + data[item].title + "\">" + data[item].title + "</a></h2>\n" +
                        "                        </div>\n" +
                        "                        <div class=\"meta\">\n" +
                        "                            <span class=\"time\" title=\"\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"发布时间\"><i class=\"layui-icon layui-icon-release\"></i> " + data[item].create_time + "</span>\n" +
                        "                            <span class=\"views\" title=\"\" data-toggle=\"tooltip\" data-placement=\"bottom\" data-original-title=\"阅读量\"><i class=\"layui-icon layui-icon-search\"></i> " + data[item].view_count + "</span>\n" +
                        "                            <span class=\"comment\" title=\"\" data-toggle=\"tooltip\" data-placement=\"bottom\" target=\"_blank\" data-original-title=\"评论量\"><i class=\"layui-icon layui-icon-note\"></i> " + data[item].comment_count + "</span>\n" +
                        "                        </div>\n" +
                        "                        <div class=\"note\">" + data[item].abstract + "</div>\n" +
                        "                    </li>";
                }
                $('.app-article-list.app-article-new ul').html(html);
                flow.lazyimg();
                $('.app-progress-load').addClass('layui-hide');
            }
        });
    }

    // 图片懒加载
    flow.lazyimg();
});
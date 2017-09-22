知乎RSS订阅源生成器
===
### 本生成器使用PHP编写


1.  生成指定用户回答页RSS:
`zhihu_usr_answer_rss.php?user={UID}` 
其中UID出现在该用户详情页URL中: 
`https://www.zhihu.com/people/{UID}` 

2. 生成专栏RSS:
`b.php?user={id}` 
其中id出现在专栏页URL中:
`https://zhuanlan.zhihu.com/{id}` 

3. 可以在`zhihu_rss_config.php` 中修改curl用的UA信息，我也不记得当初为啥要把这东西单独拿出来了…

### 注意事项：
1. 知乎专栏页图片获取似乎有referer校验，所以`b.php` 内部将使用`getzimg.php` 获取图片，`getzimg.php` 内部使用`curl` 改`header` 避开校验。
2. 这个东西一开始只为了自己临时用着方便，无脑正则，代码一坨…… 全都是正则表达式匹配网页字符串，懒得改了。如果以后知乎页面变了这东西也就废了……恩，只要变一点这东西就废了

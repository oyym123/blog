=== WP Accelerator for Chinese ===
Contributors: suifengtec
Donate link: http://coolwp.com/wp-accelerator-for-chinese.html
Tags: speedup, accelerator
Requires at least: 3.8
Tested up to: 4.4.2
Stable tag: 0.9.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

为中国的 WordPress 使用者加速Ta们的网站！

== Description ==

为中国的 WordPress 使用者加速Ta们的网站！



*   本插件未对jQuery库使用公共CDN:因为那对网站加速,起不到一点儿作用,反而可能会拖慢(外部域名解析,外部文件拉取),自行CDN倒是可取的.该说法来自WordPress核心团队的某个插件审查人员,如果你理解 WordPress 的运行流程和浏览器的渲染流程,你也不会使用公共CDN去替换WordPress自带的jQuery了;
*	前后台默认使用微软雅黑或者STXihei字体;
*	移除 WordPress 以及其默认主题(2014-2016)自带的谷歌字体;
*	谷歌字体链接换为useso的字体链接;
*	将获取头像的服务器换为 Gravatar 支持国内的cn.gravatar.com;
*	可禁用emoji,如果你在自己的网站上不用这个,可禁用,如果你用得上,建议换为在设置页推荐的那个emoji头像CDN服务器;
*	移除或替换掉 WordPress 自带的 Meta 小工具;
*	管理工具条上移除 WordPress 的logo以及链接;
*	移除后台首页的 WordPress 新闻等无用的小工具;
*	安全地清理页面头部的输出;
*	移除脚本的版本号
*	缓存外部域名DNS解析;
*	防止自Ping,并禁用Pingback;
*   将Bing每日一图作为登录页的背景图(不支持HTTPS),支持将缓存此图链接;
*   支持在用户名中使用中文;
*	自0.9.3版本起已取消~~安全地使用相对链接,形如`/post-123` or `/post-123.html`~~(注意:不要使用相对链接,也不要使用任何将绝对链接变为相对链接的插件或主题);



== Installation ==


*   如常安装和启用后，点击"设置"按钮，到设置页面进行设置。或者，点击后台的"设置"菜单下的子菜单"静态加速"去到设置页面进行设置;
*   **设置菜单** - 在插件列表页面该插件名称下;
*   设置项参见 [项目主页](http://coolwp.com/wp-accelerator-for-chinese.html)。


== Frequently Asked Questions ==

[项目主页](http://coolwp.com/wp-accelerator-for-chinese.html)

== Screenshots ==

1. **后台设置页面** - 不要嫌丑，我尽量的让它小而为之.



== Upgrade Notice ==

Not Need.

== Changelog ==


= 0.9.4 =

*	change hook `get_avatar_data` to `get_avatar`.

= 0.9.3 =

*   remove :fonts.googleapis.com ->fonts.useso.com as useso.com do not support combined query font css.
*   code optimization
= 0.9.2 =
*   adding Bing everyday image as background of the login screen .
*   fonts.googleapis.com ->fonts.useso.com .
*   ajax.googleapis.com ->ajax.useso.com .
*   code optimization .
= 0.9.1 =
*   change *.gravatar.com to cn.gravatar.com .
= 0.9.0 =
*   Initial release.

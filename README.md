# OnlineJudge

Forked from https://github.com/zhblue/hustoj

### Deploy OnlineJudge

```Bash
wget https://github.com/cjcdi/OnlineJudge/raw/tjau-local/src/deploy/install-ubuntu16%2B.sh
sudo bash install-ubuntu16+.sh
```

> 使用 admin 用户名注册后既是管理员用户

### ReadMe

&emsp;&emsp;此判题系统原作者是浙江传媒学院新媒体学院张浩斌老师（[Blog](http://www.hustoj.com)，[GitHub](https://github.com/zhblue/hustoj)），在此感谢张浩斌老师的开源系统提供我们学习。  
&emsp;&emsp;参考文献请引用[基于开放式云平台的开源在线评测系统设计与实现](http://kns.cnki.net/KCMS/detail/detail.aspx?dbcode=CJFQ&dbname=CJFD2012&filename=JSJA2012S3088&uid=WEEvREcwSlJHSldRa1FhdXNXYXJwcFhRL1Z1Q2lKUDFMNGd0TnJVVlh4bz0=$9A4hF_YAuvQ5obgVAqNKPCYcEjKensW4ggI8Fm4gTkoUKaID8j8gFw!!&v=MjgwNTExVDNxVHJXTTFGckNVUkwyZlllWm1GaURsV3IvQUx6N0JiN0c0SDlPdnJJOU5iSVI4ZVgxTHV4WVM3RGg=)。

该判题系统部署使用 LNMP 架构：  
&emsp;&emsp;Linux 系统：[Ubuntu 16.04 server](http://releases.ubuntu.com/16.04/ubuntu-16.04.5-server-amd64.iso)，  
&emsp;&emsp;服务器：[nginx](http://nginx.org/en/download.html)，  
&emsp;&emsp;数据库：[MySql](https://www.mysql.com/)，  
&emsp;&emsp;网站语言：[PHP](http://php.net/)。

在原系统的基础上，增加结果填空题和代码填空题，并修复少许 bug。

<?php

/**
 * JudgeOnline Config
 */

@session_start();

/**
 * 调试模式
 */
ini_set("display_errors", "off");
ini_set("session.cookie_httponly", 1);
header('X-Frame-Options:SAMEORIGIN');

/**
 * 配置数据库
 */
static $DB_HOST = "localhost";
static $DB_NAME = "jol";
static $DB_USER = "";
static $DB_PASS = "";

/**
 * 配置 JudgeOnline
 */
static $OJ_NAME = "JudgeOnline";
static $OJ_HOME = "./";
static $OJ_ADMIN = "root@localhost";
static $OJ_DATA = "/home/judge/data";   // 测试数据路径
static $OJ_BBS = "discuss3";            // bbs 论坛，自带简易 discuss 论坛
static $OJ_ONLINE = false;              // 监控用户在线数量，消耗一定的内存和计算
static $OJ_LANG = "cn";                 // 网站语言，cn 中文，en 英文
static $OJ_SIM = true;                  // 相似度检测
static $OJ_DICT = false;                // 在线英语字典
static $OJ_LANGMASK = 261940;           // 1mC 2mCPP 4mPascal 8mJava 16mRuby 32mBash 关闭判题语言，填写总和
static $OJ_EDITE_AREA = true;           // 高亮显示
static $OJ_ACE_EDITOR = true;           // 行号显示
static $OJ_AUTO_SHARE = false;          // 提交成功后共享提交代码答案
static $OJ_CSS = "white.css";
static $OJ_VCODE = false;               // 提交代码时和登陆时是否启用验证码
static $OJ_APPENDCODE = false;          // 自动添加代码，参考 $OJ_DATA 目录里是否存在 append.c 文件
static $OJ_CE_PENALTY = false;
static $OJ_PRINTER = false;             // 打印功能
static $OJ_MAIL = false;                // 邮件功能
static $OJ_RANK_LOCK_PERCENT = 0;       // 比赛封榜时间比例
static $OJ_SHOW_DIFF = true;            // 显示 WA 的对比说明
static $OJ_TEST_RUN = false;            // 测试运行
static $OJ_BLOCKLY = false;             // Blockly 界面
static $OJ_ENCODE_SUBMIT = true;        // base64 编码提交以回避 WAF 防火墙误拦截
static $OJ_OI_1_SOLUTION_ONLY = false;  // NOIP 仅保留最后一次提交的规则
// static $OJ_EXAM_CONTEST_ID=1000;     // 启用考试状态，填写考试比赛ID
// static $OJ_ON_SITE_CONTEST_ID=1000;  // 启用现场赛状态，填写现场赛比赛ID
static $OJ_SHARE_CODE = true;           // 代码分享功能
static $OJ_ON_SITE_TEAM_TOTAL = 0;      // 根据比例计算奖牌队伍总数
static $OJ_OPENID_PWD = '8a367fe87b1e406ea8e94d7d508dcf01';
static $OJ_SAE = false;
static $SAE_STORAGE_ROOT = "http://hustoj-web.stor.sinaapp.com/";
static $OJ_CDN_URL = "";
static $OJ_TEMPLATE = "bs3";            // 前端模板
if (isset($_GET['tp'])) $OJ_TEMPLATE = $_GET['tp'];

/**
 * 配置 Memcache
 */
static $OJ_MEMCACHE = false;            // 默认使用 /cache 目录进行缓存
static $OJ_MEMSERVER = "127.0.0.1";     // 缓存服务器
static $OJ_MEMPORT = 11211;             // 缓存端口

/**
 * 配置 Redis
 */
static $OJ_REDIS = false;
static $OJ_REDISSERVER = "127.0.0.1";
static $OJ_REDISPORT = 6379;
static $OJ_REDISQNAME = "hustoj";

/**
 * 配置 Auth
 */
static $OJ_LOGIN_MOD = "hustoj";
static $OJ_REGISTER = true;             // 允许注册新用户
static $OJ_REG_NEED_CONFIRM = false;    // 新注册用户需要审核
static $OJ_NEED_LOGIN = false;          // 需要登录才能访问
static $OJ_WEIBO_AUTH = false;          // weibo
static $OJ_WEIBO_AKEY = '1124518951';
static $OJ_WEIBO_ASEC = 'df709a1253ef8878548920718085e84b';
static $OJ_WEIBO_CBURL = 'http://192.168.0.108/JudgeOnline/login_weibo.php';
static $OJ_RR_AUTH = false;             // renren
static $OJ_RR_AKEY = 'd066ad780742404d85d0955ac05654df';
static $OJ_RR_ASEC = 'c4d2988cf5c149fabf8098f32f9b49ed';
static $OJ_RR_CBURL = 'http://192.168.0.108/JudgeOnline/login_renren.php';
static $OJ_QQ_AUTH = false;             // QQ
static $OJ_QQ_AKEY = '1124518951';
static $OJ_QQ_ASEC = 'df709a1253ef8878548920718085e84b';
static $OJ_QQ_CBURL = '192.168.0.108';

// if (date('H') < 5 || date('H') > 21 || isset($_GET['dark'])) $OJ_CSS = "dark.css";
if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && strstr($_SERVER['HTTP_ACCEPT_LANGUAGE'], "zh-CN")) { //strstr查找字符串的首次出现
    $OJ_LANG = "cn";
}
if (isset($_SESSION[$OJ_NAME . '_' . 'OJ_LANG'])) $OJ_LANG = $_SESSION[$OJ_NAME . '_' . 'OJ_LANG'];
require_once(dirname(__FILE__) . "/pdo.php");
// use db
// pdo_query("set names utf8");

if (isset($OJ_CSRF) && $OJ_CSRF && $OJ_TEMPLATE == "bs3" && basename($_SERVER['PHP_SELF']) != "problem_judge")
    require_once('csrf_check.php');

date_default_timezone_set("Asia/Shanghai");
// pdo_query("SET time_zone ='+8:00'");

# SMSBombing

短信轰炸

# Install

```bash
composer global require james.xue/sms-bombing 
```

# Use

普通轰炸（默认 10 次）

```bash
sms-bombing 136xxxxxxxx
```

全量轰炸

```bash
sms-bombing 136xxxxxxxx --num=all
```

启动 5 次,轰炸一个人的手机号(136xxxxxx)

```bash
sms-bombing 136xxxxxxxx --num=5
```

启动 5 次,轰炸一个人的手机号(136xxxxxx), 启动循环轰炸, 轮番轰炸2次

```bash
sms-bombing 136xxxxxxxx --num=5 -l 2
```

启动 5 次,轰炸一个人的手机号(136xxxxxx), 启动循环轰炸, 轮番轰炸2次，循环间隔30秒

```bash
sms-bombing 136xxxxxxxx --num=5 -l 2 -i 30
```

# 轰炸效果

```mermaid
sms-bombing 136xxxxxxxx -l 2
索引：3 请求结果：{"resultCode": 2009, "message": "phone_format_error", "data": null, "redirectUrl": null}
索引：4 请求结果：{"code":"3","codeInfo":" permission deny"}
索引：0 请求结果：{"code":65,"desc":"访问太频繁"}
索引：2 请求结果：{"success":false,"msg":"操作过于频繁，请稍后再试","data":[]}
索引：1 请求结果：
索引：7 请求结果：0
索引：6 请求结果：success
索引：8 请求结果：
索引：9 请求结果：1
索引：5 请求结果：{"code":0,"msg":"success"}
索引：0 请求结果：{"code":65,"desc":"访问太频繁"}
索引：4 请求结果：{"code":"3","codeInfo":" permission deny"}
索引：3 请求结果：{"resultCode": 2009, "message": "phone_format_error", "data": null, "redirectUrl": null}
索引：1 请求结果：
索引：2 请求结果：{"success":false,"msg":"操作过于频繁，请稍后再试","data":[]}
索引：7 请求结果：0
索引：6 请求结果：success
索引：5 请求结果：{"code":2701,"msg":"\u89e6\u53d1\u5206\u949f\u7ea7\u6d41\u63a7Permits:1"}
索引：9 请求结果：1
索引：8 请求结果：
索引：4 请求结果：{"code":"3","codeInfo":" permission deny"}
索引：0 请求结果：{"code":65,"desc":"访问太频繁"}
索引：3 请求结果：{"resultCode": 2009, "message": "phone_format_error", "data": null, "redirectUrl": null}
索引：1 请求结果：
索引：2 请求结果：{"success":false,"msg":"操作过于频繁，请稍后再试","data":[]}
索引：6 请求结果：success
索引：7 请求结果：0
索引：5 请求结果：{"code":2701,"msg":"\u89e6\u53d1\u5206\u949f\u7ea7\u6d41\u63a7Permits:1"}
索引：9 请求结果：1
```

# 免责声明

若使用者滥用本项目,本人 无需承担 任何法律责任. 本程序仅供娱乐,源码全部开源,禁止滥用 和二次 贩卖盈利. 禁止用于商业用途.

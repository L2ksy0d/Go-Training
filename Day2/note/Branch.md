# 逻辑分支语句

tips：go没有三元运算符，即使是基本的条件判断，也需要使用完整的if语句。

## if...else
tips:
* 条件无需加括号
* 注意自动加分号的问题


## switch...case
tips:
* case结尾会自动break，如果需要继续匹配下一项，可以使用`fallthrough`
* `default`可以省略

不带表达式的 switch 是实现 if/else 逻辑的另一种方式

## for
### 无限循环

# 逻辑分支语句

tips：go没有三元运算符，即使是基本的条件判断，也需要使用完整的if语句。

## if...else
tips:
* 条件无需加括号
* 注意自动加分号的问题

有下面几个问题注意一下：

### if的代码块是独立的作用域吗?
```go
package main

import "fmt"

func main() {
    a := 1
    b := 2

    if 1 > 0 {
        fmt.Println("a:", a)
        fmt.Println("b:", b)
        a = 10
        b = 20
        a := 100
        b := 200
        fmt.Println("a:", a)
        fmt.Println("b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}
```
输出如下：
```
a: 1
b: 2
a: 100
b: 200
a: 10
b: 20
---------------
if的代码块是独立的作用域
```

### if条件使用短声明时是独立的变量还是引用外部变量?
```go
package main

import "fmt"

func main() {
    a := 1
    b := 2

    if a := 10000; 1 > 0 {
        fmt.Println("a:", a)
        fmt.Println("b:", b)
        a = 10
        b = 20
        a := 100
        b := 200
        fmt.Println("a:", a)
        fmt.Println("b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}
```
输出如下：
```
a: 10000
b: 2
a: 100
b: 200
a: 1
b: 20
```
if条件使用短声明时是独立的变量。下面这个例子更能说明
```go
package main

import "fmt"

func main() {
    b := 2

    if a := 10000; 1 > 0 {
        fmt.Println("a:", a)
        fmt.Println("b:", b)
        a = 10
        b = 20
        a := 100
        b := 200
        fmt.Println("a:", a)
        fmt.Println("b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}
```
=======>./if.go:19: undefined: a
在if外引用变量a，提示未找到，因为a只在if作用域

### if条件使用赋值语句时是独立的变量还是引用外部变量?
```go
package main

import "fmt"

func main() {
    a := 1
    b := 2

    if a = 10000; 1 > 0 {
        fmt.Println("a:", a)
        fmt.Println("b:", b)
        a = 10
        b = 20
        a := 100
        b := 200
        fmt.Println("a:", a)
        fmt.Println("b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}
------------
a: 10000
b: 2
a: 100
b: 200
a: 10
b: 20
============>if条件使用赋值语句时是引用外部变量
```

### 若if条件不满足，那么条件句里的赋值语句是否有生效?
```go
package main

import "fmt"

func main() {
    a := 1
    b := 2

    if a = 10000; false {
        fmt.Println("if-a:", a)
        fmt.Println("if-b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}
```
输出如下：
```
a: 10000
b: 2
```
如果有elseif，情况如下
```go
package main

import "fmt"

func main() {
    a := 1
    b := 2

    if a = 10; false {
        fmt.Println("if-a:", a)
        fmt.Println("if-b:", b)
    } else if a = 20; true {
        fmt.Println("if-a:", a)
        fmt.Println("if-b:", b)
    } else if a = 30; false {
        fmt.Println("if-a:", a)
        fmt.Println("if-b:", b)
    }

    fmt.Println("a:", a)
    fmt.Println("b:", b)
}

--------------------
if-a: 20
if-b: 2
a: 20
b: 2
```

由此可见，若if条件不满足，那么条件句里的赋值语句依然生效。若有else if，则会依次执行，直到匹配到为止，因此谨慎在if条件句里引用外部变量。

## switch...case
tips:
* case结尾会自动break，如果需要继续匹配下一项，可以使用`fallthrough`
* `default`可以省略

不带表达式的 switch 是实现 if/else 逻辑的另一种方式

## for
### 无限循环

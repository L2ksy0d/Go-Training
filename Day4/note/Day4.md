# Day4

## map
在Go中Map也是 Key-Value pair的组合但是Map所有Key的资料型别都要一样所有Value的资料型别也要一样。另外，在设值和取值需要使用[]而非.
### 定义
```go
ages := make(map[string]int) // mapping from strings to ints
```
我们也可以用map字面值的语法创建map，同时还可以指定一些最初的key/value：
```go
ages := map[string]int{
    "alice":   31,
    "charlie": 34,
}
```
### 使用

Map中的元素通过key对应的下标语法访问：
```go
ages["alice"] = 32
fmt.Println(ages["alice"]) // "32"
```
使用内置的delete函数可以删除元素：
```go
delete(ages, "alice") // remove element ages["alice"]
```
所有这些操作是安全的，即使这些元素不在map中也没有关系；如果一个查找失败将返回value类型对应的零值，例如，即使map中不存在“bob”下面的代码也可以正常工作，因为ages["bob"]失败时将返回0。

```go
ages["bob"] = ages["bob"] + 1 // happy birthday!
```
而且x += y和x++等简短赋值语法也可以用在map上，所以上面的代码可以改写成

```go
ages["bob"] += 1
```
更简单的写法
```go
ages["bob"]++
```


## type&struct

### 定义
使用struct关键字可以定义一个结构体,结构体中的成员，称为结构体的字段或属性
```go
type Member struct {
    id     int
    name   string
    email  string
    gender int
    age    int
}
```
结构体也可以不包含任何字段，称为空结构体，struct{}表示一个空的结构体，注意，直接定义一个空的结构体并没有意义，但在并发编程中，channel之间的通讯，可以使用一个struct{}作为信号量
```go
ch := make(chan struct{})
ch <- struct{}{}
```

### 初始化
直接定义变量，这个使用方式并没有为字段赋初始值，因此所有字段都会被自动赋予自已类型的零值，比如name的值为空字符串""，age的值为0。
```go
var m1 Member//所有字段均为空值
```
使用字面量创建变量，这种使用方式，可以在大括号中为结构体的成员赋初始值，有两种赋初始值的方式，一种是按字段在结构体中的顺序赋值，下面代码中m2就是使用这种方式，这种方式要求所有的字段都必须赋值，因此如果字段太多，每个字段都要赋值，会很繁琐，另一种则使用字段名为指定字段赋值，如下面代码中变量m3的创建，使用这种方式，对于其他没有指定的字段，则使用该字段类型的零值作为初始化值

```go
var m2 = Member{1,"小明","xiaoming@163.com",1,18} // 简短变量声明方式：m2 := Member{1,"小明","xiaoming@163.com",1,18}
var m3 = Member{id:2,"name":"小红"}// 简短变量声明方式：m3 := Member{id:2,"name":"小红"}
```

### 访问字段
通过变量名，使用逗号(.)，可以访问结构体类型中的字段，或为字段赋值，也可以对字段进行取址(&)操作
```go
fmt.Println(m2.name)//输出：小明
m3.name = "小花"
fmt.Println(m3.name)//输出：小花

age := &m3.age
*age = 20
fmt.Println(m3.age)//20
```

### 结构体指针
结构体与数组一样，都是值传递，比如当把数组或结构体作为实参传给函数的形参时，会复制一个副本，所以为了提高性能，一般不会把数组直接传递给函数，而是使用切片(引用类型)代替，而把结构体传给函数时，可以使用指针结构体。
指针结构体，即一个指向结构体的指针,声明结构体变量时，在结构体类型前加*号，便声明一个指向结构体的指针
```go
var m1 *Member
m1.name = "小明"//错误用法，未初始化,m1为nil

m1 = &Member{}
m1.name = "小明"//初始化后，结构体指针指向某个结构体地址，才能访问字段，为字段赋值。
```
另外，使用Go内置new()函数，可以分配内存来初始化结构休，并返回分配的内存指针，因为已经初始化了，所以可以直接访问字段
```go
var m2 = new(Member)
m2.name = "小红"
```

### Tag
在定义结构体字段时，除字段名称和数据类型外，还可以使用反引号为结构体字段声明元信息，这种元信息称为Tag，用于编译阶段关联到字段当中,如我们将上面例子中的结构体修改为

```go
type Member struct {
    Id     int    `json:"id,-"`
    Name   string `json:"name"`
    Email  string `json:"email"`
    Gender int    `json:"gender,"`
    Age    int    `json:"age"`
}
```

上面例子演示的是使用encoding/json包编码或解码结构体时使用的Tag信息
Tag由反引号括起来的一系列用空格分隔的key:"value"键值对组成：

## 方法
在Go语言中，将函数绑定到具体的类型中，则称该函数是该类型的方法，其定义的方式是在func与函数名称之间加上具体类型变量，这个类型变量称为`方法接收器`.

```go
func setName(m Member,name string){//普通函数
    m.Name = name
}

func (m Member)setName(name string){//绑定到Member结构体的方法
    m.Name = name
}
```

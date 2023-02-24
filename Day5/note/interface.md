# 接口

接口(interface)定义了一个对象的行为规范，只定义不实现，由具体的对象来实现细节

## 接口类型
在Go中，接口是一种类型，一种抽象的类型。

`interface`是一组`method`的集合

## 接口的定义
```go
type 接口类型名 interface{
	func_name(args...) return_value
	...
}
```

例子：
```go
type Writer interface {
	Write([]byte) error
}
```
## 接口实现的条件
一个对象只要全部实现了接口中的方法，那么就实现了这个接口，比如我们定义的`Animal`接口
```go
type animal interface {
	Say()
}
```
定义`dog`和`cat`两个结构体，因为`animal`接口里只有一个`Say`方法，所以只需要给`cat`和`dog`分别实现`Say`方法就可以实现`animal`接口了
```go
type dog struct{}
//dog实现了animal接口
func (d dog) Say() {
fmt.Println("汪汪汪")
}

type cat struct{}
//cat实现了animal接口
func (c cat) Say() {
fmt.Println("喵喵喵")
}
```

## 接口变量类型
那实现了接口有什么用呢？
```go
func main() {
    //只要你实现了接口中定义的所有方法，那么这个变量就可以赋值给该接口类型的变量
    //golang不支持方法重载，通过接口来实现多态
    var s animal
    c1 := cat{}
    s = c1
    fmt.Println(s)
    p1 := person{name: "张磊"}
    s = p1
    fmt.Println(s)
}
```
接口类型变量能够存储所有实现了该接口的实例。 例如上面的示例中，animal类型的变量能够存储dog和cat类型的变量。

## 值接收者和指针接收者实现接口的区别
使用值接收者实现接口和使用指针接收者实现接口有什么区别呢？接下来我们通过一个例子看一下其中的区别。
我们有一个Mover接口和一个dog结构体。
```go
type Mover interface {
    move()
}

type dog struct {}
//值接收者实现接口
func (d dog) move() {
    fmt.Println("狗会动")
}
```
此时实现接口的是dog类型：
```go
func main() {
    var x Mover
    var wangcai = dog{} // 旺财是dog类型
    x = wangcai         // x可以接收dog类型
    var fugui = &dog{}  // 富贵是*dog类型
    x = fugui           // x可以接收*dog类型
    x.move()
}
```
从上面的代码中我们可以发现，使用值接收者实现接口之后，不管是dog结构体还是结构体指针*dog类型的变量都可以赋值给该接口变量。因为Go语言中有对指针类型变量求值的语法糖，dog指针fugui内部会自动求值*fugui。

指针接收者实现接口

同样的代码我们再来测试一下使用指针接收者有什么区别：
```go
func (d *dog) move() {
    fmt.Println("狗会动")
}

func main() {
    var x Mover
    var wangcai = dog{} // 旺财是dog类型
    x = wangcai         // x不可以接收dog类型
    var fugui = &dog{}  // 富贵是*dog类型
    x = fugui           // x可以接收*dog类型
}
```
此时实现Mover接口的是*dog类型，所以不能给x传入dog类型的wangcai，此时x只能存储*dog类型的值。

## 类型与接口的关系

省流：一个类型可以实现多个接口

一个类型可以同时实现多个接口，而接口间彼此独立，不知道对方的实现。 例如，狗可以叫，也可以动。我们就分别定义Sayer接口和Mover接口，如下： Mover接口。
```go
// Sayer 接口
type Sayer interface {
	say()
}

// Mover 接口
type Mover interface {
	move()
}
```
dog既可以实现Sayer接口，也可以实现Mover接口
```go
type dog struct {
    name string
}

// 实现Sayer接口
func (d dog) say() {
    fmt.Printf("%s会叫汪汪汪\n", d.name)
}

// 实现Mover接口
func (d dog) move() {
    fmt.Printf("%s会动\n", d.name)
}

func main() {
    var x Sayer
    var y Mover
    
    var a = dog{name: "旺财"}
    x = a
    y = a
    x.say()
    y.move()
}
```

## 接口嵌套
接口与接口间可以通过嵌套创造出新的接口
```go
// Sayer 接口
type Sayer interface {
    say()
}

// Mover 接口
type Mover interface {
    move()
}

// 接口嵌套
type animal interface {
    Sayer
    Mover
}
```
嵌套得到的接口的使用与普通接口一样，这里我们让cat实现animal接口：
```go
type cat struct {
	name string
}

func (c cat) say() {
	fmt.Println("喵喵喵")
}

func (c cat) move() {
	fmt.Println("猫会动")
}

func main() {
	var x animal
	x = cat{name: "花花"}
	x.move()
	x.say()
}
```

## 空接口
### 空接口的定义
空接口是指没有定义任何方法的接口。因此任何类型都实现了空接口。
空接口类型的变量可以存储任意类型的变量。
```go
func EmptyInterface() {
	var x interface{}
	x = "hello world"
	x = 18
	x = false
	fmt.Println(x)
}
```

### 空接口的应用
#### 空接口作为函数的参数
使用空接口实现可以接收任意类型的函数参数
```go
// 空接口作为函数参数
func show(a interface{}) {
	fmt.Printf("type:%T value:%v\n", a, a)
}
```
#### 空接口作为map的值
使用空接口实现可以保存任意值的字典
```go
// 空接口作为map值
var studentInfo = make(map[string]interface{})
studentInfo["name"] = "蔡徐坤"
studentInfo["age"] = 18
studentInfo["hobby"] = []string{"唱", "跳", "rap", "篮球"}
fmt.Println(studentInfo)
```


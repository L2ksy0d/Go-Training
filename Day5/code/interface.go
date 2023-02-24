package main

import "fmt"

type dog struct{}

func (d dog) Say() {
	fmt.Println("汪汪汪")
}

type cat struct{}

func (c cat) Say() {
	fmt.Println("喵喵喵")
}

//定义一个抽象的类型，只要实现了say这个方法的类型，都可以是animal
type animal interface {
	Say()
}

func (p person) Say() {
	fmt.Println("哼啊啊啊啊啊啊啊啊啊啊~~")
}

type mover interface {
	move()
}

type person struct {
	name string
	age  int
}

//func (p person) move() {
//	fmt.Printf("%s在跑！", p.name)
//}

func (p *person) move() {
	fmt.Printf("%s在跑！", p.name)
}

type emtpyinterface interface{}

func main() {
	//c1 := cat{}
	//d1 := dog{}
	//p1 := person{name: "张磊"}
	//interfaceDemo(c1)
	//interfaceDemo(d1)
	//interfaceDemo(p1)

	//只要你实现了接口中定义的所有方法，那么这个变量就可以赋值给该接口类型的变量
	//golang不支持方法重载，通过接口来实现多态
	//var s animal
	//c1 := cat{}
	//s = c1
	//fmt.Println(s)
	//p1 := person{name: "张磊", age: 18}
	//s = p1
	//fmt.Println(s)
	//ValueAndPointer()
	EmptyInterface()
}

//接口不管你是什么类型，只关心你实现什么方法
func interfaceDemo(arg animal) {
	arg.Say()
}

func ValueAndPointer() {
	//使用值接收实现接口：类型的值和类型的指针都能保存到接口变量中
	//只用指针接收实现接口：类型的值不能保存在接口变量中
	var m mover
	//p1 := person{name: "张磊", age: 18}
	//m = p1
	//m.move()

	p2 := &person{name: "代雨辰", age: 50}
	m = p2
	m.move()
}

func EmptyInterface() {
	var x interface{}
	x = "hello world"
	x = 18
	x = false
	fmt.Println(x)
}

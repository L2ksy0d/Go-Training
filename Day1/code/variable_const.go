package main

import "fmt"

var global_variable = "I am a global_variable because me without the func body"

func main() {
	//单行注释，反斜线转义引号
	fmt.Println("双引号")
	fmt.Println("\"Hello World!\"")
	/*多行注释
	  测试其他的转义字符
	*/
	fmt.Println("换行符")
	fmt.Println("Hello\nWorld!")
	VariableAndConst()
}

func VariableAndConst() {
	var age int //第一种不带初始值的声明，此时默认值为0
	age = 20
	var (
		hight  int
		weight int
	)
	FirstName := "Nakano"
	LastName := "Itsuki"
	hight, weight = 165, 50
	fmt.Printf("My name is %v %v,age is %v,%vcm %vkg\n", FirstName, LastName, age, hight, weight)

	const (
		c1 = 8
		c2 = iota
	)
	fmt.Printf("c1 is %v,c2 is %v", c1, c2)
	fmt.Println("Global Variable")
	fmt.Println(global_variable)
}

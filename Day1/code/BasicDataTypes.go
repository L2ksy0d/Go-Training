package main

import "fmt"

func main() {
	fmt.Println("BasicDataTypes：")
	BasicDataType()
}

func BasicDataType() {
	var (
		v1      = 1
		v2 int8 = 2
		v3 uint16
		b1         = 0b0101 //二进制声明
		o1         = 0o077  //八进制声明
		h1         = 0x5b5b //十六进制声明
		f1 float32 = 3.14
	)

	fmt.Printf("v1 = %v,type is %T\n", v1, v1)
	fmt.Printf("v2 = %v,type is %T\n", v2, v2)
	fmt.Printf("v3 = %v,type is %T\n", v3, v3)
	fmt.Printf("b1 = %v,type is %T\n", b1, b1)
	fmt.Printf("o1 = %v,type is %T\n", o1, o1)
	fmt.Printf("h1 = %v,type is %T\n", h1, h1)
	fmt.Printf("f1 = %v,type is %T\n", f1, f1)

	n2 := int(f1) //类型转换
	fmt.Printf("n2 = %v,type is %T\n", n2, n2)
}

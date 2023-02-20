package main

import "fmt"

func main(){
	Pointer()
}

func increase(n *int){
	*n++
	fmt.Printf("调用inc之后，n指向的值为%v，n的内存地址为：%v\n", *n, &n)
}

func Pointer(){
	year := 2023
	ptr := &year
	increase(ptr)

	fmt.Printf("调用inc之后，year的值为%v，内存地址为：%v\n", year, &year)
	fmt.Printf("调用inc之后，ptr的值为%v，指向地址的值为：%v\n", ptr, *ptr)
}

package main

import "fmt"

func main(){
	DeferRecover()
	fmt.Println("我是main函数，我没有中途退出")
}

func deferdemo() func(int) int{
	i := 0
	return func(n int) int {
		i++
		fmt.Printf("匿名函数被第%v次调用\n", i)
		fmt.Printf("本次调用n的值为%v\n", n)
		return i
	}
}

func Defer() int{
	f := deferdemo()
	defer f(f(3))
	return f(2)
}

func DeferRecover(){
	//通过defer匿名函数来在执行代码之后收集错误，阻止程序退出
	defer func(){
		err := recover()
		if err != nil{
			fmt.Println(err)
		}
	}()
	//正常情况下这里会报错panic: runtime error: integer divide by zero
	n := 0
	fmt.Println(3 / n)
}
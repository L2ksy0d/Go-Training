package main

import (
	"fmt"
	"time"
)

func main(){
	fordemo()
}

func ifdemo(){
	var age uint8
	fmt.Println("input your age")
	fmt.Scanln(&age)
	if age < 13 {
		fmt.Println("you are too small!")
	} else if age > 50{
		fmt.Println("you are too big!!!")
	} else {
		fmt.Println("you are good!")
	}
}

func switchdemo(){
	var age uint8
	fmt.Println("input your age")
	fmt.Scanln(&age)
	switch{
	case age < 13:
		fmt.Println("you are too small!")
	case age > 50:
		fmt.Println("you are too big!!!")
	default:
		fmt.Println("you are good!")
	}

	fmt.Println(time.Now().Hour())
	t:= time.Now()
	switch {
	case t.Hour() > 19:
		fmt.Println("已经过了酉时")
		fallthrough
	case t.Hour() > 21:
		fmt.Println("已经过了戊时")
		fallthrough
	case t.Hour() > 23:
		fmt.Println("已经过了亥时")
	}
}

func fordemo(){
	//无限循环
	i := 1
	for{
		fmt.Println("i ==> " , i)
		i++
		if i == 10{
			break
		}
	}

	//条件循环
	for i < 11 {
		fmt.Println("i ==> " , i)
		i++
		if i == 12 {
			break
		}
	}

	//标准的for循环
	for i := 0; i < 11; i++ {
		fmt.Println(i, "\t")
	}
}

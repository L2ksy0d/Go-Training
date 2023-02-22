package main

import "fmt"

func main(){
	ArrayDemo()
	fmt.Println("正常退出")
}

func ArrayDemo(){
	var a [3]int = [3]int{1, 2, 3}
	//invalid argument: array index 3 out of bounds [0:3]
	//a[3] = 2
	for i := 0; i < len(a); i++ {
		fmt.Printf("a[%v] = %v\n", i, a[i])
	}

	for k, v := range a {
		fmt.Printf("a[%v] = %v\n", k, v)
	}

	//二维数组
	var twoDimensionalArray [3][4]int = [3][4]int{
		{1, 2, 3, 4},
		{11, 22, 33, 44},
		{111, 222, 333, 444},
	}

	//遍历多维数组
	for k, v := range twoDimensionalArray {
		for kk, vv := range v {
			fmt.Printf("a[%v][%v] = %v\t", k, kk, vv)
		}
		fmt.Println()
	}
}

package main

import (
	"fmt"
)

func main() {
	StructDemo()
}

func StructDemo() {
	type mesType int
	var textMes mesType = 1000
	fmt.Printf("textMes = %v, Type of textMes = %T\n", textMes, textMes)

	type user struct {
		Name string
		Id   uint32
	}

	type Account struct {
		user
		password string
	}

	user1 := user{
		Name: "张三",
		Id:   5,
	}

	Ac1 := Account{
		user:     user1,
		password: "string",
	}

	fmt.Println(user1)
	fmt.Println(Ac1)
}

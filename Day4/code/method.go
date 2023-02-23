package main

import (
	"fmt"
)

func main() {
	MethodDemo()
}

type User struct {
	Name string
	Id   uint32
}

type Account struct {
	User
	password string
}

func (c User) printcName() {
	fmt.Println("c.name = ", c.Name)
}

func (a Account) printAcName() {
	fmt.Println("Ac.User = ", a.User)
}

func MethodDemo() {
	user1 := User{
		Name: "张三",
		Id:   5,
	}

	Ac1 := Account{
		User:     user1,
		password: "string",
	}

	user1.printcName()
	Ac1.printAcName()
}

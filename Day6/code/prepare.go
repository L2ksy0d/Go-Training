/* 练习题目
使用“面向对象”的思维方式编写一个学生信息管理系统。
学生有id、姓名、年龄、分数等信息
程序提供展示学生列表、添加学生、编辑学生信息、删除学生等功能
*/

package main

import (
	"fmt"
	"os"
)

type Student struct {
	Id    int
	Name  string
	Age   int
	Score int
}

type Person struct {
	Info []*Student
}

func (p *Person) ShowStudent() {
	for _, v := range p.Info {
		fmt.Println("学生信息如下：")
		fmt.Println("id：", v.Id)
		fmt.Println("name：", v.Name)
		fmt.Println("age：", v.Age)
		fmt.Print("score：", v.Score)
	}
}

func (p *Person) AddStudent() {
	fmt.Println("您现在要添加一名学生")
	var (
		id    int
		name  string
		age   int
		score int
	)
	fmt.Print("请输入他的信息: ")
	fmt.Scanf("%d %s %d %d", &id, &name, &age, &score)
	fmt.Printf("您录入的学生ID为%d,名字是\"%s\",%d岁,成绩是%d\n", id, name, age, score)
	stu := &Student{Id: id, Name: name, Age: age, Score: score}
	p.Info = append(p.Info, stu)
}

func (p *Person) EditStudent() {
	fmt.Println("您现在要编辑一名学生的信息")
	var (
		id    int
		name  string
		age   int
		score int
		exist bool = false
	)
	fmt.Print("请输入更新的信息: ")
	fmt.Scanf("%d %s %d %d", &id, &name, &age, &score)
	for _, v := range p.Info {
		if id == v.Id {
			fmt.Printf("您录入的学生ID为%d,名字是\"%s\",%d岁,成绩是%d\n", id, name, age, score)
			stu := &Student{Id: id, Name: name, Age: age, Score: score}
			p.Info = append(p.Info, stu)
			exist = true
		}
	}
	if !exist {
		fmt.Println("输入的学生信息不存在")
	}
}

//func (p *Person) DeleteStudent() {
//
//}

func main() {
	fmt.Println("Day6 Prepare")
	//映射使用map实现
	sysinfo := &Person{
		Info: make([]*Student, 0, 200),
	}

	for {
		var chose string
		fmt.Printf("请输入你的选项：\n")
		fmt.Scanf("%s\n", &chose)

		switch {
		case chose == "add":
			sysinfo.AddStudent()
		case chose == "edit":
			sysinfo.EditStudent()
		case chose == "show":
			sysinfo.ShowStudent()
		case chose == "exit":
			os.Exit(0)
		}
	}
}

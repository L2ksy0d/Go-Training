/* 练习题目
使用“面向对象”的思维方式编写一个学生信息管理系统。
学生有id、姓名、年龄、分数等信息
程序提供展示学生列表、添加学生、编辑学生信息、删除学生等功能
*/

package main

import "fmt"

type Student struct {
	Id    int
	Name  string
	Age   int
	Score int
}

type Person struct {
	Title string
	Info  []*Student
}

func (p *Person) ShowStudent(s *Person) {
	Student_list := s.Info
	for _, v := range Student_list {
		fmt.Println("学生信息如下：")
		fmt.Println("id：", v.Id)
		fmt.Println("name：", v.Name)
		fmt.Println("age：", v.Age)
		fmt.Println("score：", v.Score)
	}
}

func (p *Person) AddStudent(s *Person) {
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
	s.Info = append(s.Info, stu)
}

//func (s *Student) EditStudent() string {}
//
//func (s *Student) DeleteStudent() string {}

func main() {
	fmt.Println("Day6 Prepare")
	//映射使用map实现
	sysinfo := &Person{
		Title: "class-1",
		Info:  make([]*Student, 0, 200),
	}
	sysinfo.AddStudent(sysinfo)
	sysinfo.ShowStudent(sysinfo)
	//var select int
	//fmt.Scanf("请输入您的选项", &select)
	//switch {
	//case select = 0:
	//	ShowStudent(systeminfo)
	//case select = 1:
	//	AddStudent(&systeminfo)
	//case select = 2:
	//	EditStudent()
	//case select = 3:
	//	DeleteStudent()
	//}
}

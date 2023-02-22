package main

import "fmt"

func main() {
	SliceDemo()
}

func SliceDemo() {
	array := [5]int{1, 2, 3, 4, 5}
	var s1 []int = array[:4]
	//修改切片内容，数组内容也会发生改变
	s1[0] = 123
	fmt.Println(array) //[123 2 3 4 5]
	s2 := s1[1:]
	s2[0] = 666
	fmt.Println(array) //[123 666 3 4 5]

	for k, v := range s1 {
		fmt.Printf("the key is %v, the value is %v\n", k, v)
	}

	//通过make可以初始化内存空间，如果不写容量则默认和长度相同
	s3 := make([]int, 3, 5)
	fmt.Printf("len() ==> %v\n", len(s3))
	fmt.Printf("cap() ==> %v\n", cap(s3))

	//由系统自动创建底层数组
	s4 := []int{1, 2, 3, 5}
	fmt.Println(s4)

	//切片动态追加使用append
	//追加之后，底层将创建新的数组，不再引用原数组
	//每次append时，如果发现cap已经不足以给len使用，就会重新分配原cap两倍的容量，把原切片里已有内容全部迁移过去
	s1 = append(s1, 666, 777, 888)
	fmt.Println(s1)
	s1[0] = 999
	fmt.Println(s1)
	//array由于不再被引用，不会发生变化 ==> [123 666 3 4 5]
	fmt.Println(array)

	//复制数组使用copy函数,超出范围的或者范围不够的不会继续复制
	s5 := make([]int, 2)
	copy(s5, s1)
	fmt.Println(s5) //[999 666]


	//切片截取有两个冒号的情况
	//s[start:end:max]表示截取的长度是[end - start],但实际引用的数组为[start : max]
	data := [...]int{0, 1, 2, 3, 4, 5, 6, 7, 8, 9}
	v := data[:6:8]
	fmt.Println(v)
	fmt.Println(len(v))
	fmt.Println(cap(v))
	/*
	[0 1 2 3 4 5]
	6
	8
	*/
}

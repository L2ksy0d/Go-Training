package main

import "fmt"

func main() {
	MapDemo()
}

func MapDemo() {
	var m1 map[string]int
	fmt.Println("m1 == nil?", m1 == nil)

	//分配内存空间,size可省略，默认为1
	m1 = make(map[string]int, 2)
	m1["早上"] = 1
	m1["中午"] = 2
	m1["晚上"] = 3
	fmt.Println(m1)

	m2 := map[string]string{
		"P250":    "500",
		"Degeal":  "700",
		"Pisitol": "300",
	}
	fmt.Println(m2)

	v, ok := m2["P250"]
	fmt.Println(ok)
	if ok {
		fmt.Println(v)
	} else {
		fmt.Println("error")
	}

	//删除key P250
	delete(m2, "P250")
	fmt.Println(m2)

	//快速删除整个map
	// m2 = nil
	// fmt.Println(m2)

	for k, v := range m2 {
		fmt.Printf("map[%v] == > %v\n", k, v)
	}
}

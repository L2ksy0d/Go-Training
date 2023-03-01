package main

import (
	"fmt"
	"reflect"
)

type Dog struct{}

type Cat struct{}

func reflectDemo(x interface{}) {
	v := reflect.TypeOf(x)
	fmt.Println(v)
}

func reflectKindDemo(x interface{}) {
	v := reflect.TypeOf(x)
	fmt.Println(v.Kind())
}

func ValueDemo(x interface{}) {
	v := reflect.ValueOf(x)
	fmt.Printf("value: %v, type:%T\n", v, v)
	switch k := v.Kind(); k {
	case reflect.Float32:
	case reflect.Float64:
		ret := v.Float()
		fmt.Printf("value: %v, type:%T\n", ret, ret)
	}
}

func main() {
	a := 3.2
	b := "sre"
	ValueDemo(a)
	ValueDemo(b)
	var c Cat
	var d Dog
	ValueDemo(c)
	ValueDemo(d)

}

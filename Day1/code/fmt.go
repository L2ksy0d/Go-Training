package main

import "fmt"

func main(){
	fmt.Println("fmt 格式化输出")
	i := 2023
	fmt.Printf("%%v is value\n")
	fmt.Printf("%%T is type\n")
	fmt.Printf("variable i's value is %v, type is %T\n",i, i)
  //%v is value%T is typevariable i's value is 2023, type is int
	fmt.Printf("%%d is decimal\n")
	fmt.Printf("%%b is binary\n")
	fmt.Printf("%%o is octal\n")
	fmt.Printf("%%x is hexadecimal\n")
	fmt.Printf("variable i's binary value is %b\n",i)
	fmt.Printf("variable i's octal value is %o\n",i)
	fmt.Printf("variable i's decimal value is %d\n",i)
	fmt.Printf("variable i's hexadecimal value is %x\n",i)
	/*
	variable i's binary value is 11111100111
	variable i's octal value is 3747        
	variable i's decimal value is 2023      
	variable i's hexadecimal value is 7e7
	*/
	f := 3.1314
	fmt.Printf("%%f is float\n")
	fmt.Printf("variable f's value is %f\n",f)
	//%x.yf意思是最小宽度为x位，保留y位小数
	b := true
	fmt.Printf("%%t is true or false\n")
	fmt.Printf("variable b's value is %t\n",b)
	s := "fmt lucky"
	fmt.Printf("%%s is string\n")
	fmt.Printf("variable s's value is %s\n",s)
	fmt.Printf("%%s is string with double-qoute\n")
	fmt.Printf("variable s's value is %q\n",s)

	p := &s
	fmt.Printf("%%p is pointer\n")
	fmt.Printf("variable p's value is %p\n",p)
}
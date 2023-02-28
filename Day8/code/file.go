package main

import (
	"bufio"
	"fmt"
	"io"
	"io/ioutil"
	"os"
)

func FileDemo1() {
	fileObj, err := os.Open("xx.txt")
	if err != nil {
		fmt.Println("Error opening")
		return
	}
	defer fileObj.Close()
	//读取文件
	var tmp = make([]byte, 128)
	n, err := fileObj.Read(tmp)
	if err == io.EOF {

	}
	if err != nil {
		fmt.Println("Error reading")
		return
	}
	fmt.Printf("Read %d bytes from file.\n", n)
	fmt.Println(string(tmp))
}

func FileDemo2() {
	fileObj, err := os.Open("xx.txt")
	if err != nil {
		fmt.Println("Error opening")
		return
	}
	defer fileObj.Close()
	//读取文件的所有内容
	for {
		var tmp = make([]byte, 128)
		n, err := fileObj.Read(tmp)
		if err == io.EOF {
			//打印出当前的字节数
			fmt.Println(string(tmp[:n]))
			return
		}
		if err != nil {
			fmt.Println("Error reading")
			return
		}
		fmt.Printf("Read %d bytes from file.\n", n)
		fmt.Println(string(tmp[:n]))
	}
}

func ReadByBufio() {
	fileObj, err := os.Open("xx.txt")
	if err != nil {
		fmt.Println("Error opening")
		return
	}
	defer fileObj.Close()
	reader := bufio.NewReader(fileObj)
	for {
		line, err := reader.ReadString('\n')
		if err == io.EOF {
			fmt.Print(line)
			return
		}
		if err != nil {
			fmt.Println("Error reading")
			return
		}
		fmt.Print(line)
	}
}

func ioutildemo() {
	var tmp = make([]byte, 128)
	tmp, err := ioutil.ReadFile("xx.txt")
	if err != nil {
		fmt.Println("Error reading")
		return
	}
	fmt.Println(string(tmp))
}

func WriteFile1() {
	fileObj, err := os.OpenFile("xx.txt", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0644)
	if err != nil {
		fmt.Println("Error opening")
		return
	}
	defer fileObj.Close()
	str := "Nakano、Itsuki"
	n, err := fileObj.Write([]byte(str))
	fmt.Printf("输入了%d个字节", n)
	fileObj.WriteString("RainSeccccccccc")
}

func WriteByBufio() {
	fileObj, err := os.OpenFile("xx.txt", os.O_CREATE|os.O_WRONLY|os.O_APPEND, 0644)
	if err != nil {
		fmt.Println("Error opening")
		return
	}
	defer fileObj.Close()
	writer := bufio.NewWriter(fileObj)
	writer.WriteString("ADADADA")
	//如果不调用这句话 不会将内容写入缓冲区，不会生效
	writer.Flush()
}

func WriteByIoutil() {
	str := "我最喜欢五月小姐了嘿嘿嘿嘿嘿"
	err := ioutil.WriteFile("xx.txt", []byte(str), 0644)
	if err != nil {
		fmt.Println("Error writing")
		return
	}
}

func main() {
	fmt.Println("文件操作")
	WriteByIoutil()
}

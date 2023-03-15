package main

import (
	"bufio"
	"fmt"
	"net"
)

func process(c net.Conn) {
	defer c.Close()
	for {
		reader := bufio.NewReader(c)
		var buff [128]byte
		n, err := reader.Read(buff[:])
		if err != nil {
			fmt.Println("Read Error", err)
			break
		}
		recv := string(buff[:n])
		fmt.Printf("recv data:%s", recv)
		c.Write([]byte("ok"))
	}
}

func main() {
	listen, err := net.Listen("tcp", "127.0.0.1:20000")
	if err != nil {
		fmt.Println("Listen Error", err)
	}
	for {
		conn, err := listen.Accept()
		if err != nil {
			fmt.Println("Connect Error", err)
			continue
		}

		go process(conn)
	}
}

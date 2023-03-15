package main

import (
	"bufio"
	"fmt"
	"net"
	"os"
	"strings"
)

func main() {
	conn, err := net.Dial("tcp", "127.0.0.1:20000")
	if err != nil {
		fmt.Println("Connect Error")
	}
	input := bufio.NewReader(os.Stdin)
	for {
		s, _ := input.ReadString('\n')
		s = strings.TrimSpace(s)
		if strings.ToUpper(s) == "Q" {
			return
		}

		_, err := conn.Write([]byte(s))
		if err != nil {
			fmt.Println("Send Error")
		}

		var buff [1024]byte
		n, err := conn.Read(buff[:])
		if err != nil {
			fmt.Println("Read Error")
		}
		fmt.Println("收到回复：", string(buff[:n]))
	}
}

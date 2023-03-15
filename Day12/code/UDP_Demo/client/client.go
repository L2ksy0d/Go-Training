package main

import (
	"bufio"
	"fmt"
	"net"
	"os"
)

func main() {
	c, err := net.DialUDP("udp", nil, &net.UDPAddr{
		IP:   net.IPv4(127, 0, 0, 1),
		Port: 30000,
	})
	if err != nil {
		fmt.Println("Error")
	}
	defer c.Close()
	input := bufio.NewReader(os.Stdin)
	for {
		s, _ := input.ReadString('\n')
		_, err = c.Write([]byte(s))
		if err != nil {
			fmt.Println("Send to Server Failed")
			return
		}

		var buff [1024]byte
		_, addr, err := c.ReadFromUDP(buff[:])
		if err != nil {
			fmt.Println("Send to Server Failed")
			return
		}
		fmt.Printf("read from %v, data:%v\n", addr, string(buff[:]))
	}
}

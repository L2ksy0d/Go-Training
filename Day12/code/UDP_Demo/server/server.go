package main

import (
	"fmt"
	"net"
)

func main() {
	listen, err := net.ListenUDP("udp", &net.UDPAddr{
		IP:   net.IPv4(127, 0, 0, 1),
		Port: 30000,
	})
	if err != nil {
		fmt.Println("Listen Error")
	}
	defer listen.Close()
	for {
		var buff [1024]byte
		n, addr, err := listen.ReadFromUDP(buff[:])
		if err != nil {
			fmt.Println("Read Error")
		}
		fmt.Println("接收到数据： ", string(buff[:n]))
		_, err = listen.WriteTo([]byte("okkkkk"), addr)
		if err != nil {
			fmt.Println("Send Error")
		}
	}
}

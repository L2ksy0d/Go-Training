package main

import (
	"context"
	"fmt"
	"io/ioutil"
	"net/http"
	"strings"
)

func PostData(url string) (context string, err error) {
	res, err := http.Post(url, 'application/x-www-from-urlencoded', strings.NewReader(string([]byte("{'aaa':'bbb'}"))))
	if err != nil {
		return err
	}

	defer res.Body.Close()
	context, err := ioutil.ReadAll(res.Body)
	if err != nil {
		return err
	}
	return string(context), nil
}

func main() {
	fmt.Println("ThinkPHP 5.0.23 RCE Detection")
	url := ""
	body := []byte({})
	context, err := PostData(url, body)
}

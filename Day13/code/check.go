package main

import (
	"crypto/tls"
	"fmt"
	"io/ioutil"
	"net/http"
	"net/url"
	"time"
)

var BrowserUA string = "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.74 Safari/537.36"

func NetflixConnectTest(client *http.Client) {
	/*
		判断逻辑：
		1. 访问https://www.netflix.com/title/81280792 获取状态码
		2. 当状态码为404表示当前只能解锁版权宽松的自制剧
		3. 当状态码为403表示当前未解锁Netflix
		4. 当状态码为200表示当前已经解锁
	*/
	req, _ := http.NewRequest("GET", "https://www.netflix.com/title/80018499", nil)
	req.Header.Set("User-Agent", BrowserUA)
	resp, err := client.Do(req)
	if err != nil {
		fmt.Println(err)
		return
	}
	defer resp.Body.Close()
	body, _ := ioutil.ReadAll(resp.Body)
	fmt.Println(string(body))
	code := resp.StatusCode
	switch code {
	case 404:
		fmt.Println("Originals Only")
	case 403:
		fmt.Println("NO")
	case 200:
		fmt.Println("YES")
	default:
		break
	}
	//fmt.Println(code)
}

func main() {
	proxyUrl := "http://127.0.0.1:1080"
	proxy, _ := url.Parse(proxyUrl)
	tr := &http.Transport{
		Proxy:           http.ProxyURL(proxy),
		TLSClientConfig: &tls.Config{InsecureSkipVerify: true},
	}

	client := &http.Client{
		Transport: tr,
		Timeout:   time.Second * 5, //超时时间
	}

	NetflixConnectTest(client)
}
